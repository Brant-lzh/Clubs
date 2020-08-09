<?php


namespace app\api\controller\v1;


use app\api\model\MsgUser;
use app\api\model\User;
use app\api\service\ActivityMsg;
use app\api\service\MeetingMsg;
use app\api\validate\IDMustBePositiveInt;

class Send
{
    public function sendMeetingMsg($id){
        (new IDMustBePositiveInt())->goCheck();
        $meetingMsg = new MeetingMsg();
        $msgUsers = MsgUser::all(['mid'=>$id,'is_read'=>0]);
        foreach ($msgUsers as $key=>$vo) {
            if (User::checkOpenID($vo['uid'])){
                $meetingMsg->sendDeliveryMessage($vo['uid'], $id);
            }
        }
        return true;
    }

    public function sendActivityMsg($id){
        (new IDMustBePositiveInt())->goCheck();
        $activityMsg = new ActivityMsg();
        $msgUsers = MsgUser::all(['mid'=>$id,'is_read'=>0]);
        foreach ($msgUsers as $key=>$vo){
            $activityMsg->sendDeliveryMessage($vo['uid'],$id);
        }
        return true;
    }
}