
@extends('layout')
@section('content')

	


	<section id="form"><!--form-->
		<div class="container">
			<div class="row">
				<div class="col-sm-4 col-sm-offset-1">
					<div class="login-form"><!--login form-->
						<h2>Đăng nhập tài khoản</h2>
						<form action="{{URL::to('/login-customer')}}" method="POST">

							{{csrf_field()}}

							<input type="text" name="email_account" placeholder="Tài khoản" />
							<input type="password" name="password_account" placeholder="Mật khẩu" />
							<span>
								<input type="checkbox" class="checkbox"> 
								Ghi nhớ đăng nhập?
							</span>
							<button type="submit" class="btn btn-default">Đăng nhập</button>
						</form>
					</div><!--/login form-->
				</div>
				<div class="col-sm-1">
					<h2 class="or">OR</h2>
				</div>
				<div class="col-sm-4">
					<div class="signup-form"><!--sign up form-->
						<h2>Đăng ký tài khoản mới</h2>
						<form action="{{URL::to('/add-customer')}}" method="POST">

							{{csrf_field()}}

							<input type="text" placeholder="Họ tên" name="customer_name"/>
							<input type="email" placeholder="Địa chỉ email" name="customer_email"/>
							<input type="text" placeholder="Số điện thoại" name="customer_phone"/>
							<input type="password" placeholder="Mật khẩu" name="customer_password"/>

							<button type="submit" class="btn btn-default">Đăng ký</button>

						</form>
					</div><!--/sign up form-->
				</div>
			</div>
		</div>
	</section><!--/form-->







@endsection