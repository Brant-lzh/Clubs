<?php


namespace app\api\model;


use think\Model;

class WxUser extends Model
{
    protected $table="tb_wxuser";
    public static function getByOpenID($openid){
        $user = self::where('openid','=',$openid)
            ->find();
        return $user;
    }
}