<?php

namespace app\api\model;

use think\exception\HttpException;
use think\facade\Cache;
use think\facade\Db;


class UserPayment extends Base
{


    /**
     * @param string $user_id
     * @param array $data
     * @return bool
     */
    public static function savePayment(string $user_id,array $data)
    {
        if (!$data) {
            return true;
        }
        $payMent = self::where('user_id',$user_id)->find();
        if (!$payMent || $payMent->isEmpty()) {
            $data['user_id'] = $user_id;
            $result = self::create($data);
        } else {
            $result  = self::where('user_id',$user_id)->save($data);
        }
        if (false === $result) throw_exception(lang('set_payment_failed'));
        return true;
    }


    /**
     * @param string $user_id
     */
    public static function getUserPayment(string $user_id)
    {
        $data = self::where('user_id',$user_id)->find();
        if ($data && !$data->isEmpty()) {
            return $data;
        }
        return [];
    }




}