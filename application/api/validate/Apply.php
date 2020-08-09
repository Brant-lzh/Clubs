<?php


namespace app\api\validate;


class Apply extends BaseValidate
{
    protected $rule = [
        'aid' => 'require|isNotEmpty',
        'phone'=>'require|isPhone|isNotEmpty',
        'remark'=>'require|isNotEmpty',
    ];
}