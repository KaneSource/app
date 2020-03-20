<?php

namespace app\api\model;

use think\exception\HttpException;
use think\facade\Cache;


class UserReadRecord extends Base
{


    /**
     * @param string $user_id
     */
   public static function readCountToday(string $user_id)
   {
       $num = self::where('user_id',$user_id)->whereDay('create_time')->count();
       return $num;
   }


    /**
     * @param string $user_id
     */
    public static function readCount(string $user_id)
    {
        $num = self::where('user_id',$user_id)->count();
        return $num;
    }





}