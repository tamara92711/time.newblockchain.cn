<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\common\model\LinkModel;

class LinksController extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $this->assign('root_nav', 'links');
        $this->assign('sub_nav', '');
        return $this->fetch();
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {

        return $this->fetch('/admin/links/index');
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        $link = new LinkModel;
        $link->name = $request->param('link_name');
        $link->url = $request->param('link_url');
        $link->is_show = $request->param('is_show');
        $link->save();
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
            $result = LinkModel::where('is_deleted', 0)->select();
            return json_encode(["data" => $result]);
        }
        $data = LinkModel::get($id);
        return json_encode($data);
    }

    public function getLinksList()
    {
        $data = LinkModel::all();
        return json_encode(["data" => $data]);
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        $data = LinkModel::get($id);
        $this->assign([
            'id' => $data->id,
            'name' => $data->name,
            'url' => $data->url,
            'logo' => $data->logo,
            'order_by' => $data->order_by,
            'is_show' => $data->is_show,
            'new_window' => $data->new_window,
        ]);
        return $this->fetch();
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
        $link = LinkModel::get($id);
        $link->name = $request->param('link_name');
        $link->url = $request->param('link_url');
        $link->is_show = $request->param('is_show');
        $link->save();
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        $link = LinkModel::get($id);
        $link->is_deleted = 1;
        $link->save();
    }
}
