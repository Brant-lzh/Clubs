<?php


namespace app\api\model;


use think\Model;
use traits\model\SoftDelete;

class News extends BaseModel
{
    use SoftDelete;
    protected $name='news';
    protected $hidden = ['delete_time','update_time','delete_id','update_id'];


    public function getCheckTimeAttr($value)
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

    public function checkUser(){
        return $this->belongsTo('User','check_id','id');
    }



    public static function getBannerItem($id){
        return self::where('id',$id)
            ->field('id,title,thumbnail')
            ->find();
    }

    public static function getInfo($id){
        return self::where('id',$id)
            ->with([
                'club'=>function($query){
                    $query->field('id,club_name');
                }
            ])
            ->field('id,cid,keywords,title,thumbnail,description,check_time')
            ->find();
    }

    public static function getNewInfo($id){
        return self::where('id',$id)
            ->with([
                'club'=>function($query){
                    $query->field('id,avatar,club_name');
                }
            ])
            ->field('id,cid,title,thumbnail')
            ->find();
    }


    public static function getOneById($id){
        return self::where(['id'=>$id,'status'=>1])
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
                'checkUser'=>function($query){
                    $query->field('id,user_name');
                },
            ])
            ->find();
    }

}