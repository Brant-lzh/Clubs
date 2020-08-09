<?php


namespace app\api\model;

class Info extends BaseModel
{
    protected $hidden = ['id','cid','fid','delete_time'];

    public function getCheckTimeAttr($value)
    {
        if (!empty($value)){
            return  date("Y-m-d",$value);
        }
        return $value;
    }

    public static function getNewInfo(){
        $infos = self::order('check_time desc')
            ->limit(3)
            ->select();
        foreach ($infos as $key => $vo) {
            if ($vo['type'] == 0) {
                $infos[$key]['info'] = News::getNewInfo($vo['fid']);
            } else {
                $infos[$key]['info'] = Activity::getNewInfo($vo['fid']);
            }
        }
        return $infos;
    }

    public static function getInfoList($cid = '', $type = '',$page = 1, $size = 4)
    {
        $where = [];
        if (!empty($cid)) {
            $where['cid'] = $cid;
        }
        if ($type == 1) {
            $where['type'] = $type;
        }
        $infos = self::order('check_time desc')
            ->where($where)
            ->paginate($size, true, ['page' => $page]);
        foreach ($infos as $key => $vo) {
            if ($vo['type'] == 0) {
                $infos[$key]['info'] = News::getInfo($vo['fid']);
            } else {
                $infos[$key]['info'] = Activity::getInfo($vo['fid']);
            }
        }
        return $infos;
    }


}