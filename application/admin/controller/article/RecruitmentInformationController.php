<?php

namespace app\admin\controller\article;

use think\Controller;
use think\Request;
use app\common\model\RecruitmentModel;

class RecruitmentInformationController extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
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
        $info = new RecruitmentModel;
        $info->name = $request->param('name');
        $info->is_show = $request->param('is_show');
        $info->description = $request->param('description');
        $info->requirements = $request->param('requirements');
        $info->is_deleted = 0;
        $info->save(); 
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
            $result = RecruitmentModel::where('is_deleted', 0)->select();
            $result = $result->toArray();
            return json_encode(["data" => $result]);
        }
        $data = RecruitmentModel::get($id);
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
        $info = RecruitmentModel::get($id);
        $info->name = $request->param('name');
        $info->is_show = $request->param('is_show');
        $info->description = $request->param('description');
        $info->requirements = $request->param('requirements');
        $info->save(); 
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        $info = RecruitmentModel::get($id);
        $info->is_deleted = 1;
        $info->save();
    }
}
