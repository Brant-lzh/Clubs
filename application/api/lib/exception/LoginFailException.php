<?php


namespace app\api\lib\exception;


class LoginFailException extends BaseException
{
    public $code = 401;
    public $msg = '登录失败！';
    public $errorCode = 50004;
}