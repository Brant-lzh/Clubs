<?php


namespace app\api\lib\exception;


class SiseBusyException extends BaseException
{
    public $code = 500;
    public $msg = '现在系统繁忙，请稍后再试!';
    public $errorCode = 50001;
}