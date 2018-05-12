<?php

namespace app\common\model;

use think\Model;

class RegionModel extends Model
{
    public function address()
    {
        return $this->hasMany('address','region_id_1')->hasMany('address', 'region_id_2');
    }
}
