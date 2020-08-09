<?php


namespace app\api\model;


use think\Model;
use traits\model\SoftDelete;

class Activity extends BaseModel
{
    use SoftDelete;

    protected $name = 'activity';
    protected $hidden = ['delete_time', 'update_time', 'delete_id', 'update_id'];

    public function club()
    {
        return $this->belongsTo('Club', 'cid', 'id');
    }

    public function clubson()
    {
        return $this->belongsTo('ClubSon', 'csid', 'id');
    }

    public function addUser()
    {
        return $this->belongsTo('User', 'create_id', 'id');
    }

    public function checkUser()
    {
        return $this->belongsTo('User', 'check_id', 'id');
    }

    public function collect()
    {
        return $this->belongsTo('Collect', 'id', 'aid');
    }

    public function apply()
    {
        return $this->belongsTo('Apply', 'id', 'aid');
    }

    public function getCheckTimeAttr($value)
    {
        if (!empty($value)) {
            return date("Y-m-d h:i", $value);
        }
        return $value;
    }

    public function getStartTimeAttr($value)
    {
        if (!empty($value)) {
            return date("Y年m月d日 h:i", $value);
        }
        return $value;
    }

    public function getEndTimeAttr($value)
    {
        if (!empty($value)) {
            return date("m月d日 h:i", $value);
        }
        return $value;
    }

    public function getApplyStartTimeAttr($value)
    {
        if (!empty($value)) {
            return date("Y年m月d日 h:i", $value);
        }
        return $value;
    }

    public function getApplyEndTimeAttr($value)
    {
        if (!empty($value)) {
            return date("m月d日 h:i", $value);
        }
        return $value;
    }

    public static function getIsNewActivityList($page=1,$size=4){
        $activity_new = self::with([
                'club' => function ($query) {
                    $query->field('id,club_name');
                }
            ])
            ->where(['is_new'=>1,'init'=>1])
            ->where('end_time','<',time())
            ->field('id,cid,keywords,title,thumbnail,start_time,place,apply_start_time,apply_end_time')
            ->paginate($size, true, ['page' => $page]);
        return $activity_new;
    }


    public static function getHotActivity(){
        return self::limit(6)
            ->where('end_time','>',time())
            ->where('init',1)
            ->field('id,title,thumbnail')
            ->select();
    }

    public static function getHotApplyActivity()
    {
        $result = self::where(['init' => 1])
            ->where('apply_start_time', '<=', time())
            ->where('apply_end_time', '>=', time())
            ->order('apply_end_time desc')
            ->field('id,title,people,thumbnail,apply_end_time,apply_start_time')
            ->select();
        foreach ($result as $key => $vo) {
            if ($vo['people'] != 0) {
                $result[$key]['apply_num'] = Apply::countActivityNum($vo['id']);
            }
        }
        return $result;
    }


    public static function getOneById($id, $uid)
    {
        return self::where(['id' => $id, 'init' => 1])
            ->with([
                'apply' => function ($query) use ($id, $uid) {
                    $query->where(['uid' => $uid, ' aid ' => $id]);
                },
                'collect' => function ($query) use ($id, $uid) {
                    $query->where(['uid' => $uid, ' aid ' => $id]);
                },
                'club' => function ($query) {
                    $query->field('id,club_name');
                },
                'clubson' => function ($query) {
                    $query->field('id,clubson_name');
                },
                'addUser' => function ($query) {
                    $query->field('id,user_name');
                },
                'checkUser' => function ($query) {
                    $query->field('id,user_name');
                },
            ])
            ->find();
    }

    public static function getBannerItem($id)
    {
        return self::where('id', $id)
            ->field('id,title,thumbnail')
            ->find();
    }

    public static function getInfo($id)
    {
        $activity = self::where('id', $id)
            ->with([
                'club' => function ($query) {
                    $query->field('id,club_name');
                }
            ])
            ->field('id,cid,keywords,title,thumbnail,start_time,place,apply_start_time,apply_end_time')
            ->find();
        if ($activity->getData('apply_start_time') > time()) {
            $activity['status'] = 0;
        } elseif ($activity->getData('apply_end_time') < time()) {
            $activity['status'] = 2;
        } else {
            $activity['status'] = 1;
        }
        return $activity;
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


    public static function getActivityLimit($aid)
    {
        return self::where('id', $aid)->field('people,apply_start_time,apply_end_time')->find();
    }

}