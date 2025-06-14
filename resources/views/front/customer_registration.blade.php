@extends('front.app_front')

@section('content')

<div class="page-banner" style="background-image: url('{{ asset('uploads/page_banners/'.$page_other_item->registration_page_banner) }}')">
	<div class="page-banner-bg"></div>
	<h1>{{ REGISTRATION }}</h1>
	<nav>
		<ol class="breadcrumb justify-content-center">
			<li class="breadcrumb-item"><a href="{{ url('/') }}">{{ HOME }}</a></li>
			<li class="breadcrumb-item active">{{ REGISTRATION }}</li>
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

						<form action="{{ route('customer_registration_store') }}" method="post">
							@csrf
							<div class="form-group">
								<label for="">{{ NAME }}</label>
								<input type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="Informe seu nome" />
							</div>
							<div class="form-group">
								<label for="cnpj">CNPJ</label>
                                                                <input type="tel" class="form-control" name="cnpj" value="{{ old('cnpj') }}" maxlength="14" placeholder="Informe o CNPJ" />
							</div>
							<div class="form-group">
								<label for="">{{ EMAIL_ADDRESS }}</label>
								<input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Informe o e-mail" />
							</div> 
							<div class="form-group">
								<label for="">{{ PASSWORD }}</label>
								<input type="password" class="form-control" name="password" placeholder="Digite a senha" />
							</div>
							<div class="form-group">
								<label for="">{{ RETYPE_PASSWORD }}</label>
								<input type="password" class="form-control" name="re_password" placeholder="Redigite a senha" />
							</div>
							@if($g_setting->google_recaptcha_status == 'Show')
							<div class="form-group">
								<div class="g-recaptcha" data-sitekey="{{ $g_setting->google_recaptcha_site_key }}"></div>
							</div>
							@endif
							<button type="submit" class="btn btn-dark btn-block btn-lg mt-3 mb-4">{{ MAKE_REGISTRATION }}</button>
							<div class="new-user mt-4 mb-4">
								{{ HAVE_AN_ACCOUNT }} <a href="{{ route('customer_login') }}" class="link">{{ LOGIN_NOW }}</a>
							</div>
						</form>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>

@endsection
