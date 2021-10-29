<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Http\Requests;
use Session;
use Illuminate\Support\Facades\Redirect;
session_start();

use App\Models\Feeship;
use App\Models\Shipping;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Banner;

use PDF;



class OrderController extends Controller
{
   	public function manage_order(){

   		$order = Order::orderby('created_at','DESC')->paginate(5);

   		return view('admin.order.manage_order')->with(compact('order'));
   	}

   	public function view_order($order_code){

   		$order_details = OrderDetails::with('product')->where('order_code',$order_code)->get();
   		$order = Order::where('order_code',$order_code)->get();

   		foreach ($order as $key => $o) {
   			$customer_id = $o->customer_id;
   			$shipping_id = $o->shipping_id;

            $order_status = $o->order_status;
   		}
   		$customer = Customer::where('customer_id',$customer_id)->first();
   		$shipping = Shipping::where('shipping_id',$shipping_id)->first();

   		$order_details_product = OrderDetails::with('product')->where('order_code',$order_code)->get();

   		return view('admin.order.view_order')->with(compact('order_details','customer','shipping','order','order_status'));
   	}

   	public function print_order($order_code){

   		$pdf = \App::make('dompdf.wrapper');
   		$pdf->loadHTML($this->print_order_convert($order_code));

   		return $pdf->stream();
   	}

      public function update_order_qty(Request $request){

         $data = $request->all();

         //cập nhật tình trạng đơn
         $order = Order::find($data['order_id']);
         $order->order_status = $data['order_status'];
         $order->save();
         if($order->order_status==2){
            foreach($data['order_product_id'] as $key => $product_id) {
               $product = Product::find($product_id);
               $product_quantity = $product->product_quantity;
               $product_sold = $product->product_sold;
               foreach($data['quantity'] as $key2 => $qty) {
                  if($key==$key2){
                     $pro_remain = $product_quantity - $qty;
                     $product->product_quantity = $pro_remain;
                     $product->product_sold = $product_sold + $qty;
                     $product->save();
                  }
               }
            }
         }elseif($order->order_status != 2 && $order->order_status != 3){
            foreach($data['order_product_id'] as $key => $product_id) {
               $product = Product::find($product_id);
               $product_quantity = $product->product_quantity;
               $product_sold = $product->product_sold;
               foreach($data['quantity'] as $key2 => $qty) {
                  if($key==$key2){
                     $pro_remain = $product_quantity + $qty;
                     $product->product_quantity = $pro_remain;
                     $product->product_sold = $product_sold - $qty;
                     $product->save();
                  }
               }
            }
         }

      }

      public function update_qty(Request $request){

         $data = $request->all();

         $order_details = OrderDetails::where('product_id', $data['order_product_id'])->where('order_code', $data['order_code'])->first();

         $order_details->product_sales_quantity = $data['order_qty'];
         $order_details->save();
      }

   	public function print_order_convert($order_code){

         $order_details = OrderDetails::where('order_code',$order_code)->get();
         $order = Order::where('order_code',$order_code)->get();

         foreach ($order as $key => $o) {
            $customer_id = $o->customer_id;
            $shipping_id = $o->shipping_id;
         }
         $customer = Customer::where('customer_id',$customer_id)->first();
         $shipping = Shipping::where('shipping_id',$shipping_id)->first();

         $order_details_product = OrderDetails::with('product')->where('order_code',$order_code)->get();

         $output='';
         $output.='
         <style>

            body{ font-family: Dejavu Sans; }
            .table-styling{
               border: 1px solid #000;
            }
            .table-styling thead tr th{
               border: 1px solid #000;
            }
            .table-styling tbody tr td{
               border: 1px solid #000;
            }

         </style>
         <center>
         <h3>Cửa hàng phụ kiện điện tử và Laptop - FLap</h3>
         <p>---------</p>
         <h3>HÓA ĐƠN ĐẶT HÀNG</h3>
         </center>
         <br>
         <p>I. Thông tin tài khoản đặt hàng</p>
         <table class="table-styling">
            <thead>
               <tr>
                  <th>Mã khách hàng</th>
                  <th>Họ tên khách hàng</th>
                  <th>Email</th>
                  <th>SĐT</th>
               </tr>
            </thead>
            <tbody> ';
            
            $output.='
               <tr>
                  <td>'.$customer->customer_id.'</td>
                  <td>'.$customer->customer_name.'</td>
                  <td>'.$customer->customer_email.'</td>
                  <td>'.$customer->customer_phone.'</td>
               </tr> ';

            $output.='
            </tbody>
         </table>
         <br>
         <p>II. Thông tin người nhận hàng</p>
         <table class="table-styling">
            <thead>
               <tr>
                  <th>Mã vận chuyển</th>
                  <th>Tên người nhận</th>
                  <th>SĐT</th>
                  <th>Email</th>
                  <th>Địa chỉ</th>
                  <th>Ghi chú</th>
               </tr>
            </thead>
            <tbody> ';
            
            $output.='
               <tr>
                  <td>'.$shipping->shipping_id.'</td>
                  <td>'.$shipping->shipping_name.'</td>
                  <td>'.$shipping->shipping_phone.'</td>
                  <td>'.$shipping->shipping_email.'</td>
                  <td>'.$shipping->shipping_address.'</td>
                  <td>'.$shipping->shipping_notes.'</td>
               </tr> ';

            $output.='
            </tbody>
         </table>
         <br>
         <p>III. Thông tin đơn hàng</p>
         <table class="table-styling">
            <thead>
               <tr>
                  <th>Thứ tự</th>
                  <th>Mã sản phẩm</th>
                  <th>Tên sản phẩm</th>
                  <th>Số lượng</th>
                  <th>Giá</th>
                  <th>Tổng tiền</th>               
               </tr>
            </thead>
            <tbody> ';
            $i=0;
            $total=0;
            foreach ($order_details_product as $key => $odp) {
               $i++;
               $subtotal=$odp->product_sales_quantity*$odp->product_price;
               $total+=$subtotal;
               $feeship=$odp->product_feeship;
               $coupon=$odp->product_coupon;
            $output.='
               <tr>
                  <td>'.$i.'</td>
                  <td>'.$odp->product_id.'</td>
                  <td>'.$odp->product_name.'</td>
                  <td>'.$odp->product_sales_quantity.'</td>
                  <td>$'.$odp->product_price.'</td>
                  <td>$'.$subtotal.'</td>
               </tr> ';
            }
               if($feeship!=NULL || $coupon!=NULL){
            $output.='
                  <tr>
                     <td colspan="4">
                     <td colspan="2"><b>Tổng tiền:</b> $'.$total.'</td>
                  </tr>';
               }
               if($feeship!=NULL){
            $output.='
                   <tr>
                     <td colspan="4">
                     <td colspan="2"><b>Phí vận chuyển:</b> $'.$feeship.'</td>
                   </tr>';
               }
               if($coupon!=NULL){
            $output.='
                   <tr>
                     <td colspan="4">
                     <td colspan="2"><b>Giảm giá:</b> $'.$coupon.'</td>
                   </tr>';
               }
               if($feeship!=NULL && $coupon!=NULL){
            $output.='
                   <tr>
                     <td colspan="4">
                     <td colspan="2"><b>Tổng thanh toán:</b> $'.$total+$feeship-$coupon.'</td>
                   </tr>';
               }
               elseif($feeship!=NULL && $coupon==NULL){
            $output.='
                   <tr>
                     <td colspan="4">
                     <td colspan="2"><b>Tổng thanh toán:</b> $'.$total+$feeship.'</td>
                   </tr>';
               }
               elseif($feeship==NULL && $coupon!=NULL){
            $output.='
                   <tr>
                     <td colspan="4">
                     <td colspan="2"><b>Tổng thanh toán:</b> $'.$total-$coupon.'</td>
                   </tr>';
               }
               else{
            $output.='
                   <tr>
                     <td colspan="4">
                     <td colspan="2"><b>Tổng thanh toán:</b> $'.$total.'</td>
                   </tr>';
               }
            
            $output.='
            </tbody>
         </table>
         <br>
         <table>
            <thead>
               <tr>
                  <th width="200px">Người lập phiếu</th>
                  <th width="800px">Người nhận hàng</th>
               </tr>
            </thead>
            <tbody> ';
            $output.='
            </tbody>
         </table>';

         return $output;

   	}
}
