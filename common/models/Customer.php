<?php

namespace common\models;

use common\helpers\CUtils;
use common\helpers\ErrorCode;
use common\helpers\OneSignalApi;
use Firebase\JWT\JWT;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\Json;
use yii\web\IdentityInterface;
use yii\web\UnauthorizedHttpException;

/**
 * This is the model class for table "customer".
 *
 * @property int $id
 * @property string $username
 * @property string $full_name
 * @property string $nickname
 * @property string $email
 * @property string $phone
 * @property string $password
 * @property string $avatar
 * @property string $referral_code
 * @property int $sex 1 - nam, 2 - nữ
 * @property int $birthday
 * @property int $membership
 * @property int $referral_count
 * @property int $green_point
 * @property int $purple_point
 * @property double $wallet_total
 * @property double $debt_old
 * @property double $total_amount
 * @property double $total_paid
 * @property double $total_debt
 * @property string $address
 * @property string $code
 * @property string $info Giới thiệu bản thân
 * @property string $cmt
 * @property int $status 0 - chưa active, 1 - đã active
 * @property int $is_install_app 0 - chưa cài app, 1 - đã cài app
 * @property int $created_at
 * @property int $updated_at
 *
 * @property int|null $debtTotalAmount
 * @property int|null $debtTotalPaid
 * @property CustomerDebt[] $customerDebts
 */
class Customer extends ActiveRecord implements IdentityInterface
{
    public $confirm_password;

    const STATUS_NOT_ACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_CANCEL = 2;

    public static $arrStatus = [
        self::STATUS_NOT_ACTIVE => 'Chưa kích hoạt',
        self::STATUS_ACTIVE => 'Đã kích hoạt',
        self::STATUS_CANCEL => 'Huỷ kích hoạt',
    ];

    const SEX_NULL = 0;
    const SEX_NAM = 1;
    const SEX_NU = 2;
    public static $arrSex = [
        self::SEX_NULL => "Chưa xác định",
        self::SEX_NAM => "Nam",
        self::SEX_NU => "Nữ",
    ];

    const MEMBERSHIP_0 = 0;
    const MEMBERSHIP_1 = 1;
    const MEMBERSHIP_2 = 2;
    public static $arrMembership = [
        self::MEMBERSHIP_0 => "Hạng thường",
        self::MEMBERSHIP_1 => "Hạng thân thiết",
        self::MEMBERSHIP_2 => "Hạng Vip",
    ];

    const IS_INSTALL_APP_0 = 0;
    const IS_INSTALL_APP_1 = 1;
    public static $installApp = [
        self::IS_INSTALL_APP_0 => "Chưa cài app",
        self::IS_INSTALL_APP_1 => "Đã cài app",
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'customer';
    }

    use \damirka\JWT\UserTrait;

    /**
     * Getter for secret key that's used for generation of JWT
     * @return string secret key used to generate JWT
     */
    protected static function getSecretKey()
    {
        return Yii::$app->params['jwt-config']['key'];
    }

    /**
     * Getter for encryption algorytm used in JWT generation and decoding
     * Override this method to set up other algorytm.
     * @return string needed algorytm
     */
    public static function getAlgo()
    {
        return Yii::$app->params['jwt-config']['alt'];
    }

    /**
     * Generate access token with jwt with params are loaded from config
     *
     * @param array $payload
     * @return string
     */
    public static function generateApiToken($payload = [])
    {
        $alg = Yii::$app->params['jwt-config']['alt']; // get encode algorithm from config file
        $key = Yii::$app->params['jwt-config']['key']; // get secret key from config file

        $jwt = JWT::encode($payload, $key, $alg); // generate access token

        return $jwt;
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['phone'], 'required'],
            [['username', 'phone', 'code'], 'unique'],
            [['is_install_app', 'sex', 'status', 'created_at', 'updated_at', 'referral_count', 'green_point', 'purple_point', 'membership'], 'integer'],
            [['username', 'full_name', 'email', 'password', 'avatar', 'address', 'referral_code'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 25],
            [['email'], 'email'],
            [['info', 'nickname', 'cmt'], 'string'],
            [['birthday', 'wallet_total', 'debt_old', 'total_amount', 'total_paid', 'total_debt'], 'safe'],
            [['password'], 'required', 'message' => Yii::t('app', 'invalid information, password has more than 6 characters and include a-z; A-Z; 0-9 or special character "!@#$%^&*()'), 'on' => ['reset-password']],
            [['password'], 'string', 'min' => 6, 'message' => Yii::t('app', 'invalid information, password has more than 6 characters and include a-z; A-Z; 0-9 or special character "!@#$%^&*()'), 'on' => ['reset-password']],
            [['confirm_password'], 'required', 'message' => Yii::t('app', 'invalid information, password confirm has more than 6 characters and include a-z; A-Z; 0-9 or special character "!@#$%^&*()'), 'on' => ['reset-password']],
            [
                'confirm_password',
                'compare',
                'compareAttribute' => 'password',
                'message' => Yii::t('app', 'invalid information, password confirm and password not match '),
                'on' => ['reset-password']
            ],
            [['phone'], 'validateMsisdn'],
            [['email'], 'validateEmail'],
        ];
    }

    public function validateMsisdn($attribute, $params, $validator)
    {
        $this->$attribute = CUtils::validateMsisdn($this->$attribute);
        if (empty($this->$attribute)) {
            $this->addError($attribute, Yii::t('app', 'Số điện thoại không đúng'));
        }
    }
    public function validateEmail($attribute, $params, $validator)
    {
        list($a, $b) = explode("@", $this->$attribute);
        $rB = explode(".", $b);
        if (count($rB) >= 3) {
            $this->addError($attribute, Yii::t('app', 'Địa chỉ email không hợp lệ'));
        }
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'username' => Yii::t('app', 'Tên đăng nhập'),
            'nickname' => Yii::t('app', 'Biệt danh'),
            'full_name' => Yii::t('app', 'Họ tên'),
            'cmt' => Yii::t('app', 'CMT'),
            'email' => Yii::t('app', 'Email'),
            'phone' => Yii::t('app', 'Điện thoại'),
            'password' => Yii::t('app', 'Mật khẩu'),
            'avatar' => Yii::t('app', 'Ảnh đại diện'),
            'sex' => Yii::t('app', 'Giới tính'),
            'birthday' => Yii::t('app', 'Ngày sinh'),
            'address' => Yii::t('app', 'Địa chỉ'),
            'membership' => Yii::t('app', 'Hạng thành viên'),
            'info' => Yii::t('app', 'Lời giới thiệu'),
            'status' => Yii::t('app', 'Trạng thái'),
            'code' => Yii::t('app', 'Mã giới thiệu'),
            'referral_code' => Yii::t('app', 'Mã người giới thiệu'),
            'referral_count' => Yii::t('app', 'Số người đã giới thiệu'),
            'wallet_total' => Yii::t('app', 'Tổng ví'),
            'debt_old' => Yii::t('app', 'Nợ đầu kỳ'),
            'green_point' => Yii::t('app', 'Điểm đổi quà'),
            'purple_point' => Yii::t('app', 'Điểm đổi xếp hạng'),
            'is_install_app' => Yii::t('app', 'Trạng thái cài app'),
            'created_at' => Yii::t('app', 'Thời gian tạo'),
            'updated_at' => Yii::t('app', 'Thời gian cập nhật'),
        ];
    }

    /**
     * @inheritdoc
     */
    function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'time',
                'attributes' => [
                    self::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    self::EVENT_BEFORE_UPDATE => ['updated_at'],
                    self::EVENT_BEFORE_DELETE => ['updated_at'],
                ]
            ],
        ];
    }

    /**
     * Finds an identity by the given ID.
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id)
    {
        // TODO: Implement findIdentity() method.
        return static::find()->where(['id' => $id])->one();
    }

    /**
     * Finds an identity by the given token.
     * @param mixed $token the token to be looked for
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     * @return IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
        $secret = static::getSecretKey();
        $userLoginToken = AccessToken::findOne(['token_hash' => md5($token), 'type' => AccessToken::TYPE_ACCESS_TOKEN, 'refer_type' => AccessToken::REFER_TYPE_CUSTOMER]);
        if (empty($userLoginToken)) {
            throw new UnauthorizedHttpException(Yii::t('app', 'Your request was made with invalid credentials'));
        }
        // Decode token and transform it into array.
        // Firebase\JWT\JWT throws exception if token can not be decoded
        try {
            $decoded = JWT::decode($token, $secret, [static::getAlgo()]);
        } catch (\Exception $e) {
            throw new UnauthorizedHttpException(Yii::t('app', 'Your request was made with invalid credentials'));
        }
        static::$decodedToken = (array)$decoded;

        // If there's no jti param - exception
        if (!isset(static::$decodedToken['jti'])) {
            throw new UnauthorizedHttpException(Yii::t('app', 'Your request was made with invalid credentials'));
        }

        $userInfo = self::find()->where(['id' => static::$decodedToken['jti']])->one();
        if ($userInfo == null) {
            throw new UnauthorizedHttpException(Yii::t('app', 'Your request was made with invalid credentials'));
        }

        return $userInfo;

    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|int an ID that uniquely identifies a user identity.
     */
    public function getId()
    {
        // TODO: Implement getId() method.
        return $this->getPrimaryKey();
    }

    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @param string $authKey the given auth key
     * @return bool whether the given auth key is valid.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey)
    {
        // TODO: Implement validateAuthKey() method.
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash("$password");
    }

    function validateOldPassword($attribute)
    {
        if (!$this->hasErrors()) {
            $user = self::findOne(['id' => $this->id]);
            if ($user->validatePassword($this->$attribute) == false) {
                $this->addError($attribute, Yii::t('app', 'Old password invalid'));
            }
        }
    }
    /**
     * @param string $
     * @return Customer
     */
    static function findByEmail($email)
    {
        return self::find()->where(['email' => $email])->one();
    }

    /**
     * @param string $
     * @return Customer
     */
    static function findByUsername($username)
    {
        return self::find()->where(['username' => $username])->one();
    }

    /**
     * @param string $
     * @return Customer
     */
    static function findByPhone($phone)
    {
        return self::find()->where(['phone' => $phone])->one();
    }

    /**
     * @param string $
     * @return Customer
     */
    static function findByCode($code)
    {
        return self::find()->where(['code' => $code])->one();
    }

    /**
     * @inheritDoc
     */
    public function getAuthKey()
    {
        // TODO: Implement getAuthKey() method.
    }

    public function setLogin(){
        $jwt_config = Yii::$app->params['jwt-config'];
        $payload = array(
            'iss' => $jwt_config['iss'],
            'aud' => $jwt_config['aud'],
            'exp' => $jwt_config['time'],
            'jti' => $this->id
        );
        $jwt = Customer::generateApiToken($payload);

        //sinh refresh token
        $payloadRefresh = $payload;
        $payloadRefresh['time_refresh'] = $jwt_config['time_refresh'];
        $jwtRefresh = Customer::generateApiToken($payloadRefresh);

//        AccessToken::deleteAll(['refer_id' => $this->id, 'refer_type' => AccessToken::REFER_TYPE_CUSTOMER]);

        $tokenLogin = new AccessToken();
        $tokenLogin->refer_id = $this->id;
        $tokenLogin->refer_type = AccessToken::REFER_TYPE_CUSTOMER;
        $tokenLogin->token = $jwt;
        $tokenLogin->setTokenHash();
        $tokenLogin->expired_at = $jwt_config['time'];
        if (!$tokenLogin->save()) {
            return [
                'success' => false,
                'message' => Yii::t('app', 'Hệ thống bận'),
                'statusCode' => ErrorCode::ERROR_SYSTEM_ERROR,
                'error' => $tokenLogin->errors
            ];
        }

        $tokenLoginRefresh = new AccessToken();
        $tokenLoginRefresh->refer_id = $this->id;
        $tokenLoginRefresh->refer_type = AccessToken::REFER_TYPE_CUSTOMER;
        $tokenLoginRefresh->type = AccessToken::TYPE_REFRESH_TOKEN;
        $tokenLoginRefresh->token = $jwtRefresh;
        $tokenLoginRefresh->setTokenHash();
        $tokenLoginRefresh->expired_at = $jwt_config['time_refresh'];
        if (!$tokenLoginRefresh->save()) {
            return [
                'success' => false,
                'message' => Yii::t('app', 'Hệ thống bận'),
                'statusCode' => ErrorCode::ERROR_SYSTEM_ERROR,
                'error' => $tokenLoginRefresh->errors
            ];
        }
        return [
            'success' => true,
            'access_token' => $jwt,
            'refresh_token' => $jwtRefresh,
            'info_user' => CustomerResponse::findOne(['id' => $this->id])
        ];
    }

    /**
     * Resets password.
     *
     * @return bool if password was reset.
     */
    public function resetPassword() {
        $this->setPassword($this->password);
        return $this->save(false);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $system_code = "";
                while (empty($system_code)) {
                    $system_code = 'RF'.CUtils::generateRandomNumber(6);
                    if (self::findOne(['code' => $system_code])) {
                        $system_code = "";
                    }
                }
                $this->code = $system_code;
            }
            //set hạng
            $this->membership = self::MEMBERSHIP_0;
            if($this->purple_point >= 5000 && $this->purple_point < 50000){
                $this->membership = self::MEMBERSHIP_1;
            }else if($this->purple_point >= 50000){
                $this->membership = self::MEMBERSHIP_2;
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomerDebts()
    {
        return $this->hasMany(CustomerDebt::className(), ['customer_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDebtTotalAmount()
    {
        $customerDebt = CustomerDebt::find()
            ->select(["SUM(total_amount) as total_amount"])
            ->where([
                'customer_id' => $this->id,
            ])->one();
        if(!empty($customerDebt) && (int)$customerDebt->total_amount >= 0){
            return $customerDebt->total_amount;
        }
        return 0;
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDebtTotalPaid()
    {
        $customerDebt = CustomerDebt::find()
            ->select(["SUM(total_paid) as total_paid"])
            ->where([
                'customer_id' => $this->id,
            ])->one();
        if(!empty($customerDebt) && (int)$customerDebt->total_paid >= 0){
            return $customerDebt->total_paid;
        }
        return 0;
    }

    public function upDebtTotal(){
        $customerDebts = CustomerDebt::find()->where(['customer_id' => $this->id])->all();
        $total_amount = 0;
        $total_paid = 0;
        $total_debt = $this->debt_old;
        foreach ($customerDebts as $customerDebt){
            $total_amount += $customerDebt->total_amount;
            $total_paid += $customerDebt->total_paid;
            $total_debt += $customerDebt->total_amount - $customerDebt->total_paid;
        }
        $this->total_amount = $total_amount;
        $this->total_paid = $total_paid;
        $this->total_debt = $total_debt;
        if(!$this->save()){
            Yii::error($this->errors);
        }
    }
}
