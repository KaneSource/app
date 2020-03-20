<?php

namespace app\api\model;

use app\manager\model\SystemPayment;
use think\exception\HttpException;
use think\facade\Cache;
use think\facade\Db;


class UserGoodOrder extends Base
{

    public static function getOutTimeOrder()
    {
        $data = self::where('out_time','<',now_date())->where('state',1)->select()->toArray();
        return $data;
    }


    /**
     * @param string $order_nub
     */
    public static function setOrderPayed(array $param)
    {
        $order = self::where('order_nub',$param['order_nub'])->find();
        if(!$order || $order->isEmpty()) {
            throw_exception(lang('order_not_found'));
        }
        if ($order->state == 1) {
            $order->state = 5;
            $order->payment_img = $param['payment_img'];
            $result = $order->save();
            if (false === $result) throw_exception(lang('op_failed'));
        }
        return true;
    }


    /**
     * @param string $user_id
     * @param array $param
     */
    public static function getUserGoodOrder(string $user_id,array $param)
    {
        $model  = Db::name('user_good_order')->alias('a')->join('user_good b','b.good_id=a.good_id')->join('otc_currency c','b.currency_id=b.currency_id')->join('user d','d.user_id=a.user_id')->where('b.user_id',$user_id);
        if (isset($param['state']) && $param['state']) {
            $model->where('a.state',$param['state']);
        }
        $data = $model->field('a.*,b.price,c.currency_id,c.currency_name,d.user_nick,d.user_avatar,b.user_id seller_id')->order('a.id desc')->page(page_offset(1),page_offset(2))->group('a.id')->select()->toArray();
        foreach ($data as $index => $item) {
            $data[$index]['user_avatar'] = $item['user_avatar'] ? $item['user_avatar'] : DEFAULT_AVATAR;
            if ($item['seller_id']) {

                $payment = UserPayment::getUserPayment($item['seller_id']);;
            }  else{

                $payment = SystemPayment::getPayment();
            }
            $data[$index]['payment'] = $payment;
        }
        return $data;
    }

    /**
     * @param string $user_id
     * @param array $param
     */
    public static function getUserBuyGoodOrder(string $user_id,array $param)
    {
        $model  = Db::name('user_good_order')->alias('a')->join('user_good b','b.good_id=a.good_id')->join('otc_currency c','b.currency_id=b.currency_id','left')->join('user d','d.user_id=a.user_id','left')->where('a.user_id',$user_id);
        if (isset($param['state']) && $param['state']) {
           $model =  $model->where('a.state',$param['state']);
        }
        $data = $model->field('a.*,b.price,c.currency_id,c.currency_name,d.user_nick,d.user_avatar')->order('a.id desc')->page(page_offset(1),page_offset(2))->group('a.id')->select()->toArray();
        foreach ($data as $index => $item) {
            $data[$index]['user_avatar'] = $item['user_avatar']?$item['user_avatar']:DEFAULT_AVATAR;
        }
        return $data;
    }


    /**
     * @param array $param
     */
    public static function orderAppeal(array $param)
    {
        $order = self::where('order_nub',$param['order_nub'])->find();
        if (!$order || $order->isEmpty()) {
            throw_exception(lang('order_not_found'));
        }
        if ($order->appeal || $order->appeal_image) {
            throw_exception(lang('order_already_appeal'));
        }
        if (!in_array($order->state,[1,5,10,30])) throw_exception(lang('order_state_not_appeal'));
        $order->state = 15;
        $order->appeal = $param['appeal'];
        $order->appeal_image = $param['appeal_image'];
        $result = $order->save();
        if (false === $result) throw_exception(lang('pull_failed'));
        return true;
    }

    /**
     * @param string $order_nub
     * @return bool
     */
    public static function userSetOrderNoPay(string $order_nub)
    {
        $order = self::where('order_nub',$order_nub)->find();
        if (!$order || $order->isEmpty()) {
            throw_exception(lang('order_not_found'));
        }
        if ($order->state != 5) throw_exception(lang('order_state_not_set'));
        $order->state = 30;
        $result = $order->save();
        if (false === $result) throw_exception(lang('pull_failed'));
        return true;
    }


    /**
     * @param string $order_nub
     */
    public static function userSendCurrency(string $order_nub)
    {
        $order = self::where('order_nub',$order_nub)->find();
        if (!$order || $order->isEmpty()) {
            throw_exception(lang('order_not_found'));
        }
        if ($order->state != 5) throw_exception(lang('order_state_not_set'));
        $order->state = 10;
        self::startTrans();
        try {
            UserWallet::sendWalletIn($order->user_id,$order->num,FlowRecord::USE_TYPE_OTC_TRADE);
            $result = $order->save();
            if (false === $result) throw_exception(lang('pull_failed'));
            self::commit();
        } catch (HttpException $e) {
            self::rollback();
            throw_exception($e);
        }
        return true;
    }


    /**
     * @return array
     */
    public static function tradeRecord($param)
    {
        $model = Db::name('user_good_order')->alias('a')->join('user_good b','b.good_id=a.good_id')->join('user_wallet c','c.user_id=b.user_id')->join('user_wallet d','d.user_id=a.user_id')->where('a.state',10);
        if (isset($param['wallet_address']) && $param['wallet_address']) {
            $model = $model->whereLike('d.wallet_address','%'.$param['wallet_address'].'%');
        }
        $count = $model->count();
        $data = $model->limit(page_offset(1),page_offset(2))->field('a.order_nub,a.create_time,c.wallet_address buy_user_wallet_address,d.wallet_address sell_user_wallet_address,a.num,a.amount')->order('a.id desc')->select();
        return ['data'=>$data,'count'=>$count];
    }



}