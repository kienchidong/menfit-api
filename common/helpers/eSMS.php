<?php

namespace common\helpers;
use Exception;
use Yii;
use yii\helpers\Json;

class eSMS
{
    private $base_url = '';
    private $api_key = '';
    private $api_secret = '';
    private $ch;
    private $production = false;

    const SMS_TYPE_CSKH = 6;
    const SMS_TYPE_NOTIFY = 4;
    const SMS_TYPE_FIX = 8;
    const SMS_TYPE_BRAND_NAME = 2;

    const RESPONSE_SUCCESS = 100;

    public function __construct()
    {
        $this->production = Yii::$app->params['in_production'];
        $esms_config = Yii::$app->params['esms'];
        $this->base_url = $esms_config['base_url'];
        $this->api_key = $esms_config['api_key'];
        $this->api_secret = $esms_config['api_secret'];
        $this->ch = new \common\helpers\MyCurl();
    }

    /**
     * @param $msisdn
     * @param $message
     * @param $production
     */
    public function send($msisdn, $message){

        $msisdn = \common\helpers\CUtils::validateMsisdn($msisdn);
        if(empty($msisdn) || empty($message)) return null;

        try{
            if($this->production){
                $response = $this->ch->get($this->base_url.'/SendMultipleMessage_V4_get', [
                    'Phone' => $msisdn,
                    'ApiKey' => $this->api_key,
                    'SecretKey' => $this->api_secret,
                    'Content' => $message,
                    'SmsType' => self::SMS_TYPE_NOTIFY
                ]);
            }else{
                $response = new \stdClass();
                $response->body = '{"CodeResult": "100","CountRegenerate":"0","SMSID": "24342680"}';
            }
        }catch (Exception $e){
            Yii::error($e->getMessage());
            return null;
        }
        if(!$response){
            Yii::error('Response null');
            return null;
        }
        Yii::info($response->body);
        return Json::decode($response->body);
    }

}