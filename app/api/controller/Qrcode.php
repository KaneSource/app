<?php

namespace app\api\controller;

use think\Validate;

class Qrcode extends Base
{

    function read()
    {
        include APP_PATH.'../../extend/phpqrcode/phpqrcode.php';
        $param = input('param.');
        $Qrcode = new \QRcode();
        ob_start();
        $Qrcode->png($param['param'],false,'L',4);
        $imageString = base64_encode(ob_get_contents());
        ob_end_clean();
        self::$_data = '<img src="data:image/png;base64,'.$imageString.'" />';
        $this->responseJson();
    }



}