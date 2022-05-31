<?php

namespace backend\models;

use common\helpers\ErrorCode;
use common\models\Customer;
use common\models\PublicUser;
use common\models\User;
use Firebase\JWT\JWT;
use phpDocumentor\Reflection\Types\This;
use yii\base\Model;
use Yii;

class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user;

   private $hiddenField = ['password_salt', 'password_hash'];

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required', 'on' => 'loginUser'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        }

        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->username);
        }
        return $this->_user;
    }


    public function loginUser()
    {
        if ($this->validate()) {
            $model = self::getUser();
            if (empty($model)) {
                $model = new User();
            }


            $model->auth_key = JWT::encode(
                [
                    'id' => $model->id,
                    'username' => $model->username,
                    'password_hash' => $model->password_hash,
                    'user_agent' => \Yii::$app->request->getUserAgent(),
                    'expired_at' => (time() + 86400),
                    'refresh_token' => \Yii::$app->security->generateRandomString(32)
                ],
                \Yii::$app->params['backendAuthKey']
            );
            $auth = self::getPublicUser();
            return $auth;
        } else {
            return [
                'success' => false,
                'message' => Yii::t('app', 'Hệ thống bận'),
                'statusCode' => ErrorCode::ERROR_SYSTEM_ERROR,
                'error' => $this->errors
            ];
        }
    }

    /**
     * @return PublicUser|null
     */
    function getPublicUser()
    {
        $user = $this->_user;
        if ($user !== null) {
            foreach ($this->hiddenField as $item) {
                unset($user[$item]);
            }
        }

        return $user;
    }
}
