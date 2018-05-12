<?php

namespace app\common\model;

use think\Model;

class ComplaintTypeModel extends Model
{
    public function complaints()
    {
        return $this->hasMany('ComplaintModel', 'type');
    }
}
