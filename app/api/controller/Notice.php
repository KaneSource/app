<?php

namespace app\api\controller;

use think\exception\HttpException;



class Notice extends Base
{



    public function lists()
    {
        $lang = \think\facade\Lang::getLangSet();
        try {
            $queryData = \app\api\model\Notice::lists($lang);
            self::$_data = $queryData;
            $this->responseJson();
        } catch (HttpException $e) {
            $this->exceptionHandle($e);
        }
    }



    public function noticeDetail()
    {
        $param = input('param.');
        $lang = \think\facade\Lang::getLangSet();
        try {
            if (isset($param['notice_id'])) {
                $notice_id = $param['notice_id'];
            } else {
                $notice_id = '';
            }
            $queryData = \app\api\model\Notice::noticeDetail($notice_id,$this->getUid(),$this->getU()->parent_user_id,$lang);
            self::$_data = $queryData;
            $this->responseJson();
        } catch (HttpException $e) {
            $this->exceptionHandle($e);
        }
    }












}