<?php


namespace app\api\controller;

use app\crontab\controller\Curl;
use think\Controller;
use think\Request;

class Index extends Controller
{
    public function getTimeTable(){
        $is_login = $this->login("1740224170", "0000");
        $url = 'http://class.sise.com.cn:7001/sise/module/student_schedular/student_schedular.jsp';
        $info = $this->getInfo($is_login, $url);
        libxml_use_internal_errors(true);
        $DOMDocument = new \DOMDocument();
        $info = mb_convert_encoding($info ,'HTML-ENTITIES',"UTF-8");
        $DOMDocument->loadHTML($info);
        $DOMXPath = new \DOMXPath($DOMDocument);
        $timeTableDom = $DOMXPath->query('//td[@align="left"]');
        $timeTable=[];
        foreach ($timeTableDom as $key=>$vo){
            $timeTable[$key]=$vo->textContent;
        }
        return json($timeTable);
    }

    private function getLoginInfo(){
        $curl = new Curl();
        //获取页面信息
        $curl->get('http://class.sise.com.cn:7001/sise/login.jsp');
        $JSessionId= "";
        foreach ($curl->response_headers as $value) {
            if (strpos($value, 'Set-Cookie:') === 0) {
                preg_match('/Set-Cookie: (.*);/iU', $value, $str);
                $str = explode('=', $str[1]);
                $JSessionId = $str[1];
            }
        }

        $response = $curl->response;
        $curl->close();
        $regex_data    = '<input type="hidden" name="(.*?)"  value="(.*?)">';
        preg_match($regex_data, $response, $matches);
        $data_key   = $matches[1];
        $data_value = $matches[2];
        $regex_random  = '<input id="random"   type="hidden"  value="(.*?)"  name="random" />';
        preg_match($regex_random, $response, $matches1);
        $random     = $matches1[1];

        //拼接所需token参数
        $JSESSIONID = explode('!',$JSessionId)[0];
        $src   = str_split(strtoupper(md5('http://class.sise.com.cn:7001/sise/'.$JSESSIONID.$random)));
        $ran   = str_split($random);
        $token = '';
        foreach ($src as $k => $v){
            $token .= $v.(isset($ran[$k])?$ran[$k]:'');
        }

        $data = [
            $data_key => $data_value,
            'random' => $random,
            'token' => $token,
            'JSESSIONID'=>$JSessionId
        ];
        return $data;
    }

    private function login($username,$password){
        $data = $this->getLoginInfo();
        $JSessionId =$data['JSESSIONID'];
        unset($data['JSESSIONID']);
//        $data['username'] = "1740224170";
//        $data['password'] = "0000";
        $data['username'] = $username;
        $data['password'] = $password;
        $curl = new Curl();
        //模拟请求数据
        $curl->setUserAgent(Request::instance()->server('HTTP_USER_AGENT'));//模拟浏览器
        $curl->setHeader('Accept','text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3');
        $curl->setHeader('Accept-Encoding','gzip, deflate');
        $curl->setHeader('Accept-Language','zh-CN,zh;q=0.9');
        $curl->setHeader('Cache-Control','max-age=0');
        $curl->setHeader('Connection','keep-alive');
        $curl->setHeader('Origin', 'http://class.sise.com.cn:7001');
        $curl->setHeader('Host', 'class.sise.com.cn:7001');
        $curl->setHeader('Referer', 'http://class.sise.com.cn:7001/sise/login.jsp');
        $curl->setHeader('Content-Type', 'application/x-www-form-urlencoded');
        $curl->setHeader('Upgrade-Insecure-Requests', 1);
        $curl->setCookie('JSESSIONID',$JSessionId);
        $curl->post('http://class.sise.com.cn:7001/sise/login_check_login.jsp', http_build_query($data));
        $curl->close();
        return $JSessionId;
    }

    private function getInfo($JSessionId,$url){
        $curl = new Curl();
        //模拟请求数据
        $curl->setUserAgent(Request::instance()->server('HTTP_USER_AGENT'));//模拟浏览器
        $curl->setHeader('Accept','text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9');
        $curl->setHeader('Accept-Encoding','gzip, deflate');
        $curl->setHeader('Accept-Language','zh-CN,zh;q=0.9');
        $curl->setHeader('Connection','keep-alive');
        $curl->setHeader('Host', 'class.sise.com.cn:7001');
        $curl->setHeader('Referer', 'http://class.sise.com.cn:7001/sise/index.jsp');
        $curl->setHeader('Upgrade-Insecure-Requests', 1);
        $curl->setCookie('JSESSIONID', $JSessionId);
        $curl->get($url);
        $curl->close();
        return $this->setCode($curl->response);
    }

    private function setCode($str){
        return iconv("GBK","UTF-8",$str);
    }
}