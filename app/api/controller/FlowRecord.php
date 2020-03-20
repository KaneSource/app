<?php


namespace app\api\controller;

use think\exception\HttpException;


class FlowRecord extends Base
{


    public function useType()
    {
        try {
            $data = [];
            $data[] = ['key'=>'1','value'=>lang('USE_TYPE_MINE')];
            $data[] = ['key'=>'5','value'=>lang('USE_TYPE_TRANS_FORM_SEND')];
            $data[] = ['key'=>'10','value'=>lang('USE_TYPE_SEND_TO_HOLD')];
            $data[] = ['key'=>'15','value'=>lang('USE_TYPE_TRANS')];
            $data[] = ['key'=>'20','value'=>lang('USE_TYPE_SEND_TO_OTC')];
            $data[] = ['key'=>'25','value'=>lang('USE_TYPE_TRANS_FORM_OTC')];
            $data[] = ['key'=>'30','value'=>lang('USE_TYPE_INVITE_REWARD')];
            $data[] = ['key'=>'35','value'=>lang('USE_TYPE_REGISTER_REWARD')];
            $data[] = ['key'=>'40','value'=>lang('USE_TYPE_READ_REWARD')];
            $data[] = ['key'=>'45','value'=>lang('USE_TYPE_OTC_TRADE')];
            $data[] = ['key'=>'50','value'=>lang('USE_TYPE_OTC_RETURN')];
            $data[] = ['key'=>'55','value'=>lang('USE_TYPE_OTC_UP')];
            $data[] = ['key'=>'60','value'=>lang('USE_TYPE_TRANS_TO_SEND')];
            $data[] = ['key'=>'65','value'=>lang('USE_TYPE_UNDER_ORDER')];
            self::$_data = $data;
            $this->responseJson();
        } catch (HttpException $e) {
            $this->exceptionHandle($e);
        }
    }



    public function lists()
    {
        try {
            $param = input('param.');
            self::$_data = \app\api\model\FlowRecord::lists($this->getUid(),$param);
            $this->responseJson();
        } catch (HttpException $e) {
            $this->exceptionHandle($e);
        }
    }



}