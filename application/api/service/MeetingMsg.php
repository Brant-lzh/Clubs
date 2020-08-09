<?php


namespace app\api\service;

use app\api\lib\exception\UserException;
use app\api\model\Msg;
use app\api\model\User;
use think\Db;

class MeetingMsg extends WxMessage
{
    const DELIERY_MSG_ID = 'Gzurs39t840Zxrb_Mh-r_4o1kykXVzYNbK-God9VFGc';//模板ID

    public function sendDeliveryMessage($uid,$mid, $tplJumpPage = 'pages/myinfo/myinfo')
    {

        $this->tplID = self::DELIERY_MSG_ID;
        $this->prepareMessageData($mid);
        $this->page = $tplJumpPage;
        return parent::sendMessage($this->getUserOpenID($uid));
    }

    private function prepareMessageData($mid)
    {
        $message = $this->getMessage($mid);
        $clubName = $this->getClubName($message['cid']);

        $data = [
            'thing1' => [
                'value' =>  $message['msg_title'],
            ],
            'thing2' => [
                'value' => $clubName['club_name'],
            ],
            'time3' => [
                'value' => $message['start_time'],
            ],
            'thing4' => [
                'value' => $message['place'],
            ],
        ];
        $this->data = $data;
    }

    private function getMessage($mid){
        $msg = Msg::get($mid);
        return $msg;
    }

    private function getClubName($cid){
        return Db::table('tb_club')
            ->where('id',$cid)
            ->field('club_name')
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