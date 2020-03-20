<?php

namespace app\api\controller;

use app\api\model\Lang;
use app\api\model\User;
use app\ExceptionHandle;
use think\App;
use think\exception\HttpException;
use think\Response;
use think\response\Json;


/**
 * manager base controller extend to common base
 * need custom logic processing
 * Class Base
 * @package app\manager\controller
 */
class Base extends \app\Base
{



    /**
     * need'nt check access_token,for example 'login'
     */
    const NO_LOGIN_ACTION = [
        'user' => ['register','login','mobilelogin','resetpassword','checkisregister'],
        'tool' => ['sendcode','appversion'],
        'timer' => ['startmine','handleouttimeorder','settodaystate','sendinvitereward'],
        'otc' => ['traderecord','walletlist']
    ];

    /**
     * request controller and use strtolower
     * @var string
     */
    protected $controller;


    /**
     * request action and use strtolower
     * @var string
     */
    protected $action;


    /**
     * from request 'get' or 'post' use to check user identity
     * use the access token to get managerId
     * it's not necessary and only for a part of apis
     * @var string
     */
    protected $accessToken;


    /**current app name default:manager
     * @var
     */
    protected $appName = 'api';


    /**
     * @var string
     */
    protected $lang = 'zh-cn';


    protected $user = [];



    protected $userId;


    /**
     * global request params filter
     * @var array
     */
    protected $filter = ['trim'];



    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->controller = strtolower($app->request->controller());
        $this->action = strtolower($app->request->action());
        $this->accessToken = input('param.access_token');
        $this->appName = app('http')->getName();
        $this->lang = input('param.lang','zh-cn');
        Lang::setLang($this->lang);
        $this->authority();
    }


    /**
     * manager authorization
     *
     */
    private function authority()
    {
        if (isset(self::NO_LOGIN_ACTION[$this->controller]) && in_array($this->action,self::NO_LOGIN_ACTION[$this->controller])) {
            goto No_login; #need'nt login,skip the action
        } else {
                #need login,check the access_token
             try {
                 $user  =  User::checkLogin($this->accessToken);
                 $this->user = $user;
                 $this->userId = $user->user_id;
             }catch (HttpException $e) {
                 $this->exceptionHandle($e);
             }
        }
        No_login:
        return true;
    }



    /**the request return response data and write log
     * @return array
     */
    protected function responseJson(string $type = 'json')
    {
        Json::create([
            'code' => self::$_code ?: self::STATE_SUCCESS,
            'msg' => self::$_msg ?: 'OK',
            'data' => self::$_data ?: '',
            'count' => self::$_count ?: 0
        ],$type)->send();exit;
    }


    /**
     * treat the application logic error as an exception and return
     * @param $e
     * @return Response
     */
    protected function exceptionHandle($e)
    {
       return (new ExceptionHandle($this->app))->render($this->request, $e);
    }



    public function getU()
    {
        return $this->user;
    }

    public function getUid()
    {
        return $this->userId;
    }








}