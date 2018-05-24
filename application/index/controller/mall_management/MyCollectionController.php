<?php

namespace app\index\controller\mall_management;

use think\Controller;
use think\Request;

use app\common\model\CartModel;
use app\common\model\AddressModel;
use app\common\model\OrdersModel;
use app\common\model\OrdersRootModel;
use app\common\model\ProductModel;
use app\common\model\RegionModel;
use app\common\model\TransactionUserModel;
use app\common\model\UserModel;

use think\facade\Session;

class MyCollectionController extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //
        $this->assign('header_nav', 'mall');
        $this->assign("nav_type", 0);
        $this->assign('side_nav', '');
        $this->assign('products',CartModel::alias('cart')->where(['state'=>1,'user_id'=>session('user_id')])->join('qkl_product','qkl_product.id = cart.product_id')->select());
        return $this->fetch();
    }

    public function order_view()
    {
        $this->assign('header_nav', 'mall');
        $this->assign("nav_type", 1);
        $this->assign('side_nav', '');
        $this->assign('products',CartModel::alias('cart')->where(['state'=>3,'user_id'=>session('user_id')])->join('qkl_product','qkl_product.id = cart.product_id')->select());
        return $this->fetch();
    }

    public function purchase_view(Request $request)
    {
        $data = $request->post('ids');
        $ids = explode(',',$data);
        $regions_level_1 = RegionModel::where('level', 1)->column('name', 'id');

        $this->assign('region_level_1', $regions_level_1);

        $this->assign('header_nav', 'mall');
        $this->assign("nav_type", 1);
        $this->assign('side_nav', '');
        $address_model = new AddressModel();
        $address_data = $address_model->getAddressList(session('user_id'));
        $this->assign('product_ids',$data);
        $this->assign('products',CartModel::alias('cart')->where('state','1')->whereIn('product_id',$ids)->where('user_id', session('user_id'))->join('qkl_product','qkl_product.id = cart.product_id')->select());
        $this->assign('addresses',$address_data);
        return $this->fetch();
    }

    private function randomString($length = 6) {
        $str = "";
        $characters = array_merge(range('0','9'));
        $max = count($characters) - 1;
        for ($i = 0; $i < $length; $i++) {
            $rand = mt_rand(0, $max);
            $str .= $characters[$rand];
        }
        return $str;
    }

    public function order(Request $request) 
    {
        $user_id = session('user_id');
        $image_added = false;
        $address_id = $request->post('address');
        $data = $request->post('ids');
        $ids = explode(',',$data);
        $carts = CartModel::alias('cart')->field('cart.*, qkl_product.type, qkl_product.name, qkl_product.price,qkl_product.description')->where('state','1')->whereIn('product_id',$ids)->where('user_id', $user_id)->join('qkl_product','qkl_product.id = cart.product_id')->select();

        $order = new OrdersModel;
        $order->user_id = $user_id;
        $order->contact_id = $address_id;
        $order->no = $this->randomString(13);
        $order->address_id = $address_id;
        $order->state = 2;
        $order->id = OrdersModel::max('id') + 1;
        // $order->save();
        $total_amount = 0;
        
        foreach ($carts as $key => $cart) {
            if (!$image_added) {
                $product = ProductModel::get($cart->product_id);
                $order->thumbnail = $product->thumbnail;
                $order->description = $product->description;
                $image_added = true;
            }
            $cart->order_id = $order->id;
            $cart->state = 4;

            $total_amount += ($cart->amount * $cart->price);
            $cart->save();
        }
        $order->total_amount = $total_amount;
        $order->save();

        $user = UserModel::get($user_id);
        $user->total_amount = $user->total_amount - $total_amount;
        $user->save();

        $trans1 = new TransactionUserModel;
        $trans1->user1_id = $user_id;
        $trans1->user2_id = 0;
        $trans1->amount = $total_amount;
        $trans1->action = 0;
        $trans1->transaction_type = 3;
        $trans1->state = 1;
        $trans1->rate = 1;
        $trans1->balance = UserModel::get($user_id)->total_amount;
        $trans1->currency_type = 1;
        // $trans2->doUserTransaction($user1_id, 1, $amount, 1, 1, 1, 1);

        $trans2 = new TransactionUserModel;
        $trans2->user2_id = $user_id;
        $trans2->user1_id = 0;
        $trans2->amount = $amount;
        $trans2->action = 1;
        $trans2->transaction_type = 1;
        $trans2->state = 1;
        $trans2->rate = 1;
        $trans2->balance = UserModel::get(1)->total_amount;
        $trans2->currency_type = 1;

        return redirect('/order_published');
    }

    public function published_orders_view()
    {
        $cnt_pending_pay = 0;
        $cnt_pending_ship = 0;
        $cnt_pending_comment = 0;

        $cnt_pending_pay = OrdersModel::where(['user_id' => session('user_id'), 'state' => 1])->select()->count();
        $cnt_pending_ship = OrdersModel::where(['user_id' => session('user_id'), 'state' => 2])->select()->count();
        $cnt_pending_comment = OrdersModel::where(['user_id' => session('user_id'), 'state' => 4])->select()->count();

        $this->assign('header_nav', 'mall');
        $this->assign("nav_type", 0);
        $this->assign('side_nav', 'orders');

        $this->assign('cnt_pending_pay', $cnt_pending_pay);
        $this->assign('cnt_pending_ship', $cnt_pending_ship);
        $this->assign('cnt_pending_comment', $cnt_pending_comment);

        return $this->fetch();
    }

    public function get_orders()
    {
        $time_from = request()->param('time_from');
        $time_to = request()->param('time_to');
        $time_to = date("Y-m-d", strtotime("+1 day", strtotime($time_to)));
        $status = request()->param('status');
        $user_id = session('user_id');
        
        // $data = OrdersModel::where('create_time', '>=', $time_from)->where('create_time', '<=', $time_to)->where('status', $status)->select();
        $query = OrdersModel::alias('o')
                ->field('o.id, o.total_amount, o.create_time, o.no, o.thumbnail, o.description, a.name, a.phone, concat(r1.name , r2.name , a.detail) address, o.state as sid, os.state')
                ->join('qkl_address a', 'a.id = o.address_id')
                ->join('qkl_region r1', 'r1.id = a.region_id_1')
                ->join('qkl_region r2', 'r2.id = a.region_id_2')
                ->join('qkl_order_state os', 'os.id = o.state')
                ->where('o.create_time', '>=', $time_from)
                ->where('o.create_time', '<=', $time_to)
                ->where('o.user_id', $user_id)
                ->order('o.create_time desc');
        if ($status != 0) $query = $query->where('o.state', $status);
        // $query = $query->fetchSql()->select();
        $data = $query->select();
        return json_encode(['data' => $data]);
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

    public function add_to_cart(Request $request)
    {
        $product_id = $request->post('id');
        if (Session::has('user_id'))
        {
            if (CartModel::where(['state'=>1,'product_id'=>$product_id, 'user_id' => session('user_id')])->select()->count() == 0)
            {
                $cart_product = new CartModel;
                $cart_product->user_id = session('user_id');
                $cart_product->product_id = $product_id;
                $cart_product->amount = 1;
                $cart_product->state = 1;
                $cart_product->save();
                echo ('added');
            }
            else
            {
                echo ('already exist');
            }
        }
        else
        {
            echo ('need_auth');
        }
    }

    public function update_amount(Request $request)
    {
        $product_id = $request->post('id');
        $amount = $request->post('value');
        if (CartModel::where(['state'=>1,'product_id'=>$product_id])->select()->count() > 0)
        {
            $cart_product = CartModel::where(['state'=>1,'product_id'=>$product_id])->find();
            $cart_product->amount = $amount;
            $cart_product->save();
            echo ("success");
        }
        else {
            echo ("doesn't exist");
        }
    }
    
    public function apply_payment(Request $request)
    {
        $data = $request->post('data');
        $ids = explode(',',$data);
        foreach ($ids as $key => $id) {
            CartModel::where(['state'=>'1','product_id'=>$id,'user_id' => session('user_id')])->setField('state',3);
        }
    }

    public function delete_from_cart(Request $request)
    {
        $product_id = $request->post('id');
        if (CartModel::where(['state'=>1,'product_id'=>$product_id])->select()->count() > 0)
        {
            $cart_product = CartModel::where(['state'=>1,'product_id'=>$product_id])->find();
            $cart_product->state = 2;
            $cart_product->save();
            echo ('deleted');
        }
        else {
            echo ("doesn't exist");
        }
    }

    public function get_pending_cart_count()
    {
        echo CartModel::where(['state'=>1,'user_id'=>session('user_id')])->select()->count();
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
