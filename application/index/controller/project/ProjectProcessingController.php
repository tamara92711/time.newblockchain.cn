<?php

namespace app\index\controller\project;


use app\common\model\ApplyModel;
use app\common\model\DemandModel;
use app\common\model\ProfessionalCertificateModel;
use app\common\model\UserModel;
use think\Controller;
use think\Exception;
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

    public function project_published($id, $mode,$display_id)
    {
        $temp = DemandModel::getProjectInformation($id);
        $data = DemandModel::getProjectJoin($temp);
        //check possible to bid
        $isbid = ApplyModel::where('user_id',session('user_id'))->where('demand_id',$id)->count();
        if($isbid == 1 )
        {
            $this->assign(['mode'=>0,'display_id'=>$display_id]);
        }
        else
        {
            $this->assign(['mode'=>$mode,'display_id'=>$display_id]);
        }

        if (DemandModel::where(['id'=>$id,'user_id'=>session('user_id'),'applied_user_id'=>0])->where('is_reviewed','<>',4)->count() > 0)
            $this->assign('cancelMode',1);//project cancel button see
        else
            $this->assign('cancelMode',0);//don't see project cancel button

        $this->assign(['data'=>$data,'header_nav'=>'project_apply','nav_type'=>1,'side_nav'=>'project_published']);

        return $this->fetch();
    }

    public function project_accepted($id,$mode)
    {
        $temp = DemandModel::getProjectField($id);
        $data = DemandModel::getProjectCompletedJoin($temp);

        $this->assign('data',$data);
        $this->assign('mode',$mode);
        $this->assign('header_nav', 'project_apply');
        $this->assign("nav_type", 1);
        $this->assign('side_nav', 'project_published');
        return $this->fetch();
    }

    public function project_completed($id,$mode)
    {
        $temp = DemandModel::getProjectField($id);
        $data = DemandModel::getProjectCompletedJoin($temp);

        $freelancer_id = $data[0]['applied_user_id'];
        $publisher_id  = $data[0]['publisher_id'];

        $freelancer_review_data = DemandModel::getReviewInformation($id,$publisher_id);
        $publisher_review_data = DemandModel::getReviewInformation($id,$freelancer_id);

        $this->assign('freelancer_review_data',$freelancer_review_data);
        $this->assign('publisher_review_data',$publisher_review_data);
        $this->assign('data',$data);
        $this->assign('mode',$mode);
        $this->assign('header_nav', 'project_apply');
        $this->assign("nav_type", 1);
        $this->assign('side_nav', 'project_published');
        return $this->fetch();
    }

    public function project_cancled($id,$mode)
    {
        $temp = DemandModel::getProjectInformation($id);
        $data = DemandModel::getProjectJoin($temp);

        $this->assign('data',$data);
        $this->assign('mode',$mode);
        $this->assign('header_nav', 'project_apply');
        $this->assign("nav_type", 1);
        $this->assign('side_nav', 'project_published');
        return $this->fetch();
    }


    /*
    * for show accepted and publish  processing for myself
    */
    public function accepter_completed($id,$mode)
    {
        try
        {
            $temp = DemandModel::getProjectField($id);
            $data = DemandModel::getProjectCompletedJoin($temp);

            $user_id = $data[0]['publisher_id'];
            $publisher_id = $data[0]['publisher_id'];
            $freelance_id = $data[0]['applied_user_id'];
            $applied_avarta = UserModel::getAvarta($data[0]['applied_user_id']);
            $publisher_avarta = UserModel::getAvarta($data[0]['publisher_id']);

            $this->assign('applied_avarta',$applied_avarta);
            $this->assign('publisher_avarta',$publisher_avarta);
            $this->assign('data',$data);
            $this->assign('header_nav', 'project_apply');
            //display empty
            if ($mode == 0)
                $this->assign('mode',$mode);
            //display freelance's review data
            else if ($mode == 1)
            {
                $review_data = DemandModel::getReviewInformation($id,$freelance_id);
                $this->assign('review_data',$review_data);
                $this->assign('mode',$mode);

            }
            else if ($mode == 2)
            {
                $review_data = DemandModel::getReviewInformation($id,$publisher_id);
                $this->assign('review_data',$review_data);
                $this->assign('mode',$mode);

            }
            $this->assign($data);

            $this->assign("nav_type", 1);
            $this->assign('side_nav', 'project_applied');

            return $this->fetch();
        }
        catch (Exception $exception)
        {
            \think\facade\Log::write('accepter_completed function error',$exception->getMessage());
        }

    }
    /*
     * upload image
     */
    public function publish_completed($id,$mode)
    {

        try
        {
            $temp = DemandModel::getProjectField($id);
            $data = DemandModel::getProjectCompletedJoin($temp);

            $publisher_id  = $data[0]['publisher_id'];
            $freelancer_id  = $data[0]['applied_user_id'];

            $applied_avarta = UserModel::getAvarta($data[0]['applied_user_id']);
            $publisher_avarta = UserModel::getAvarta($data[0]['publisher_id']);

            if(!empty($applied_avarta))
                $applied_avarta = $applied_avarta;
            else
                $applied_avarta ='';

            if(!empty($publisher_avarta))
                $publisher_avarta = $publisher_avarta;
            else
                $publisher_avarta ='';

            $this->assign(['applied_avarta'=>$applied_avarta,'publisher_avarta'=>$publisher_avarta,'data'=>$data,
                         'header_nav'=>'project_apply',"nav_type", 1,"nav_type"=>1,'side_nav'=>'project_published']);

            if ($mode == 0)
                $this->assign('mode',$mode);
            //freelance give review to publisher ,,,so goto complete step  , display freelance's evaulating
            else if ($mode == 1 )
            {
                $review_data = DemandModel::getReviewInformation($id,$freelancer_id);
                $this->assign('review_data',$review_data);
                $this->assign('mode',$mode);

            }

            else if ( $mode == 2)
            {
                $review_data = DemandModel::getReviewInformation($id,$publisher_id);
                $this->assign('review_data',$review_data);
                $this->assign('mode',$mode);

            }
            return $this->fetch();
        }
        catch (Exception $exception)
        {
            \think\facade\Log::write('publish_completed function error',$exception->getMessage());
        }

    }

    public function project_publish_complete($id)
    {

        try{
            $temp = DemandModel::getProjectField($id);
            $data = DemandModel::getProjectCompletedJoin($temp);

            $freelancer_id = $data[0]['applied_user_id'];
            $publisher_id  = $data[0]['publisher_id'];

            $freelancer_review_data = DemandModel::getReviewInformation($id,$publisher_id);
            $publisher_review_data = DemandModel::getReviewInformation($id,$freelancer_id);

            $this->assign('freelancer_review_data',$freelancer_review_data);
            $this->assign('publisher_review_data',$publisher_review_data);
            $this->assign('data',$data);

            $this->assign('header_nav', 'project_apply');
            $this->assign("nav_type", 1);
            $this->assign('side_nav', 'project_published');
            return $this->fetch();
        } catch (Exception $exception)
        {
            \think\facade\Log::write('project_publish_complete function error',$exception->getMessage());
        }
    }

    /*
     * check real name verify
     */
    public function insertApplier()
    {

        $link = new ApplyModel();
        $count = UserModel::where('id',session('user_id'))->where('real_name_verified',1)->count();
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
        $this->assign(['header_nav'=>'project_publish',"nav_type"=>1,'side_nav'=>'project_published']);

        //get porject published detail
        $temp = DemandModel::getProjectInformation($demand_id);
        $demand_data = DemandModel::getProjectJoin($temp);

        $user_data = ApplyModel::getUserReviewList($user_id);

        $certificate = ProfessionalCertificateModel::where('user_id',$user_id)
            ->column('*');

        $avarta = UserModel::where('id',$user_id)->column('avarta_image');

        $this->assign(['data'=>$demand_data,'user_data'=>$user_data,'certi_data'=>$certificate,'demand_id'=>$demand_id,'user_id'=>$user_id,'avarta'=>$avarta]);

        return $this->fetch();
    }

    //cancel project function
    public function cancelProject(Request $request)
    {
        $demand_id = $request->param('demand_id');
        DemandModel::where(['id'=>$demand_id])->update(['is_reviewed'=>4]);
        return "success";

    }


}
