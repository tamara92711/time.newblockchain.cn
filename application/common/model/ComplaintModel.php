<?php

namespace app\common\model;

use think\Model;

class ComplaintModel extends Model
{
    public function user()
    {
        return $this->belongsTo('user');
    }
}
