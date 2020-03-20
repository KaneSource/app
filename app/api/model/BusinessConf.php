<?php

namespace app\api\model;

class BusinessConf extends Base
{




    /**
     * @param string $key
     * @return mixed
     */
    public static function getConf(string $key)
    {
       $data =  self::where('conf_key',$key)->field('conf_value')->find();
       if($data && !$data->isEmpty())
       {
           return $data->conf_value;
       }
       throw_exception(lang('conf_not_found'));
    }





}