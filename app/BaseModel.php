<?php

namespace app;

use think\Model;

/**
 * Class BaseModel
 * @package app
 */
class BaseModel extends Model
{

    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }




}