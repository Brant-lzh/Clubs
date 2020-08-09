<?php


namespace app\api\model;


class Collect extends BaseModel
{
    protected $name = 'collect';

    public function collect(){
        return $this->belongsTo('Activity','aid','id');
    }

    public static function getOne($uid,$aid){
        return self::where(['uid'=>$uid,'aid'=>$aid])
            ->find();
    }

    public static function getCollectListByUid($uid){
        return self::where('uid',$uid)
            ->with([
                'collect'=>function($query){
                    $query->field('id,thumbnail,title,place,start_time');
                }
            ])->select();
    }

}