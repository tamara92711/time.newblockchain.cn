<?php

namespace app\admin\controller\article;

use think\Controller;
use think\Request;
use app\common\model\ArticleModel;
use app\common\model\ArticleTypeModel;

class IndexController extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $article_types = array_values(ArticleTypeModel::where('is_deleted', 0)->column(['id', 'pid', 'name', 'description']));
        $article_types1 = ArticleTypeModel::where('is_deleted', 0)->column(['id', 'name']);
        $this->assign('article_types', $article_types);
        $this->assign('article_types1', json_encode($article_types1));
        return $this->fetch();
        // return json_encode($article_types1);
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
        $article = new ArticleModel;
        $article->title = $request->param('title');
        // $article->title = "djskf";
        $article->description = $request->param('description');
        $article->is_show = $request->param('is_show');
        $article->is_deleted = 0;
        $article->type = $request->param('type');
        // $article->type = "51";
        $article->publish_time = $request->param('publish_time');
        $article->save();
        // echo $article->publish_time;
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
            $result = ArticleModel::where('is_deleted', 0)->select();
            $result = $result->toArray();
            return json_encode(["data" => $result]);
        }
        $data = ArticleModel::get($id);
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
        $article = ArticleModel::get($id);
        $article->title = $request->param('title');
        $article->description = $request->param('description');
        $article->is_show = $request->param('is_show');
        $article->is_deleted = 0;
        $article->type = $request->param('type');
        $article->publish_time = $request->param('publish_time');
        $article->save();
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        $article = ArticleModel::get($id);
        $article->is_deleted = 1;
        $article->save();
    }
}
