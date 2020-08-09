<?php


namespace app\api\service;


use app\api\lib\exception\LoginOtherException;
use app\api\lib\exception\LoginWarningException;
use app\api\lib\exception\SiseBusyException;
use app\api\lib\exception\SiseErrorException;
use app\crontab\controller\Curl;
use think\Request;

class Login
{
    public function login($username,$password)
    {
        $curl = new Curl();
        $curl->get('http://class.sise.com.cn:7001/sise/login.jsp');
        if ($curl->error || $curl->response == '') {
            throw new SiseBusyException();
        }
        $curl->close();
        foreach ($curl->response_headers as $value) {
            if (strpos($value, 'Set-Cookie:') === 0) {
                preg_match('/Set-Cookie: (.*);/iU', $value, $str);
                $str = explode('=', $str[1]);
                $cookie[$str[0]] = $str[1];
            }
        }

        //获取登录所需参数
        $response = $curl->response;
        $regex_data = '<input type="hidden" name="(.*?)"  value="(.*?)">';
        preg_match($regex_data, $response, $matches);
        $data_key = $matches[1];
        $data_value = $matches[2];
        $regex_random = '<input id="random"   type="hidden"  value="(.*?)"  name="random" />';
        preg_match($regex_random, $response, $matches1);
        $random = $matches1[1];

        //拼接所需token参数
        $JSESSIONID = explode('!', $cookie['JSESSIONID'])[0];
        $src = str_split(strtoupper(md5('http://class.sise.com.cn:7001/sise/' . $JSESSIONID . $random)));
        $ran = str_split($random);
        $token = '';
        foreach ($src as $k => $v) {
            $token .= $v . (isset($ran[$k]) ? $ran[$k] : '');
        }

        //准备数据
        $data = [
            $data_key => $data_value,
            'password' => $password,
            'random' => $random,
            'token' => $token,
            'username' => $username,
        ];

        //模拟请求数据
        $curl = new Curl();
        $curl->setUserAgent(Request::instance()->server('HTTP_USER_AGENT'));//模拟浏览器
        $curl->setHeader('Accept', 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3');
        $curl->setHeader('Accept-Encoding', 'gzip, deflate');
        $curl->setHeader('Accept-Language', 'zh-CN,zh;q=0.9');
        $curl->setHeader('Cache-Control', 'max-age=0');
        $curl->setHeader('Connection', 'keep-alive');
        $curl->setHeader('Origin', 'http://class.sise.com.cn:7001');
        $curl->setHeader('Host', 'class.sise.com.cn:7001');
        $curl->setHeader('Referer', 'http://class.sise.com.cn:7001/sise/login.jsp');
        $curl->setHeader('Content-Type', 'application/x-www-form-urlencoded');
        $curl->setHeader('Upgrade-Insecure-Requests', 1);
        $curl->setCookie('JSESSIONID', $cookie['JSESSIONID']);

        //登录
        $curl->post('http://class.sise.com.cn:7001/sise/login_check_login.jsp', http_build_query($data));

        //转码
        $result = $this->setCode($curl->response);

        //关闭
        $curl->close();

        if (strpos($result, '警告') !== false) {
            throw new LoginWarningException();
        } elseif (strpos($result, 'error.jsp') !== false) {
            $regex_error = '/error.jsp\?messages=(.*?)\'/is';
            preg_match($regex_error, $result, $error);
            throw new LoginOtherException([
                'msg' => 'Myscse服务器异常,请稍后再试！',
            ]);
        } elseif (strpos($result, '/sise/index.jsp') !== false) {
            $getStudentId = $this->getStudentId($cookie);
            $curl = new Curl();
            $curl->post('http://class.sise.com.cn:7001/SISEWeb/pub/course/courseViewAction.do?method=doMain&' . $getStudentId[0][0]);
            $curl->close();

            $filterInfo = $this->filter($this->setCode($curl->response));//修改

            $insert['stid'] = $data['username'];
            $insert['user_name'] = $filterInfo['username'];
            $insert['password'] = md5($data['password'] . config('salt'));
            $insert['major'] = $filterInfo['major'];
            $insert['department'] = $filterInfo['major'];

            //插入数据
            return $insert;
        } else {
            throw new SiseErrorException();
        }
    }

    private function getStudentId($cookie)
    {
        $curl = new Curl();
        //模拟请求数据
        $curl->setUserAgent(Request::instance()->server('HTTP_USER_AGENT'));//模拟浏览器
        $curl->setHeader('Accept', 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9');
        $curl->setHeader('Accept-Encoding', 'gzip, deflate');
        $curl->setHeader('Accept-Language', 'zh-CN,zh;q=0.9');
        $curl->setHeader('Connection', 'keep-alive');
        $curl->setHeader('Host', 'class.sise.com.cn:7001');
        $curl->setHeader('Referer', 'http://class.sise.com.cn:7001/sise/index.jsp');
        $curl->setHeader('Upgrade-Insecure-Requests', 1);
        $curl->setCookie('JSESSIONID', $cookie['JSESSIONID']);
        $curl->get('http://class.sise.com.cn:7001//sise/module/student_states/student_select_class/main.jsp');
        preg_match_all('#studentid=(.*?)=#', $this->setCode($curl->response), $arr);
        $curl->close();
        return $arr;
    }


    private function setCode($str)
    {
        return iconv("GBK", "UTF-8", $str);
    }



    //过滤数据
    private function filter($str)
    {
        //利用正则表达式找到数据
        preg_match_all('#(?<=<div align="left">)([\s\S]*?)(?=</div>)#', $str, $arr);
        /*        preg_match_all('#<div.+?>(.+?)</div>#',$str, $arr);*/

        foreach ($arr[0] as $key => $vo) {
            $arr[0][$key] = trim($vo);
        }

        return [
            'username'=>$arr[0][3],
            'major'=>$arr[0][5],
        ];
    }

}