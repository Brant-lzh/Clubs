<?php


namespace app\api\service;


use app\api\lib\exception\TokenException;
use app\api\lib\exception\UserException;
use app\api\lib\exception\WxChatException;
use app\api\model\User;
use think\Cache;
use think\Exception;
use app\api\model\User as UserModel;


class UserToken extends Token
{
    protected $code;
    protected $wxAppID;
    protected $wxAppSecret;
    protected $wxLoginUrl;

    function __construct($code)
    {
        $this->code = $code;
        $this->wxAppID = config('wx.app_id');
        $this->wxAppSecret = config('wx.app_secret');
        $this->wxLoginUrl = sprintf(config('wx.login_url'),
            $this->wxAppID,$this->wxAppSecret,$this->code);
    }
//
//    public function get($insert=''){
//        $result = curl_get($this->wxLoginUrl);
//        $wxResult = json_decode($result,true);
//        if (empty($wxResult)){
//            throw new Exception('获取session_key及openID时异常，微信内部错误');
//        }else{
//            $loginFail = array_key_exists('errcode',$wxResult);
//            if ($loginFail){
//                $this->processLoginError($wxResult);
//            }else{
//                $grantToken = $this->grantToken($wxResult,$insert);
//            }
//        }
//        return $grantToken;
//    }


    public function get(){
//        $result = curl_get($this->wxLoginUrl);
//        $wxResult = json_decode($result,true);
//        if (empty($wxResult)){
//            throw new Exception('获取session_key及openID时异常，微信内部错误');
//        }else{
//            $loginFail = array_key_exists('errcode',$wxResult);
//            if ($loginFail){
//                $this->processLoginError($wxResult);
//            }else{
//                $grantToken = $this->grantToken($wxResult,$insert);
//            }
//        }
        $wxResult = $this->getOpenID();
        $grantToken = $this->grantToken($wxResult);
        return $grantToken;
    }

    public function getOpenID(){
        $result = curl_get($this->wxLoginUrl);
        $wxResult = json_decode($result,true);
        if (empty($wxResult)){
            throw new Exception('获取session_key及openID时异常，微信内部错误');
        }else{
            $loginFail = array_key_exists('errcode',$wxResult);
            if ($loginFail){
                $this->processLoginError($wxResult);
            }
            return $wxResult;
        }
    }

    public function grantToken($wxResult){
        //获取openid
        //数据库里看一下，是否存在openid
        //如果存在则不处理，如果不存在返回登录
        //生成令牌，准备缓存，写入缓存
        //把令牌返回到客户端去
        //key:令牌
        //value：wxResult,uid,scope
        $openid = $wxResult['openid'];
        $user = UserModel::getByOpenID($openid);
        if ($user){
            $uid = $user->id;
        }else{
//            if (empty($insert)){
                throw new UserException([
                    'msg'=>'请登录!!!'
                ]);
//            }
//            $insert['openid'] = $openid;
//            $uid = $this->insert($insert);
        }
        $cachedValue = $this->prepareCacheValue($wxResult, $uid);
        $token = $this->saveToCache($cachedValue);
        return $token;
    }

    private function saveToCache($cachedValue){
        $key = self::generateToken();
        $value = json_encode($cachedValue);
        $expire_in = config('settings.token_expire_in');
        $request = cache($key,$value,$expire_in);
        if (!$request){
            throw new TokenException([
                'msg' => '服务器缓存异常',
                'errorCode' => 10005
            ]);
        }
        return $key;
    }


    private function prepareCacheValue($wxResult,$uid){
        $cachedValue = $wxResult;
        $cachedValue['uid'] = $uid;
        return $cachedValue;
    }


    private function newUser($openid){
        $user = WxUserModel::create([
            'openid' => $openid
        ]);
    }

    private function processLoginError($wxResult){
        throw new WxChatException([
            'msg' => $wxResult['errmsg'],
            'errorCode' => $wxResult['errcode'],
        ]);
    }

    //插入数据
    private function insert($insert)
    {
        $user = new User();
        $insert['password'] = md5($insert['password'] . config('salt'));
        $insert['department'] =  $insert['major'];
        $uid = $user->insertUser($insert);
        return $uid;
    }

}