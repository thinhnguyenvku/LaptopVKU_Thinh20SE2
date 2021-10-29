@extends('admin_layout')
@section('admin_content')

    <div class="table-agile-info">
  <div class="panel panel-default">
    <div class="panel-heading">
      Thông tin đăng nhập
    </div>
    
      
    <div class="table-responsive">
                      <?php
                            $message = Session::get('message');
                            if($message){
                                echo '<span class="text-alert">'.$message.'</span>';
                                Session::put('message',null);
                            }
                            ?>
      <table class="table table-striped b-t b-light">
        <thead>
          <tr>
            
            
            <th>Mã khách hàng</th>
            <th>Họ tên</th>
            <th>Số điện thoại</th>
            <th>Email</th>
            
            
            {{-- <th style="width:30px;"></th> --}}
          </tr>

        </thead>
        <tbody>
          
          <tr>

           

            <td>{{$customer->customer_id}}</td>
            <td>{{$customer->customer_name}}</td>
            <td>{{$customer->customer_phone}}</td>
            <td>{{$customer->customer_email}}</td>

          </tr>
          
        </tbody>
      </table>
    </div>
    
  </div>
</div>

<br><br>


  <div class="table-agile-info">
  <div class="panel panel-default">
    <div class="panel-heading">
      Thông tin vận chuyển
    </div>
    
      
    <div class="table-responsive">
                      <?php
                            $message = Session::get('message');
                            if($message){
                                echo '<span class="text-alert">'.$message.'</span>';
                                Session::put('message',null);
                            }
                            ?>
      <table class="table table-striped b-t b-light">
        <thead>
          <tr>
            
            <th>Mã vận chuyển</th>
            <th>Tên người nhận hàng</th>
            <th>Số điện thoại</th>
            <th>Email</th>
            <th>Địa chỉ</th>
            <th>Phương thức thanh toán</th>
            <th>Lưu ý</th>

            
            
            {{-- <th style="width:30px;"></th> --}}
          </tr>

        </thead>
        <tbody>
          
          <tr>

            

            <td>{{$shipping->shipping_id}}</td>
            <td>{{$shipping->shipping_name}}</td>
            <td>{{$shipping->shipping_phone}}</td>
            <td>{{$shipping->shipping_email}}</td>
            <td>{{$shipping->shipping_address}}</td>
            <td>
                @if($shipping->shipping_method==0) Qua thẻ ATM
                @else Thanh toán khi nhận hàng
                @endif
            </td>
            <td>{{$shipping->shipping_notes}}</td>
           
          </tr>
          
        </tbody>
      </table>
    </div>
    
  </div>
</div>


<br><br>

<div class="table-agile-info">
  <div class="panel panel-default">
    <div class="panel-heading">
      Chi tiết đơn hàng
    </div>
    
      
    <div class="table-responsive">
                      <?php
                            $message = Session::get('message');
                            if($message){
                                echo '<span class="text-alert">'.$message.'</span>';
                                Session::put('message',null);
                            }
                            ?>
      <table class="table table-striped b-t b-light">
        <thead>
          <tr>
            
            <th>Thứ tự</th>
            <th>Mã SP</th>
            <th>Tên sản phẩm</th>
            <th>SLSP trong kho</th>
            <th>Số lượng mua</th>
            <th>Giá</th>
            <th>Tổng tiền</th>
            
            
            {{-- <th style="width:30px;"></th> --}}
          </tr>

        </thead>
        <tbody>
          @php
            $i=0;
            $total=0;
          @endphp
          
          @foreach($order_details as $key => $d)
          <tr class="color_qty_{{$d->product_id}}">

            @php
              $i++;
              $subtotal=$d->product_sales_quantity*$d->product_price;
              $total+=$subtotal;
              $feeship=$d->product_feeship;
              $coupon=$d->product_coupon;
            @endphp

            <td>{{ $i }}</td>
            <td>{{$d->product_id}}</td>
            <td>{{$d->product_name}}</td>
            <td>{{$d->product->product_quantity}}</td>
            
            <td>
                <input {{$order_status == 2 ? 'disabled' : ''}} type="number" min="1" value="{{$d->product_sales_quantity}}" name="product_sales_quantity" class="order_qty_{{$d->product_id}}">
                
                <input type="hidden" name="order_qty_storage" class="order_qty_storage_{{$d->product_id}}" value="{{$d->product->product_quantity}}">
                <input type="hidden" name="order_code" class="order_code" value="{{$d->order_code}}">
                <input type="hidden" name="order_product_id" class="order_product_id" value="{{$d->product_id}}">

                @if($order_status != 2)
                <button type="" class="btn btn-default update_quantity_order" name="update_quantity_order" data-product_id="{{$d->product_id}}">Cập nhật</button>
                @endif
            </td>


            <td>${{$d->product_price}}</td>
            <td>${{$subtotal}}</td> 

          </tr>
          @endforeach
          
          @if($feeship!=NULL || $coupon!=NULL)
          <tr>
            <td colspan="5">
            <td colspan="2"><b>Tổng tiền:</b> ${{$total}}</td>
          </tr>
          @endif
          @if($feeship!=NULL)
          <tr>
            <td colspan="5">
            <td colspan="2"><b>Phí vận chuyển:</b> ${{$feeship}}</td>
          </tr>
          @endif
          @if($coupon!=NULL)
          <tr>
            <td colspan="5">
            <td colspan="2"><b>Giảm giá:</b> ${{$coupon}}</td>
          </tr>
          @endif
          @if($feeship!=NULL && $coupon!=NULL)
          <tr>
            <td colspan="5">
            <td colspan="2"><b>Tổng thanh toán:</b> ${{$total+$feeship-$coupon}}</td>
          </tr>
          @elseif($feeship!=NULL && $coupon==NULL)
          <tr>
            <td colspan="5">
            <td colspan="2"><b>Tổng thanh toán:</b> ${{$total+$feeship}}</td>
          </tr>
          @elseif($feeship==NULL && $coupon!=NULL)
          <tr>
            <td colspan="5">
            <td colspan="2"><b>Tổng thanh toán:</b> ${{$total-$coupon}}</td>
          </tr>
          @else
          <tr>
            <td colspan="5">
            <td colspan="2"><b>Tổng thanh toán:</b> ${{$total}}</td>
          </tr>
          @endif

          <tr>
            <td colspan="3">
              @foreach($order as $key => $or)
                @if($or->order_status==1)
                  <form>
                    {{csrf_field()}}
                    <select class="form-control order_details">
                      <option value="">Chọn tình trạng đơn hàng</option>
                      <option id="{{$or->order_id}}" value="1" selected>Chưa xử lý - Đợi duyệt</option>
                      <option id="{{$or->order_id}}" value="2">Đã xử lý -> Giao hàng</option>
                      <option id="{{$or->order_id}}" value="3">Hủy đơn hàng</option>
                    </select>
                  </form>
                @elseif($or->order_status==2)
                  <form>
                    {{csrf_field()}}
                    <select class="form-control order_details">
                      <option value="">Chọn tình trạng đơn hàng</option>
                      <option id="{{$or->order_id}}" value="1">Chưa xử lý</option>
                      <option id="{{$or->order_id}}" value="2" selected>Đã xử lý</option>
                      <option id="{{$or->order_id}}" value="3">Hủy đơn hàng</option>
                    </select>
                  </form>
                @else
                  <form>
                    {{csrf_field()}}
                    <select class="form-control order_details">
                      <option value="">Chọn tình trạng đơn hàng</option>
                      <option id="{{$or->order_id}}" value="1">Chưa xử lý</option>
                      <option id="{{$or->order_id}}" value="2">Đã xử lý</option>
                      <option id="{{$or->order_id}}" value="3" selected>Hủy đơn hàng</option>
                    </select>
                  </form>
                @endif
              @endforeach
            </td>
          </tr>

        </tbody>
      </table>
      @foreach($order as $key => $order)
      <a href="{{URL::to('print-order/'.$order->order_code)}}">In đơn</a>
      @endforeach
    </div>
    
  </div>
</div>

@endsection