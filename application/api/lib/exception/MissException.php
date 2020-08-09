<?php


namespace app\api\lib\exception;


class MissException extends BaseException
{
    public $code = 404;
    public $msg = '请求的数据不存在';
    public $errorCode = 40000;
}