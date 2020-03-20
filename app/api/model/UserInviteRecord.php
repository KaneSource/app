<?php

namespace app\api\model;

use think\facade\Db;

class UserInviteRecord extends Base
{


    /**
     * @param $user_id
     * @return int
     */
    public static function getUserInviteNum($user_id): int
    {
        return self::where('user_id',$user_id)->count();
    }


    /**
     * @return array
     */
    public static function UserInviteCount(string $user_id): array
    {
        $sql = "SELECT COUNT(id) invite_num FROM kn_user_invite_record WHERE user_id = '".$user_id."'";
        return Db::query($sql);
    }

    /**
     * @return array
     */
    public static function lists(string $user_id):array
    {
       $data =  Db::name('user_invite_record')->alias('a')->join('user b','b.user_id=a.invite_user_id')->where('a.user_id',$user_id)->field('a.invite_user_id,b.user_avatar,b.user_nick,a.create_time')->limit(page_offset(1),page_offset(2))->order('a.id desc')->select()->toArray();
        foreach ($data as $index => $item) {
            $data[$index]['user_avatar'] = $item['user_avatar']?$item['user_avatar']:DEFAULT_AVATAR;
            $user_level = UserMine::userLevel($item['invite_user_id']);
            $data[$index]['user_level'] =  lang('user_level'.$user_level);
        }
        return $data;
    }





}