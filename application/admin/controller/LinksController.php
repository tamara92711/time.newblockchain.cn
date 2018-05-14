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
        $logo_image = $request->file('logo_image');
        $info = $logo_image->move('./uploads/');
        
        $link = new LinkModel;
        $link->name = $request->param('link_name');
        $link->url = $request->param('link_url');
        $link->logo = $info->getSaveName();
        $link->order_by = $request->param('link_order');
        $link->is_show = $request->param('is_show');
        $link->new_window = $request->param('new_window');
        $link->save();
        return redirect('/admin/links/index');
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
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
        $link->delete();
        return redirect('/admin/links');
    }
}
