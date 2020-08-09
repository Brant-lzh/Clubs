<?php


namespace app\api\lib\exception;


class LoginWarningException extends BaseException
{
    public $code = 401;
    public $msg = '警告！您不是本站用户！';
    public $errorCode = 50000;
}