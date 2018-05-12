<?php

namespace app\index\controller\index;

use think\Controller;
use think\Request;
use think\Db;
use app\common\model\ComplaintModel;

class ComplaintsController extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $complaint_type = Db::table('qkl_complaint_types')->field('id,type')->select();
        $this->assign('complaint_type',$complaint_type);
        $this->assign('header_nav', 'home');
        $this->assign('side_nav', 'complaints');
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
        $link=new ComplaintModel();
        $link->user_id        = 0;
        $link->type_complaint = $request->param("type_complaint");
        $link->complaint_title= $request->param("complaint_title");
        $link->complaints     = $request->param("complaints");
        $link->contact        = $request->param("contact");
        $link->contact_number = $request->param("contact_number");
        $link->veri_code      = $request->param("captcha");
        if ( $link->save()){
            echo "success";
        }


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
