<?php

namespace app\api\controller;

use app\api\model\BusinessConf;
use app\api\model\OtcCurrency;
use app\api\model\UserGood;
use app\api\model\UserGoodOrder;
use app\api\model\UserPayment;
use app\api\model\UserWallet;
use app\manager\model\SystemPayment;
use think\exception\HttpException;
use think\facade\Cache;


class Otc extends Base
{

    public function currencyLists()
    {
        try {
            self::$_data = OtcCurrency::lists();
            self::$_code = self::STATE_SUCCESS;
            $this->responseJson();
        } catch (HttpException $e) {
            $this->exceptionHandle($e);
        }
    }


    public function setPayment()
    {
        try {
            $param = input('param.');
            $data = [];
            if (isset($param['alipay_account']) && $param['alipay_account']) {
                $data['alipay_account'] = $param['alipay_account'];
            }
            if (isset($param['alipay_qrcode']) && $param['alipay_qrcode']) {
                $data['alipay_qrcode'] = $param['alipay_qrcode'];
            }
            if (isset($param['alipay_username']) && $param['alipay_username']) {
                $data['alipay_username'] = $param['alipay_username'];
            }
            if (isset($param['wxpay_account']) && $param['wxpay_account']) {
                $data['wxpay_account'] = $param['wxpay_account'];
            }
            if (isset($param['wxpay_qrcode']) && $param['wxpay_qrcode']) {
                $data['wxpay_qrcode'] = $param['wxpay_qrcode'];
            }
            if (isset($param['bank_account']) && $param['bank_account']) {
                $data['bank_account'] = $param['bank_account'];
            }
            if (isset($param['bank_open_address']) && $param['bank_open_address']) {
                $data['bank_open_address'] = $param['bank_open_address'];
            }
            if (isset($param['bank_username']) && $param['bank_username']) {
                $data['bank_username'] = $param['bank_username'];
            }
            self::$_data = UserPayment::savePayment($this->getUid(),$data);
            self::$_code = self::STATE_SUCCESS;
            $this->responseJson();
        } catch (HttpException $e) {
            $this->exceptionHandle($e);
        }
    }

    public function getPayment()
    {
        try {
            self::$_data = UserPayment::getUserPayment($this->getUid());
            self::$_code = self::STATE_SUCCESS;
            $this->responseJson();
        } catch (HttpException $e) {
            $this->exceptionHandle($e);
        }
    }


    public function sellCurrency()
    {
        $param = input('param.');
        try {
            self::validate($param, 'Otc.createGood');
            UserGood::createGood($this->getUid(),$param);
            self::$_code = self::STATE_SUCCESS;
            $this->responseJson();
        } catch (HttpException $e) {
            $this->exceptionHandle($e);
        }
    }


    public function buyCurrency()
    {
            $param = input('param.');
            try {
                self::validate($param, 'Otc.buyCurrency');
                self::$_data =  UserGoodOrder::createOrder($this->getUid(),$param);
                self::$_code = self::STATE_SUCCESS;
                $this->responseJson();
            } catch (HttpException $e) {
                $this->exceptionHandle($e);
            }
    }



    public function underGood()
    {
        $param = input('param.');
        try {
            self::validate($param, 'Otc.underGood');
            self::$_data =  UserGood::underGood($this->getUid(),$param);
            self::$_code = self::STATE_SUCCESS;
            $this->responseJson();
        } catch (HttpException $e) {
            $this->exceptionHandle($e);
        }
    }

    public function getGoodPayment()
    {
        $param = input('param.');
        try {
            self::validate($param, 'Otc.getGoodPayment');
            self::$_data =  UserGood::getGoodPayment($param['good_id']);
            self::$_code = self::STATE_SUCCESS;
            $this->responseJson();
        } catch (HttpException $e) {
            $this->exceptionHandle($e);
        }
    }

    public function setOrderPayed()
    {
        $param = input('param.');
        try {
            self::validate($param, 'Otc.setOrderPayed');
             UserGoodOrder::setOrderPayed($param);
            self::$_code = self::STATE_SUCCESS;
            $this->responseJson();
        } catch (HttpException $e) {
            $this->exceptionHandle($e);
        }
    }



    public function userGoodAll()
    {
        $param = input('param.');
        try {
            self::$_data =  UserGood::userGoodAll($param);
            self::$_code = self::STATE_SUCCESS;
            $this->responseJson();
        } catch (HttpException $e) {
            $this->exceptionHandle($e);
        }
    }



    public function getUserGood()
    {
            $param = input('param.');
            try {
                self::$_data =  UserGood::getUserGood($this->getUid(),$param);
                self::$_code = self::STATE_SUCCESS;
                $this->responseJson();
            } catch (HttpException $e) {
                $this->exceptionHandle($e);
            }
    }



    public function getUserGoodOrder()
    {
        $param = input('param.');
        try {
            self::$_data =  UserGoodOrder::getUserGoodOrder($this->getUid(),$param);
            self::$_code = self::STATE_SUCCESS;
            $this->responseJson();
        } catch (HttpException $e) {
            $this->exceptionHandle($e);
        }
    }



    public function getUserBuyGoodOrder()
    {
        $param = input('param.');
        try {
            self::$_data =  UserGoodOrder::getUserBuyGoodOrder($this->getUid(),$param);
            self::$_code = self::STATE_SUCCESS;
            $this->responseJson();
        } catch (HttpException $e) {
            $this->exceptionHandle($e);
        }
    }




    public function orderAppeal()
    {
        $param = input('param.');
        try {
            self::validate($param, 'Otc.orderAppeal');
            UserGoodOrder::orderAppeal($param);
            self::$_code = self::STATE_SUCCESS;
            $this->responseJson();
        } catch (HttpException $e) {
            $this->exceptionHandle($e);
        }
    }



    public function userSetOrderNoPay()
    {
        $param = input('param.');
        try {
            self::validate($param, 'Otc.userSetOrderNoPay');
            UserGoodOrder::userSetOrderNoPay($param['order_nub']);
            self::$_code = self::STATE_SUCCESS;
            $this->responseJson();
        } catch (HttpException $e) {
            $this->exceptionHandle($e);
        }
    }


    public function userSendCurrency()
    {
        $param = input('param.');
        try {
            self::validate($param, 'Otc.userSendCurrency');
            UserGoodOrder::userSendCurrency($param['order_nub']);
            self::$_code = self::STATE_SUCCESS;
            $this->responseJson();
        } catch (HttpException $e) {
            $this->exceptionHandle($e);
        }
    }

    public function getCommission()
    {
        try {
            self::$_data = BusinessConf::getConf(BusinessConf::CONF_KEY_OTC_SELL_FEE);
            self::$_code = self::STATE_SUCCESS;
            $this->responseJson();
        } catch (HttpException $e) {
            $this->exceptionHandle($e);
        }
    }


    public function tradeRecord()
    {
        try {
            $param = input('param.');
            $data  = UserGoodOrder::tradeRecord($param);
            self::$_data  = $data['data'];
            self::$_count = $data['count'];
            self::$_code = self::STATE_SUCCESS;
            $this->responseJson();
        } catch (HttpException $e) {
            $this->exceptionHandle($e);
        }
    }



    public function walletList()
    {
        try {
            $param = input('param.');
            $data  = UserWallet::walletList($param);
            self::$_data  = $data['data'];
            self::$_count = $data['count'];
            self::$_code = self::STATE_SUCCESS;
            $this->responseJson();
        } catch (HttpException $e) {
            $this->exceptionHandle($e);
        }
    }


    public function getSystemPayment()
    {
        try {
            self::$_data = SystemPayment::getPayment();
            self::$_code = self::STATE_SUCCESS;
            $this->responseJson();
        } catch (HttpException $e) {
            $this->exceptionHandle($e);
        }
    }










}