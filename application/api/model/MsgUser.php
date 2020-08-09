<?php


namespace app\api\model;


use traits\model\SoftDelete;

class MsgUser extends BaseModel
{
    use SoftDelete;
    protected $name = 'message_user';
    protected $hidden = ['delete_time','delete_id'];

    public function getCheckTimeAttr($value)
    {
        if (!empty($value)){
            return  date("Y-m-d H:i",$value);
        }
        return $value;
    }

    public function msg(){
        return $this->belongsTo('Msg','mid','id');
    }

    public static function countMsg($uid){
        return self::where(['uid'=>$uid,'is_read'=>0])->count();
    }

    public static function getMsgList($uid){
        return self::where('uid',$uid)
            ->with([
                'msg'=>function($query){
                    $query->with([
                        'club'=>function($query){
                            $query->field('id,club_name,avatar');
                        },
                        'clubson'=>function($query){
                            $query->field('id,clubson_name');
                        },
                        'addUser'=>function($query){
                            $query->field('id,user_name');
                        },
                    ]);
                },
            ])
            ->select();
    }

    public static function getMsgOne($id,$uid){
        return self::where(['mid'=>$id,'uid'=>$uid])
            ->with([
                'msg'=>function($query){
                    $query->with([
                        'club'=>function($query){
                            $query->field('id,club_name,avatar');
                        },
                        'clubson'=>function($query){
                            $query->field('id,clubson_name');
                        },
                        'addUser'=>function($query){
                            $query->field('id,user_name');
                        },
                    ]);
                },
            ])
            ->find();
    }
}