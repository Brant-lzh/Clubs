<?php


namespace app\api\controller\v1;

use app\api\lib\exception\MissException;
use app\api\model\Activity as ActivityModel;
use app\api\service\Token as TokenService;
use app\api\validate\IDMustBePositiveInt;
use app\api\validate\PagingParameter;

class Activity
{
    public function getOne($id)
    {
        (new IDMustBePositiveInt())->goCheck();
        $uid = TokenService::getCurrentUid();
        $activity = ActivityModel::getOneById($id,$uid);
        if (!$activity) {
            throw new MissException();
        } else {
            if ($activity->getData('apply_start_time') > time()){
                $activity['status']=0;
            }elseif ($activity->getData('apply_end_time') <time()){
                $activity['status']=2;
            }else{
                $activity['status']=1;
            }
            return $activity;
        }
    }

    public function getHotActivity(){
        return ActivityModel::getHotActivity();
    }

    public function getHotApplyActivity()
    {
        return ActivityModel::getHotApplyActivity();
    }

    public function getIsNewActList($page=1,$size=4){
        (new PagingParameter())->goCheck();
        return ActivityModel::getIsNewActivityList($page,$size);
    }

}