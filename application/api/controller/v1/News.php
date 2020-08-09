<?php


namespace app\api\controller\v1;


use app\api\lib\exception\MissException;
use app\api\model\News as NewsModel;
use app\api\service\Token as TokenService;
use app\api\validate\IDMustBePositiveInt;

class News
{
    public function getOne($id){
        (new IDMustBePositiveInt())->goCheck();
        $new = NewsModel::getOneById($id);
        if (!$new){
            throw new MissException();
        }else{
            return $new;
        }
    }
}