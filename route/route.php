<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------


use application\http\middleware\AuthMiddleware;

Route::get('think', function () {
    return 'hello,ThinkPHP5!';
});

Route::get('hello/:name', 'index/hello');

// Route::get('links/getLinksList', '/admin/links/getLinksList');

Route::resource('admin/links','@admin/links');

Route::resource('admin/volunteers','@admin/volunteers');

//Route::get('index/data_management.address_manage/getregion2ByRegion1/:region1', '@index/data_management.address_manage/getregion2byregion1/:region1');

Route::resource('admin/love_enterprise','admin/love_enterprise');

Route::resource('admin/love_enterprise','admin/charitable_organizations');

Route::resource('admin/advertise/trend_types','@admin/advertise/trend_types');

Route::resource('admin/article/types','@admin/article/types');

Route::resource('admin/article/index','@admin/article/index');

Route::resource('admin/article/local_institutions','@admin/article/local_institutions');

Route::resource('admin/article/recruitment_information','@admin/article/recruitment_information');

Route::resource('admin/complaints/categories','@admin/complaints/categories');

Route::resource('admin/complaints/index','@admin/complaints/index');

Route::resource('admin/project/types','@admin/project/types');

Route::resource('admin/project/index','@admin/project/index');

Route::resource('admin/member/index','@admin/member/index');

Route::resource('admin/member/certificate','@admin/member/certificate');

Route::resource('admin/member/real_name_verify','@admin/member/real_name_verify');

Route::resource('admin/product/categories','@admin/product/categories');

Route::resource('admin/product/index','@admin/product/index');

Route::resource('admin/product/orders','@admin/product/orders');

Route::resource('admin/news/index','@admin/news/index');

Route::resource('admin/news/types','@admin/news/types');

Route::resource('admin/member/index','@admin/member/index');

Route::resource('admin/member/certificate','@admin/member/certificate');

Route::resource('admin/member/real_name_verify','@admin/member/real_name_verify');

///////////////////////////////////////////////////////////////////////////
// Route::group('front');
Route::get('login_form','@index/user/login_form');
Route::post('login','@index/user/submit_login');

Route::get('register_form','@index/user/register_form');//->middleware('AuthMiddleware');
Route::post('register','@index/user/submit_register');

Route::get('sign_out','@index/user/sign_out');
Route::post('create_phone_verify_code','@index/user/create_phone_verify_code');

Route::post('add_to_cart','@index/mall_management/my_collection/add_to_cart');
Route::post('update_amount','@index/mall_management/my_collection/update_amount');
Route::post('delete_from_cart','@index/mall_management/my_collection/delete_from_cart');
Route::post('apply_payment','@index/mall_management/my_collection/apply_payment');//->middleware('AuthMiddleware');

Route::get('open_cart','/index/mall_management.my_collection/index')->middleware('AuthMiddleware');
Route::get('order_published','/index/mall_management.my_collection/published_orders_view')->middleware('AuthMiddleware');
Route::post('purchase_view','@index/mall_management.my_collection/purchase_view');//->middleware('AuthMiddleware');

Route::get('ajax_alarm_cart','@index/mall_management.my_collection/get_pending_cart_count');


Route::get('get_balance','@index/user/get_balance');
Route::post('order_now','@index/mall_management.my_collection/order');
Route::post('charge_coin','@common/pay/charge');

Route::get('transactionhistory','/index/time_money/transactionhistory')->middleware('AuthMiddleware');
Route::get('charge','/index/time_money/buy')->middleware('AuthMiddleware');
Route::get('transfer','/index/time_money/transfer')->middleware('AuthMiddleware');

    
Route::resource('index/data_management/address_manage','@index/data_management/address_manage');

Route::get('address_book', '/index/data_management.address_manage');//;//->middleware('AuthMiddleware');

Route::resource('index/data_management/professional_certificate','@index/data_management/professional_certificate');

Route::get('certificates', '/index/data_management.professional_certificate');//;//->middleware('AuthMiddleware');

Route::resource('index/data_management/personal_infomation','@index/data_management/personal_infomation');

Route::get('personal_information', '/index/data_management.personal_information');//->middleware('AuthMiddleware');

Route::post('phoneChangeVerifyCode','@index/data_management.personal_information/phoneChangeVerifyCode');

Route::post('personal_update','@index/data_management.personal_information/personalUpdate');

Route::resource('index/data_management/verify','@index/data_management/verify');

Route::get('verify', '/index/data_management.verify');//->middleware('AuthMiddleware');

Route::resource('index/index/love_enterprise','@index/index/love_enterprise');

Route::resource('index/index/charitable_organization','@index/index/charitable_organization');

Route::resource('index/index/complaints','@index/index/complaints');

Route::get('complaints', '/index/index.complaints');//->middleware('AuthMiddleware');

Route::resource('index/index/volunteer_grace','@index/index/volunteer_grace');

Route::resource('index/index/news_center','@index/index/news_center');

Route::get('/membercenter','index/index/membercenter')->middleware('AuthMiddleware');

Route::resource('index/service_management/release_requirement','@index/service_management/release_requirement');

Route::get('/publish_demand', '/index/service_management.release_requirement')->middleware('AuthMiddleware');

Route::resource('index/service_management/undertake_service','@index/service_management/undertake_service');

Route::get('/apply_demand', '/index/service_management.undertake_service')->middleware('AuthMiddleware');

Route::resource('index/service_management/show_published','@index/service_management/show_published');//->middleware('AuthMiddleware');

Route::get('/project_published', '/index/service_management.show_published');//->middleware('AuthMiddleware');

Route::resource('index/service_management/show_undertaken','@index/service_management/show_undertaken');//->middleware('AuthMiddleware');

Route::get('/project_accepted', '/index/service_management.show_undertaken')->middleware('AuthMiddleware');

Route::resource('index/project/project_published','@index/project/project_published');

Route::resource('index/project/project_accepteded','@index/project/project_accepteded');

Route::resource('index/project/project_cancled','@index/project/project_cancled');

Route::resource('index/project/project_completed','@index/project/project_completed');

Route::resource('index/project/project_expired','@index/project/project_expired');

Route::resource('index/mall/index','@index/mall/index');

