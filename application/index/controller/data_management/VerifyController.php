<?php

namespace app\index\controller\data_management;

use app\common\model\RealNameVerifyModel;
use app\common\model\UserModel;
use think\Controller;
use think\Request;

class VerifyController extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $this->assign('side_nav', 'real_name_verify');
        $this->assign('header_nav', 'project_publish');
        $this->assign("nav_type", 1);
        $user_id = session('user_id');
        $user = UserModel::get($user_id)->toArray();
        $user['card_front_image'] = $this->getImageUrl($user['card_front_image']) ?: "";
        $user['card_back_image'] = $this->getImageUrl($user['card_back_image']) ?: "";
        $user['card_handled_image'] = $this->getImageUrl($user['card_handled_image']) ?: "";
        $this->assign('user', $user);
        return $this->fetch();
    }

    private function getImageUrl($path)
    {
        $url = "";
        if (!empty($path)) {
            $path = explode('\\',$path);
            $url = '/uploads/' . $path[0] . '/' . $path[1];
        }
        return $url;
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
        $user = new RealNameVerifyModel();

        $user->user_id = session("user_id");

        $user->user_name = $request->param('name');
        $user->id_card_number = $request->param('iden_number');

        if (!empty($request->file('positive_id')))
        {
            $image = $request->file('positive_id');
            $info = $image->move('./uploads/');
            $user->card_front_image = $info->getSaveName();
        }
        if (!empty($request->file('negative_id')))
        {
            $image = $request->file('negative_id');
            $info = $image->move('./uploads/');
            $user->card_back_image = $info->getSaveName();
        }
        if (!empty($request->file('handheld_id')))
        {
            $image = $request->file('handheld_id');
            $info = $image->move('./uploads/');
            $user->card_handled_image = $info->getSaveName();
        }

        if (!empty($request->post('avatar_image')))
        {
            $data = $request->post('avatar_image');

            list($type, $data) = explode(';', $data);
            list(, $data)      = explode(',', $data);
            $data = base64_decode($data);
            if(!file_exists('./uploads/avarta'))
                mkdir('./uploads/avarta',0777,true);
            $avatar_file = '' . time() . '.png';
            
            file_put_contents('./uploads/avarta/' . $avatar_file, $data);
            $user->avarta_image = '/uploads/avarta/' . $avatar_file;
            // $image = $request->file('handheld_id');
            // $info = $image->move('./uploads/');
            // $user->avarta_image = $info->getSaveName();
        }

        $user->save();
        return redirect('/index/data_management.verify');
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
        $id = session("user_id");

        $user = UserModel::get($id);

        $user->real_name = $request->param('name');
        $user->id_card_number = $request->param('iden_number');
        $user->real_name_verified = false;

        if (!empty($request->file('positive_id')))
        {
            $image = $request->file('positive_id');
            $info = $image->move('./uploads/');
            $user->card_front_image = $info->getSaveName();
        }
        if (!empty($request->file('negative_id')))
        {
            $image = $request->file('negative_id');
            $info = $image->move('./uploads/');
            $user->card_back_image = $info->getSaveName();
        }
        if (!empty($request->file('handheld_id')))
        {
            $image = $request->file('handheld_id');
            $info = $image->move('./uploads/');
            $user->card_handled_image = $info->getSaveName();
        }

        if (!empty($request->post('avatar_image')))
        {
            $data = $request->post('avatar_image');

            list($type, $data) = explode(';', $data);
            list(, $data)      = explode(',', $data);
            $data = base64_decode($data);
            if(!file_exists('./uploads/avarta'))
                mkdir('./uploads/avarta',0777,true);
            $avatar_file = '' . time() . '.png';
            
            file_put_contents('./uploads/avarta/' . $avatar_file, $data);
            $user->avarta_image = '/uploads/avarta/' . $avatar_file;
        }

        $user->save();
        return redirect('/verify')->with('result', 'success');
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
