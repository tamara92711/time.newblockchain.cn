<?php

namespace app\admin\controller\article;

use think\Controller;
use think\Request;
use app\common\model\ArticleTypeModel;

class TypesController extends Controller
{
    protected $pk = 'id';
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $this->assign('root_nav', 'article');
        $this->assign('sub_nav', 'article_types');
        return $this->fetch();
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        return "create:";
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        $articleType = new ArticleTypeModel;
        $articleType->name = $request->post('name');
        $articleType->description = $request->post('description');
        $articleType->pid = $request->post('pid');
        $articleType->save();
        return $articleType->id;
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
            $data_array = array_values(ArticleTypeModel::where('is_deleted', 0)->column(['id', 'pid', 'name', 'description']));
            return json_encode([ "data" => $data_array]);
        }
        return json_encode(ArticleTypeModel::where('id', $id)->column(['id', 'pid', 'name', 'description']));
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
        $articleType = ArticleTypeModel::get($id);
        $articleType->name = $request->param('name');
        $articleType->description = $request->param('description');
        $articleType->save();
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        $articleType = ArticleTypeModel::get($id);
        $articleType->is_deleted = 1;
        $articleType->save();
    }
}
