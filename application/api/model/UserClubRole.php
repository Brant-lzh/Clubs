<?php


namespace app\api\model;


use traits\model\SoftDelete;

class UserClubRole extends BaseModel
{
    use SoftDelete;

    protected $name = 'user_club_role';

    public function userInfo()
    {
        return $this->belongsTo('User', 'uid', 'id');
    }

    public function club()
    {
        return $this->belongsTo('Club', 'cid', 'id');
    }

    public function clubson()
    {
        return $this->belongsTo('ClubSon', 'csid', 'id');
    }

    public static function getClubMembers($cid)
    {
        return self::where(['cid' => $cid, 'rid' => ['<', 4]])
            ->with([
                'userInfo' => function ($query) {
                    $query->field('id,user_name');
                }
            ])
            ->field('uid,rid,position')
            ->select();
    }

    public static function getUserClubInfo($uid){
        return self::where('uid',$uid)
            ->with([
                'club' => function ($query) {
                    $query->field('id,avatar,club_name');
                },
                'clubson' => function ($query) {
                    $query->field('id,clubson_name');
                }
            ])->field('id,cid,uid,csid,position,open_msg')
            ->select();
    }
}