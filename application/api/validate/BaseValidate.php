<?php


namespace app\api\validate;


use app\api\lib\exception\ParameterException;
use think\Request;
use think\Validate;

class BaseValidate extends Validate
{
    public function goCheck()
    {
        $params = Request::instance()->param();
        $result = $this->batch()->check($params);
        if (!$result) {
            throw new ParameterException([
                'msg' => $this->error
            ]);

        } else {
            return true;
        }
    }

    protected function isNotEmpty($value, $rule, $data = '', $field)
    {
        if (empty($value)) {
            return false;
        } else {
            return true;
        }
    }

    // 自定义验证规则
    protected function isPositiveInt($value,$rule,$data=[],$field)
    {
        if (is_numeric($value) && is_int($value+0) && ($value + 0)>0){
            return true;
        }else{
            return false;
        }
    }



    protected function isPhone($value)
    {
        $rule = '/^1[0-9]{10}$/';
        $result = preg_match($rule,$value);
        if ($result){
            return true;
        }else{
            return false;
        }
    }

    public function getDataByRule($arrays)
    {
        if (array_key_exists('user_id', $arrays) ||
            array_key_exists('uid', $arrays)
        ) {
            throw new ParameterException([
                'msg' => '参数中包含有非法的参数名user_id或者uid'
            ]);
        }
        $newArray = [];
        foreach ($this->rule as $key => $value) {
            $newArray[$key] = $arrays[$key];
        }
        return $newArray;
    }
}