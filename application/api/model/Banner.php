<?php


namespace app\api\model;


use think\Exception;
use think\Model;

class Banner extends Model
{
    protected $name = 'banner';
    protected $hidden = ['id','aid','nid','order'];

    public static function getBanner(){
        $banners = self::order('order')->select();
        foreach ($banners as $key=>$vo){
            if ($vo['type'] == 0){
                $banners[$key]['item'] = News::getBannerItem($vo['nid']);
            }else{
                $banners[$key]['item'] = Activity::getBannerItem($vo['aid']);
            }
        }
        return $banners;
    }
}