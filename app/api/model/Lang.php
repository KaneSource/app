<?php

namespace app\api\model;

use think\exception\HttpException;
use think\facade\Cache;


class Lang extends Base
{


    /**
     * @param string $lang
     * @return bool
     */
    public static function setLang(string $lang): bool
    {
        $count = self::where('lang',$lang)->count();
        if ($count < 1) {
            \think\facade\Lang::setLangSet('zh-cn');
        } else {
            \think\facade\Lang::setLangSet($lang);
        }
        return true;
    }


    /**
     * @return array
     */
    public static function lists()
    {
        $data = self::field('lang')->select()->toArray();
        $d = [];
        foreach ($data as $k => $v) {
            $d[] = $v['lang'];
        }
        return $d;
    }







}