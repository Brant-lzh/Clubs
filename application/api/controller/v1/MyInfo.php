<?php


namespace app\api\controller\v1;

use app\api\service\Token as TokenService;
use app\api\model\UserClubRole as UserClubRoleModel;
use app\api\model\Follow as FollowModel;
use app\api\model\Collect as CollectModel;
use app\api\model\User as UserModel;
use app\api\model\MsgUser as MsgUserModel;
use app\api\validate\IDMustBePositiveInt;

class MyInfo
{
    public function openMsg($id){
        (new IDMustBePositiveInt())->goCheck();
        $uid = TokenService::getCurrentUid();
        return UserClubRoleModel::update(['id'=>$id,'uid'=>$uid,'open_msg'=>1]);
    }

    public function isRead($id){
        (new IDMustBePositiveInt())->goCheck();
        $uid = TokenService::getCurrentUid();
        return MsgUserModel::update(['id'=>$id,'uid'=>$uid,'is_read'=>1]);
    }

    public function getMyMsgList(){
        $uid = TokenService::getCurrentUid();
        return MsgUserModel::getMsgList($uid);
    }

    public function getMsgOne($id){
        (new IDMustBePositiveInt())->goCheck();
        $uid = TokenService::getCurrentUid();
        return MsgUserModel::getMsgOne($id,$uid);
    }

    public function getMyInfo(){
        $uid = TokenService::getCurrentUid();
        $result = UserModel::getMyInfo($uid);
        $result['is_read'] = MsgUserModel::countMsg($uid);
        return $result;
    }

    public function getMyApply(){
        $uid = TokenService::getCurrentUid();
        $result = UserClubRoleModel::getUserClubInfo($uid);
        return $result;
    }

    public function getMyClubInfo(){
        $uid = TokenService::getCurrentUid();
        $result = UserClubRoleModel::getUserClubInfo($uid);
        return $result;
    }

    public function getMyFollow(){
        $uid = TokenService::getCurrentUid();
        $result = FollowModel::getFollowListByUid($uid);
        return $result;
    }

    public function getMyCollect(){
        $uid = TokenService::getCurrentUid();
        $result = CollectModel::getCollectListByUid($uid);
        return $result;
    }
}