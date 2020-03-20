<?php

namespace app\api\controller;

use think\exception\HttpException;



class MineRule extends Base
{



    public function rule()
    {
        $lang = \think\facade\Lang::getLangSet();
        try {
            $queryData = \app\manager\model\MineRule::getRule($lang);
            self::$_data = $queryData;
            $this->responseJson();
        } catch (HttpException $e) {
            $this->exceptionHandle($e);
        }
    }










}