<?php

namespace app\admin\controller\news;

use think\Controller;
use think\Request;
use app\common\model\NewsModel;
use app\common\model\NewsTypeModel;

class IndexController extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $news_types = array_values(NewsTypeModel::where('is_deleted', 0)->column(['id', 'pid', 'name', 'description']));
        $news_types1 = NewsTypeModel::where('is_deleted', 0)->column(['id', 'name']);
        $this->assign('news_types', $news_types);
        $this->assign('news_types1', json_encode($news_types1));
        return $this->fetch();
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        $news = new NewsModel;
        $news->title = $request->param('title');
        $news->content = $request->param('content');
        $news->is_show = $request->param('is_show');
        $news->is_deleted = 0;
        $news->type = $request->param('type');
        $news->save();
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        if ($id == 0)
        {
            $result = NewsModel::where('is_deleted', 0)->select();
            $result = $result->toArray();
            return json_encode(["data" => $result]);
        }
        $data = NewsModel::get($id);
        return json_encode($data);
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        $news = NewsModel::get($id);
        $news->title = $request->param('title');
        $news->content = $request->param('content');
        $news->is_show = $request->param('is_show');
        $news->is_deleted = 0;
        $news->type = $request->param('type');
        $news->save();
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        $news = NewsModel::get($id);
        $news->is_deleted = 1;
        $news->save();
    }
}
