<?php


namespace app\api\model;


use traits\model\SoftDelete;

class Msg extends BaseModel
{
    use SoftDelete;
    protected $name = 'message';
    protected $hidden = ['delete_time','delete_id'];

    public function getCreateTimeAttr($value)
    {
        if (!empty($value)){
            return  date("Y-m-d H:i",$value);
        }
        return $value;
    }


    public function getStartTimeAttr($value)
    {
        if (!empty($value)){
            return  date("Y-m-d H:i",$value);
        }
        return $value;
    }

    public function club(){
        return $this->belongsTo('Club','cid','id');
    }

    public function clubson(){
        return $this->belongsTo('ClubSon','csid','id');
    }

    public function addUser(){
        return $this->belongsTo('User','create_id','id');
    }

    public static function getOne($id){
        return self::where('id',$id)
            ->with([
                'club'=>function($query){
                    $query->field('id,club_name,avatar');
                },
                'clubson'=>function($query){
                    $query->field('id,clubson_name');
                },
                'addUser'=>function($query){
                    $query->field('id,user_name');
                },
            ])
            ->find();
    }
}