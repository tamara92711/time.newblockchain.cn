<?php

namespace app\common\model;

use think\Model;

class ArticleTypeModel extends Model
{
    public function articles()
    {
        return $this->hasMany('ArticleModel', 'type');
    }
}
