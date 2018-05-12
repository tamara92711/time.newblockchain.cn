<?php

namespace app\index\controller\project;

use app\common\model\ApplyModel;
use app\common\model\DemandModel;
use app\common\model\ProfessionalCertificateModel;
use app\common\model\RealNameVerifyModel;
use think\Controller;
use think\Request;

class ProjectProcessingController extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //
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

    public function project_published($id, $mode)
    {
        $temp = DemandModel::getProjectField($id);
        $data = DemandModel::getProjectJoin($temp);
        //check possible to bid
        $isbid = ApplyModel::where('user_id',session('user_id'))->where('demand_id',$id)->count();
        if($isbid == 1 )
        {
            $this->assign('mode',0);
        }
        else{
            $this->assign('mode',$mode);
        }
        $this->assign('data',$data);
        $this->assign('header_nav', 'project_apply');
        $this->assign('side_nav', 'project_published');
        return $this->fetch();
    }

    public function project_accepted($id,$mode)
    {
        $temp = DemandModel::getProjectField($id);
        $data = DemandModel::getProjectJoin($temp);

        $this->assign('data',$data);
        $this->assign('mode',$mode);
        $this->assign('header_nav', 'project_apply');
        $this->assign('side_nav', 'project_published');
        return $this->fetch();
    }

    public function project_completed($id,$mode)
    {
        $temp = DemandModel::getProjectField($id);
        $data = DemandModel::getProjectCompletedJoin($temp);
//        $rating_text = DemandModel::getEvaluationText($data[0]['publisher_review_rate']);
//        $this->assign('rating_text',$rating_text);
        $this->assign('data',$data);
        $this->assign('mode',$mode);
        $this->assign('header_nav', 'project_apply');
        $this->assign('side_nav', 'project_published');
        return $this->fetch();
    }

    public function project_cancled($id,$mode)
    {
        $temp = DemandModel::getProjectField($id);
        $data = DemandModel::getProjectJoin($temp);

        $this->assign('data',$data);
        $this->assign('mode',$mode);
        $this->assign('header_nav', 'project_apply');
        $this->assign('side_nav', 'project_published');
        return $this->fetch();
    }


    /*
    * for show accepted and publish  processing for myself
    */
    public function accepter_completed($id)
    {

        $temp = DemandModel::getProjectField($id);
        $data = DemandModel::getProjectCompletedJoin($temp);

        $this->assign('data',$data);
        $this->assign('header_nav', 'project_apply');
        $this->assign('side_nav', 'project_apply');
        return $this->fetch();
    }
    /*
     * upload image
     */
    public function publish_completed($id)
    {

        $temp = DemandModel::getProjectField($id);
        $data = DemandModel::getProjectCompletedJoin($temp);

        $this->assign('data',$data);
        $this->assign('header_nav', 'project_apply');
        $this->assign('side_nav', 'project_published');
        return $this->fetch();
    }

    public function project_publish_complete($id)
    {

        $temp = DemandModel::getProjectField($id);
        $data = DemandModel::getProjectCompletedJoin($temp);

        $this->assign('data',$data);
        $this->assign('header_nav', 'project_apply');
        $this->assign('side_nav', 'project_published');
        return $this->fetch();
    }

    /*
     * check real name verify
     */
    public function insertApplier()
    {

        $link = new ApplyModel();
        $count = RealNameVerifyModel::where('user_id',session('user_id'))->where('is_passed',1)->count();
        if ($count > 0)
        {
            $link->demand_id = request()->param('demand_id');
            $link->user_id = session('user_id');
            $link->save();
        }
        else{
            echo "not";
        }

    }

    /*
    * show details freelance information
    */
    public function show_freelancer_detail($demand_id,$user_id)
    {
        $this->assign('header_nav', 'project_publish');
        $this->assign('side_nav', 'project_published');

        //get porject published detail
        $temp = DemandModel::getProjectField($demand_id);
        $demand_data = DemandModel::getProjectJoin($temp);

        $user_data = ApplyModel::getUserReviewList($user_id);

        $certificate = ProfessionalCertificateModel::where('user_id',$user_id)
            ->column('*');

        $avarta = RealNameVerifyModel::where('user_id',$user_id)->column('avarta_image');

        $this->assign('data',$demand_data);
        $this->assign('user_data',$user_data);
        $this->assign('certi_data',$certificate);
        $this->assign('demand_id',$demand_id);
        $this->assign('user_id',$user_id);
        $this->assign('avarta',$avarta);
        return $this->fetch();
    }


}
