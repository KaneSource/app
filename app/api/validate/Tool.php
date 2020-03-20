<?php

namespace app\api\validate;

use app\BaseValidate;

class Tool extends BaseValidate
{

    protected $rule = [
        'position|position' => 'require',
    ];


    public function sceneUpload()
    {
        return $this->only(['position']);
    }









}