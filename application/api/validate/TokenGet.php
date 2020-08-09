<?php


namespace app\api\validate;


class TokenGet extends BaseValidate
{
    protected $rule = [
        'code' => 'require|isNotEmpty',
    ];

    protected $message = [
        'code' => '没有code，不要获取token',
    ];

}