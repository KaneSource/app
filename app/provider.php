<?php
use app\ExceptionHandle;
use app\Request;

// doctor Provider
return [
    'think\Request'          => Request::class,
    'think\exception\Handle' => ExceptionHandle::class,
];
