<?php

namespace app\index\controller\about_us;

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
        $job_offers = RecruitmentModel::all()->toArray();
        $this->assign('job_offers', $job_offers);
        $this->assign('header_nav', 'about_us');
        $this->assign('side_nav', 'recruitment_information');
        return $this->fetch();
    }


    public function getContactInfo()
    {
        $id = request()->param('id');
        $data = RecruitmentModel::where('id', $id)->field('id, contact_phone, email')->find();
        return json_encode($data);
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
        //
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
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
        //
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
    }
}
