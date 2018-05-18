<?php

namespace app\index\controller\service_management;

use app\common\model\AddressModel;
use app\common\model\RealNameVerifyModel;
use app\common\model\DemandModel;
use app\common\model\DemandTypeModel;
use app\common\model\RegionModel;
use think\Controller;
use think\Request;
use think\captcha\Captcha;

class ReleaseRequirementController extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */


    public function index()
    {
        //tree data display
        $jsonData = $this->readDemandType();
        //region_1 display
        $regions_level_1 = RegionModel::where('level', 1)->column('name', 'id');


        //when modal clicked display address list of logined user
        $addressList = AddressModel::getAddressList(session('user_id'));

        $this->assign('addressList',$addressList);
        $this->assign('jsonData', $jsonData);
        $this->assign('region_level_1', $regions_level_1);
        $this->assign('header_nav', 'project_publish');
        $this->assign("nav_type", "1");
        $this->assign('side_nav', 'project_publish');
        return $this->fetch();
    }
    //all demand project of drafts state convert confirm and publish state 1=>2
    public function draftsEdit($demand_id,$state_id)
    {
        //tree data display
        $jsonData = $this->readDemandType();
        //region_1 display
        $regions_level_1 = RegionModel::where('level', 1)->column('name', 'id');

        $temp_query = DemandModel::getProjectInformation($demand_id);
        $draft_project = DemandModel::getProjectJoin($temp_query);

        //when modal clicked display address list of logined user
        $addressList = AddressModel::getAddressList(session('user_id'));

        $this->assign('addressList',$addressList);
        $this->assign('jsonData', $jsonData);
        $this->assign('region_level_1', $regions_level_1);
        $this->assign('draft_project', $draft_project);
        $this->assign('header_nav', 'project_publish');
        $this->assign('side_nav', 'project_publish');
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
    public function save(Request $request,$mode)
    {

        $link=new DemandModel();
        $link->user_id = session('user_id');

        $link->demand_type              = $request->param("demand_type");
        $link->title                    = $request->param("title");
        $link->detail                   = $request->param("detail");
        $link->apply_requirement        = $request->param("apply_requirement");
        $link->service_time_from        = $request->param("service_time_from");
        $link->service_time_to          = $request->param("service_time_to");
        $link->time_total               = $request->param("time_required");
        $link->valid_time               = $request->param("valid_time");
        $link->pay_amount               = $request->param("pay_amount");
        $link->region_1                 = $request->param("region_1");
        $link->region_2                 = $request->param("district");
        $link->address_detail           = $request->param("detail_address");
        $link->contact_name             = $request->param("contact");
        $link->contact_phone            = $request->param("contact_phone");
        $link->published_time           = $request->param("published_time");

        $count = RealNameVerifyModel::where('user_id',session('user_id'))->where('is_passed',1)->count();
        if($count == 0)
            echo "not_verify";
        else
        {
            //mode =0 save drafts insert state value 1
            if ($mode == 0 )
                $link->state = 1;
            //mode =1 confirm and publish insert state value 2
            else if ($mode == 1)
                $link->state = 2;
        }
        $link->save();
    }

    public function draftUpdate(Request $request,$mode)
    {
        $link=DemandModel::get($request->param('demand_id'));

        $link->demand_type          = $request->param("demand_type");
        $link->title                = $request->param("title");
        $link->detail               = $request->param("detail");
        $link->apply_requirement    = $request->param("apply_requirement");
        $link->service_time_from    = $request->param("service_time_from");
        $link->service_time_to      = $request->param("service_time_to");
        $link->time_total           = $request->param("time_required");
        $link->valid_time           = $request->param("valid_time");
        $link->pay_amount           = $request->param("pay_amount");
        $link->region_1             = $request->param("region_1");
        $link->region_2             = $request->param("district");
        $link->address_detail       = $request->param("detail_address");
        $link->contact_name         = $request->param("contact");
        $link->contact_phone        = $request->param("contact_phone");
        $link->published_time       = $request->param("published_time");

        if($mode == 1)
            $link->state = 2;
        else
            $link->state = 1;

        $link->save();

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
        return "update".$id;
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


    /*
     * At initial display demand types  as accordian tree
     * 2018.5.9
     */
    public function readDemandType()
    {

        $parent_list = DemandTypeModel::where('pid',0)->field('id,name,pid')->select();
        $data = array();
        $i = 0;
        while ($i < count($parent_list))
        {
            $items = array();
            $child_result = DemandTypeModel::where('pid',$parent_list[$i]->id)
                ->field('id,name,pid')
                ->select();

            $one = [
              'id' => $parent_list[$i]->id,
              'name' => $parent_list[$i]->name,
              'items' => $child_result
            ];

            $data[$i] = $one;
            $i++;
        }
        return json_encode($data);
    }
    /*
     * display all image verify initial setting
     * 2018-05-08
     */
    public function verify($code='')
    {
        $code =input( 'post.captcha' );
        $captcha = new Captcha();
        $captcha->length = 4;
        $captcha->imageW = 120;
        $captcha->imageH = 35;
        $captcha->fontSize = 16;
        $captcha->expire = 30;  //有效期
        $captcha->useNoise = true;  //不添加杂点

        if (!$captcha->check($code)) {
//            return false;
        } else {
//            return true;
        }
        return $captcha->entry($code);
    }

    public function check($value = '')
    {
        $captcha = new Captcha();
        if (!$captcha->check($value)) {
            return false;
        } else {
            return true;
        }

    }
}
