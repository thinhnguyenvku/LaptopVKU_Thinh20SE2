
@extends('layout')
@section('content')

	
<section id="cart_items">
		{{-- <div class="container"> --}}
			<div class="breadcrumbs">
				<ol class="breadcrumb">
				  <li><a href="{{URL::to('/')}}">Trang chủ</a></li>
				  <li class="active">Thanh toán</li>
				</ol>
			</div><!--/breadcrums-->

			

			<div class="register-req">
				<p>Hãy đăng ký hoặc đăng nhập trước khi thanh toán !!!</p>
			</div><!--/register-req-->

			<div class="shopper-informations">
				<div class="row">
					
					<div class="col-sm-12 clearfix">
						<div class="bill-to">
							<p>Thông tin nhận hàng</p>
							<div class="form-one">
								<form action="{{URL::to('/save-checkout-customer')}}" method="POST">


									{{csrf_field()}}

									<input type="text" placeholder="Email" name="shipping_email" class="shipping_email">
									<input type="text" placeholder="Họ tên" name="shipping_name" class="shipping_name">
									<input type="text" placeholder="Địa chỉ" name="shipping_address" class="shipping_address">
									<input type="text" placeholder="Số điện thoại" name="shipping_phone" class="shipping_phone">
									<textarea name="shipping_notes" class="shipping_notes"  placeholder="Ghi chú lại yêu cầu khác của bạn" rows="4"></textarea>

									@if(Session::get('fee'))
										<input type="hidden" name="order_fee" class="order_fee" value="{{Session::get('fee')}}">
									@else
										<input type="hidden" name="order_fee" class="order_fee" value="1">
									@endif

									@if(Session::get('coupon'))
										@foreach(Session::get('coupon') as $key => $c)
											<input type="hidden" name="order_coupon" class="order_coupon" value="{{$c['coupon_number']}}">
										@endforeach
									@else
										<input type="hidden" name="order_coupon" class="order_coupon" value="0">
									@endif

									<div class="">
										<div class="form-group">
		                                	<label for="exampleInputPassword1">Chọn phương thức thanh toán</label>
		                                    <select name="payment_select" id="payment_select" class="form-control input-sm m-bot15 payment_select">
		                                    	<option value="0">Qua thẻ ATM</option>
		                                    	<option value="1">Thanh toán khi nhận hàng</option>
		                                    </select>
	                                	</div>
									</div>
									
									<input type="button" value="Xác nhận đơn hàng" name="send_order" class="btn btn-primary send_order">
								</form>
								<form>

                                	{{csrf_field()}}

	                                <div class="form-group">
	                                	<label for="exampleInputPassword1">Chọn Tỉnh/Thành phố</label>
	                                    <select name="city" id="city" class="form-control input-sm m-bot15 choose city" required="">
	                                    	<option value="">--- Chọn Tỉnh/Thành phố ---</option>

	                                    	@foreach($city as $key => $c)
	                                    		<option value="{{$c->matp}}">{{$c->name_city}}</option>
	                                    	@endforeach

	                                    </select>
	                                </div>

	                                <div class="form-group">
	                                	<label for="exampleInputPassword1">Chọn Quận/Huyện</label>
	                                    <select name="province" id="province" class="form-control input-sm m-bot15 choose province" required="">
	                                    	<option value=""></option>
	                                    	
	                                    </select>
	                                </div>

	                                <div class="form-group">
	                                	<label for="exampleInputPassword1">Chọn Xã/Phường/Thị trấn</label>
	                                    <select name="wards" id="wards" class="form-control input-sm m-bot15 wards" required="">
	                                    	<option value=""></option>
	                                    	
	                                    </select>
	                                </div>

	                                
	                                <input type="button" value="Tính phí vận chuyển" name="calculate_order" class="btn btn-primary btn-sm calculate_delivery">

                            	</form>
							</div>
							<div class="form-two">
								
								
							</div>
						</div>
					</div>

					<div class="col-sm-12 clearfix">

						@if(Session()->has('message'))
							<div class="alert alert-success">
								{{ Session()->get('message') }}
							</div>

						@elseif(Session()->has('error'))
							<div class="alert alert-danger">
								{{ Session()->get('error') }}
							</div>
						@endif

						<div class="table-responsive cart_info">

							<form action="{{URL::to('/update-cart')}}" method="POST">
								{{csrf_field()}}
							<table class="table table-condensed">
								<thead>
									<tr class="cart_menu">
										<td class="image">Hình ảnh</td>
										<td class="description">Tên sản phẩm</td>
										<td class="price">Giá sản phẩm</td>
										<td class="quantity">Số lượng</td>
										<td class="total">Thành tiền</td>
										<td></td>
									</tr>
								</thead>
								<tbody>

									@if(Session::get('cart')==true)
										
									@php
											$total = 0;
									@endphp
									@foreach(Session::get('cart') as $key => $cart)
										@php
											$subtotal = $cart['product_price']*$cart['product_qty'];
											$total+=$subtotal;
										@endphp

									<tr>
										<td class="cart_product">
											<img src="{{asset('public/uploads/product/'.$cart['product_image'])}}" width="90" alt="{{$cart['product_name']}}" />
										</td>
										<td class="cart_description">
											<h4><a href=""></a></h4>
											<p>{{$cart['product_name']}}</p>
										</td>
										<td class="cart_price">
											<p>$ {{number_format($cart['product_price'],0,',','.')}}</p>
										</td>
										<td class="cart_quantity">
											<div class="cart_quantity_button">
												<form action="" method="POST">
											
												<input class="cart_quantity" type="number" min="1" name="cart_qty[{{$cart['session_id']}}]" value="{{$cart['product_qty']}}"  >
											
												
												
											</div>
										</td>
										<td class="cart_total">
											<p class="cart_total_price">
												$ {{number_format($subtotal,0,',','.')}}
												
											</p>
										</td>
										<td class="cart_delete">
											<a class="cart_quantity_delete" href="{{URL::to('/del-product/'.$cart['session_id'])}}"><i class="fa fa-times"></i></a>
										</td>
									</tr>
									@endforeach
									<tr>
										<td colspan="5"><input type="submit" value="Cập nhật số lượng" name="update_qty" class="btn btn-default btn-sm check_out pull-right"></td>
									</tr>
									<tr>	

										<td colspan="3"><a class="btn btn-default check_out" href="{{URL::to('/del-all-product')}}">Xóa tất cả giỏ</a>

										@if(Session::get('coupon'))
										<a class="btn btn-default check_out" href="{{URL::to('/unset-coupon')}}">Xóa mã giảm giá</a>
										@endif

										</td>

										<td>
											<li>Tổng tiền :<span>${{$total}}</span></li>
										@if(Session::get('coupon'))
										<li>
											
												@foreach(Session::get('coupon') as $key => $cou)
													@if($cou['coupon_condition']==1)
														Mã giảm : {{$cou['coupon_number']}} %
														
															@php 
															$total_coupon = ($total*$cou['coupon_number'])/100;
														
															@endphp
														
														@php 
															$total_after_coupon = $total-$total_coupon;
														@endphp
														</p>
													@elseif($cou['coupon_condition']==2)
														Mã giảm : ${{number_format($cou['coupon_number'],0,',','.')}}
														
															@php 
															$total_coupon = $total - $cou['coupon_number'];
														
															@endphp
														
														@php 
															$total_after_coupon = $total_coupon;
														@endphp
													@endif
												@endforeach
											
											

										</li>
										@endif

										@if(Session::get('fee'))
										<li>	
											<a class="cart_quantity_delete" href="{{url('/del-fee')}}"><i class="fa fa-times"></i></a>

											Phí vận chuyển <span>${{Session::get('fee')}}</span></li> 
											<?php $total_after_fee = $total + Session::get('fee'); ?>
										@endif 
										<li>Tổng thanh toán:
										@php 
											if(Session::get('fee') && !Session::get('coupon')){
												$total_after = $total_after_fee;
												echo '$'.$total_after;
											}elseif(!Session::get('fee') && Session::get('coupon')){
												$total_after = $total_after_coupon;
												echo '$'.$total_after;
											}elseif(Session::get('fee') && Session::get('coupon')){
												$total_after = $total_after_coupon;
												$total_after = $total_after + Session::get('fee');
												echo '$'.$total_after;
											}elseif(!Session::get('fee') && !Session::get('coupon')){
												$total_after = $total;
												echo '$'.$total_after;
											}

										@endphp
										</li>



										</td>
									</tr>
									@else
									<tr>
										<td colspan="5">
											<center>
												@php
													echo 'Giỏ hàng của bạn đang trống!';
												@endphp
											</center>
										</td>
									</tr>	

								</tbody>
								@endif
								</form>

									@if(Session::get('cart'))

									<tr>
										<td>
											<form action="{{URL::to('/check-coupon')}}" method="POST">

												{{csrf_field()}}
			                                
			                                	<input type="text" class="form-control" placeholder="Nhập mã giảm giá" name="coupon">
			                                	<input type="submit" class="btn btn-default check_coupon" name="check_coupon" value="Áp mã giảm giá">

			                            	</form>
										</td>
									</tr>

									@endif
							</table>
						</div>
					</div>									
				</div>
			</div>

			
			
			
		{{-- </div> --}}
	</section> <!--/#cart_items-->





@endsection