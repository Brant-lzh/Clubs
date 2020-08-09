<?php

namespace app\api\model;

use think\Model;

class Club extends BaseModel
{
    protected $name = 'club';
    protected $hidden = ['delete_time', 'delete_id', 'update_time', 'update_id', 'create_time', 'create_id'];

    public function follow(){
        return $this->belongsTo('Follow','id','cid');
    }

    public static function getOneById($id,$uid)
    {
        return self::where(['id' => $id, 'status' => 1])
            ->with([
                'follow'=>function($query) use ($id,$uid){
                    $query->where(['uid'=>$uid,' cid '=>$id]);
                }
            ])
            ->field('people_num,status',true)
            ->find();
    }

    public static function getSearchClubList($words)
    {
        return self::where('status', 1)
            ->where('club_name','like','%'.$words.'%')
            ->field('id,sort_id,keywords,image,club_name,intro')
            ->select();
    }

    public static function getClubList()
    {
        return self::where('status', 1)
            ->field('id,sort_id,keywords,image,club_name,intro')
            ->select();
    }

    public static function getClubSortList($sort)
    {
        return self::where('sort_id', $sort)
            ->field('id,club_name,keywords,intro,sort_id')
            ->select();
    }

    public static function getClubInfo($id)
    {
        return self::where(['status' => 1, 'id' => $id])
            ->field('club_name,intro,content,avatar,image,keywords')
            ->find();
    }
}
