<?php

namespace app\api\validate;

use app\BaseValidate;

class User extends BaseValidate
{
    protected  $msg = [];

    public function __construct()
    {
        parent::__construct();
        $this->msg =   [
            'user_mobile.require' => lang('mobile_require'),
            'user_mobile.mobile' => lang('mobile_error'),
            'user_mobile.unique' => lang('mobile_unique'),
            'user_password.require' => lang('user_password_require'),
            'user_password.length' => lang('user_password_length'),
            'code.require' =>  lang('code_require'),
            'code.length' => lang('code_error'),
            'user_trade_password.require' => lang('trade_password_require'),
            'user_trade_password.length' => lang('trade_password_length'),
            'user_nick.max' => lang('user_nick_max'),
            'user_avatar.url' => lang('user_avatar_url'),
            'content.require' => lang('feedback_content_require'),
            'content.max' => lang('feedback_content_max'),
            'wallet_address.require'=>lang('wallet_address_require'),
            'amount.require' => lang('trans_amount_require'),
        ];
    }

    protected $rule = [
        'user_mobile' => 'require|unique:user|mobile',
        'user_password' => 'require|length:6,20',
        'user_trade_password' => 'require|length:6,20',
        'code' => 'require|length:6',
        'user_avatar' => 'require|url',
        'user_nick' => 'max:25',
        'content' => 'require|max:255',
        'wallet_address'=> 'require',
        'amount' => 'require'
    ];



    public function sceneAdd()
    {
        return $this->only(['user_mobile','user_password','code','user_trade_password'])->message($this->msg);
    }


    public function sceneSendCode()
    {
        return $this->only(['user_mobile'])->remove('user_mobile','unique')->message($this->msg);
    }


    public function sceneLogin()
    {
        return $this->only(['user_mobile','user_password'])->remove('user_mobile','unique')->message($this->msg);
    }


    public function sceneResetPassword()
    {
        return $this->only(['user_mobile','code','user_password'])->remove('user_mobile','unique')->message($this->msg);
    }

    public function sceneResetPasswordApp()
    {
        return $this->only(['code','user_password'])->message($this->msg);
    }

    public function sceneResetTradePassword()
    {
        return $this->only(['code','user_trade_password'])->message($this->msg);
    }


    public function sceneSaveUserInfo()
    {
        return $this->only(['user_avatar','user_nick'])->remove('user_avatar','require')->message($this->msg);
    }

    public function sceneSaveMobile()
    {
        return $this->only(['user_mobile','code'])->message($this->msg);
    }


    public function sceneFeedback()
    {
        return $this->only(['content'])->message($this->msg);
    }

    public function sceneUserTransfer()
    {
        return $this->only(['wallet_address','amount','user_trade_password'])->message($this->msg);
    }

    public function sceneTransferToHoldWallet()
    {
        return $this->only(['amount','user_trade_password'])->message($this->msg);
    }

    public function sceneTransferToOtcWallet()
    {
        return $this->only(['amount','user_trade_password'])->message($this->msg);
    }

    public function sceneTransferOtcToSendWallet()
    {
        return $this->only(['amount','user_trade_password'])->message($this->msg);
    }

    public function sceneCheckIsRegister()
    {
        return $this->only(['user_mobile'])->message($this->msg);
    }

}