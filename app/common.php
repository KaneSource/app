<?php
// 应用公共文件

if (!function_exists('throw_exception')) {
    /**
     * throw a http error
     * @param $message
     */
    function throw_exception($message,$statusCode = 1004)
    {
        throw new \think\exception\HttpException($statusCode,$message);
    }
}

if (!function_exists('unique_str')) {
    /**
     * return unique str
     * @return string
     */
    function unique_str(): string
    {
       return str_replace('.','',uniqid('ID',true));
    }
}


if (!function_exists('create_token')) {

    /**
     * return shuffle str length = 12
     * @return string
     */
    function create_token(): string
    {
         $strs = "QWERTYUIOPASDFGHJKLZXCVBNM1234567890qwertyuiopasdfghjklzxcvbnm";
         $str  = substr(str_shuffle($strs),mt_rand(0,strlen($strs)-11),12);
         return md5($str);
    }
}


if (!function_exists('page_offset')) {
    function page_offset(int $t)
    {
        $page = input('param.page',1);
        $limit = intval(input('param.limit',8));
        if ($t == 1) {
          return  ($page-1)*$limit;
        }
        return $limit;
    }

}

if (!function_exists('now_date')) {

    /**
     * now time to format date
     * @param bool $time
     * @param string $format
     * @return string
     */
    function now_date($time = false,$format = 'Y-m-d H:i:s') :string
    {
        $time = $time ?: time();
        return date($format, $time);
    }
}



if (!function_exists('send_request')) {

    function send_request($url,$data)
    {
        $requestData = $data;
        $url = $url . '?' . http_build_query($requestData);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        $result = curl_exec($ch);
        curl_close($ch);
        return true;
    }
}



