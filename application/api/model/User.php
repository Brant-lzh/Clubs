<?php


namespace app\api\model;


use app\api\lib\exception\LoginFailException;
use think\Exception;
use think\Model;

class User extends BaseModel
{

    protected $name='user';

    public static function getMyInfo($uid){
        return self::where('id',$uid)->field('user_name,stid')->find();
    }

    public static function getByOpenID($openid){
        $user = self::where('openid','=',$openid)
            ->find();
        return $user;
    }

    public static function checkOpenID($id){
        $user = self::where('id','=',$id)
            ->field('openid')
            ->find();
        if ($user['openid'] == null){
            return false;
        }else{
            return true;
        }
    }


    public function insertUser($param){
        try{
            $result = $this->save($param);
            if(false === $result){
                // 验证失败 输出错误信息
                throw new LoginFailException();
            }else{
                return $this->id;
            }
        }catch(PDOException $e){
            throw new Exception($e->getMessage());
        }
    }

    public static function checkUser($sid){
        return self::where('stid',$sid)
            ->field('id,stid,password')
            ->find();
    }

}