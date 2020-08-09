<?php


namespace app\api\controller\v1;


use app\api\validate\IDMustBePositiveInt;
use app\api\model\ClubSon as ClubSonModel;

class Clubson
{
    public function getClubSonList($id){
        (new IDMustBePositiveInt())->goCheck();
        $clubSonList = ClubSonModel::getClubSonList($id);
        return $clubSonList;
    }
}