<?php


namespace app\api\controller\v1;


use app\api\model\Collect as CollectModel;
use app\api\service\Token as TokenService;
use app\api\validate\IDMustBePositiveInt;

class Collect
{
    public function addCollect($id)
    {
        (new IDMustBePositiveInt())->goCheck();
        $uid = TokenService::getCurrentUid();
        $follow = CollectModel::getOne($uid, $id);
        if ($follow) {
            $result = CollectModel::destroy(['uid' => $uid, 'aid' => $id]);
        } else {
            $result = CollectModel::create(['uid' => $uid, 'aid' => $id]);
        }
        return true;
    }
}