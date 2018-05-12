<?php

namespace app\admin\controller\product;

use think\Controller;
use think\Request;
use app\common\model\ProductTypeModel;

class CategoriesController extends Controller
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
        $productType = new ProductTypeModel;
        $productType->name = $request->post('name');
        $productType->description = $request->post('description');
        $productType->pid = $request->post('pid');
        $productType->save();
        return $productType->id;
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
            $data_array = array_values(ProductTypeModel::where('is_deleted', 0)->column(['id', 'pid', 'name', 'description']));
            return json_encode([ "data" => $data_array]);
        }
        return json_encode(ProductTypeModel::where('id', $id)->column(['id', 'pid', 'name', 'description']));
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
        $productType = ProductTypeModel::get($id);
        $productType->name = $request->param('name');
        $productType->description = $request->param('description');
        $productType->save();
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        $productType = ProductTypeModel::get($id);
        $productType->is_deleted = 1;
        $productType->save();
    }
}
