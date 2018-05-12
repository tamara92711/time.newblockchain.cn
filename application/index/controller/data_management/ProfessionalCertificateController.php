<?php

namespace app\index\controller\data_management;

use app\common\model\ProfessionalCertificateModel;
use think\Controller;
use think\Request;

class ProfessionalCertificateController extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $this->assign('side_nav', 'certification');
        $this->assign('header_nav', 'home');
        return $this->fetch();
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        $this->assign('header_nav', 'home');
        $this->assign('side_nav', 'certification');
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
        $link = new ProfessionalCertificateModel();
        $link->user_id = session('user_id');
        $link->certificate_no = $request->param('certificate_no');
        $link->certificate_name = $request->param('certificate_name');
        $link->professional_level = $request->param('professional_level');

        if (!empty($request->file('negative_id')))
        {
            $image = $request->file('negative_id');
            $info = $image->move('./uploads/');
            $link->negative_card_id = $info->getSaveName();
        }

        if (!empty($request->file('front-image')))
        {
            $image = $request->file('front-image');
            $info = $image->move('./uploads/');
            $link->positive_image = $info->getSaveName();
        }

        if (!empty($request->file('back-image')))
        {
            $image = $request->file('back-image');
            $info = $image->move('./uploads/');
            $link->back_image = $info->getSaveName();
        }
        $link->save();
        return redirect('/index/data_management.professional_certificate');
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {

        if($id == 0 )
        {
            $data = ProfessionalCertificateModel::where('user_id',session('user_id'))
                    ->where('is_deleted',0)
                    ->field('certificate_no,certificate_name,professional_level,approval_status')
                    ->select();
        }
        else
        {
            $data = ProfessionalCertificateModel::get($id);
        }

        return json_encode(["data" => $data]);
//        return "read";
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
