<?php


namespace app\api\controller\v1;
use app\api\model\Info as InfoModel;
use app\api\service\Token as TokenService;
use app\api\validate\IDMustBePositiveInt;
use app\api\model\Follow as FollowModel;
use app\api\validate\PagingParameter;

class Info
{
    public function getInfoList($page=1,$size=4){
        (new PagingParameter())->goCheck();
        $infoList = InfoModel::getInfoList('','',$page,$size);
        return $infoList;
    }

    public function getInfoClubList($id,$page=1,$size=3){
        (new IDMustBePositiveInt())->goCheck();
        (new PagingParameter())->goCheck();
        $infoClubList = InfoModel::getInfoList($id,'',$page,$size);
        return $infoClubList;
    }


    public function getNewInfo(){
        return InfoModel::getNewInfo();
    }

    public function getMyFollowInfo($page=1,$size=4){
        (new PagingParameter())->goCheck();
        $uid = TokenService::getCurrentUid();
        return FollowModel::getMyFollowInfo($uid,$page,$size);
    }

    public function getActivityList($page=1,$size=4){
        (new PagingParameter())->goCheck();
        return InfoModel::getInfoList('',1,$page,$size);
    }


}