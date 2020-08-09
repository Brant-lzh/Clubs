<?php


namespace app\api\model;


use traits\model\SoftDelete;

class Sort extends BaseModel
{
    use SoftDelete;
    protected $name = 'sort';
    protected $hidden = ['delete_time'];

}