<?php

namespace app;

class Base extends BaseController
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



    const BIND_MOBILE = 1100;



    /**
     * http request response code
     * return code of http response code
     * @var int
     */
    protected static $_code = self::STATE_SUCCESS;




    /**
     * http request response msg,success or fail tips
     * return msg of http response msg
     * @var string
     */
    protected static $_msg = 'OK';


    /**
     * http request response data
     * return data of http response data
     * @var void array|string
     */
    protected static $_data;


    /**
     * http request response data
     * return data of http response data length
     * @var void array
     */
    protected static $_count;







}
