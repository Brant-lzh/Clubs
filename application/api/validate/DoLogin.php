<?php


namespace app\api\validate;


class DoLogin extends BaseValidate
{
    protected $rule = [
        'code' => 'require|isNotEmpty',
        'sid'=>'require|isNotEmpty|min:9|max:11',
        'password'=>'require|isNotEmpty',
    ];

    protected $message = [
        'code' => 'code缺失',
        'sid' => '学号格式错误！',
        'password' => '密码格式错误！',
    ];

}