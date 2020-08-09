<?php


namespace app\api\lib\exception;


class LoginOtherException extends BaseException
{
    public $code = 401;
    public $msg = '未知错误!';
    public $errorCode = 50002;
}