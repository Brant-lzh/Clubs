<?php


namespace app\api\lib\exception;


class ParameterException extends BaseException
{
    public $code = 400;
    public $msg = '请求的参数错误';
    public $errorCode = 10000;
}