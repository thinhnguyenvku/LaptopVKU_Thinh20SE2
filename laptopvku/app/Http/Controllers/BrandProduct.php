<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB; 

use App\Http\Requests;
use Session;
use Illuminate\Support\Facades\Redirect;
session_start();

use App\Models\Brand;
use App\Models\Banner;

use App\Imports\ExcelImportBrand;
use App\Exports\ExcelExportBrand;
use Excel;

class BrandProduct extends Controller
{
    public function AuthLogin(){ //bảo mật, chưa login thì ko thể truy cập các đường dẫn của admin
        $admin_id = Session::get('admin_id');
        if($admin_id){
            Redirect::to('dashboard');

        }else{
            return Redirect::to('admin')->send();
        }
    }

    public function add_brand_product(){
        $this->AuthLogin(); //gọi hàm bảo mật
    	return view('admin.brand.add_brand_product');
    }
     public function all_brand_product(){

        $this->AuthLogin(); //gọi hàm bảo mật
     	$all_brand_product = DB::table('tbl_brand_product')->orderby('tbl_brand_product.brand_id','desc')->paginate(5);
     	$manager_brand_product = view('admin.brand.all_brand_product')->with('all_brand_product',$all_brand_product);

     	return view('admin_layout')->with('admin.brand.all_brand_product',$manager_brand_product);
    }

    public function save_brand_product(Request $request){

        $this->AuthLogin(); //gọi hàm bảo mật
    	$data = array();
    	$data['brand_name'] = $request->brand_product_name;
    	// tên cột trong CSDL = tên thẻ trong giao diện
    	$data['brand_desc'] = $request->brand_product_desc;
    	$data['brand_status'] = $request->brand_product_status;

        // $data['brand_slug'] = $request->brand_slug;


    	DB::table('tbl_brand_product')->insert($data);
    	Session::put('message','Thêm thành công!');
    	Return Redirect::to('add-brand-product');
    }


    public function unactive_brand_product($brand_product_id){

        $this->AuthLogin(); //gọi hàm bảo mật
    	DB::table('tbl_brand_product')->where('brand_id',$brand_product_id)->update(['brand_status'=>0]);

    	Session::put('message','Ẩn thương hiệu thành công!');
    	return Redirect::to('all-brand-product');
    }
    public function active_brand_product($brand_product_id){

        $this->AuthLogin(); //gọi hàm bảo mật
    	DB::table('tbl_brand_product')->where('brand_id',$brand_product_id)->update(['brand_status'=>1]);

    	Session::put('message','Hiển thị thương hiệu thành công!');
    	return Redirect::to('all-brand-product');
    }


    public function edit_brand_product($brand_product_id){

        $this->AuthLogin(); //gọi hàm bảo mật

    	$edit_brand_product = DB::table('tbl_brand_product')->where('brand_id',$brand_product_id)->get(); 
     	$manager_brand_product = view('admin.edit_brand_product')->with('edit_brand_product',$edit_brand_product);

     	return view('admin_layout')->with('admin.brand.edit_brand_product',$manager_brand_product);
    }


    public function update_brand_product(Request $request, $brand_product_id){

        $this->AuthLogin(); //gọi hàm bảo mật
    	$data = array();
    	$data['brand_name'] = $request->brand_product_name;
    	$data['brand_desc'] = $request->brand_product_desc;
        // $data['brand_slug'] = $request->brand_slug;

    	DB::table('tbl_brand_product')->where('brand_id',$brand_product_id)->update($data);
    	Session::put('message','Cập nhật thương hiệu thành công!');
    	return Redirect::to('all-brand-product');
    }

    public function delete_brand_product($brand_product_id){

        $this->AuthLogin(); //gọi hàm bảo mật
    	
    	DB::table('tbl_brand_product')->where('brand_id',$brand_product_id)->delete();
    	Session::put('message','Xóa thương hiệu thành công!');
    	return Redirect::to('all-brand-product');
    }




    // người dùng

    public function show_brand_home($brand_id){


        $banner = Banner::orderby('banner_id','DESC')->where('banner_status','1')->take(3)->get();
        $cate_product = DB::table('tbl_category_product')->where('category_status','1')->orderby('category_id','desc')->get(); 
        $brand_product = DB::table('tbl_brand_product')->where('brand_status','1')->orderby('brand_id','desc')->get(); 

        $brand_by_id = DB::table('tbl_product')->join('tbl_brand_product','tbl_product.brand_id','=','tbl_brand_product.brand_id')->where('tbl_brand_product.brand_id',$brand_id)->paginate(6);


        $brand_name = DB::table('tbl_brand_product')->where('tbl_brand_product.brand_id',$brand_id)->limit(1)->get();

        return view('pages.brand.show_brand')->with('category',$cate_product)->with('brand',$brand_product)->with('brand_by_id',$brand_by_id)->with('brand_name',$brand_name)->with('banner',$banner);
    }

    public function import_brand(Request $request){

        $path = $request->file('file_brand')->getRealPath();
        Excel::import(new ExcelImportBrand, $path);

        return back();

    }

    public function export_brand(){

        return Excel::download(new ExcelExportBrand , 'brand_vku.xlsx');
    }
}
