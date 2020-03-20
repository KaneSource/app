<?php

namespace app\api\controller;

use app\api\model\UserMine;
use app\api\model\UserNoble;
use app\api\model\UserProp;
use app\api\model\UserRechargeRecord;
use app\api\model\UserWallet;
use think\exception\HttpException;
use think\facade\Cache;

/**
 * @apiDefine User 用户
 * 操作用户资源相关的接口
 */

class User extends Base
{

    /**
     * @api {post}  /user/register 用户注册
     * @apiUse User
     * @apiPermission 所有人
     * @apiDescription 用户注册
     * @apiGroup User
     * @apiName user-register
     * @apiSampleRequest /user/register
     * @apiParam {string}  user_mobile  用户手机号
     * @apiParam {string}  user_password  用户密码
     * @apiParam {string}  user_trade_password  交易密码
     * @apiParam {int}  code  验证码
     * @apiParam {int}  invite_code  邀请码
     * @apiSuccess {string}  data  用户身份令牌
     * @apiSuccess {integer} code 响应码 请参照code对照
     * @apiSuccess {string}  msg 提示信息
     * @apiVersion 1.0.0
     */
    public function register()
    {
        try {
            $addParam = input('param.');
            self::validate($addParam, 'User.add');
            check_code($addParam['user_mobile'],$addParam['code']);
            $addResult = \app\api\model\User::addUser($addParam);
            self::$_code = self::STATE_SUCCESS;
            self::$_msg  = lang('register_success');
            self::$_data = $addResult;
            $this->responseJson();
        } catch (HttpException $e) {
            $this->exceptionHandle($e);
        }
    }


    /**
     * @api {post}  /user/checkIsRegister 检测手机号是否注册
     * @apiUse User
     * @apiPermission 所有人
     * @apiDescription 检测手机号是否注册
     * @apiGroup User
     * @apiName user-checkIsRegister
     * @apiSampleRequest /user/checkIsRegister
     * @apiSuccess {string}  data  1 已注册  5未注册
     * @apiSuccess {integer} code 响应码 请参照code对照
     * @apiSuccess {string}  msg 提示信息
     * @apiVersion 1.0.0
     */
    public function checkIsRegister()
    {
        try {
            $param = input('param.');
            self::validate($param, 'User.checkIsRegister');
            self::$_code = self::STATE_SUCCESS;
            $this->responseJson();
        } catch (HttpException $e) {
            $this->exceptionHandle($e);
        }
    }



    /**
     * @api {post}  /user/login 密码登录
     * @apiUse User
     * @apiPermission 所有人
     * @apiDescription 密码登录
     * @apiGroup User
     * @apiName user-login
     * @apiSampleRequest /user/login
     * @apiParam {string}  user_mobile  用户手机号
     * @apiParam {string}  user_password  用户密码
     * @apiSuccess {string}  data  用户身份令牌
     * @apiSuccess {integer} code 响应码 请参照code对照
     * @apiSuccess {string}  msg 提示信息
     * @apiVersion 1.0.0
     */
    public function login()
    {
        try {
            $loginParam = input('param.');
            self::validate($loginParam, 'User.login');
            $loginResult = \app\api\model\User::login($loginParam);
            self::$_code = self::STATE_SUCCESS;
            self::$_msg  = lang('login_success');
            self::$_data = $loginResult;
            $this->responseJson();
        } catch (HttpException $e) {
            $this->exceptionHandle($e);
        }

    }



    /**
     * @api {post}  /user/resetPassword 重置登录密码(登录页面的忘记密码,这里需要输入手机号)
     * @apiUse User
     * @apiPermission 所有人
     * @apiDescription 重置登录密码(登录页面的忘记密码,这里需要输入手机号)
     * @apiGroup User
     * @apiName user-resetPassword
     * @apiSampleRequest /user/resetPassword
     * @apiParam {string}  user_mobile  用户手机号
     * @apiParam {string}  user_password  新密码
     * @apiParam {string}  code  验证码
     * @apiSuccess {integer} code 响应码 请参照code对照
     * @apiSuccess {string}  msg 提示信息
     * @apiSuccess {string}  data token 身份令牌
     * @apiVersion 1.0.0
     */
    public function resetPassword()
    {
        try {
            $resetParam = input('param.');
            self::validate($resetParam, 'User.resetPassword');
            $token = \app\api\model\User::resetPassword($resetParam);
            self::$_code = self::STATE_SUCCESS;
            self::$_msg  = lang('reset_user_password_success');
            self::$_data = $token;
            $this->responseJson();
        } catch (HttpException $e) {
            $this->exceptionHandle($e);
        }

    }


    /**
     * @api {post}  /user/resetPasswordApp 重置登录密码(app里面的重置密码)
     * @apiUse User
     * @apiPermission 所有人
     * @apiDescription 重置登录密码(app里面的重置密码)
     * @apiGroup User
     * @apiName user-resetPasswordApp
     * @apiSampleRequest /user/resetPasswordApp
     * @apiParam {string}  user_password  新密码
     * @apiParam {string}  code  验证码
     * @apiSuccess {integer} code 响应码 请参照code对照
     * @apiSuccess {string}  msg 提示信息
     * @apiSuccess {string}  data token 身份令牌
     * @apiVersion 1.0.0
     */
    public function resetPasswordApp()
    {
        try {
            $resetParam = input('param.');
            self::validate($resetParam, 'User.resetPasswordApp');
            check_code($this->getU()->user_mobile,$resetParam['code']);
            $token = \app\api\model\User::resetPasswordApp($this->getUid(),$resetParam['user_password']);
            self::$_code = self::STATE_SUCCESS;
            self::$_msg  = lang('reset_user_password_success');
            self::$_data = $token;
            $this->responseJson();
        } catch (HttpException $e) {
            $this->exceptionHandle($e);
        }

    }


    /**
     * @api {post}  /user/resetTradePassword 重置交易密码(不让用户输入手机号 短信发送到已绑定的手机号)
     * @apiUse User
     * @apiPermission 所有人
     * @apiDescription 重置交易密码不让用户输入手机号 短信发送到已绑定的手机号)
     * @apiGroup User
     * @apiName user-resetTradePassword
     * @apiSampleRequest /user/resetTradePassword
     * @apiParam {string}  user_trade_password 新密码
     * @apiParam {string}  code  验证码
     * @apiSuccess {integer} code 响应码 请参照code对照
     * @apiSuccess {string}  msg 提示信息
     * @apiSuccess {string}  data token 身份令牌
     * @apiVersion 1.0.0
     */
    public function resetTradePassword()
    {
        try {
            $resetParam = input('param.');
            self::validate($resetParam, 'User.resetTradePassword');
            check_code($this->getU()->user_mobile,$resetParam['code']);
            $token = \app\api\model\User::resetTradePassword($this->getUid(),$resetParam['user_trade_password']);
            Cache::tag(\app\api\model\User::$_cache_tag)->clear($this->accessToken);
            self::$_code = self::STATE_SUCCESS;
            self::$_msg  = lang('reset_user_trade_password_success');
            self::$_data = $token;
            $this->responseJson();
        } catch (HttpException $e) {
            $this->exceptionHandle($e);
        }

    }

    /**
     * @api {post}  /user/userInfo 获取用户信息
     * @apiUse User
     * @apiPermission 所有人
     * @apiDescription 获取用户基本信息
     * @apiGroup User
     * @apiName user-userInfo
     * @apiSampleRequest /user/userInfo
     * @apiSuccess {string}  user[user_mobile] 手机号、账号
     * @apiSuccess {string}  user[avatar] 头像
     * @apiSuccess {string}  user[user_nick] 昵称
     * @apiSuccess {string}  user[invite_code] 邀请码
     * @apiSuccess {string}  user[user_lang] 用户选择的语言版本
     * @apiSuccess {float}   wallet[hold_amount] 持币账户余额
     * @apiSuccess {float}   wallet[open_amount] 可发送账户余额
     * @apiSuccess {float}   wallet[otc_amount]  otc账户余额
     * @apiSuccess {float}   wallet[wallet_address]  钱包地址
     * @apiSuccess {integer} code 响应码 请参照code对照
     * @apiSuccess {string}  msg 提示信息
     * @apiSuccess {string}  data 返回数据
     * @apiVersion 1.0.0
     */
    public function userInfo()
    {
        $user = [];
        $u = $this->getU();
        $user['user_mobile'] = sub_mobile($u->user_mobile);
        $user['mobile'] = $u->user_mobile;
        $user['avatar'] = $u->user_avatar ? $u->user_avatar : DEFAULT_AVATAR;
        $user['user_nick'] = $u->user_nick;
        $user['invite_code'] = $u->invite_code;
        $user['user_lang'] = $u->user_lang;
        $user_level = UserMine::userLevel($u->user_id);
        $user['user_level'] = lang('user_level'.$user_level);
        $wallet =  UserWallet::userWallet($this->getUid(),'hold_amount,open_amount,otc_amount,wallet_address');
        self::$_data = ['user'=>$user,'wallet'=>$wallet];
        $this->responseJson();
    }


    /**
     * @api {post}  /user/saveUserInfo 保存用户信息
     * @apiUse User
     * @apiPermission 所有人
     * @apiDescription 获取用户基本信息(目前只支持保存头像)
     * @apiGroup User
     * @apiName user-saveUserInfo
     * @apiSampleRequest /user/saveUserInfo
     * @apiParam {string}  user_avatar  用户头像地址
     * @apiParam {string}  user_nick  用户昵称
     * @apiParam {string}  user_lang  语言版本
     * @apiSuccess {integer} code 响应码 请参照code对照
     * @apiSuccess {string}  msg 提示信息
     * @apiVersion 1.0.0
     */
    public function saveUserInfo()
    {

        try {
            $param = input('param.');
            self::validate($param, 'User.saveUserInfo');
            \app\api\model\User::saveUserInfo($param,$this->getUid());
            Cache::tag(\app\api\model\User::$_cache_tag)->clear($this->accessToken);
            self::$_code = self::STATE_SUCCESS;

            $this->responseJson();
        } catch (HttpException $e) {
            $this->exceptionHandle($e);
        }
    }



    /**
     * @api {post}  /user/saveMobile 更换手机
     * @apiUse User
     * @apiPermission 所有人
     * @apiDescription 更换手机
     * @apiGroup User
     * @apiName user-saveMobile
     * @apiSampleRequest /user/saveMobile
     * @apiParam {string}  user_mobile  新手机号
     * @apiParam {string}  code  验证码
     * @apiSuccess {integer} code 响应码 请参照code对照
     * @apiSuccess {string}  msg 提示信息
     * @apiVersion 1.0.0
     */
    public function saveMobile()
    {
        try {
            $param = input('param.');
            self::validate($param, 'User.saveMobile');
            check_code($this->getU()->user_mobile,$param['code']);
            \app\api\model\User::saveMobile($param['user_mobile'],$this->getUid());
            Cache::tag(\app\api\model\User::$_cache_tag)->clear($this->accessToken);
            self::$_code = self::STATE_SUCCESS;
            $this->responseJson();
        } catch (HttpException $e) {
            $this->exceptionHandle($e);
        }
    }

    /**
     * @api {post}  /user/userTransfer 转账给其他用户(可发送账户)
     * @apiUse User
     * @apiPermission 所有人
     * @apiDescription 转账给其他用户(可发送账户)
     * @apiGroup User
     * @apiName user-userTransfer
     * @apiSampleRequest /user/userTransfer
     * @apiParam {string}  wallet_address  收款钱包地址
     * @apiParam {string}  user_trade_password  交易密码
     * @apiParam {string}  amount  转账额度
     * @apiSuccess {integer} code 响应码 请参照code对照
     * @apiSuccess {string}  msg 提示信息
     * @apiVersion 1.0.0
     */
    public function userTransfer()
    {
        try {
            $param = input('param.');
            self::validate($param, 'User.userTransfer');
            \app\api\model\User::checkTradePassword($param['user_trade_password'],$this->getU()->user_trade_password);
             UserWallet::userTransfer($this->getUid(),$param['wallet_address'],$param['amount']);
            self::$_code = self::STATE_SUCCESS;
            $this->responseJson();
        } catch (HttpException $e) {
            $this->exceptionHandle($e);
        }
    }


    /**
     * @api {post}  /user/transferToHoldWallet 从可发送账户转入持币账户
     * @apiUse User
     * @apiPermission 所有人
     * @apiDescription 从可发送账户转入持币账户
     * @apiGroup User
     * @apiName user-transferToHoldWallet
     * @apiSampleRequest /user/transferToHoldWallet
     * @apiParam {string}  user_trade_password  交易密码
     * @apiParam {string}  amount  转账额度
     * @apiSuccess {integer} code 响应码 请参照code对照
     * @apiSuccess {string}  msg 提示信息
     * @apiVersion 1.0.0
     */
    public function transferToHoldWallet()
    {
        try {
            $param = input('param.');
            self::validate($param, 'User.transferToHoldWallet');
            \app\api\model\User::checkTradePassword($param['user_trade_password'],$this->getU()->user_trade_password);
            UserWallet::transferToHoldWallet($this->getUid(),$param['amount']);
            self::$_code = self::STATE_SUCCESS;
            $this->responseJson();
        } catch (HttpException $e) {
            $this->exceptionHandle($e);
        }
    }


    /**
     * @api {post}  /user/transferToOtcWallet 从可发送钱包转入OTC钱包
     * @apiUse User
     * @apiPermission 所有人
     * @apiDescription 从可发送钱包转入OTC钱包
     * @apiGroup User
     * @apiName user-transferToOtcWallet
     * @apiSampleRequest /user/transferToOtcWallet
     * @apiParam {string}  user_trade_password  交易密码
     * @apiParam {string}  amount  转账额度
     * @apiSuccess {integer} code 响应码 请参照code对照
     * @apiSuccess {string}  msg 提示信息
     * @apiVersion 1.0.0
     */
    public function transferToOtcWallet()
    {
        try {
            $param = input('param.');
            self::validate($param, 'User.transferToOtcWallet');
            \app\api\model\User::checkTradePassword($param['user_trade_password'],$this->getU()->user_trade_password);
            UserWallet::transferToOtcWallet($this->getUid(),$param['amount']);
            self::$_code = self::STATE_SUCCESS;
            $this->responseJson();
        } catch (HttpException $e) {
            $this->exceptionHandle($e);
        }
    }


    /**
     * @api {post}  /user/transferToSendWallet 从OTC钱包转入可发送钱包
     * @apiUse User
     * @apiPermission 所有人
     * @apiDescription 从OTC钱包转入可发送钱包
     * @apiGroup User
     * @apiName user-transferToSendWallet
     * @apiSampleRequest /user/transferToSendWallet
     * @apiParam {string}  user_trade_password  交易密码
     * @apiParam {string}  amount  转账额度
     * @apiSuccess {integer} code 响应码 请参照code对照
     * @apiSuccess {string}  msg 提示信息
     * @apiVersion 1.0.0
     */
    public function transferToSendWallet()
    {
        try {
            $param = input('param.');
            self::validate($param, 'User.transferOtcToSendWallet');
            \app\api\model\User::checkTradePassword($param['user_trade_password'],$this->getU()->user_trade_password);
            UserWallet::transferToSendWallet($this->getUid(),$param['amount']);
            self::$_code = self::STATE_SUCCESS;
            $this->responseJson();
        } catch (HttpException $e) {
            $this->exceptionHandle($e);
        }
    }

















}