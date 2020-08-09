<?php


namespace app\api\controller\v1;

use app\api\lib\exception\ApplyException;
use app\api\lib\exception\SuccessMessage;
use app\api\lib\exception\UserException;
use app\api\service\ApplySuccessMsg;
use app\api\validate\Apply as ApplyValidate;
use app\api\service\Token as TokenService;
use app\api\model\User as UserModel;
use app\api\model\Apply as ApplyModel;
use app\api\validate\IDMustBePositiveInt;
use app\api\validate\PagingParameter;
use app\api\model\Activity as ActivityModel;
use think\Cache;

class Apply
{
    public function submitApply()
    {

        $validate = new ApplyValidate();
        $validate->goCheck();
        $uid = TokenService::getCurrentUid();
        $user = UserModel::get($uid);
        if (!$user){
            throw new UserException();
        }
        $dataArray = $validate->getDataByRule(input('post.'));
//        $limit = ActivityModel::getActivityLimit($dataArray['aid']);

        //查询是否到时间报名
        $activity = ActivityModel::get($dataArray['aid']);

        if ($activity->getData('apply_start_time')>time()){
            throw new ApplyException([
                'msg'=>'还没有到报名时间'
            ]);
        }
        if ($activity->getData('apply_end_time')<time()){
            throw new ApplyException([
                'msg'=>'报名已结束'
            ]);
        }
        //查询是否限制人数

        if (!empty($activity->people)){
            $activityNum = ApplyModel::countActivityNum($dataArray['aid']);
            //查询是否已经满人
            if ($activity->people <= $activityNum){
                throw new ApplyException([
                    'msg'=>'报名人数已满！'
                ]);
            }
        }

        //判断是否重复报名
        $find = ApplyModel::getOne($dataArray['aid'], $uid);
        if ($find){
            throw new ApplyException([
                'msg'=>'已报名,请勿重复提交'
            ]);
        }

        //插入
        $dataArray['uid'] = $uid;
        $dataArray['apply_time'] = time();
        $apply = new ApplyModel();
        $apply->save($dataArray);
        $sendRuselt = $this->sendSuccessMsg($apply->id);
        return json(new SuccessMessage(),201);
    }

    public function sendSuccessMsg($id){
        $apply = ApplyModel::get($id);
        if (!$apply){
            throw new ApplyException();
        }
        $message = new  ApplySuccessMsg();
       return $message->sendDeliveryMessage($apply,'');
    }

    public function delivery($id,$jumpPage){
        (new IDMustBePositiveInt())->goCheck();
        $apply = ApplyModel::get($id);
        if (!$apply){
            throw new ApplyException();
        }
        $message = new  ApplySuccessMsg();
        return $message->sendDeliveryMessage($apply,$jumpPage);
    }


    public function getApplyByUser($page=1,$size=15){
        (new PagingParameter())->goCheck();
        $uid = TokenService::getCurrentUid();
        $pagingApply = ApplyModel::getApplyByUser($uid,$page,$size);
        if (!$pagingApply){
            return [
                'data' => [],
                'current_page'=>$pagingApply->currentPage(),
            ];
        }
        return [
            'data' => $pagingApply,
            'current_page'=>$pagingApply->currentPage(),
        ];
    }
}