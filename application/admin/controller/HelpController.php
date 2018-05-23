<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\common\model\HelpLinkModel;

class HelpController extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $this->assign('root_nav', 'helps');
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
        $help = new HelpLinkModel;
        $help->name = $request->param('link_name');
        $help->url = $request->param('link_url');
        $help->is_show = $request->param('is_show');
        $help->save();
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
            $result = HelpLinkModel::where('is_deleted', 0)->select();
            return json_encode(["data" => $result]);
        }
        $data = HelpLinkModel::get($id);
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
        $help = HelpLinkModel::get($id);
        $help->name = $request->param('link_name');
        $help->url = $request->param('link_url');
        $help->is_show = $request->param('is_show');
        $help->save();
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        $help = HelpLinkModel::get($id);
        $help->is_deleted = 1;
        $help->save();
    }
}