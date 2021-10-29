<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB; //TV dùng database

use App\Http\Requests;
use Session;
use Illuminate\Support\Facades\Redirect;
session_start();

use App\Models\Category;
use App\Models\Banner;

use App\Imports\ExcelImportCategory;
use App\Exports\ExcelExportCategory;
use Excel;


class CategoryProduct extends Controller
{

    //admin

    public function AuthLogin(){ //bảo mật, chưa login thì ko thể truy cập các đường dẫn của admin
        $admin_id = Session::get('admin_id');
        if($admin_id){
            Redirect::to('dashboard');

        }else{
            return Redirect::to('admin')->send();
        }
    }

    public function add_category_product(){

        $this->AuthLogin(); //gọi hàm bảo mật
    	return view('admin.category.add_category_product');
    }
     public function all_category_product(){

        $this->AuthLogin(); //gọi hàm bảo mật

     	$all_category_product = DB::table('tbl_category_product')->orderby('tbl_category_product.category_id','desc')->paginate(5);
     	$manager_category_product = view('admin.category.all_category_product')->with('all_category_product',$all_category_product);

     	return view('admin_layout')->with('admin.category.all_category_product',$manager_category_product);
    }

    public function save_category_product(Request $request){


        $this->AuthLogin();
        $data = array();
        $data['category_name'] = $request->category_product_name;
        $data['category_desc'] = $request->category_product_desc;
        $data['category_status'] = $request->category_product_status;
        
        DB::table('tbl_category_product')->insert($data);
        Session::put('message','Thêm danh mục thành công!');
        return Redirect::to('add-category-product');
    }


    public function unactive_category_product($category_product_id){

        $this->AuthLogin(); //gọi hàm bảo mật
    	DB::table('tbl_category_product')->where('category_id',$category_product_id)->update(['category_status'=>0]);

    	Session::put('message','Ẩn danh mục thành công!');
    	return Redirect::to('all-category-product');
    }
    public function active_category_product($category_product_id){

        $this->AuthLogin(); //gọi hàm bảo mật
    	DB::table('tbl_category_product')->where('category_id',$category_product_id)->update(['category_status'=>1]);

    	Session::put('message','Hiển thị danh mục thành công!');
    	return Redirect::to('all-category-product');
    }


    public function edit_category_product($category_product_id){

        $this->AuthLogin(); //gọi hàm bảo mật

    	$edit_category_product = DB::table('tbl_category_product')->where('category_id',$category_product_id)->get(); 
     	$manager_category_product = view('admin.category.edit_category_product')->with('edit_category_product',$edit_category_product);

     	return view('admin_layout')->with('admin.category.edit_category_product',$manager_category_product);
    }


    public function update_category_product(Request $request, $category_product_id){

        
        $this->AuthLogin();
        $data = array();
        $data['category_name'] = $request->category_product_name;
        $data['category_desc'] = $request->category_product_desc;
        

        DB::table('tbl_category_product')->where('category_id',$category_product_id)->update($data);
        Session::put('message','Cập nhật danh mục thành công!');
        return Redirect::to('all-category-product');

    }

    public function delete_category_product($category_product_id){

        $this->AuthLogin(); //gọi hàm bảo mật
    	
    	DB::table('tbl_category_product')->where('category_id',$category_product_id)->delete();
    	Session::put('message','Xóa danh mục thành công!');
    	return Redirect::to('all-category-product');
    }




    // người dùng

    public function show_category_home($category_id){

        
        $banner = Banner::orderby('banner_id','DESC')->where('banner_status','1')->take(3)->get();
        $cate_product = DB::table('tbl_category_product')->where('category_status','1')->orderby('category_id','desc')->get(); 
        $brand_product = DB::table('tbl_brand_product')->where('brand_status','1')->orderby('brand_id','desc')->get(); 

        $category_by_id = DB::table('tbl_product')->join('tbl_category_product','tbl_product.category_id','=','tbl_category_product.category_id')->where('tbl_category_product.category_id',$category_id)->paginate(6);
        
        
        $category_name = DB::table('tbl_category_product')->where('tbl_category_product.category_id',$category_id)->limit(1)->get();

        return view('pages.category.show_category')->with('category',$cate_product)->with('brand',$brand_product)->with('category_by_id',$category_by_id)->with('category_name',$category_name)->with('banner',$banner);
    }


    public function import_category(Request $request){

        $path = $request->file('file_category')->getRealPath();
        Excel::import(new ExcelImportCategory, $path);

        return back();

    }

    public function export_category(){

        return Excel::download(new ExcelExportCategory , 'category_vku.xlsx');
    }

}
