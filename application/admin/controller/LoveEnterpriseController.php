<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\common\model\LoveEnterpriseModel;

class LoveEnterpriseController extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $this->assign('root_nav', 'love_enterprise');
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
        $le = new LoveEnterpriseModel;
        $le->name = $request->param('lo_name');
        $le->description = $request->param('lo_description');
        $le->is_show = $request->param('is_show');
        $le->is_deleted = 0;
        if (!empty($request->file('lo_photo')))
        {
            $logo_image = $request->file('lo_photo');
            $info = $logo_image->move('./uploads/');
            $le->image = $info->getSaveName();
        }
        $le->save();
        return redirect('/admin/love_enterprise');
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
            $data = LoveEnterpriseModel::where('is_deleted', 0)->select();
            return json_encode(["data" => $data]);
        }
        $data = LoveEnterpriseModel::get($id);
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
        $data = LoveEnterpriseModel::get($id);
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
        $le = LoveEnterpriseModel::get($id);
        $le->name = $request->param('lo_name');
        $le->description = $request->param('lo_description');
        $le->is_show = $request->param('is_show');
        $le->is_deleted = 0;
        if (!empty($request->param('lo_photo')))
        {
            $logo_image = $request->file('lo_photo');
            $info = $logo_image->move('./uploads/');
            $le->image = $info->getSaveName();
        }
        $le->save();
        return redirect('/admin/love_enterprise');
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        $le = LoveEnterpriseModel::get($id);
        $le->is_deleted = 1;
        $le->save();
        return redirect('/admin/volunteers');
    }
}
