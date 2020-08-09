<?php


namespace app\api\controller\v1;


use app\api\lib\exception\LoginFailException;
use app\api\lib\exception\SuccessMessage;
use app\api\lib\exception\UserException;
use app\api\model\User;
use app\api\service\Token as TokenService;
use app\api\service\UserToken;
use app\api\service\Login as LoginService;
use app\api\validate\DoLogin;

class Login
{
    public function doLogin($sid='',$password='',$code=''){
        (new DoLogin())->goCheck();
        //先到数据库校验原有的学号和密码
        $user = User::checkUser($sid);
        $userToken = new UserToken($code);
        $wxResult = $userToken->getOpenID();
        if (empty($user) || md5($password . config('salt')) != $user['password']){
            $login = new LoginService();
            $insert = $login->login($sid, $password);
            if (!$insert){
                throw new LoginFailException();
            }
            $insert['openid'] = $wxResult['openid'];
            $userModel = new User();
            $userModel->save($insert);
        }else{
            //说明原有数据在数据库里且学号密码正确
            User::update(['id'=>$user['id'],'stid'=>$sid,'openid'=>$wxResult['openid']]);
        }
        $token = $userToken->grantToken($wxResult);
        return [
            'token' => $token
        ];
    }

    public function loginOut(){
        $uid = TokenService::getCurrentUid();
        $user = User::get($uid);
        if (!$user){
            throw new UserException();
        }
        $user->save(['openid' => null]);
        throw new SuccessMessage();
    }
}