<?php

namespace app\api\controller;


use think\exception\HttpException;
use think\exception\ValidateException;
use think\facade\Cache;

/**
 * Class UserFeedback
 * @package app\api\controller
 */
class UserFeedback extends Base
{


    /**
     * @api {post}  /user_feedback/save 用户意见反馈
     * @apiUse User
     * @apiPermission 所有人
     * @apiDescription 用户意见反馈
     * @apiGroup User
     * @apiName user_feedback-save
     * @apiSampleRequest /user_feedback/save
     * @apiParam {string}   content  反馈内容 255字以内
     * @apiSuccess {integer} code 响应码 请参照code对照
     * @apiSuccess {string}  msg 提示信息
     * @apiSuccess {string}  data 返回数据
     * @apiVersion 1.0.0
     */
    public function save()
    {
        try {
            $param = input('param.');
            self::validate($param, 'User.feedback');
            \app\api\model\UserFeedback::userFeedback($this->getUid(),$param['content']);
            self::$_code = self::STATE_SUCCESS;
            self::$_msg  = lang('pull_success');
            $this->responseJson();
        } catch (HttpException $e) {
            $this->exceptionHandle($e);
        }
    }









}