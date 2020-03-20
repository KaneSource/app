<?php

namespace app\api\model;


class UserFeedback extends Base
{

    /**
     * @param string $user_id
     * @param string $content
     * @return bool
     */
    public static function userFeedback(string $user_id,string $content): bool
    {
        $data = [];
        $data['create_time'] = now_date();
        $data['user_id'] = $user_id;
        $data['content'] = $content;
        $result = self::create($data);
        if (!$result) throw_exception(lang('pull_failed'));
        return true;
    }





}