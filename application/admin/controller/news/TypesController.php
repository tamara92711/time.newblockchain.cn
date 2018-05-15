<?php

namespace app\admin\controller\news;

use think\Controller;
use think\Request;
use app\common\model\NewsTypeModel;

class TypesController extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $this->assign('root_nav', 'news');
        $this->assign('sub_nav', 'news_types');
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
        $newsType = new NewsTypeModel;
        $newsType->name = $request->post('name');
        $newsType->description = $request->post('description');
        $newsType->pid = $request->post('pid');
        $newsType->save();
        return $newsType->id;
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
            $data_array = array_values(NewsTypeModel::where('is_deleted', 0)->column(['id', 'pid', 'name', 'description']));
            return json_encode([ "data" => $data_array]);
        }
        return json_encode(NewsTypeModel::where('id', $id)->column(['id', 'pid', 'name', 'description']));
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
        $newsType = NewsTypeModel::get($id);
        $newsType->name = $request->param('name');
        $newsType->description = $request->param('description');
        $newsType->save();
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        $newsType = NewsTypeModel::get($id);
        $newsType->is_deleted = 1;
        $newsType->save();
    }
}
