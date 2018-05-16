<?php

namespace app\index\controller\data_management;


use app\common\model\UserModel;
use think\Controller;
use think\Request;

class PersonalInformationController extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */

    public function index()
    {
        $this->assign('header_nav', 'home');
        $this->assign('side_nav', 'personal_info');
        $user_data = UserModel::getAlluserInformation();
        $this->assign('user_data',$user_data);
        $this->assign('user_id',session('user_id'));
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
//        return "update".$id;
        $oldPass = $request->param("beforepass");
        $checkPass = UserModel::where('id',session('user_id'))->where('password',$oldPass)->count();
        if ($checkPass == 0)
        {
            return "failure";
        }
        else
        {
            $link=UserModel::get(session('user_id'));
            $link->real_name = $request->param("realname");
            $link->sex = $request->param("gender");
            $link->password = md5($request->param("newpass"));
            $link->job_type = $request->param("occupation");
            $link->education_type = $request->param("education");
            $link->mobile = $request->param("cellphone");
            $link->fixed = $request->param("fixed");
            $link->email = $request->param("email");
            $link->save();
            return "ok";
        }

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
