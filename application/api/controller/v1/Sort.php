<?php


namespace app\api\controller\v1;
use app\api\model\Sort as SortModel;

class Sort
{
    public function getSort(){
        return SortModel::all();
    }
}