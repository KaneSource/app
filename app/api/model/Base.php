<?php

namespace app\api\model;

use app\BaseModel;
use think\facade\Cache;

class Base extends BaseModel
{


    /**
     * http request response code
     * this response code represent the request successful
     * @var int
     */
    const STATE_SUCCESS = 0;


    /**
     * http request response code
     * this response code represent the request business process failed
     * @var int
     */
    const STATE_ERROR = 1004;


    /**
     * http request response code
     * this response code represent missing require params of the request
     * @var int
     */
    const STATE_PARAM_ERROR = 400;


    /**
     * http request response code
     * this response code represent user's identity verify failed of the request
     * @var int
     */
    const STATE_IDENTIFY_ERROR = 1001;


    /**
     * data status enable
     */
    const STATE_ENABLE = 1;


    /**
     * data status forbidden
     */
    const STATE_FORBIDDEN = 5;


    /**
     * show'nt filed
     */
    const HIDDEN_FILED = ['id'];



    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }

    /**
     * clear data cache form tag
     * @param $model
     * @return bool
     */
    public static function clearCache($tag)
    {
        return Cache::tag($tag)->clear();
    }


}