<?php

namespace common\models;

use common\helpers\ErrorCode;
use Yii;
use yii\base\Model;
use yii\db\Exception;

/**
 * @SWG\Definition(
 *   type="object",
 *   @SWG\Xml(name="CustomerResponse")
 * )
 */
class CustomerResponse extends Customer
{
    /**
     * @SWG\Property(property="id", type="integer"),
     * @SWG\Property(property="username", type="string"),
     * @SWG\Property(property="code", type="string", description="mã giới thiệu để mời người khác"),
     * @SWG\Property(property="phone", type="string"),
     * @SWG\Property(property="email", type="string"),
     * @SWG\Property(property="full_name", type="string"),
     * @SWG\Property(property="info", type="string"),
     * @SWG\Property(property="address", type="string"),
     * @SWG\Property(property="avatar", type="string"),
     * @SWG\Property(property="referral_code", type="string", description="mã của người giới thiệu"),
     * @SWG\Property(property="referral_count", type="integer", description="số người đã giới thiệu"),
     * @SWG\Property(property="membership", type="integer", description="hạng thành viên: 0 - thường, 1- ..."),
     * @SWG\Property(property="sex", type="integer"),
     * @SWG\Property(property="status", type="integer"),
     * @SWG\Property(property="wallet_total", type="number", description="tổng tiền trong ví"),
     * @SWG\Property(property="green_point", type="integer", description="điểm đổi quà"),
     * @SWG\Property(property="purple_point", type="integer", description="điểm xếp hạng"),
     * @SWG\Property(property="created_at", type="integer"),
     * @SWG\Property(property="birthday", type="integer"),
     * @SWG\Property(property="cmt", type="string"),
     */

    public function fields()
    {
        return [
            'id',
            'username',
            'phone',
            'email',
            'full_name',
            'address',
            'info',
            'avatar' => function($model){
                return !empty($model->avatar) ? $model->avatar : '';
            },
            'sex',
            'birthday',
            'info',
            'status',
            'code',
            'referral_code',
            'referral_count',
            'membership',
            'wallet_total',
            'green_point',
            'purple_point',
            'cmt',
            'created_at'
        ];
    }
}
