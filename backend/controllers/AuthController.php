<?php

namespace backend\controllers;

use common\helpers\ErrorCode;
use backend\models\LoginForm;
use common\models\User;
use Yii;
use yii\web\Controller;

/**
 * Auth Controller
 */
class AuthController extends BaseController
{
    //public $isPublic = true;
    public function actionLogin()
    {
        $this->checkMethod('POST');
        $model = new LoginForm();
         $model->setScenario('loginUser');
        $model->load(Yii::$app->request->bodyParams, '');
        if (!$model->validate()) {
            return [
                'success' => false,
                'message' => Yii::t('app', "Invalid data"),
                'statusCode' => ErrorCode::ERROR_INVALID_PARAM,
                'errors' => $model->getErrors()
            ];
        }

        $user = $model->loginUser();
        return $this->response(true, $user, '', '', 200, null);
    }

    public function actionLogout(){
        return 1;
    }
}