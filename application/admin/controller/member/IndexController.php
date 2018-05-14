<?php

namespace app\admin\controller\member;

use think\Controller;
use think\Request;

use app\common\model\UserModel;
use app\common\model\JobTypeModel;
use app\common\model\EducationTypeModel;
use app\common\model\RegionModel;

class IndexController extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $root_regions = RegionModel::where('level', 1)->column('name', 'id');
        $this->assign('regions', $root_regions);
        $this->assign('regions1', json_encode($root_regions));

        $job_types = JobTypeModel::all()->column('name', 'id');
        $this->assign('job_types', $job_types);
        $this->assign('job_types1', json_encode($job_types));

        $edu_types = EducationTypeModel::all()->column('name', 'id');
        $this->assign('edu_types', $edu_types);
        $this->assign('edu_types1', json_encode($edu_types));


        $this->assign('root_nav', 'member');
        $this->assign('sub_nav', 'member_index');
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
        $user = new UserModel;
        $user->name = $request->param('name');
        $user->mobile = $request->param('mobile');
        $user->region_1 = $request->param('region_1');
        $user->region_2 = $request->param('region_2');
        $user->real_name = $request->param('real_name');
        $user->sex = $request->param('sex');
        $user->password = md5('123456');
        $user->job_type = $request->param('job_type');
        $user->education_type = $request->param('education_type');
        $user->fixed = $request->param('fixed');
        $user->save();
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
            $result = UserModel::all()->toArray();
            return json_encode(["data" => $result]);
        }
        $data = UserModel::get($id);
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
        $user = UserModel::get($id);
        $user->name = $request->param('name');
        $user->mobile = $request->param('mobile');
        $user->region_1 = $request->param('region_1');
        $user->region_2 = $request->param('region_2');
        $user->real_name = $request->param('real_name');
        $user->sex = $request->param('sex');
        $user->job_type = $request->param('job_type');
        $user->education_type = $request->param('education_type');
        $user->fixed = $request->param('fixed');
        $user->save();
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        $user = UserModel::get($id);
        $user->is_deleted = 1;
        $user->save();
    }

    public function resetPassword($id)
    {
        $user = UserModel::get($id);
        $user->password = md5('123456');
        $user->save();
    }
}
