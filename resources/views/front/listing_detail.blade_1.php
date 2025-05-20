@extends('front.app_front')

@section('content')

<!--<script type='text/javascript' src='https://platform-api.sharethis.com/js/sharethis.js#property=5993ef01e2587a001253a261&product=inline-share-buttons' async='async'></script>-->


 @if(!$listing_photos->isEmpty())
		<div class="row">
                    <link rel='stylesheet' id='swiper-css-css' href='https://unpkg.com/swiper/swiper-bundle.min.css' media='all' />
                    <style>    
        .swiper-container {
            width: 100%;
            height: 280px; /* Ajuste a altura conforme necessário */
            overflow: hidden; /* Oculta qualquer conteúdo que ultrapasse o contêiner */
        }
        .swiper-wrapper {
            display: flex;
            align-items: center;
            width: 100%;
        }
        .swiper-slide {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            flex-shrink: 0; /* Impede o encolhimento dos slides */
        }
        .swiper-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover; /* Mantém a proporção da imagem e cobre o espaço disponível */
        }
        /* Remove a barra de rolagem */
        .swiper-scrollbar {
            display: none;
        }
        /* Ajuste os botões de navegação */
        .swiper-button-next, .swiper-button-prev {
            
            color: #000; /* Cor do ícone */
            border-radius: 50%; /* Botões circulares */
            width: 60px; /* Largura do botão */
            height: 60px; /* Altura do botão */
            line-height: 40px; /* Centraliza o texto verticalmente */
            text-align: center; /* Centraliza o texto horizontalmente */
            font-size: 18px; /* Tamanho do ícone */
            /*box-shadow: 0 0 10px rgba(255, 255, 255, 0.8); /* Sombra branca */
            box-shadow: 0 0 10px rgba(255, 0, 0, 0.8); /* Sombra vermelha */
        }
        @media (max-width: 768px) {
            .swiper-container {
                margin-top: 50px;
                width:96.5%;
                height: 280px; /* Altura para dispositivos móveis */                
            overflow: hidden; /* Oculta qualquer conteúdo que ultrapasse o contêiner */
            }
            .swiper-button-next, .swiper-button-prev {
                top:30%;
            }
        }
        
    </style>
                        <div class="swiper-container">
                            <div class="swiper-wrapper">
                                @foreach($listing_photos as $rowPhotos)
                                <div class="swiper-slide">
                                    @if($detail->canal == 'dsautoestoque')
                                    <a href="{{ asset($rowPhotos->photo) }}" data-fancybox="gallery" class="magnific">
                            <img src="{{ asset($rowPhotos->photo) }}" alt="" style="width: 100%; height: auto;" />
                        </a>
                                    @else
                                    <a href="{{ asset('uploads/listing_photos/'.$rowPhotos->photo) }}" data-fancybox="gallery" class="magnific">
                            <img src="{{ asset('uploads/listing_photos/'.$rowPhotos->photo) }}" alt="" style="width: 100%; height: auto;" />
                        </a>
                                    @endif
                    </div>
                                @endforeach
                            </div>
                            <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
                        </div>                    
                </div>
            <script defer="defer" src="https://unpkg.com/swiper/swiper-bundle.min.js" id="swiper-js-js"></script>
<script id="swiper-js-js-after">
            document.addEventListener('DOMContentLoaded', function() {
            var swiper = new Swiper('.swiper-container', {
                slidesPerView: 2,
                spaceBetween: 0,
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                breakpoints: {
                    1024: {
                        slidesPerView: 3,
                        spaceBetween: 0,
                    },
                    600: {
                        slidesPerView: 2,
                        spaceBetween: 0,
                    },
                    480: {
                        slidesPerView: 2,
                        spaceBetween: 0,
                    },
                    320: {
                        slidesPerView: 1,
                        spaceBetween: 0,
                    }
                }
            });
        });
            </script>
            @endif

       
<div class="listing-single-banner" style="padding-top: 25px !important;padding-bottom: 25px !important;">

    <div class="bg"></div>
	<div class="container">
		<div class="row">
			<div class="col-md-12">
                           
				<h1 style="color: #000 !important;font-size: 22px !important;">{{ $detail->listing_name }} - {{ $detail->veiculo_id }}</h1>
				<div class="price">
                                    <span style="font-size: 20px;color:white;" class="badge bg-dark">
					@if(!session()->get('currency_symbol'))
						${{ number_format($detail->listing_price) }}
					@else
						{{ session()->get('currency_symbol') }}{{ number_format($detail->listing_price*session()->get('currency_value'),0,',','.') }}
					@endif
                                    </span>
				</div>
				<div class="location" style="color: #333 !important;font-size: 18px !important;">
					<i class="fas fa-map-marker-alt"></i> {{ $detail->rListingLocation->listing_location_name }}
				</div>
				<div class="review" style="color: #e00445 !important;font-size: 15px !important;display:none;">
                    @if($overall_rating == 5)
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    @elseif($overall_rating == 4.5)
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    @elseif($overall_rating == 4)
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="far fa-star"></i>
                    @elseif($overall_rating == 3.5)
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                        <i class="far fa-star"></i>
                    @elseif($overall_rating == 3)
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="far fa-star"></i>
                        <i class="far fa-star"></i>
                    @elseif($overall_rating == 2.5)
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                        <i class="far fa-star"></i>
                        <i class="far fa-star"></i>
                    @elseif($overall_rating == 2)
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="far fa-star"></i>
                        <i class="far fa-star"></i>
                        <i class="far fa-star"></i>
                    @elseif($overall_rating == 1.5)
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                        <i class="far fa-star"></i>
                        <i class="far fa-star"></i>
                        <i class="far fa-star"></i>
                    @elseif($overall_rating == 1)
                        <i class="fas fa-star"></i>
                        <i class="far fa-star"></i>
                        <i class="far fa-star"></i>
                        <i class="far fa-star"></i>
                        <i class="far fa-star"></i>
                    @elseif($overall_rating == 0)
                        <i class="far fa-star"></i>
                        <i class="far fa-star"></i>
                        <i class="far fa-star"></i>
                        <i class="far fa-star"></i>
                        <i class="far fa-star"></i>
                    @endif
					<span>({{ count($reviews) }} {{ REVIEWS }})</span>
				</div>
				<div class="call" style="color: #333 !important;font-size: 17px !important;">
					<i class="fas fa-phone-volume"></i> {{ $detail->listing_phone }}
				</div>
				<div class="listing-items" style="margin-top: 10px !important;margin-bottom: 20px !important;">
					<a href="{{ route('front_listing_brand_detail',$detail->rListingBrand->listing_brand_slug) }}">
						<i class="far fa-edit"></i> {{ $detail->rListingBrand->listing_brand_name }}
					</a>
					<a href="{{ route('front_add_wishlist',$detail->id) }}">
						<i class="fas fa-heart"></i> {{ ADD_TO_WISHLIST }}
					</a>
					<a href="" data-toggle="modal" data-target="#send_message_modal">
						<i class="far fa-envelope"></i> {{ SEND_MESSAGE }}
					</a>
                                    @if($detail->canal == 'dsautoestoque')
                                    <a href="{{ $detail->listing_featured_photo }}" class="magnific">
                                        Ampliar imagem
                                    </a>
                                    @else
                                    <a href="{{ asset('uploads/listing_featured_photos/'.$detail->listing_featured_photo) }}" class="magnific">
                                        Ampliar imagem
                                    </a>
                                    @endif
                                    
                                    <a href="#parcelas" class="btn btn-danger float-right">
                                        Simular financiamento
                                    </a>

                    <!-- Send Message Modal -->
                    <div class="modal fade modal_listing_detail" id="send_message_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">{{ SEND_MESSAGE }}</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('front_listing_detail_send_message') }}" method="post">
                                        @csrf
                                        <input type="hidden" name="listing_name" value="{{ $detail->listing_name }}">
                                        <input type="hidden" name="listing_slug" value="{{ $detail->listing_slug }}">
                                        <input type="hidden" name="agent_name" value="{{ $agent_detail->name }}">
                                        <input type="hidden" name="agent_email" value="{{ $agent_detail->email }}">
                                        <input type="hidden" name="id" value="{{ $detail->id }}">
                                        <div class="form-group">
                                            <label for="">{{ NAME }}</label>
                                            <div>
                                                <input type="text" name="name" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="">{{ EMAIL }}</label>
                                            <div>
                                                <input type="email" name="email" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="">{{ PHONE }}</label>
                                            <div>
                                                <input type="text" name="phone" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="">{{ MESSAGE }}</label>
                                            <div>
                                                <textarea name="message" class="form-control h-100" cols="30" rows="10" required>Olá, tenho interesse no veículo. Por favor entre em contato.</textarea>
                                            </div>
                                        </div>
                                        @if($g_setting->google_recaptcha_status == 'Show')
                                            <div class="form-group">
                                                <div class="g-recaptcha" data-sitekey="{{ $g_setting->google_recaptcha_site_key }}"></div>
                                            </div>
                                        @endif
                                        <div class="form-group">
                                            <div>
                                                <button type="submit" class="btn btn-success">{{ SEND_MESSAGE }}</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- // Send Message Modal -->
					<a href="" data-toggle="modal" data-target="#report_modal">
						<i class="far fa-flag"></i> {{ REPORT }}
					</a>
                    <!-- Report Modal -->
                    <div class="modal fade modal_listing_detail" id="report_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">{{ SUBMIT_REPORT }}</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('front_listing_detail_report_listing') }}" method="post">
                                        @csrf
                                        <input type="hidden" name="listing_name" value="{{ $detail->listing_name }}">
                                        <input type="hidden" name="listing_slug" value="{{ $detail->listing_slug }}">
                                        <input type="hidden" name="id" value="{{ $detail->id }}">
                                        <div class="form-group">
                                            <label for="">{{ NAME }}</label>
                                            <div>
                                                <input type="text" name="name" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="">{{ EMAIL }}</label>
                                            <div>
                                                <input type="email" name="email" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="">{{ PHONE }}</label>
                                            <div>
                                                <input type="text" name="phone" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="">{{ MESSAGE }}</label>
                                            <div>
                                                <textarea name="message" class="form-control h-100" cols="30" rows="10" required></textarea>
                                            </div>
                                        </div>
                                        @if($g_setting->google_recaptcha_status == 'Show')
                                            <div class="form-group">
                                                <div class="g-recaptcha" data-sitekey="{{ $g_setting->google_recaptcha_site_key }}"></div>
                                            </div>
                                        @endif
                                        <div class="form-group">
                                            <div>
                                                <button type="submit" class="btn btn-success">{{ SUBMIT_REPORT }}</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- // Report Modal -->


				</div>

				@if(!$listing_social_items->isEmpty())
				<div class="social">
					<ul>
						@foreach($listing_social_items as $row)
						@if($row->social_icon == 'Facebook')
                    	@php $icon_code = 'fab fa-facebook-f'; @endphp

                    	@elseif($row->social_icon == 'Twitter')
                    	@php $icon_code = 'fab fa-twitter'; @endphp

                    	@elseif($row->social_icon == 'LinkedIn')
                    	@php $icon_code = 'fab fa-linkedin-in'; @endphp

                    	@elseif($row->social_icon == 'YouTube')
                    	@php $icon_code = 'fab fa-youtube'; @endphp

                    	@elseif($row->social_icon == 'Pinterest')
                    	@php $icon_code = 'fab fa-pinterest-p'; @endphp

                    	@elseif($row->social_icon == 'GooglePlus')
                    	@php $icon_code = 'fab fa-google-plus-g'; @endphp

                    	@elseif($row->social_icon == 'Instagram')
                    	@php $icon_code = 'fab fa-instagram'; @endphp

                    	@endif
						<li>
							<a href="{{ $row->social_url }}"><i class="{{ $icon_code }}"></i></a>
						</li>
						@endforeach
					</ul>
				</div>
				@endif

			</div>
		</div>
	</div>
</div>
<div class="page-content">
	<div class="container"> 
           
		<div class="row">
			<div class="col-lg-8 col-md-12 col-sm-12" style="padding: 0 !important;"> 
				<div class="listing-page" style="margin-left:4px;">
                                    <div style="border-radius: 4px;padding: 10px; box-shadow: 3px 3px 5px rgba(0, 0, 0, 0.3);">
					<h2><i class="fas fa-folder"></i> {{ DESCRIPTION }}</h2>
					<p>
						{!! clean($detail->listing_description) !!}
					</p>
                                    </div> 
					

					@if(!$listing_videos->isEmpty())
					<div class="gap"></div>
					<div class="video-all" style="border-radius: 4px;padding: 10px; box-shadow: 3px 3px 5px rgba(0, 0, 0, 0.3);">
					<h2><i class="fas fa-video"></i> {{ VIDEOS }}</h2>
						<div class="row">
							@foreach($listing_videos as $row)
							<div class="col-md-6 col-lg-4">
								<div class="item">
									<a class="video-button" href="http://www.youtube.com/watch?v={{ $row->youtube_video_id }}">
										<img src="http://img.youtube.com/vi/{{ $row->youtube_video_id }}/0.jpg" alt="">
										<div class="icon">
											<i class="far fa-play-circle"></i>
										</div>
										<div class="bg"></div>
									</a>
								</div>
							</div>
							@endforeach
						</div>
					</div>
					@endif


					@if($detail->listing_map!='')
					<div class="gap"></div>
					<div class="map" style="border-radius: 4px;padding: 10px; box-shadow: 3px 3px 5px rgba(0, 0, 0, 0.3);">
					<h2><i class="fas fa-map"></i> {{ LOCATION_MAP }}</h2>
						{!! $detail->listing_map !!}
					</div>
					@endif


					<div class="gap"></div>
					<div class="contact" style="border-radius: 4px;padding: 10px; box-shadow: 3px 3px 5px rgba(0, 0, 0, 0.3);">
					<h2><i class="fas fa-atom"></i> {{ FEATURES }}</h2>
                                            <ul class="two-columns">
                                                <li><span style="font-size: 14px;color:white;" class="badge bg-dark">
                                                        @if(!session()->get('currency_symbol'))
											${{ number_format($detail->listing_price) }}
										@else
											{{ session()->get('currency_symbol') }}{{ number_format($detail->listing_price*session()->get('currency_value'),0,',','.') }}
                                                                                        @endif 
                                                    </span></li>
                                                <li><i class="fas fa-check-square"></i> {{ TYPE }}: {{ $detail->listing_type }}</li>
                                                @if($detail->listing_exterior_color != '')
                                                <li><i class="fas fa-check-square"></i> {{ EXTERIOR_COLOR }}: {{ $detail->listing_exterior_color }}</li>
                                                @endif
                                               
					
								@if($detail->listing_interior_color != '')
								<li><i class="fas fa-check-square"></i> {{ INTERIOR_COLOR }}: {{ $detail->listing_interior_color }}</li>
								@endif

								@if($detail->listing_cylinder != '')
								<li><i class="fas fa-check"></i> {{ CYLINDER }}: {{ $detail->listing_cylinder }}</li>
								@endif

								@if($detail->listing_fuel_type != '')
								<li><i class="fas fa-check-square"></i> {{ FUEL_TYPE }}: {{ $detail->listing_fuel_type }}</li>
								@endif

								@if($detail->listing_transmission != '')
								<li><i class="fas fa-check-square"></i> {{ TRANSMISSION }}: {{ $detail->listing_transmission }}</li>
								@endif

								@if($detail->listing_engine_capacity != '')
								<li><i class="fas fa-check-square"></i> {{ ENGINE_CAPACITY }}: {{ $detail->listing_engine_capacity }}</li>
								@endif

								@if($detail->listing_vin != '')
								<li><i class="fas fa-check-square"></i> {{ VIN }}: {{ $detail->listing_vin }}</li>
								@endif

								@if($detail->listing_body != '')
								<li><i class="fas fa-check-square"></i> {{ BODY }}: {{ $detail->listing_body }}</li>
								@endif

								@if($detail->listing_seat != '')
								<li><i class="fas fa-check-square"></i> {{ SEAT }}: {{ $detail->listing_seat }}</li>
								@endif

								@if($detail->listing_wheel != '')
								<li><i class="fas fa-check-square"></i> {{ WHEEL }}: {{ $detail->listing_wheel }}
									</li>
								@endif

								@if($detail->listing_door != '')
								<li><i class="fas fa-check-square"></i> {{ DOOR }}: {{ $detail->listing_door }}
									</li>
								@endif

								@if($km != '')
								<li><i class="fas fa-check-square"></i> {{ MILEAGE }}: {{ $km }}
									</li>
								@endif

								@if($detail->listing_model_year != '')
								<li><i class="fas fa-check-square"></i> {{ MODEL_YEAR }}: {{ $detail->listing_model_year }}
									</li>
								@endif
							 </ul>
					</div>


					@if(!$listing_amenities->isEmpty())
					<div class="gap"></div>
					<div class="amenities" style="border-radius: 4px;padding: 10px; box-shadow: 3px 3px 5px rgba(0, 0, 0, 0.3);">
					<h2><i class="fas fa-bullhorn"></i> {{ AMENITIES }}</h2>
						<ul class="two-columns">
							@foreach($listing_amenities as $row)
							@php
							$res = DB::table('amenities')->where('id',$row->amenity_id)->first();
							@endphp
							<li><i class="fas fa-check-square" style="color:#000 !important;"></i> {{ $res->amenity_name }}</li>
							@endforeach
						</ul>
					</div> 
					@endif


					@if(!$listing_additional_features->isEmpty())
					<div class="gap"></div>
					<div class="contact" style="border-radius: 4px;padding: 10px; box-shadow: 3px 3px 5px rgba(0, 0, 0, 0.3);">
					<h2><i class="far fa-id-card"></i> {{ ADDITIONAL_FEATURES }}</h2>
						<ul class="two-columns">
								@foreach($listing_additional_features as $row)
								<li><i class="fas fa-check-square"></i> {{ $row->additional_feature_name }}: {{ $row->additional_feature_value }}</li>
								@endforeach
							</ul>
					</div>
					@endif


					<div class="gap"></div>
					<div class="contact" style="border-radius: 4px;padding: 10px; box-shadow: 3px 3px 5px rgba(0, 0, 0, 0.3);">
						<h2><i class="far fa-id-card"></i> {{ CONTACT_INFORMATION }}</h2>
                                        <div class="table-responsive">
							<table class="table table-condensed">
								@if($detail->listing_address!='')
								<tr>
									<td class="w-200">{{ ADDRESS }}</td>
									<td>
										{!! clean(nl2br($detail->listing_address)) !!}
									</td>
								</tr>
								@endif

								<tr>
									<td>{{ PHONE_NUMBER }}</td>
									<td>
										{!! clean(nl2br($detail->listing_phone)) !!}
									</td>
								</tr>

								@if($detail->listing_email!='')
								<tr>
									<td>{{ EMAIL_ADDRESS }}</td>
									<td>
										{!! clean(nl2br($detail->listing_email)) !!}
									</td>
								</tr>
								@endif

								@if($detail->listing_website!='')
								<tr>
									<td>{{ WEBSITE }}</td>
									<td class="website">
										<a href="{{ $detail->listing_website }}" target="_blank">{{ $detail->listing_website }}</a>
									</td>
								</tr>
								@endif

							</table>
						</div>
					</div>
                                        
                                        <div class="gap"></div>
                                        <div id="parcelas" style="border-radius: 4px;padding: 10px; box-shadow: 3px 3px 5px rgba(0, 0, 0, 0.3);">
                                            <h2 style="border-bottom: 1px solid #ccc;padding-bottom:10px;"><i class="far fa-credit-card"></i> Veja as parcelas desse veículo <br><small>Tudo sem compromisso, vamos começar com alguns dados :)</small></h2>
						<!--<img src="{{ asset('images/logo-itau-financiamentos.png') }}" style="float:right;height:90px;"/>-->
                                                <div class="clearfix clear"></div>
                                                
                                                <form action="?action=simular" method="post" id="financiamentoForm" class="form-simulador formulario-simulador-modal mt-4 pl-2">  
                        <div class="row">
                            <div class="col-md-6 form-floating mb-3">  
                                <label for="nomeCompleto-modal">Nome Completo <span class="badge bg-danger" style="color:white;">*</span></label>
                                <input type="text" name="nomeCompleto" id="nomeCompleto-modal" class="form-control nomeCompleto-modal" placeholder="Nome completo*" required="true">
                                                            </div>
                            <div class="col-md-6 form-floating mb-3">
                                <label for="email_simulador-modal">E-mail <span class="badge bg-danger" style="color:white;">*</span></label>
                                <input type="email" id="email_simulador-modal" name="email_simulador" class="form-control email_simulador-modal" placeholder="E-mail*" required="true">
                                                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-floating mb-3">
                                <label for="cpf_simulador-modal">CPF <span class="badge bg-danger" style="color:white;">*</span></label>
                                <input type="tel" id="cpf_simulador-modal" name="cpf_simulador" min="11" max="11" maxlength="11" class="form-control cpf_simulador-modal cpf" placeholder="CPF*" required="true">
                               </div>
                            <div class="col-md-6 form-floating mb-3">
                                <label for="telefone_simulador-modal">Celular <span class="badge bg-danger" style="color:white;">*</span></label>
                                <input type="tel" id="telefone_simulador-modal" name="telefone_simulador" min="11" max="11" maxlength="11" class="form-control telefone_simulador-modal celular" placeholder="Celular*" required="true">
                                                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12 mb-3">
                                <label for="autorizacao">
                                    <input type="checkbox" name="autorizacao" value="1" id="autorizacao-modal"> 
                                    <span style="font-weight: 400 !important;">Quero receber contatos da Vila dos Carros por e-mail, WhatsApp e outros canais.</span>
                                </label>
                                
                                <input type="hidden" name="vehicleMake" class="form-control" id="vehicleMake-modal" value="{{ $detail->vehicleMake }}">
                            <!--<input type="hidden" name="vehicleMake" class="form-control" id="vehicleMake" value="VOLKSWAGEN"/>-->
                            <input type="hidden" name="vehicleModel" class="form-control" id="vehicleModel-modal" value="{{ $detail->vehicleModel }}">
                            <input type="hidden" name="vehicleModelYear" class="form-control" id="vehicleModelYear-modal" value="{{ $detail->listing_model_year }}">
                            <input type="hidden" name="vehicleManufactureYear" class="form-control" id="vehicleManufactureYear-modal" value="{{ $detail->listing_model_year }}">
                            <input type="hidden" name="vehicleValue" class="form-control" id="vehicleValue-modal" value="{{ $detail->listing_price }}">
                            <input type="hidden" name="newVehicle" class="form-control" id="newVehicle-modal" value="{{ $detail->listing_type === 'Usado' ? 'false' : 'true' }}">
                            <div class="--wf-pre-simulation-details">
                                <p style="font-size: 12px !important;">
                                    <span class="badge bg-danger" style="color:white;">*</span> Todos os campos são obrigatórios<br>
                                    * Para realizar sua simulação, suas informações serão compartilhadas com o Banco Financiador, parceira Vila dos Carros.
                                </p>
                            </div>
                            </div>
                            
                            <div class="form-group col-md-12 mt-4 d-flex justify-content-between">
                                <button type="submit" class="btn btn-warning btn-flat btn-block btn-lg  ms-auto" 
                                        style="font-size: 16px;background-color: #ec7000;color: #fff;" id="btn-simular">simular parcelas agora</button>
                            </div>
                        </div> 
                    </form>
                                                
                                                <div class="clearfix clear"></div>
                                                
                                                <div class="modal fade" id="simulador-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-backdrop="static" backdrop="static">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Simular financiamento</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div id="zflow-container" style="margin-top: 20px !important;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                                                
                                                <div class="clearfix clear"></div>
                                        </div>
                                       <script>
    var zflowData = {}; // Defina a variável como global

    $(document).ready(function() {
    
        $('#financiamentoForm').submit(function(e) {
            e.preventDefault();
            // Captura os valores dos campos do formulário
            var nomeCompleto = $('#nomeCompleto-modal').val();
            var email_simulador = $('#email_simulador-modal').val();
            var cpf_simulador = $('#cpf_simulador-modal').val();

            // Captura e separa o DDD e o número de telefone
            var telefone_simulador = $('#telefone_simulador-modal').val();
            var telefoneApenasNumeros = telefone_simulador.replace(/\D/g, '');
            var ddd = telefoneApenasNumeros.substring(0, 2); // Captura os dois primeiros dígitos como DDD
            var numeroTelefone = telefoneApenasNumeros.substring(2); // Captura o restante como número
            
            // Captura os valores dos campos do veículo
            var vehicleMake = $('#vehicleMake-modal').val();
            var vehicleModel = $('#vehicleModel-modal').val();
            var vehicleModelYear = $('#vehicleModelYear-modal').val();
            var vehicleManufactureYear = $('#vehicleManufactureYear-modal').val();
            var vehicleValue = $('#vehicleValue-modal').val();
            var newVehicle = $('#newVehicle-modal').val();

            // Monta o objeto zflowData
            zflowData = {
                "client": "{{ $z_flow_client }}",
                "from": "{{ $z_flow_from }}",
                "elem": "{{ $z_flow_elem }}",
                transaction: {
                    "externalClientId": "{{ time() }}",
                    "client": {
                        "name": nomeCompleto,
                        "email": email_simulador,
                        "cpf": cpf_simulador,
                        "personalPhones": [{
                            "areaCode": ddd,
                            "number": numeroTelefone,
                            "phoneType": "MOBILE"
                        }]
                    },
                    "operationRequest": {
                        "vehicleSellerDocument": "{{ $z_flow_vehicleSellerDocument }}",
                        "vehicleMake": vehicleMake,
                        "vehicleModel": vehicleModel,
                        "vehicleModelYear": vehicleModelYear,
                        "vehicleManufactureYear": vehicleManufactureYear,
                        "vehicleValue": vehicleValue,
                        "newVehicle": newVehicle
                    }
                }
            };

            // Carrega o script zflow.js
            (function(d, t) {
                var zf = d.createElement(t), s = d.getElementsByTagName(t)[0];
                zf.async = true;
                zf.src = "https://channels-assistants-vehicle.itau.com.br/static/zflow.js";
                s.parentNode.insertBefore(zf, s);
            })(document, 'script');
            $('#simulador-modal').modal('show')
        });
    });
</script>

                                        
					<div class="gap"></div>

					<div class="review-overall" style="display:none;border-radius: 4px;padding: 10px; box-shadow: 3px 3px 5px rgba(0, 0, 0, 0.3);">
					<h2>{{ REVIEWS }} ({{ count($reviews) }})</h2>
						<div class="review">
                            @if($overall_rating == 5)
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            @elseif($overall_rating == 4.5)
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            @elseif($overall_rating == 4)
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="far fa-star"></i>
                            @elseif($overall_rating == 3.5)
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                                <i class="far fa-star"></i>
                            @elseif($overall_rating == 3)
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="far fa-star"></i>
                                <i class="far fa-star"></i>
                            @elseif($overall_rating == 2.5)
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                                <i class="far fa-star"></i>
                                <i class="far fa-star"></i>
                            @elseif($overall_rating == 2)
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="far fa-star"></i>
                                <i class="far fa-star"></i>
                                <i class="far fa-star"></i>
                            @elseif($overall_rating == 1.5)
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                                <i class="far fa-star"></i>
                                <i class="far fa-star"></i>
                                <i class="far fa-star"></i>
                            @elseif($overall_rating == 1)
                                <i class="fas fa-star"></i>
                                <i class="far fa-star"></i>
                                <i class="far fa-star"></i>
                                <i class="far fa-star"></i>
                                <i class="far fa-star"></i>
                            @elseif($overall_rating == 0)
                                <i class="far fa-star"></i>
                                <i class="far fa-star"></i>
                                <i class="far fa-star"></i>
                                <i class="far fa-star"></i>
                                <i class="far fa-star"></i>
                            @endif
						</div>
						<div class="total">
							@if(count($reviews) != 0)
							    ({{ OVERALL }} {{ $overall_rating }} {{ OUT_OF_5 }})
                            @else
                                ({{ OVERALL }} 0 {{ OUT_OF_5 }})
							@endif
						</div>
					</div>


					<div class="reviews" style="display:none;">

                        @if($reviews->isEmpty())
                        <span class="text-danger">{{ NO_REVIEW_FOUND }}</span>
                        @else
						@foreach($reviews as $item)
                            @if($item->agent_type=="Customer")
                                @php
                                    $u_detail = DB::table('users')->where('id',$item->agent_id)->first();
                                @endphp
                            @else
                                @php
                                    $u_detail = DB::table('admins')->where('id',$item->agent_id)->first();
                                @endphp
                            @endif
						<div class="row item">
							<div class="col-md-12 col-lg-2">
								<div class="photo">
									@if($u_detail->photo == '')
										<img src="{{ asset('uploads/user_photos/default_photo.jpg') }}" alt="">
									@else
										<img src="{{ asset('uploads/user_photos/'.$u_detail->photo) }}" alt="">
									@endif
								</div>
							</div>
							<div class="col-md-12 col-lg-10">
								<div class="name">
									{{ $u_detail->name }}
								</div>
								<div class="date-time">
									{{ \Carbon\Carbon::parse($u_detail->created_at)->format('d M, Y') }}
								</div>

                                <div class="score">
                                    @if($item->rating == 5)
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    @elseif($item->rating == 4.5)
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star-half-alt"></i>
                                    @elseif($item->rating == 4)
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="far fa-star"></i>
                                    @elseif($item->rating == 3.5)
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star-half-alt"></i>
                                        <i class="far fa-star"></i>
                                    @elseif($item->rating == 3)
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="far fa-star"></i>
                                        <i class="far fa-star"></i>
                                    @elseif($item->rating == 2.5)
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star-half-alt"></i>
                                        <i class="far fa-star"></i>
                                        <i class="far fa-star"></i>
                                    @elseif($item->rating == 2)
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="far fa-star"></i>
                                        <i class="far fa-star"></i>
                                        <i class="far fa-star"></i>
                                    @elseif($item->rating == 1.5)
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star-half-alt"></i>
                                        <i class="far fa-star"></i>
                                        <i class="far fa-star"></i>
                                        <i class="far fa-star"></i>
                                    @elseif($item->rating == 1)
                                        <i class="fas fa-star"></i>
                                        <i class="far fa-star"></i>
                                        <i class="far fa-star"></i>
                                        <i class="far fa-star"></i>
                                        <i class="far fa-star"></i>
                                    @endif
                                </div>
								<div class="comment">
									<p>
										{!! clean($item->review) !!}
									</p>
								</div>
							</div>
						</div>
						@endforeach

                        @endif

					</div>


					<div class="gap"></div>
					<div class="review-form" style="display:none;border-radius: 4px;padding: 10px; box-shadow: 3px 3px 5px rgba(0, 0, 0, 0.3);">
					<h2>{{ WRITE_A_REVIEW }}</h2>

						@if($current_auth_user_id == 0)

						<a href="{{ route('customer_login') }}" class="login-to-review">{{ LOGIN_TO_REVIEW }}</a>
						@elseif($current_auth_user_id == $agent_detail->id)
                            <div class="text-danger">{{ OWN_PRODUCT_REVIEW_STOP }}</div>

                        @elseif($already_given == 1)
                            <div class="text-danger">{{ ALREADY_GIVEN_REVIEW_STOP }}</div>
                        @else
						<form action="{{ route('customer_review') }}" method="post">
							@csrf
							<input type="hidden" name="listing_id" value="{{ $detail->id }}">
							<div class="form-group">
								<label for="">{{ YOUR_RATING }}</label>
								<select name="rating" class="form-control">
									<option value="1">{{ STAR_1 }}</option>
									<option value="2">{{ STAR_2 }}</option>
									<option value="3">{{ STAR_3 }}</option>
									<option value="4">{{ STAR_4 }}</option>
									<option value="5">{{ STAR_5 }}</option>
								</select>
							</div>
							<div class="form-group">
								<label for="">{{ YOUR_REVIEW }}</label>
								<textarea name="review" class="form-control h-100" cols="30" rows="10"></textarea>
							</div>
							<button type="submit" class="btn btn-primary">{{ SUBMIT }}</button>
						</form>
						@endif


					</div>


				</div>
			</div>
			<div class="col-lg-4 col-md-12 col-sm-12">
				<div class="listing-sidebar" >
 
                                    <div class="ls-widget" style="box-shadow: 3px 3px 5px rgba(0, 0, 0, 0.3);"> 
						<h2>
                                                    R$ {{ number_format($detail->listing_price,0,'','.') }}
                                                <a class="elementor-button elementor-button-link elementor-size-lg btn btn-danger float-right btn-sm" onclick="simular_parcelas();">
    <span class="elementor-button-content-wrapper">
        <span class="elementor-button-text">ver parcelas <img src="{{ asset('images/logo-itau.png') }}" style="width:24px;" /></span>
    </span>
</a>
                                                </h2>
						<div class="agent">
							
                                                            
                                                             <form action="{{ route('front_listing_detail_send_message') }}" class="form-contato" method="post">
                                        @csrf
                                        <input type="hidden" name="listing_name" value="{{ $detail->listing_name }}">
                                        <input type="hidden" name="listing_slug" value="{{ $detail->listing_slug }}">
                                        <input type="hidden" name="agent_name" value="{{ $agent_detail->name }}">
                                        <input type="hidden" name="agent_email" value="{{ $agent_detail->email }}">
                                        <input type="hidden" name="id" value="{{ $detail->id }}">
                                        <div class="form-group" style="margin-bottom: .2rem;">
                                            <label for="" style="margin-bottom: .2rem;">{{ NAME }}</label> 
                                            <div>
                                                <input type="text" name="name" class="form-custom" placeholder="Digite o seu nome" required>
                                            </div>
                                        </div>
                                        <div class="form-group" style="margin-bottom: .2rem;">
                                            <label for="" style="margin-bottom: .2rem;">{{ EMAIL }}</label>
                                            <div>
                                                <input type="email" name="email" class="form-custom" placeholder="Digite seu e-mail" required>
                                            </div>
                                        </div>
                                        <div class="form-group" style="margin-bottom: .2rem;">
                                            <label for="" style="margin-bottom: .2rem;">{{ PHONE }}</label>
                                            <div>
                                                <input type="text" name="phone" class="form-custom" placeholder="Digite seu telefone">
                                            </div>
                                        </div>
                                        <div class="form-group" style="margin-bottom: .2rem;">
                                            <label for="" style="margin-bottom: .2rem;">{{ MESSAGE }}</label>
                                            <div>
                                                <textarea name="message" class="form-custom h-100" cols="30" rows="10" required>Olá, tenho interesse no veículo. Por favor entre em contato.</textarea>
                                            </div>
                                        </div>
                                        @if($g_setting->google_recaptcha_status == 'Show')
                                            <div class="form-group">
                                                <div class="g-recaptcha" data-sitekey="{{ $g_setting->google_recaptcha_site_key }}"></div>
                                            </div>
                                        @endif
                                        <div class="form-group">
                                            <div>
                                                <button type="submit" class="btn btn-dark btn-block">{{ SEND_MESSAGE }}</button>
                                            </div>
                                        </div>
                                    </form>
                                                            
						</div>
						


						

						
					</div>
                                    
					<div class="ls-widget" style="box-shadow: 3px 3px 5px rgba(0, 0, 0, 0.3);">
						<h2>{{ AGENT }}</h2>
						<div class="agent">
							<div class="photo">
								@if($agent_detail->photo == '')
									<img src="{{ asset('uploads/user_photos/default_photo.jpg') }}" alt="">
								@else
									<img src="{{ asset('uploads/user_photos/'.$agent_detail->photo) }}" alt="">
								@endif

							</div>
							<div class="text">
                                @if($detail->user_id == 0)
                                    @php $type = "admin"; @endphp
                                @else
                                    @php $type = "user"; @endphp
                                @endif
                                    <h3><a href="{{ route('front_listing_agent_detail',[$type,$agent_detail->id]) }}">{{ $agent_detail->name }}</a></h3>
								<h4>{{ POSTED_ON }} {{ \Carbon\Carbon::parse($detail->created_at)->format('d M, Y') }}</h4>
							</div>
						</div>
						<div class="agent-contact">
							<ul>
								@if($agent_detail->address!='' || $agent_detail->city!='' || $agent_detail->state!='' || $agent_detail->country!='')
								<li>
									<i class="fas fa-map-marker-alt"></i> {{ $agent_detail->address }} {{ $agent_detail->city }} {{ $agent_detail->country }}
								</li>
								@endif
								@if($agent_detail->phone!='')
								<li><i class="fas fa-phone-volume"></i> {{ $agent_detail->phone }}</li>
								@endif
								@if($agent_detail->email!='')
								<li><i class="fas fa-envelope"></i> {{ $agent_detail->email }}</li>
								@endif
								@if (empty($agent_detail->website) || $agent_detail->website === 'http://' || $agent_detail->website === 'https://')
                                                                
                                                                @else
                                                                <li><a href="{{ $agent_detail->website }}" target="_blank"><i class="fas fa-globe"></i> {{ $agent_detail->website }}</a></li>
								@endif
							</ul>
						</div>


						@if( ($agent_detail->facebook != '') ||
						($agent_detail->twitter != '') ||
						($agent_detail->linkedin != '') ||
						($agent_detail->pinterest != '') ||
						($agent_detail->youtube != '') )
						<div class="agent-social">
							<ul>
								@if($agent_detail->facebook != '')
								<li><a href="{{ $agent_detail->facebook }}" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
								@endif

								@if($agent_detail->twitter != '')
								<li><a href="{{ $agent_detail->twitter }}" target="_blank"><i class="fab fa-twitter"></i></a></li>
								@endif

								@if($agent_detail->linkedin != '')
								<li><a href="{{ $agent_detail->linkedin }}" target="_blank"><i class="fab fa-linkedin-in"></i></a></li>
								@endif

								@if($agent_detail->pinterest != '')
								<li><a href="{{ $agent_detail->pinterest }}" target="_blank"><i class="fab fa-pinterest-p"></i></a></li>
								@endif

								@if($agent_detail->youtube != '')
								<li><a href="{{ $agent_detail->youtube }}" target="_blank"><i class="fab fa-youtube"></i></a></li>
								@endif
							</ul>
						</div>
						@endif

						<a href="{{ route('front_listing_agent_detail',[$type,$agent_detail->id]) }}" class="btn btn-primary btn-block agent-view-profile">{{ VIEW_PROFILE }}</a>
					</div>

                    @if($detail->listing_oh_monday != '' || $detail->listing_oh_tuesday != '' || $detail->listing_oh_wednesday != '' || $detail->listing_oh_thursday != '' || $detail->listing_oh_friday != '' || $detail->listing_oh_saturday != '' || $detail->listing_oh_sunday != '')
					<div class="ls-widget" style="box-shadow: 3px 3px 5px rgba(0, 0, 0, 0.3);">
						<h2>{{ OPENING_HOUR }}</h2>
						<div class="openning-hour">
							<div class="table-responsive">
								<table class="table table-bordered">
									<tr>
										<td>{{ MONDAY }}</td>
										<td>{{ $detail->listing_oh_monday }}</td>
									</tr>
									<tr>
										<td>{{ TUESDAY }}</td>
										<td>{{ $detail->listing_oh_tuesday }}</td>
									</tr>
									<tr>
										<td>{{ WEDNESDAY }}</td>
										<td>{{ $detail->listing_oh_wednesday }}</td>
									</tr>
									<tr>
										<td>{{ THURSDAY }}</td>
										<td>{{ $detail->listing_oh_thursday }}</td>
									</tr>
									<tr>
										<td>{{ FRIDAY }}</td>
										<td>{{ $detail->listing_oh_friday }}</td>
									</tr>
									<tr>
										<td>{{ SATURDAY }}</td>
										<td>{{ $detail->listing_oh_saturday }}</td>
									</tr>
									<tr>
										<td>{{ SUNDAY }}</td>
										<td>{{ $detail->listing_oh_sunday }}</td>
									</tr>
								</table>
							</div>
						</div>
					</div>
                    @endif

					<div class="ls-widget" style="box-shadow: 3px 3px 5px rgba(0, 0, 0, 0.3);">
						<h2>{{ BRANDS }}</h2>
						<div class="category">
							<ul class="duas-colunas">
								@foreach($listing_brands as $row)
								<li><a href="{{ route('front_listing_brand_detail',$row->listing_brand_slug) }}"><i class="fas fa-angle-right"></i> {{ $row->listing_brand_name }}</a></li>
								@endforeach
							</ul>
						</div>
					</div>

					<div class="ls-widget" style="box-shadow: 3px 3px 5px rgba(0, 0, 0, 0.3);">
						<h2>{{ LOCATIONS }}</h2>
						<div class="category">
							<ul>
								@foreach($listing_locations as $row)
                                                                @if(!empty($row->listing_location_slug))
								<li><a href="{{ route('front_listing_location_detail',$row->listing_location_slug) }}"><i class="fas fa-angle-right"></i> {{ $row->listing_location_name }}</a></li>
                                                                @endif
								@endforeach
							</ul>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
</div>

            
            
@endsection
