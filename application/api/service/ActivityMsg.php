<?php


namespace app\api\service;


use app\api\lib\exception\MissException;
use app\api\lib\exception\UserException;
use app\api\model\Activity;
use app\api\model\Msg;
use app\api\model\User;

class ActivityMsg extends WxMessage
{
    const DELIERY_MSG_ID = 'YuhgRiFtgxBoKuyn6WoqNIM4x4d5pYafjyZes37824k';//模板ID

    public function sendDeliveryMessage($uid,$mid, $tplJumpPage = 'pages/myinfo/myinfo')
    {
        $this->tplID = self::DELIERY_MSG_ID;
        $this->page = $tplJumpPage;
        $this->prepareMessageData($mid);
        return parent::sendMessage($this->getUserOpenID($uid));
    }

    private function prepareMessageData($mid)
    {
        $message = $this->getMessage($mid);
        $activity = $this->getActivity($message['aid']);

        $data = [
            //活动名称
            'thing2' => [
                'value' =>  mb_substr($activity['title'],0,15,'utf-8').'...',
            ],
            //活动地点
            'thing6' => [
                'value' => $message['place'],
            ],
            //开始时间
            'date3' => [
                'value' => $message['start_time'],
            ],
            //温馨提示
            'thing1' => [
                'value' =>  mb_substr($message['content'],0,16,'utf-8').'...',
            ],
        ];
        $this->data = $data;
    }

    private function getActivity($aid)
    {
        $activity = new Activity();
        return $activity->where('id', $aid)
            ->field('title')
            ->find();
    }

    private function getMessage($mid){
        $msg = Msg::get($mid);
        return $msg;
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