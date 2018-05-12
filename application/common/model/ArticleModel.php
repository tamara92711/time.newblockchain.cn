<?php

namespace app\common\model;

use think\Model;

class ArticleModel extends Model
{
    public function articleTypeModel()
    {
        return $this->belongsTo('ArticleTypeModel', 'type');
    }
}
