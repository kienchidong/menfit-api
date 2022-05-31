<?php
/**
 * Created by PhpStorm.
 * User: qhuy.duong@gmail.com
 * Date: 14/04/2017
 * Time: 10:38 SA
 */

namespace common\helpers;


class ErrorCode
{
    const ERROR_NOT_FOUND = 404;
    const ERROR_PERMISSION_DENIED = 402;
    const ERROR_INACTIVE = 403;
    const ERROR_STATUS_INVALID = 405;
    const ERROR_ONLY_GUEST = 406;
    const ERROR_INVALID_PARAM = 501;
    const ERROR_SYSTEM_ERROR = 599;
    const ERROR_ALREADY_ACCEPT = 598;
    const ERROR_INVALID_LOCATION = 407;
    const ERROR_NOT_ENOUGH_COIN = 597;
    const ERROR_NOT_MATCH_REQUEST = 408;
    const ERROR_INVALID_TIME = 409;
    const INTERNET_NOT_AVAIABLE = 597;
}