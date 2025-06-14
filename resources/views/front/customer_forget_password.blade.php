@extends('front.app_front')

@section('content')

<div class="page-banner" style="background-image: url('{{ asset('uploads/page_banners/'.$page_other_item->forget_password_page_banner) }}')">
	<div class="page-banner-bg"></div>
	<h1>{{ FORGET_PASSWORD }}</h1>
	<nav>
		<ol class="breadcrumb justify-content-center">
			<li class="breadcrumb-item"><a href="{{ url('/') }}">{{ HOME }}</a></li>
			<li class="breadcrumb-item active">{{ FORGET_PASSWORD }}</li>
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

						<form action="{{ route('customer_forget_password_store') }}" method="post">
							@csrf
							<div class="form-group">
								<label for="">{{ EMAIL_ADDRESS }}</label>
                                                                <input type="email" class="form-control" name="email" placeholder="Digite o seu e-mail">
							</div>
							@if($g_setting->google_recaptcha_status == 'Show')
							<div class="form-group">
								<div class="g-recaptcha" data-sitekey="{{ $g_setting->google_recaptcha_site_key }}"></div>
							</div>
							@endif
							<button type="submit" class="btn btn-dark btn-block btn-lg mt-3 mb-4">{{ SUBMIT }}</button>
							<div class="new-user mt-4 mb-4">
								<a href="{{ route('customer_login') }}">{{ BACK_TO_LOGIN_PAGE }}</a>
							</div>
						</form>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>

@endsection
