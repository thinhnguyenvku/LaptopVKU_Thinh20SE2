
@extends('layout')
@section('content')

	


	<section id="cart_items">
		{{-- <div class="container"> --}}
			<div class="breadcrumbs">
				<ol class="breadcrumb">
				  <li><a href="{{URL::to('/')}}">Trang chủ</a></li>
				  <li class="active">Giỏ hàng</li>
				</ol>
			</div>


			@if(Session()->has('message'))
				<div class="alert alert-success">
					{!! Session()->get('message') !!}
				</div>

			@elseif(Session()->has('error'))
				<div class="alert alert-danger">
					{!! Session()->get('error') !!}
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

							@if(Session::get('customer_id'))
							
							<a class="btn btn-default check_out" href="{{URL::to('/checkout')}}">Đặt hàng</a></td>
							@else
							
							<a class="btn btn-default check_out" href="{{URL::to('/login-checkout')}}">Đặt hàng</a></td>
							@endif


							<td>
								<li>Tổng tiền: <span>${{$total}}</span></li>
								



									@if(Session::get('coupon'))
										@foreach(Session::get('coupon') as $key => $cou)
											@if($cou['coupon_condition']==1)

												<li>Giá trị mã: {{$cou['coupon_number']}}%</li>

													@php

														$total_coupon = ($total * $cou['coupon_number']/100);
														echo '<li>Số tiền giảm: $'.$total_coupon.'</li>';

													@endphp

												<li>Tổng sau giảm: ${{$total - $total_coupon}}</li>

											@elseif($cou['coupon_condition']==2)

												<li>Giá trị mã: ${{$cou['coupon_number']}}</li>

													@php

														$total_coupon = ($total - $cou['coupon_number']);

													@endphp


												<li>Tổng sau giảm: ${{$total_coupon}}</li>
											@endif
										@endforeach
									@endif	

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

		
	</section> 


@endsection