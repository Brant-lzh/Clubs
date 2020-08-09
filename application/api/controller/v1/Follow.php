<?php


namespace app\api\controller\v1;

use app\api\service\Token as TokenService;
use app\api\validate\IDMustBePositiveInt;
use app\api\model\Follow as FollowModel;


class Follow
{
    public function addFollow($id)
    {
        (new IDMustBePositiveInt())->goCheck();
        $uid = TokenService::getCurrentUid();
        $follow = FollowModel::getOne($uid, $id);
        if ($follow) {
            $result = FollowModel::destroy(['uid' => $uid, 'cid' => $id]);
        } else {
            $result = FollowModel::create(['uid' => $uid, 'cid' => $id]);
        }
        return true;
    }



}