<?php


namespace app\api\lib\exception;


class FollowException extends BaseException
{
    public $code = 405;
    public $msg = '已经关注';
    public $errorCode = 70000;
}