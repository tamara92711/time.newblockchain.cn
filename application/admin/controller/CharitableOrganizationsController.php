<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\common\model\CharitableOrganizationModel;

class CharitableOrganizationsController extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $this->assign('root_nav', 'charitable_organizations');
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
        return $this->fetch();
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        $co = new CharitableOrganizationModel;
        $co->name = $request->param('co_name');
        $co->description = $request->param('co_description');
        $co->is_show = $request->param('is_show');
        $co->is_deleted = 0;
        if (!empty($request->file('co_photo')))
        {
            $image = $request->file('co_photo');
            $info = $image->move('./uploads/');
            $co->image = $info->getSaveName();
        }
        $co->save();
        return redirect('/admin/charitable_organizations');
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        if ($id == 0) {
            $data = CharitableOrganizationModel::where('is_deleted', 0)->select();
            return json_encode(["data" => $data]);
        }
        $data = CharitableOrganizationModel::get($id);
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
        $this->assign('root_nav', 'charitable_organizations');
        $this->assign('sub_nav', '');
        $data = CharitableOrganizationModel::get($id);
        if (!empty($data->image)) {
            $path = explode('\\',$data->image);
            $data->image = '/uploads/' . $path[0] . '/' . $path[1];
        }
        $this->assign("data", $data);
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
        $co = CharitableOrganizationModel::get($id);
        $co->name = $request->param('co_name');
        $co->description = $request->param('co_description');
        $co->is_show = $request->param('is_show');
        $co->is_deleted = 0;
        if (!empty($request->file('co_photo')))
        {
            $logo_image = $request->file('co_photo');
            $info = $logo_image->move('./uploads/');
            $co->image = $info->getSaveName();
        }
        $co->save();
        return redirect('/admin/charitable_organizations');
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        $co = CharitableOrganizationModel::get($id);
        $co->is_deleted = 1;
        $co->save();
        return redirect('/admin/volunteers');
    }
}
