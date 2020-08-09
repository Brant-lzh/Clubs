<?php


namespace app\api\lib\exception;


class ApplyException extends BaseException
{
    public $code = 403;
    public $msg = '报名失败!';
    public $errorCode = 80000;
}

