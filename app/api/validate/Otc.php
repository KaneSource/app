<?php

namespace app\api\validate;

use app\BaseValidate;

class Otc extends BaseValidate
{
    protected  $msg = [];

    public function __construct()
    {
        parent::__construct();
        $this->msg =   [
            'currency_id.require' => lang('currency_require'),
            'sell_num.require' => lang('sell_num_require'),
            'payment.require' => lang('payment_require'),
            'good_id.require' => lang('good_id_require'),
            'num.require' => lang('num_require'),
            'order_nub.require' => lang('order_nub_require'),
            'payment_img.require' => lang('payment_img_require'),
            'appeal.require' => lang('appeal_require'),
            'appeal.max' => lang('appeal_max'),
            'appeal_image.require' => lang('appeal_image_require')
        ];
    }

    protected $rule = [
        'currency_id' => 'require',
        'sell_num' => 'require',
        'payment' => 'require',
        'good_id' => 'require',
        'num' => 'require',
        'order_nub'=> 'require',
        'payment_img' => 'require',
        'appeal' => 'require|max:255',
        'appeal_image' => 'require'

    ];



    public function sceneCreateGood()
    {
        return $this->only(['currency_id','sell_num','payment'])->message($this->msg);
    }

    public function sceneBuyCurrency()
    {
        return $this->only(['good_id','num','payment'])->message($this->msg);
    }

    public function sceneUnderGood()
    {
        return $this->only(['good_id'])->message($this->msg);
    }

    public function sceneGetGoodPayment()
    {
        return $this->only(['good_id'])->message($this->msg);
    }

    public function sceneSetOrderPayed()
    {
        return $this->only(['order_nub','payment_img'])->message($this->msg);
    }

    public function sceneOrderAppeal()
    {
        return $this->only(['order_nub','appeal','appeal_image'])->message($this->msg);
    }

    public function sceneUserSetOrderNoPay()
    {
        return $this->only(['order_nub'])->message($this->msg);
    }

    public function sceneUserSendCurrency()
    {
        return $this->only(['order_nub'])->message($this->msg);
    }



}