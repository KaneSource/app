<?php

namespace app\api\controller;


use think\exception\HttpException;
use think\exception\ValidateException;
use think\facade\Cache;


/**
 * Class Lang
 * @package app\api\controller
 */
class Lang extends Base
{


    public function lists()
    {
          self::$_data = \app\api\model\Lang::lists();
          $this->responseJson();
    }









}