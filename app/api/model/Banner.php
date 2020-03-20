<?php

namespace app\api\model;



class Banner extends Base
{


    /**
     * banner lists
     */
    public static function lists()
    {
       return self::order('sort desc')->select();
    }




}