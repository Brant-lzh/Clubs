<?php


namespace app\api\model;


use traits\model\SoftDelete;

class Apply extends BaseModel
{
    use SoftDelete;

    protected $name = 'apply';

    public function getApplyTimeAttr($value)
    {
        if (!empty($value)) {
            return date("Y-m-d H:i", $value);
        }
        return $value;
    }

    public function activity()
    {
        return $this->belongsTo('Activity', 'aid', 'id');
    }


    public static function getApplyByUser($uid, $page = 1, $size = 3)
    {
        return self::where('uid', $uid)
            ->with([
                'activity' => function ($query) {
                    $query->field('id,title,thumbnail,apply_end_time,end_time');
                }
            ])
            ->order('apply_time desc')
            ->field('apply_time,id,aid')
            ->paginate($size, true, ['page' => $page]);
    }

    public static function countActivityNum($aid)
    {
        return self::where('aid', $aid)->count();
    }

    public static function getOne($aid, $uid)
    {
        return self::where(['aid' => $aid, 'uid' => $uid])->find();
    }

}