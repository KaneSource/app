<?php

namespace app\api\model;

use think\exception\HttpException;
use think\facade\Cache;
use think\facade\Db;


class Notice extends Base
{


    /**

     * @param string $lang
     * @return array
     */
    public static function lists(string $lang)
    {
        $data = Db::name('notice')->alias('a')->join('notice_list b','b.notice_id=a.notice_id')->where('b.lang',$lang)->field('a.notice_id,b.notice_title,b.notice_describe,b.notice_cover,b.create_time')->limit(page_offset(1),page_offset(2))->order('a.sort desc')->select()->toArray();
        return $data;
    }


    /**

     * @param string $notice_id
     * @param string $user_id
     * @param string $lang
     */
    public static function noticeDetail(string $notice_id,string $user_id,$parent_user_id,string $lang)
    {
        if ($notice_id) {
           $data =  Db::name('notice_list')->where('lang',$lang)->where('notice_id',$notice_id)->field('notice_title,notice_describe,notice_cover,create_time,notice_content')->find();
           if ($data)
           {
               $data['notice_content']  = htmlspecialchars_decode(html_entity_decode($data['notice_content']));
               #判断当前用户是否浏览信息
               $readNum  = UserReadRecord::readCountToday($user_id);

               self::startTrans();

               try {
                   if ($readNum == 0)
                   {#发放奖励
                       $amount = BusinessConf::getConf(BusinessConf::CONF_KEY_READ_REWARD);
                       UserWallet::sendWalletIn($user_id,$amount,FlowRecord::USE_TYPE_READ_REWARD);
                   }
                   $readCount = UserReadRecord::readCount($user_id);
                   if ($readCount == 0) {
                        $parentUser = User::parentUser($parent_user_id);
                        if ($parentUser)
                        {
                            $amount = BusinessConf::getConf(BusinessConf::CONF_KEY_READ_AND_INVITE);
                            #给上级用户发奖
                            UserWallet::sendWalletIn($parentUser,$amount,FlowRecord::USE_TYPE_INVITE_REWARD);
                        }
                   }
                   #写入浏览记录
                   $readData = [];
                   $readData['create_time'] = now_date();
                   $readData['user_id'] = $user_id;
                   $readData['notice_id'] = $notice_id;
                   UserReadRecord::create($readData);
                   #给邀请人发送奖励
                   self::commit();
               } catch (HttpException $e) {
                   self::rollback();
               }

           }
           return $data;
        }
        return [];
    }






}