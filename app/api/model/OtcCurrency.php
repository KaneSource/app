<?php

namespace app\api\model;

use think\exception\HttpException;
use think\facade\Cache;
use think\facade\Db;


class OtcCurrency extends Base
{

    /**

     */
      public static function lists()
      {
          $data = self::where('state',self::STATE_ENABLE)->field('currency_id,currency_name,user_sell,is_kn,buy_min_amount,buy_max_amount,describe,price')->order('sort desc')->select();
          return $data;
      }


    /**

     * @param string $currency_id
     */
      public static function getCurrency(string $currency_id)
      {
          $data = self::where('currency_id',$currency_id)->where('state',self::STATE_ENABLE)->find();
          if ($data && !$data->isEmpty()) {
              return $data;
          }
          return [];
      }




}