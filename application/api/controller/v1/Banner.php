<?php


namespace app\api\controller\v1;

use app\api\lib\exception\BannerMissException;
use app\api\validate\IDMustBePositiveInt;
use app\api\model\Banner as BannerModel;
use think\Exception;

class Banner
{
    /**
     * @url /banner
     * @http GET
     */
    public function getBanner()
    {

        $result = BannerModel::getBanner();
        if ($result == null) {
            throw new BannerMissException();
        }
        return $result;
    }
}