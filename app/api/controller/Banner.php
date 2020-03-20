<?php

namespace app\api\controller;

use think\exception\HttpException;

/**
 * @apiDefine Notice
 * 操作邀请相关的接口
 */

class Banner extends Base
{



    /**
     * @api {post}  /Banner/lists
     * @apiUse Notice
     * @apiPermission
     * @apiDescription
     * @apiGroup Notice
     * @apiName banner-lists
     * @apiSampleRequest /banner/lists
     * @apiSuccess {array}  data
     * @apiSuccess {integer} code
     * @apiSuccess {string}  msg
     * @apiVersion 1.0.0
     */
    public function lists()
    {
         self::$_data = \app\api\model\Banner::lists();
         $this->responseJson();
    }










}