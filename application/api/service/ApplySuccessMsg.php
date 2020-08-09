<?php


namespace app\api\service;


use app\api\lib\exception\MissException;
use app\api\lib\exception\UserException;
use app\api\model\Activity;
use app\api\model\User;

class ApplySuccessMsg extends WxMessage
{
    const DELIERY_MSG_ID = 'UB1GZDU6lWcEqLtaxwkHPnZX04T5YubsRWSqGZ7psoU';//模板ID

    public function sendDeliveryMessage($apply, $tplJumpPage = 'pages/my/myapply')
    {
        if (!$apply) {
            throw new MissException([
                'msg' => '报名数据不存在',
            ]);
        }
        $this->tplID = self::DELIERY_MSG_ID;
        $this->page = $tplJumpPage;
        $this->prepareMessageData($apply);
        return parent::sendMessage($this->getUserOpenID($apply->uid));
    }

    private function prepareMessageData($apply)
    {
        $user = $this->getUser($apply['uid']);
        $activity = $this->getActivity($apply['aid']);
        if (!$user) {
            throw new UserException();
        }
        if (!$activity) {
            throw new MissException([
                'msg' => '活动数据不存在',
            ]);
        }
        /*活动名称
{{thing1.DATA}}
活动地址
{{thing2.DATA}}
活动时间
{{date3.DATA}}
报名时间
{{date9.DATA}}
其他说明
{{thing5.DATA}}
 */
        $data = [

            'name1' => [
                'value' => $user['user_name'],
            ],
            'number11' => [
                'value' => $user['stid'],
            ],
            'thing2' => [
                'value' =>  mb_substr($activity['title'],0,15,'utf-8').'...',
            ],
            'date14' => [
                'value' => $apply['apply_time'],
            ],
            'date4' => [
                'value' => $activity['start_time'],
            ],
        ];
        $this->data = $data;
    }

    private function getUser($uid)
    {
        $user = new User();
        return $user->where('id', $uid)
            ->field('user_name,stid')
            ->find();
    }

    private function getActivity($aid)
    {
        $activity = new Activity();
        return $activity->where('id', $aid)
            ->field('title,start_time')
            ->find();
    }

    private function getUserOpenID($uid)
    {
        $user = User::get($uid);
        if (!$user) {
            throw new UserException();
        }
        return $user->openid;
    }

}