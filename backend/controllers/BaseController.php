<?php

namespace backend\controllers;

use common\models\User;
use common\request\Response;
use function PHPSTORM_META\elementType;
use yii\base\Exception;
use yii\rest\Controller;
use yii\web\BadRequestHttpException;

class BaseController extends Controller
{
    public $publicActions = [];
    public $protectActions = [];

    public $isPublic = false;
    public $isProtect = false;
    private $methodAllow = ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'];

    public function init()
    {
        parent::init();
        \Yii::$app->user->enableSession = false;
    }

    function forceResponse($data, $code = 200)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        \Yii::$app->response->data = $data;
        \Yii::$app->response->statusCode = $code;
        \Yii::$app->response->send();
        die;
    }

    public function beforeAction($action)
    {
        //allow request rest headers.
        \Yii::$app->response->headers->set('Access-Control-Allow-Credentials', 'true');
        \Yii::$app->response->headers->set('Access-Control-Allow-Headers', 'authorization,content-type');
        \Yii::$app->response->headers->set('Access-Control-Max-Age', '600');
        //allow request rest headers.

        $token = str_replace('Bearer ', '', \Yii::$app->request->headers->get('Authorization'));

        if (empty($token)) {
            $token = \Yii::$app->request->get('token');
        }

        $user = User::authByToken($token);

        if ($user == null) {
            if (!in_array(\Yii::$app->controller->action->id, $this->publicActions) && !$this->isPublic) {
                $this->forceResponse($this->response(false, null, 'Vui lòng đăng nhập', 'Unauthorized', 401), 401);
            }
        } else {
            /*try {
                if (!$this->roleCheck()) {
                    $this->forceResponse($this->responseMessage(false, 'Bạn không có quyền truy cập tính năng này'), 401);
                }
            } catch (Exception $exception) {
                $this->forceResponse($this->response(false, null, 'Vui lòng đăng nhập', 'Unauthorized', 401), 401);
            }*/
        }

        try {
            return parent::beforeAction($action);
        } catch (BadRequestHttpException $badRequestHttpException) {
        }
        return null;
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator']['formats']['text/html'] = \yii\web\Response::FORMAT_JSON;
        $behaviors['contentNegotiator']['formats']['application/xml'] = \yii\web\Response::FORMAT_JSON;
        return $behaviors;
    }

    public function response($success = true, $data = null, $message = null, $title = null, $code = 0, $paging = null, $other_data = null)
    {
        return new Response($success, $data, $message, $title, $code, $paging, $other_data);
    }

    public function success()
    {
        return new Response(true);
    }

    public function responseMessage($success = true, $message = null, $title = null, $code = 0, $data = null)
    {
        return new Response($success, $data, $message, $title, $code, null, null);
    }

    public function responsePopup($success = true, $message = null, $title = null, $code = 0)
    {
        return new Response($success, 'popup', $message, $title, $code, null, null);
    }

    public function checkMethod($method = 'GET')
    {
        $checkAllow = array_search($method, $this->methodAllow);
        $requestMethod = \Yii::$app->request->getMethod();
        if (!$checkAllow || (trim(strtolower($method)) != trim(strtolower($requestMethod)))) {
            $this->forceResponse($this->responseMessage(false, "Method $requestMethod not allowed"), 405);
        }
    }
}