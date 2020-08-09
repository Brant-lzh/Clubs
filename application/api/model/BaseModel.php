<?php


namespace app\api\model;


use think\Model;

class BaseModel extends Model
{
    public function getThumbnailAttr($value)
    {
        if (!empty($value)){
            return config('settings.img_prefix').$value;
        }
        return $value;
    }

    public function getAvatarAttr($value)
    {
        if (!empty($value)){
            return config('settings.img_prefix').$value;
        }
        return $value;
    }

    public function getImageAttr($value)
    {
        if (!empty($value)){
            return config('settings.img_prefix').$value;
        }
        return $value;
    }

    public function getKeywordsAttr($value)
    {
        if (!empty($value)){
            return explode(",",$value);
        }
        return $value;
    }
}