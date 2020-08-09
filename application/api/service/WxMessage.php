<?php


namespace app\api\service;


use think\Exception;

class WxMessage
{
    private $sendUrl = "https://api.weixin.qq.com/cgi-bin/message/subscribe/send?access_token=%s";


    protected $tplID;
    protected $page;
    protected $data;

    public function __construct()
    {
        $accessToken = new AccseeToken();
        $token = $accessToken->get();
        $this->sendUrl = sprintf($this->sendUrl,$token);
    }

    protected function sendMessage($openID){
        $data = [
            'touser' => $openID,
            'template_id' => $this->tplID,
            'page' => $this->page,
            'data' => $this->data,
        ];
        $result = curl_post($this->sendUrl,$data);
        $result = json_decode($result,true);
        if ($result['errcode'] == 0){
            return true;
        }else{
            throw new Exception('订阅消息发送失败,'.$result['errmsg']);
        }
    }
}