<?php


namespace app\api\controller\v1;

use app\api\lib\exception\MissException;
use app\api\model\Club as ClubModel;
use app\api\model\ClubSon as ClubSonModel;
use app\api\model\UserClubRole as UserClubRoleModel;
use app\api\service\Token as TokenService;
use app\api\validate\IDMustBePositiveInt;

class Club
{
    public function getOne($id){
        (new IDMustBePositiveInt())->goCheck();
        $uid = TokenService::getCurrentUid();
        $club = ClubModel::getOneById($id,$uid);
        if (!$club){
            throw new MissException();
        }else{
            $club['members'] = UserClubRoleModel::getClubMembers($id);
            $club['clubson'] = ClubSonModel::getClubSonList($id);
            return $club;
        }
    }

    public function getSearchClubList(){
        //验证中文文字
        $words = input('post.words');
        $uid = TokenService::getCurrentUid();
        return ClubModel::getSearchClubList($words);
    }

    public function getClubList(){
        return ClubModel::getClubList();
    }

    public function getClubSortList($id){
        (new IDMustBePositiveInt())->goCheck();
        $clubSortList = ClubModel::getClubSortList($id);
        return $clubSortList;
    }

    public function getClubInfo($id){
        (new IDMustBePositiveInt())->goCheck();
        $clubInfo = ClubModel::getClubInfo($id);
        return $clubInfo;
    }

    public function getSortList(){
       return \app\api\model\Sort::all();
    }
}