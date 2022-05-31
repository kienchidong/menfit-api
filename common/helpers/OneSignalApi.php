<?php

namespace common\helpers;

use Yii;
use yii\helpers\Json;


class OneSignalApi
{
    public static function pushNotify($subject, $content, $include_player_ids = [], $data = [], $ONE_KEY = 'CUS')
    {

        $message = [
              "subtitle" => ["en" => $subject],
              "contents" => ["en" => $content],
              "include_player_ids" => $include_player_ids,
//              "data" => [
//                     'type' => 'payment_fee',
//                     'action' => "pay",
//                     'post_id' => $this->id,
//               ]
        ];
        if(!empty($data)){
            $message['data'] = $data;
        }
        $OneSignal = Yii::$app->params['OneSignal'];
        $url = $OneSignal['URL_API'];
        $curl = new MyCurl();
        $curl->headers = array(
            'Content-Type' => 'application/json; charset=utf-8'
        );
        Yii::info($OneSignal);
        Yii::info($ONE_KEY);
        if($ONE_KEY == null){ $ONE_KEY = 'CUS';}
        $message['app_id'] = $OneSignal[$ONE_KEY]['APP_ID'];
        $data = $curl->post($url, json_encode($message));
        Yii::info(Json::decode($data));
        return Json::decode($data);
    }

}