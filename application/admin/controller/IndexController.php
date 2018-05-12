<?php
namespace app\admin\controller;
use think\Controller;

class IndexController extends Controller
{
    public function index()
    {
        return redirect('/admin/article.types');
    }

    public function test()
    {
        return $this->fetch();
    }
}
