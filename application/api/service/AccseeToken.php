<?php


namespace app\api\service;


use think\Exception;

class AccseeToken
{
    private $tokenUrl;
    const TOKEN_CACHED_KEY = 'access';
    const TOKEN_EXPIRE_IN = 7000;

    public function __construct()
    {
        $url = config('wx.access_token_url');
        $url = sprintf($url, config('wx.app_id'), config('wx.app_secret'));
        $this->tokenUrl = $url;
    }

    public function get()
    {
        return $this->getFromWxServer();
//        $token = $this->getFromCache();
//        if (!$token){
//            return $this->getFromWxServer();
//        }else{
//            return $token;
//        }
    }

    private function getFromCache(){
        $token = cache(self::TOKEN_CACHED_KEY);
        if (!$token){
            return $token;
        }
        return null;
    }

    private function getFromWxServer(){

        $token = curl_get($this->tokenUrl);
        $token = json_decode($token,true);
        if (!$token){
            throw new Exception('获取AccessToken异常');
        }
        if (!empty($token['errcode'])){
            throw new Exception($token['errmsg']);
        }
//        $this->saveToCache($token);
        return $token['access_token'];
    }
}
