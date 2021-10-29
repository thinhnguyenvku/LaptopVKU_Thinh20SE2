<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB; //TV dùng database

use App\Http\Requests;
use Session;
use Illuminate\Support\Facades\Redirect;
session_start();

use App\Models\Product;
use App\Models\Banner;

use App\Imports\ExcelImportProduct;
use App\Exports\ExcelExportProduct;
use Excel;

class ProductController extends Controller
{
    public function AuthLogin(){ //bảo mật, chưa login thì ko thể truy cập các đường dẫn của admin
        $admin_id = Session::get('admin_id');
        if($admin_id){
            Redirect::to('dashboard');

        }else{
            return Redirect::to('admin')->send();
        }
    }

    public function add_product(){

        $this->AuthLogin(); //gọi hàm bảo mật
    	$cate_product = DB::table('tbl_category_product')->orderby('category_id','desc')->get();
    	$brand_product = DB::table('tbl_brand_product')->orderby('brand_id','desc')->get();

    	

     	return view('admin.product.add_product')->with('cate_product',$cate_product)->with('brand_product',$brand_product);
    }

     public function all_product(){

        $this->AuthLogin(); //gọi hàm bảo mật

     	$all_product = DB::table('tbl_product')     //lấy ra danh mục và thương hiệu từ id
     			->join('tbl_category_product','tbl_category_product.category_id','=','tbl_product.category_id')
     			->join('tbl_brand_product','tbl_brand_product.brand_id','=','tbl_product.brand_id')
     			->orderby('tbl_product.product_id','desc')->paginate(8);

     	$manager_product = view('admin.product.all_product')->with('all_product',$all_product);

     	return view('admin_layout')->with('admin.product.all_product',$manager_product);
    }

    public function save_product(Request $request){


        $this->AuthLogin();
        $data = array();
        $data['product_name'] = $request->product_name;
        $data['product_quantity'] = $request->product_quantity;
        $data['product_sold'] = $request->product_sold;
        $data['product_price'] = $request->product_price;
        $data['product_desc'] = $request->product_desc;
        $data['product_content'] = $request->product_content;
        $data['category_id'] = $request->product_cate;
        $data['brand_id'] = $request->product_brand;
        $data['product_status'] = $request->product_status;
        $data['product_image'] = $request->product_status;
        $get_image = $request->file('product_image');
      
        if($get_image){
            $get_name_image = $get_image->getClientOriginalName();
            $name_image = current(explode('.',$get_name_image));
            $new_image =  $name_image.rand(0,999).'.'.$get_image->getClientOriginalExtension();
            $get_image->move('public/uploads/product',$new_image);
            $data['product_image'] = $new_image;
            DB::table('tbl_product')->insert($data);
            Session::put('message','Thêm thành công');
            return Redirect::to('add-product');
        }
        $data['product_image'] = '';
        DB::table('tbl_product')->insert($data);
        Session::put('message','Thêm thành công');
        return Redirect::to('add-product');
    	
    }


    public function unactive_product($product_id){

        $this->AuthLogin(); //gọi hàm bảo mật
    	DB::table('tbl_product')->where('product_id',$product_id)->update(['product_status'=>0]);

    	Session::put('message','Ẩn sản phẩm thành công!');
    	return Redirect::to('all-product');
    }
    public function active_product($product_id){

        $this->AuthLogin(); //gọi hàm bảo mật
    	DB::table('tbl_product')->where('product_id',$product_id)->update(['product_status'=>1]);

    	Session::put('message','Hiển thị sản phẩm thành công!');
    	return Redirect::to('all-product');
    }


    public function edit_product($product_id){

        $this->AuthLogin(); //gọi hàm bảo mật

    	$cate_product = DB::table('tbl_category_product')->orderby('category_id','desc')->get();
    	$brand_product = DB::table('tbl_brand_product')->orderby('brand_id','desc')->get();
    	

    	$edit_product = DB::table('tbl_product')->where('product_id',$product_id)->get(); 
     	$manager_product = view('admin.product.edit_product')->with('edit_product',$edit_product)->with('cate_product',$cate_product)->with('brand_product',$brand_product);

     	return view('admin_layout')->with('admin.product.edit_product',$manager_product);
    }


    public function update_product(Request $request, $product_id){

        $this->AuthLogin();
        $data = array();
        $data['product_name'] = $request->product_name;
        $data['product_quantity'] = $request->product_quantity;
        $data['product_sold'] = $request->product_sold;
        $data['product_price'] = $request->product_price;
        $data['product_desc'] = $request->product_desc;
        $data['product_content'] = $request->product_content;
        $data['category_id'] = $request->product_cate;
        $data['brand_id'] = $request->product_brand;
        $data['product_status'] = $request->product_status;
        $get_image = $request->file('product_image');
        
        if($get_image){
                    $get_name_image = $get_image->getClientOriginalName();
                    $name_image = current(explode('.',$get_name_image));
                    $new_image =  $name_image.rand(0,999).'.'.$get_image->getClientOriginalExtension();
                    $get_image->move('public/uploads/product',$new_image);
                    $data['product_image'] = $new_image;
                    DB::table('tbl_product')->where('product_id',$product_id)->update($data);
                    Session::put('message','Cập nhật thành công');
                    return Redirect::to('all-product');
        }
            
        DB::table('tbl_product')->where('product_id',$product_id)->update($data);
        Session::put('message','Cập nhật thành công');
        return Redirect::to('all-product');

    	
    }

    public function delete_product($product_id){

        $this->AuthLogin(); //gọi hàm bảo mật
    	
    	DB::table('tbl_product')->where('product_id',$product_id)->delete();
    	Session::put('message','Xóa sản phẩm thành công!');
    	return Redirect::to('all-product');
    }


    //chi tiết sản phẩm
    public function details_product($product_id){


        $banner = Banner::orderby('banner_id','DESC')->where('banner_status','1')->take(3)->get();
        $cate_product = DB::table('tbl_category_product')->where('category_status','1')->orderby('category_id','desc')->get(); 
        $brand_product = DB::table('tbl_brand_product')->where('brand_status','1')->orderby('brand_id','desc')->get(); 

        $details_product = DB::table('tbl_product')
            ->join('tbl_category_product','tbl_category_product.category_id','=','tbl_product.category_id')
            ->join('tbl_brand_product','tbl_brand_product.brand_id','=','tbl_product.brand_id')
            ->where('tbl_product.product_id',$product_id)->get();

        foreach($details_product as $key => $value){

                $category_id = $value->category_id;
                $brand_id = $value->brand_id;
            }


            

        $related_product = DB::table('tbl_product')
        ->join('tbl_category_product','tbl_category_product.category_id','=','tbl_product.category_id')
        ->join('tbl_brand_product','tbl_brand_product.brand_id','=','tbl_product.brand_id')
        ->where('tbl_category_product.category_id',$category_id)->whereNotIn('tbl_product.product_id',[$product_id])->limit(3)->get();

        $related_product2 = DB::table('tbl_product')  //sp tương tự - thương hiệu
                ->join('tbl_category_product','tbl_category_product.category_id','=','tbl_product.category_id')
                ->join('tbl_brand_product','tbl_brand_product.brand_id','=','tbl_product.brand_id')
                ->where('tbl_brand_product.brand_id',$brand_id)->whereNotIn('tbl_product.product_id',[$product_id])->limit(3)->get();


        return view('pages.product.show_details')->with('category',$cate_product)->with('brand',$brand_product)->with('product_details',$details_product)->with('related',$related_product)->with('related2',$related_product2)->with('banner',$banner);
    }

    
    public function import_product(Request $request){

        $path = $request->file('file_product')->getRealPath();
        Excel::import(new ExcelImportProduct, $path);

        return back();

    }

    public function export_product(){

        return Excel::download(new ExcelExportProduct , 'product_vku.xlsx');
    }
}
