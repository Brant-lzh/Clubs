<?php


namespace app\api\model;


class ClubSon extends BaseModel
{
    protected $name = "club_son";

    public function userClubRole()
    {
        return $this->belongsTo('UserClubRole', 'id', 'csid');
    }

    public static function getClubSonList($cid)
    {
        return self::with([
            'userClubRole' => function ($query) {
                $query->with([
                    'userInfo'=>function($query){
                        $query->field('id,user_name');
                    }
                ])->field('uid,csid,rid,position')->order('rid desc');
            }
        ])
            ->where(['fid' => $cid, 'status' => 1])
            ->field('id,clubson_name,avatar,intro')
            ->select();
    }
}