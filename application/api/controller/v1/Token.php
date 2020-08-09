<?php


namespace app\api\controller\v1;


use app\api\lib\exception\ParameterException;
use app\api\service\UserToken;
use app\api\validate\TokenGet;
use app\api\service\Token as TokenService;
class Token
{
    public function getToken($code=''){
        (new TokenGet())->goCheck();
        $userToken = new UserToken($code);
        $token = $userToken->get();
        return [
            'token' => $token
        ];
    }

    public function verifyToken($token=''){
        if (!$token){
            throw new ParameterException([
                'token不允许为空'
            ]);
        }
        $valid = TokenService::verifyToken($token);
        return [
            'isValid' => $valid
        ];
    }

}