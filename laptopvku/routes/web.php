<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//Phần người dùng


Route::get('/','HomeController@index' );


Route::get('/trang-chu','HomeController@index');



Route::get('/danh-muc-san-pham/{category_id}','CategoryProduct@show_category_home');

Route::get('/thuong-hieu-san-pham/{brand_id}','BrandProduct@show_brand_home');

Route::get('/chi-tiet-san-pham/{product_id}','ProductController@details_product');

Route::post('/search','HomeController@search');

//Lỗi

Route::get('/404','HomeController@error_page');







//Phần quản trị

Route::get('/admin','AdminController@index');

Route::get('/dashboard','AdminController@show_dashboard');

Route::post('/admin-dashboard','AdminController@dashboard');

Route::get('/logout','AdminController@logout');


//Danh muc sản phẩm (Category Product)
Route::get('/add-category-product','CategoryProduct@add_category_product'); //thêm
Route::get('/all-category-product','CategoryProduct@all_category_product'); //liệt kê full sp

Route::post('/save-category-product','CategoryProduct@save_category_product'); //lưu dm sp
Route::get('/edit-category-product/{category_product_id}','CategoryProduct@edit_category_product'); //sửa dm sp
Route::get('/delete-category-product/{category_product_id}','CategoryProduct@delete_category_product'); //xóa dm sp
Route::post('/update-category-product/{category_product_id}','CategoryProduct@update_category_product'); //cập nhật dm sp


Route::get('/unactive-category-product/{category_product_id}','CategoryProduct@unactive_category_product');//Trạng thái ẩn hiện dm sp
Route::get('/active-category-product/{category_product_id}','CategoryProduct@active_category_product'); //Trạng thái ẩn hiện dm sp



//Thương hiệu sản phẩm (Brand Product)
Route::get('/add-brand-product','BrandProduct@add_brand_product'); //thêm
Route::get('/all-brand-product','BrandProduct@all_brand_product'); //liệt kê full th

Route::post('/save-brand-product','BrandProduct@save_brand_product'); //lưu dm th
Route::get('/edit-brand-product/{brand_product_id}','BrandProduct@edit_brand_product'); //sửa th
Route::get('/delete-brand-product/{brand_product_id}','BrandProduct@delete_brand_product'); //xóa th
Route::post('/update-brand-product/{brand_product_id}','BrandProduct@update_brand_product'); //cập nhật 


Route::get('/unactive-brand-product/{brand_product_id}','BrandProduct@unactive_brand_product');//Trạng thái ẩn hiện 
Route::get('/active-brand-product/{brand_product_id}','BrandProduct@active_brand_product'); //Trạng thái ẩn hiện 




//Sản phẩm (Product)
Route::get('/add-product','ProductController@add_product'); //thêm
Route::get('/all-product','ProductController@all_product'); //liệt kê full th

Route::post('/save-product','ProductController@save_product'); //lưu dm th
Route::get('/edit-product/{product_id}','ProductController@edit_product'); //sửa th
Route::get('/delete-product/{product_id}','ProductController@delete_product'); //xóa
Route::post('/update-product/{product_id}','ProductController@update_product'); //cập nhật 


Route::get('/unactive-product/{product_id}','ProductController@unactive_product');//Trạng thái ẩn hiện 
Route::get('/active-product/{product_id}','ProductController@active_product'); //Trạng thái ẩn hiện 


//Banner

Route::get('/all-banner','BannerController@all_banner');

Route::get('/add-banner','BannerController@add_banner');

Route::post('/save-banner','BannerController@save_banner');

Route::get('/unactive-banner/{banner_id}','BannerController@unactive_banner');

Route::get('/active-banner/{banner_id}','BannerController@active_banner'); 

Route::get('/delete-banner/{banner_id}','BannerController@delete_banner');






// Giỏ hàng ajax

Route::post('/add-cart-ajax','CartController@add_cart_ajax'); //thêm vào giỏ hàng ajax

Route::get('/show-cart','CartController@show_cart'); //giỏ hàng ajax

Route::post('/update-cart','CartController@update_cart'); // sửa số lượng

Route::get('/del-product/{session_id}','CartController@del_product'); // xóa

Route::get('/del-all-product','CartController@del_all_product'); // xóa all

Route::post('/check-coupon','CartController@check_coupon'); //Mã giảm giá

Route::get('/unset-coupon','CouponController@unset_coupon'); //Xóa mã giảm giá (ng dùng)

//Admin //Giỏ hàng ajax //mã giảm giá (Coupon)
Route::get('/add-coupon','CouponController@add_coupon'); //Admin // Quản lý Mã giảm giá - thêm

Route::post('/save-coupon','CouponController@save_coupon'); //Admin // Quản lý Mã giảm giá - lưu

Route::get('/all-coupon','CouponController@all_coupon'); //Admin // liệt kê full

Route::get('/delete-coupon/{coupon_id}','CouponController@delete_coupon'); //Admin // xóa










//Thanh toán (Checkout)

Route::get('/login-checkout','CheckoutController@login_checkout'); //trường hợp thanh toán khi chưa đăng nhập

Route::post('/add-customer','CheckoutController@add_customer');

Route::get('/checkout','CheckoutController@checkout');


Route::post('/save-checkout-customer','CheckoutController@save_checkout_customer'); //lưu thông tin thanh toán

Route::get('/logout-checkout','CheckoutController@logout_checkout');

Route::post('/login-customer','CheckoutController@login_customer');

Route::get('/payment','CheckoutController@payment');

Route::post('/order-place','CheckoutController@order_place');

//Thanh toán (dùng ajax)

Route::post('/select-delivery-user','CheckoutController@select_delivery_user'); // chọn địa phương 

Route::post('/calculate-fee','CheckoutController@calculate_fee'); //tính phí vận chuyển

Route::get('/del-fee','CheckoutController@del_fee'); // xóa session phí vận chuyển

Route::post('/confirm-order','CheckoutController@confirm_order'); // xác nhận đơn hàng









// Quản lý đơn hàng

Route::get('/manage-order','OrderController@manage_order'); // liệt kê

Route::get('/view-order/{order_code}','OrderController@view_order'); // xem

Route::get('/print-order/{order_code}','OrderController@print_order'); // in

Route::post('/update-order-qty','OrderController@update_order_qty'); // tình trạng

Route::post('/update-qty','OrderController@update_qty'); //SL






// Gửi mail google

Route::get('/send-mail','HomeController@send_mail');







//Admin // Phí vận chuyển

Route::get('delivery','DeliveryController@delivery');

Route::post('/select-delivery','DeliveryController@select_delivery'); // chọn địa điểm tính phí

Route::post('/insert-delivery','DeliveryController@insert_delivery'); // thêm phí

Route::post('select-feeship','DeliveryController@select_feeship');

Route::post('update-feeship','DeliveryController@update_feeship');





//Xuất(export), nhập(import) từ file excel

//Danh mục
Route::post('/export-category','CategoryProduct@export_category');
Route::post('/import-category','CategoryProduct@import_category');

//Thương hiệu
Route::post('/export-brand','BrandProduct@export_brand');
Route::post('/import-brand','BrandProduct@import_brand');

//Sản phẩm
Route::post('/export-product','ProductController@export_product');
Route::post('/import-product','ProductController@import_product');






//Phân quyền (Authentication Roles)