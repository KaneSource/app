<?php

namespace app\api\model;

use think\exception\HttpException;
use think\facade\Cache;


class User extends Base
{
    /**
     * cache tag
     * @v string
     */
    public static $_cache_tag = 'user';


    protected $pk = 'user_id';


    /**
     * @param $token
     * @return object
     */
    public static function checkLogin($token): object
    {
        if (!$token) {
            throw_exception(lang('check_token_error'),self::STATE_IDENTIFY_ERROR);
        }
//        $user =  Cache::tag(self::$_cache_tag)->remember($token, function ()
//        use ($token) {
//            return self::where('user_token',$token)->find();
//        });
        $user = self::where('user_token',$token)->find();
        if (!$user || $user->isEmpty()) {
            throw_exception(lang('login_invalid'),self::STATE_IDENTIFY_ERROR);
        }
        if ($user->user_state == self::STATE_FORBIDDEN) {
            throw_exception(lang('account_forbidden'),self::STATE_IDENTIFY_ERROR);
        }
        return $user;
    }


    /**
     * @param $data
     * @return string
     */
    public static function addUser($data): string
    {
        $upData = [];
        $upData['user_id'] = unique_str();
        $id = self::max('id');
        $upData['user_mobile'] = $data['user_mobile'];
        $upData['user_password'] = password_hash($data['user_password'],PASSWORD_DEFAULT);
        $upData['user_trade_password'] = password_hash($data['user_trade_password'],PASSWORD_DEFAULT);
        $upData['user_avatar'] = '';
        $upData['user_nick'] = sub_mobile($upData['user_mobile']);
        $upData['user_state'] = self::STATE_ENABLE;
        $upData['invite_code'] = intval(100000+$id);
        $upData['update_time'] = now_date();
        $upData['user_token'] = create_token();
        if (isset($data['is_test'])) {
            $upData['is_test'] = $data['is_test'];
        }

        $upData['create_time'] = $upData['update_time'];
        self::startTrans();
        try
        {


            if (isset($data['invite_code'])) {
                $user = self::getUserByInviteCode($data['invite_code'],'user_id,parent_user_id');
                if ($user && !$user->isEmpty()) {
                    $upData['parent_user_id'] = ltrim(rtrim($user->parent_user_id,',').','.$user->user_id,',');
                    $upData['parent_user'] = $user->user_id;

                    $inviteData = [];
                    $inviteData['user_id'] = $user->user_id;
                    $inviteData['invite_user_id'] = $upData['user_id'];
                    $inviteData['read_state'] = self::STATE_FORBIDDEN;
                    $inviteData['create_time'] =  $upData['create_time'];
                    UserInviteRecord::create($inviteData);
                } else {
                    $upData['parent_user_id'] = 0;
                    $upData['parent_user'] = 0;
                }
            } else {
                $upData['parent_user_id'] = 0;
                $upData['parent_user'] = 0;
            }


            $result = self::create($upData);
            if (!$result) {
                throw_exception(lang('register_failed'),self::STATE_IDENTIFY_ERROR);
            }

            UserWallet::registerWallet($upData['user_id']);

            UserMine::registerMine($upData['user_id']);

            $giveAmount = BusinessConf::getConf(BusinessConf::CONF_KEY_REGISTER_GIVE);
            if ($giveAmount > 0) {
                UserWallet::sendWalletIn($upData['user_id'],$giveAmount,FlowRecord::USE_TYPE_REGISTER_REWARD);
            }
            self::commit();
            return $upData['user_token'];
        }catch(HttpException $e) {
            self::rollback();
            throw_exception($e->getMessage(),self::STATE_IDENTIFY_ERROR);
        }
    }


    /**
     * @param array $loginParam
     * @return string
     */
    public static function login(array $loginParam):string
    {
        $user  = self::getUserByMobile($loginParam['user_mobile']);
        if (!$user || $user->isEmpty()) {
            throw_exception(lang('user_mobile_not_found'));
        }
        if ($user->user_state === self::STATE_FORBIDDEN) {
            throw_exception(lang('user_mobile_forbidden'));
        }
        if (!password_verify($loginParam['user_password'],$user->user_password)) {
            throw_exception(lang('user_password_error'));
        }
        Cache::tag(self::$_cache_tag)->clear($user->user_token);
        $user_token = create_token();
        $user->user_token = $user_token;
        $user->save();
        return $user_token;
    }


    /**
     * @return string
     */
    public static function resetPassword(array $resetParam): string
    {
        $user  = self::getUserByMobile($resetParam['user_mobile']);
        if (!$user || $user->isEmpty()) {
            throw_exception(lang('user_mobile_not_found'));
        }
        if ($user->user_state === self::STATE_FORBIDDEN) {
            throw_exception(lang('user_mobile_forbidden'));
        }
        if (password_verify($resetParam['user_password'],$user->user_password)) {
            throw_exception(lang('new_password_same_old'));
        }
        check_code($resetParam['user_mobile'],$resetParam['code']);
        Cache::tag(self::$_cache_tag)->clear($user->user_token);
        $user_token = create_token();
        $user->user_token = $user_token;
        $user->user_password = password_hash($resetParam['user_password'],PASSWORD_DEFAULT);
        $user->save();
        return $user_token;
    }


    /**
     * @param array $param
     * @param string $user_id
     * @return bool
     */
    public static function saveUserInfo(array $param,string $user_id): bool
    {
        $param = array_filter($param);
        if ($param) {
            $data = [];
            if(isset($param['user_avatar'])) {
                $data['user_avatar'] = $param['user_avatar'];
            }
            if(isset($param['user_nick'])) {
                $data['user_nick'] = $param['user_nick'];
            }
            if(isset($param['user_lang'])) {
                $data['user_lang'] = $param['user_lang'];
            }
            $result = self::where('user_id',$user_id)->save($data);
            if (false === $result) throw_exception(lang('save_failed'));
        }
        return true;
    }



    /**
     * @param $code
     * @param bool $field
     * @return object
     */
    protected static function getUserByInviteCode($code,$field = true)
    {
        return self::where('invite_code',$code)->field($field)->find();
    }


    /**
     * @param string $mobile
     * @return object
     */
    protected static function getUserByMobile(string $mobile, $field = true): object
    {
        return self::where('user_mobile',$mobile)->field($field)->find();
    }


    /**
     * @param string $user_id
     */
    public static function parentUser($parent_user_id)
    {
         if ($parent_user_id) {
             $data = explode(',',$parent_user_id);
             end($data);
             return array_shift($data);
         }
         return null;
    }


    /**
     * @param string $user_mobile
     * @param string $user_id
     */
    public static function saveMobile(string $user_mobile,string $user_id): bool
    {
        $user = self::where('user_id',$user_id)->find();
        if($user->user_mobile == $user_mobile) {
            throw_exception(lang('user_mobile_same_old'));
        }
        $user->user_mobile = $user_mobile;
        $result = $user->save();
        if (false == $result) {
            throw_exception('op_failed');
        }
        return true;
    }


    /**
     * @param string $user_id
     * @param string $tradePassword
     */
    public static function resetTradePassword(string $user_id,string $tradePassword): bool
    {
        $result = self::where('user_id',$user_id)->save(['user_trade_password'=>password_hash($tradePassword,PASSWORD_DEFAULT)]);
        if( false === $result) throw_exception(lang(('save_failed')));
        return true;
    }


    /**
     * @param string $user_id
     * @param string $userPassword
     * @return bool
     */
    public static function resetPasswordApp(string $user_id,string $userPassword): bool
    {
        $result = self::where('user_id',$user_id)->save(['user_password'=>password_hash($userPassword,PASSWORD_DEFAULT)]);
        if( false === $result) throw_exception(lang(('save_failed')));
        return true;
    }



    /**
     * @param string $user_id
     * @param string $tradePassword
     */
    public static function checkTradePassword(string $tradePassword,$userTradePassword)
    {
        if (!password_verify($tradePassword,$userTradePassword)) {
            throw_exception(lang('user_trade_password_error'));
        }
        return true;
    }


    /**
     * @param string $user_id
     * @param bool $filed
     */
    public static function getUser(string $user_id,$filed= true)
    {
        return self::where('user_id',$user_id)->field($filed)->find();
    }


    /**
     * @param string $user_id
     */
    public static function getSonNum(string $user_id)
    {
        $count = self::where('parent_user',$user_id)->count();
        return $count;
    }





}