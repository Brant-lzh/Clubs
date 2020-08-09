<?php


namespace app\api\model;


class Follow extends BaseModel
{
    protected $name = 'follow';

    public function club(){
        return $this->belongsTo('Club','cid','id');
    }

    public function info(){
        return $this->belongsTo('Info','cid','cid');
    }

    public static function getOne($uid,$cid){
        return self::where(['uid'=>$uid,'cid'=>$cid])
            ->find();
    }

    public static function getFollowListByUid($uid){
        return self::where('uid',$uid)
            ->with([
                'club'=>function($query){
                    $query->field('id,avatar,club_name');
                }
            ])
            ->select();
    }

    public static function getMyFollowInfo($uid,$page,$size){
        $follows = self::where('uid',$uid)->field('cid')->select();
        foreach ($follows as $k=>$v){
            $follows[$k] = $v['cid'];
        }
        return Info::getInfoList(['in',$follows],'',$page,$size);
    }
}