@extends('front.app_front')

@section('content')

<div class="page-banner" style="background-image: url('{{ asset('uploads/page_banners/'.$page_other_item->login_page_banner) }}')">
	<div class="page-banner-bg"></div>
	<h1>{{ LOGIN }}</h1>
	<nav>
		<ol class="breadcrumb justify-content-center">
			<li class="breadcrumb-item"><a href="{{ url('/') }}">{{ HOME }}</a></li>
			<li class="breadcrumb-item active">{{ LOGIN }}</li>
		</ol>
	</nav>
</div>


<div class="page-content">
	<div class="container">
		<div class="row cart">

			<div class="col-md-4 offset-md-4">
				<div class="reg-login-forms">
					<div class="inner">

          
						@php
							$g_setting = \App\Models\GeneralSetting::where('id',1)->first();
						@endphp

						<form action="{{ route('customer_login_store') }}" method="post">
							@csrf
							<div class="form-group">
								<label for="">{{ EMAIL_ADDRESS }}</label>
                                                                <input type="email" class="form-control" name="email" placeholder="Informe o e-mail">
							</div>
							<div class="form-group">
								<label for="">{{ PASSWORD }}</label>
								<input type="password" class="form-control" name="password" placeholder="Informe a senha">
							</div>
							@if($g_setting->google_recaptcha_status == 'Show')
							<div class="form-group">
								<div class="g-recaptcha" data-sitekey="{{ $g_setting->google_recaptcha_site_key }}"></div>
							</div>
							@endif
							<button type="submit" class="btn btn-dark btn-block btn-lg mt-3 mb-4">{{ LOGIN }}</button>
							<div class="new-user">
								<a href="{{ route('customer_forget_password') }}" class="link mt-2 mb-4">{{ FORGET_PASSWORD }}</a>
                                                                <p class="mt-3">
                                                                    {{ QUESTION_NEW_USER }} <a href="{{ route('customer_registration') }}" class="link">{{ REGISTER_NOW }}</a>
                                                                </p>
                                                              	
							</div>
						</form>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>

@endsection
