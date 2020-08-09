<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: yunwuxin <448901948@qq.com>
// +----------------------------------------------------------------------

return [
    'app_id' => 'wx7763a7cd17b083e1',
    'app_secret' => '1ce894ba8712bc1028415a9ca8f256aa',
    'login_url' => 'https://api.weixin.qq.com/sns/jscode2session?appid=%s&secret=%s&js_code=%s&grant_type=authorization_code',
    'access_token_url'=>'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s',
//    appid 	string 		是 	小程序 appId
//secret 	string 		是 	小程序 appSecret
//js_code 	string 		是 	登录时获取的 code
//grant_type 	string 		是 	授权类型，此处只需填写 authorization_code

];
