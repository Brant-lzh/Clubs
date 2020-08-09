<?php


namespace app\api\lib\exception;


class SiseErrorException extends BaseException
{
    public $code = 500;
    public $msg = '系统繁忙，请稍后再试！';
    public $errorCode = 50003;
}