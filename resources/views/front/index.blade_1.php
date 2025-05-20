@extends('front.app_front')

@section('content')

<!--<div class="search-section" style="background-image:url('{{ asset('uploads/site_photos/'.$page_home_items->search_background) }}');">
	<div class="bg"></div>
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<h1>{{ $page_home_items->search_heading }}</h1>
				<p>
					{{ $page_home_items->search_text }}
				</p>
				<div class="box">
					<form action="{{ url('search-listing') }}" method="POST">
                        @csrf
						<div class="input-group input-box mb-3">
							<input type="text" class="form-control" placeholder="{{ FIND_ANYTHING }}" name="text">
							<select name="location[]" class="form-control select2">
								<option value="">{{ SELECT_LOCATION }}</option>
								@foreach($listing_locations as $row)
									<option value="{{ $row->id }}">{{ $row->listing_location_name }}</option>
								@endforeach
							</select>
							<select name="brand[]" class="form-control select2">
								<option value="">{{ SELECT_BRAND }}</option>
								@foreach($listing_brands as $row)
									<option value="{{ $row->id }}">{{ $row->listing_brand_name }}</option>
								@endforeach
							</select>
							<select name="listing_type" class="form-control select2">
								<option value="">{{ SELECT_TYPE }}</option>
								<option value="Novo">{{ NEW_CAR }}</option>
								<option value="Usado">{{ USED_CAR }}</option>
							</select>
							<div class="input-group-append">
								<button type="submit"><i class="fa fa-search"></i> {{ SEARCH }}</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>-->
<style>
    :root {
    --search-form-color: #3D3A34;
}

.search-form {
    max-width: 560px;
    font-family: 'Itau Text';
}

.flex-location {
    display: flex;
    margin-top: 20px;
    gap: 4px;
}

.search-form__options {
    width: 415px;
    border-radius: 8px;
    background: var(--search-form-color);
    font-family: 'Itau Display';
    font-size: 12px;
    display: flex;
    padding-top: var(--ids_spacing_1x);
    padding-left: var(--ids_spacing_3x);
    padding-right: var(--ids_spacing_3x);
    padding-bottom: var(--ids_spacing_4x);
}

@media screen and (max-width: 1023px){
    .search-form__input-container {
        margin-top: -12px;
        width: 100%;
    }
}

@media screen and (min-width: 1024px){
    .search-form__input-container {
        margin-top: -12px;
        width: 563px;
        max-width: 563px;
    }
}

.search-form__input-container, .search-form__location-input-container {
    display: flex;
    align-items: center;
    background: #FAF7F5;
    border-radius: 8px;
    font-family: 'Itau Text';
    border: 1px solid #89837F;
    font-size: 16px;
    padding: var(--ids_spacing_3x);
}

.search-form__input-icon-container, .search-form__location-input-icon-container {
    margin: var(--ids_spacing_1x) var(--ids_spacing_3x) 0px var(--ids_spacing_2x);
    text-align: center;
}

.search-form .search-form__input {
    border: none;
    width: 100%;
    display: block;
    background-color: #FAF7F5;
}

.search-form__submit-button {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 16px;
    width: 56px;
    height: 56px;
    background: #EC7000;
    border-radius: 8px;
    border: none;
}

.checkbox:after {
    border: solid 1px var(--color_white);
    background-color: var(--color_white);
    width: 24px;
    height: 24px;
    border-radius: 5px;
}

.checkbox:not(:checked):before {
    background: var(--search-form-color);
    border: none;
}

.checkbox:not(:checked):after {
    background: var(--search-form-color);
}

.checkbox:checked:before {
    border-color: var(--search-form-color);
}

.checkbox:before {
    left: 4px;
    top: 5px;
    transition: none;
}

.label--checkbox {
    display: flex;
    align-items: center;
}

.label--checkbox label {
    margin-left: var(--ids_spacing_2x);
}

.label--checkbox .checkbox {
    top: -3px;
}

.box-location {
    float: left;
    border-radius: 5px;
    color: #fff;
    padding-left: var(--ids_spacing_1x);
    margin-right: 15px;
    font-weight: 700;
    min-width: 200px;
    min-height: 52px;
    font-family: 'Itau Text';
}

.box-location span {
    cursor: pointer;
}

.box-location span:before {
    content: none;
}

.search-form .tt-menu {
    padding-top: var(--ids_spacing_1x);
    border-top-left-radius: 0px;
    border-top-right-radius: 0px;
    top: 40px !important;
    left: -52px !important;
    width: 560px;
    max-width: calc(100vw - 32px);
    box-shadow: 0rem 0rem 0.0625rem 1px rgba(0, 0, 0, 0.1);
}

.search-form .search-form__location_container .tt-menu {
    top: 30px !important;
}

.search-form .tt-menu::-webkit-scrollbar-track {
    margin-top: var(--ids_spacing_1x);;
}

.search-form .tt-suggestion {
    border-top: solid 0.5px var(--color_sand_30);
    padding: var(--ids_spacing_5x);
    cursor: pointer;
    background: #fff;
}

.search-form .tt-suggestion:hover {
    background: var(--search-form-color);
}

.arrow-icon {
    display: flex;
    position: absolute;
    right: 24px;
}

.container.container--smaller {
    z-index: 2;
}

.toggleLocationLoading {
    display: flex;
    line-height: 1;
}

.toggleLocationResult {
    line-height: 1;
}

.toggleLocationLoading .fa-map-marker {
    color: #fff;
}

.toggleLocationInput {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.searchIn {
    clear: both;
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.search-form__search_in_label {
    font-family: 'Itau Display';
    font-weight: 700;
    color: var(--color_white);
    margin: var(--ids_spacing_6x) 0px;
}

.search-form__search_in_options {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.search-form__custom-radio {
    display: none;
    color: var(--color_white);
    position: relative;
    padding: 0px var(--ids_spacing_6x) var(--ids_spacing_6x) var(--ids_spacing_6x);
    cursor: pointer;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}

.search-form__custom-radio input {
    position: absolute;
    opacity: 0;
    cursor: pointer;
}

.search-form__custom-radio .checkmark {
    position: absolute;
    top: 0;
    left: 0;
    height: 16px;
    width: 16px;
    border-radius: 50%;
    border: solid 1px var(--color_white);
}

.search-form__custom-radio .checkmark:after {
    content: "";
    position: absolute;
    display: none;
}

.search-form__custom-radio input:checked ~ .checkmark:after {
    display: block;
}

.search-form__custom-radio .checkmark:after {
    top: 2px;
    left: 2px;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: var(--color_white);
}


@media screen and (max-width: 767px) {
    .search-form__options {
        width: 100%;
    }

    .search-form__search_in_options {
        flex-direction: column;
    }

    .search-form__input, .search-form .tt-menu {
        font-size: 14px;
    }
}
</style>

<div class="search-section">
<div class="swiper-container mySwiper">
  <div class="swiper-wrapper">
    <div class="swiper-slide">
    <img src="{{ asset('uploads/site_photos/'.$page_home_items->search_background) }}" />
    </div>
  </div>
  <!-- Botões de navegação -->
  <div class="swiper-button-next"></div>
  <div class="swiper-button-prev"></div>
</div>
    
    <form id="buscaForm" class="form search-form" method="GET" action="/ache/listaanuncios.jsp" autocomplete="off" novalidate="novalidate" data-gtm-form-interact-id="0">
    <input type="hidden" name="bid" value="0">
    <input type="hidden" name="opcaocidade" value="1">
    <input type="hidden" name="foa" value="1">
    <input id="modelospellchecker" type="hidden" name="modelospellchecker" value="">

    <div class="search-form__options">
        <label class="label--checkbox">
            <input id="anunciosNovos" name="anunciosNovos" type="checkbox" class="checkbox valid" value="1" checked="" aria-invalid="false">
            <label for="anunciosNovos" style="white-space: nowrap">0 km</label>
        </label>
        <label class="label--checkbox">
            <input id="anunciosUsados" name="anunciosUsados" type="checkbox" class="checkbox" value="1" checked="">
            <label for="anunciosUsados">usados</label>
        </label>
        <label class="label--checkbox">
            <input id="financiamento" name="financiamento" type="checkbox" class="checkbox valid" value="true" data-gtm-form-interact-field-id="0" aria-invalid="false">
            <label for="financiamento">apenas com financiamento</label>
        </label>
    </div>

    <div class="search-form__input-container">
        <div class="search-form__input-icon-container">
            <img src="../../../ache/imagens/icons/search-itau-grey.svg" alt="Ãcone de lupa">
        </div>

        <span class="twitter-typeahead" style="position: relative; display: inline-block;"><input class="search-form__input tt-hint" type="text" autocomplete="off" tabindex="-1" readonly="" spellcheck="false" style="position: absolute; top: 0px; left: 0px; border-color: transparent; box-shadow: none; opacity: 1; background: none 0% 0% / auto repeat scroll padding-box border-box rgb(250, 247, 245);" dir="ltr"><input class="search-form__input tt-input" type="text" id="modelo" name="modeloaberto" autocomplete="off" placeholder="Busque por marca e modelo" tabindex="1" spellcheck="false" dir="auto" style="position: relative; vertical-align: top; background-color: transparent;"><pre aria-hidden="true" style="position: absolute; visibility: hidden; white-space: pre; font-family: &quot;Itau Text&quot;; font-size: 16px; font-style: normal; font-variant: normal; font-weight: 400; word-spacing: 0px; letter-spacing: 0px; text-indent: 0px; text-rendering: auto; text-transform: none;"></pre><div class="tt-menu" style="position: absolute; top: 100%; left: 0px; z-index: 100; display: none;"><div class="tt-dataset tt-dataset-0"></div></div></span>
        <button class="search-form__submit-button">
            <img src="../../../ache/imagens/icons/search-itau-white.svg" alt="Ãcone de lupa">
        </button>
    </div>

    <div class="">
        <div class="flex-location">
            <div class="toggleLocationLoading" style="display:block;">
                <span class="icones icone-alvo">
                    <i class="fa fa-map-marker" aria-hidden="true"></i>
                </span>
                <span class="localizacao_atual color-white" style="display: none;">
                    <img src="https://img0.icarros.com/comum/v1/images/img-lazy-loading.gif" class="imglazy pull-left m-right-xs" style="width: 15px;">aguardando localização...
                </span>
            </div>
            <div class="toggleLocationResult">
                <div class="box-location">
                    <span id="cidadeAbertoTexto">Localização atual</span>
                </div>
            </div>
        </div>
    </div>
<input type="hidden" name="locationSop" value="geo_0|n12p846544|n38p4744683.1_-esc_2.1_-rai_50.1_"></form>
</div>

@if($page_home_items->listing_status == 'Show')
<div class="listing">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="heading">
					<h2>{{ $page_home_items->listing_heading }}</h2>
					<h3>{{ $page_home_items->listing_subheading }}</h3>
				</div>
			</div>
		</div>
		<div class="row">
			@php
			$arr_max = array();
			for($j=0;$j<$page_home_items->listing_total;$j++) {
				$arr_max[] = 3*$j+1;
			}
			@endphp
			@php $i=0; @endphp
			@foreach($listings as $row)
			@php $i++; @endphp
			@if($i>$page_home_items->listing_total)
				@break;
			@endif
			@if($i%3==0)
				@php $fade_val = 'fadeInRight' @endphp
			@elseif(in_array($i,$arr_max))
				@php $fade_val = 'fadeInLeft' @endphp
			@else
				@php $fade_val = 'fadeInUp' @endphp
			@endif
			<div class="col-lg-3 col-md-6 col-sm-12 wow {{ $fade_val }}">
				<div class="listing-item effect-item">
					<div class="photo image-effect">
						<a href="{{ route('front_listing_detail',[$row->id,$row->listing_slug]) }}">
                                                   
                                                    @if(!empty($row->canal) && $row->canal == 'dsautoestoque')
                                                        <img src="{{ asset($row->listing_featured_photo) }}" alt="">
                                                    @else
                                                        
                                                        <img src="{{ asset('uploads/listing_featured_photos/'.$row->listing_featured_photo) }}" alt="">
                                                    @endif
                                                </a>
						<div class="brand">
							<a href="{{ route('front_listing_brand_detail',$row->rListingBrand->listing_brand_slug) }}">{{ $row->rListingBrand->listing_brand_name }}</a>
						</div>
                                           <div class="cambio" style="position: absolute;
    top: 10px;
    left: 10px;">
                            <a href="#" style="color: #fff;
    padding: 2px 8px;
    font-size: 14px;
    border-radius: 6px;background: #000000;">Câmbio: {{ $row->listing_transmission }}</a>
                        </div>
                                           
                                            @if(isset($user_data->id)) 
    @php
        $wishlist = \App\Models\Wishlist::where('user_id', $user_data->id)->where('listing_id', $row->id)->first();
    @endphp
@endif
						<div class="wishlist">
							<a href="{{ route('front_add_wishlist',$row->id) }}">
                                                           @if(!empty($wishlist->id))
                                <i class="fas fa-heart" style="color:red;"></i>
                                @else
                                 <i class="fas fa-heart"></i>
                                @endif
                                                        </a>
						</div>
						<div class="featured-text">{{ FEATURED }}</div>
					</div>
					<div class="text">

						<div class="type-price">
							<div class="type">
								@if($row->listing_type == 'Novo')
                                <div class="inner-new">
                                   {{ $row->listing_type }}
                                </div>
                                @else
                                <div class="inner-used">
                                   {{ $row->listing_type }}
                                </div>
                                @endif
							</div>
							<div class="price" style="font-size: 20px;">
								@if(!session()->get('currency_symbol'))
									R${{ number_format($row->listing_price,0,',','.') }}
								@else
									{{ session()->get('currency_symbol') }}{{ number_format($row->listing_price*session()->get('currency_value'),0,',','.') }}
								@endif
							</div>
						</div>

						<h3 style="font-size: 16px;"><a href="{{ route('front_listing_detail',[$row->id,$row->listing_slug]) }}">{{ $row->listing_name }}</a></h3>
						<div class="location">
							<i class="fas fa-map-marker-alt"></i> {{ $row->rListingLocation->listing_location_name }}
						</div>

						@php
							$count=0;
							$total_number = 0;
							$overall_rating = 0;
							$reviews = \App\Models\Review::where('listing_id',$row->id)->get();
						@endphp

						@if($reviews->isEmpty())

						@else

						@foreach($reviews as $item)
							@php
								$count++;
								$total_number = $total_number + $item->rating;
							@endphp
						@endforeach

						@php
							$overall_rating = $total_number/$count;
						@endphp

						@if($overall_rating>0 && $overall_rating<=1)
							@php $overall_rating = 1; @endphp

						@elseif($overall_rating>1 && $overall_rating<=1.5)
							@php $overall_rating = 1.5; @endphp

						@elseif($overall_rating>1.5 && $overall_rating<=2)
							@php $overall_rating = 2; @endphp

						@elseif($overall_rating>2 && $overall_rating<=2.5)
							@php $overall_rating = 2.5; @endphp

						@elseif($overall_rating>2.5 && $overall_rating<=3)
							@php $overall_rating = 3; @endphp

						@elseif($overall_rating>3 && $overall_rating<=3.5)
							@php $overall_rating = 3.5; @endphp

						@elseif($overall_rating>3.5 && $overall_rating<=4)
							@php $overall_rating = 4; @endphp

						@elseif($overall_rating>4 && $overall_rating<=4.5)
							@php $overall_rating = 4.5; @endphp

						@elseif($overall_rating>4.5 && $overall_rating<=5)
							@php $overall_rating = 5; @endphp

						@endif

						@endif

						<div class="review" style="display: none;">
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
							<span>({{ $count }} {{ REVIEWS }})</span> 
						
                        </div>
                                                <a href="{{ route('front_listing_detail',[$row->id,$row->listing_slug]) }}/#parcelas" class="btn btn-outline-dark btn-sm right float-right btn-block">Ver Parcelas</a>
<div class="clear clearfix"></div>
					</div>
				</div>
			</div>
			@endforeach				
		</div>
            @if($page_listing_item->status == 'Show')
							<a href="{{ url('listing-result') }}" class="btn btn-dark btn-block">VER TODOS OS VEÍCULOS</a>
                        @endif
	</div>
</div>
@endif

@if($adv_home_data->above_brand_status == 'Show')
<div class="ad-section">
	<div class="container">
		<div class="row">
			<div class="col-md-6 col-sm-12 wow fadeInUp">
				<div class="inner">
					@if($adv_home_data->above_brand_1_url == '')
						<img src="{{ asset('uploads/advertisements/'.$adv_home_data->above_brand_1) }}" alt="">
					@else
						<a href="{{ $adv_home_data->above_brand_1_url }}" target="_blank"><img src="{{ asset('uploads/advertisements/'.$adv_home_data->above_brand_1) }}" alt=""></a>
					@endif
				</div>
			</div>
			<div class="col-md-6 col-sm-12 wow fadeInUp">
				<div class="inner">
					@if($adv_home_data->above_brand_2_url == '')
						<img src="{{ asset('uploads/advertisements/'.$adv_home_data->above_brand_2) }}" alt="">
					@else
						<a href="{{ $adv_home_data->above_brand_2_url }}" target="_blank"><img src="{{ asset('uploads/advertisements/'.$adv_home_data->above_brand_2) }}" alt=""></a>
					@endif
				</div>
			</div>
		</div>
	</div>
</div>
@endif


@if($page_home_items->brand_status == 'Show')
<div class="popular-city">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="heading">
					<h2>{{ $page_home_items->brand_heading }}</h2>
					<h3>{{ $page_home_items->brand_subheading }}</h3>
				</div>
			</div>
		</div>
		<div class="row">
			@php $i=0; @endphp
			@foreach($orderwise_listing_brands as $row)
                @php $i++; @endphp
                @if($i> $page_home_items->brand_total)
                    @break;
                @endif
				@if($row->total == '')
        			@php $row->total = 0; @endphp
        		@endif
				<div class="col-lg-3 col-md-6 col-sm-6 wow fadeInUp">
					<div class="popular-city-item effect-item">
						<div class="photo image-effect">                                                                                                     
                                                                                                       
                                                    @php
    $brandListingAlisson = App\Models\Listing::where('listing_brand_id', $row->id)
        ->where('listing_status', 'Active')
        ->first();
@endphp

@if($brandListingAlisson && $brandListingAlisson->canal == 'dsautoestoque')
    <img src="{{ asset('images/'.$row->listing_brand_slug.'.jpg') }}" alt="">
@else
    <img src="{{ asset('uploads/listing_brand_photos/'.$row->listing_brand_photo) }}" alt="">
@endif
						</div>				
						<div class="text">
							<h4>{{ $row->listing_brand_name }}</h4>
							@php
                                $qty = 0;
                                $brandListings = App\Models\Listing::where('listing_brand_id', $row->id)->where('listing_status','Active')->get();
                                foreach ($brandListings as $key => $brandListing) {
                                    if($brandListing->user_id != 0){
                                        $activePackage = App\Models\PackagePurchase::where('user_id',$brandListing->user_id)->where('currently_active',1)->first();
                                        if(isset($activePackage->package_end_date) && $activePackage->package_end_date >= date('Y-m-d')){
                                            $qty += 1;
                                        }
                                    }else{
                                        $qty += 1;
                                    }
                                }
                            @endphp
							<p>{{ $key }} {{ ITEMS }}</p>
						</div>
						<a href="{{ route('front_listing_brand_detail',$row->listing_brand_slug) }}"></a>
					</div>
				</div>
			@endforeach
		</div>
	</div>
</div>
@endif


@if($adv_home_data->above_featured_listing_status == 'Show')
<div class="ad-section">
	<div class="container">
		<div class="row">
			<div class="col-md-6 col-sm-12 wow fadeInUp">
				<div class="inner">
					@if($adv_home_data->above_featured_listing_1_url == '')
						<img src="{{ asset('uploads/advertisements/'.$adv_home_data->above_featured_listing_1) }}" alt="">
					@else
						<a href="{{ $adv_home_data->above_featured_listing_1_url }}" target="_blank"><img src="{{ asset('uploads/advertisements/'.$adv_home_data->above_featured_listing_1) }}" alt=""></a>
					@endif
				</div>
			</div>
			<div class="col-md-6 col-sm-12 wow fadeInUp">
				<div class="inner">
					@if($adv_home_data->above_featured_listing_2_url == '')
						<img src="{{ asset('uploads/advertisements/'.$adv_home_data->above_featured_listing_2) }}" alt="">
					@else
						<a href="{{ $adv_home_data->above_featured_listing_2_url }}" target="_blank"><img src="{{ asset('uploads/advertisements/'.$adv_home_data->above_featured_listing_2) }}" alt=""></a>
					@endif
				</div>
			</div>
		</div>
	</div>
</div>
@endif


@if($page_home_items->video_status == 'Show')
<div class="home-video" style="background-image: url({{ asset('uploads/site_photos/'.$page_home_items->video_background) }})">
    <div class="bg"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>{{ $page_home_items->video_heading }}</h2>
                <p>
					{!! clean(nl2br($page_home_items->video_text)) !!}
                </p>
                <div class="video-section">
                    <a class="video-button" href="http://www.youtube.com/watch?v={{ $page_home_items->video_youtube_id }}"><i class="far fa-play-circle"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>
@endif




@if($page_home_items->testimonial_status == 'Show')
<div class="testimonial" style="background-image:url('{{ asset('uploads/site_photos/'.$page_home_items->testimonial_background) }}');">
    <div class="testimonial-bg"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="heading">
                    <h2>{{ $page_home_items->testimonial_heading }}</h2>
					<h3>{{ $page_home_items->testimonial_subheading }}</h3>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="testimonial-carousel owl-carousel">
					@foreach($testimonials as $row)
					<div class="testimonial-item wow fadeInUp">
                        <div class="photo">
                            <img src="{{ asset('uploads/testimonials/'.$row->photo) }}" alt="">
                        </div>
                        <div class="text">
                            <p>
                                {!! clean(nl2br($row->comment)) !!}
                            </p>
                            <h3>{{ $row->name }}</h3>
                            <h4>{{ $row->designation }}</h4>
                        </div>
                    </div>
					@endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endif



@if($adv_home_data->above_location_status == 'Show')
<div class="ad-section">
	<div class="container">
		<div class="row">
			<div class="col-md-6 col-sm-12 wow fadeInUp">
				<div class="inner">
					@if($adv_home_data->above_location_1_url == '')
						<img src="{{ asset('uploads/advertisements/'.$adv_home_data->above_location_1) }}" alt="">
					@else
						<a href="{{ $adv_home_data->above_location_1_url }}" target="_blank"><img src="{{ asset('uploads/advertisements/'.$adv_home_data->above_location_1) }}" alt=""></a>
					@endif
				</div>
			</div>
			<div class="col-md-6 col-sm-12 wow fadeInUp">
				<div class="inner">
					@if($adv_home_data->above_location_2_url == '')
						<img src="{{ asset('uploads/advertisements/'.$adv_home_data->above_location_2) }}" alt="">
					@else
						<a href="{{ $adv_home_data->above_location_2_url }}" target="_blank"><img src="{{ asset('uploads/advertisements/'.$adv_home_data->above_location_2) }}" alt=""></a>
					@endif
				</div>
			</div>
		</div>
	</div>
</div>
@endif


@if($page_home_items->location_status == 'Show')
<div class="popular-city">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="heading">
					<h2>{{ $page_home_items->location_heading }}</h2>
					<h3>{{ $page_home_items->location_subheading }}</h3>
				</div>
			</div>
		</div>
		<div class="row">
			
			@php $i=0; @endphp
			@foreach($orderwise_listing_locations as $row)
				@php $i++; @endphp
				@if($i>$page_home_items->location_total)
					@break;
				@endif
				@if($row->total == '')
					@php $row->total = 0; @endphp
				@endif
				<div class="col-lg-3 col-md-6 col-sm-6 wow fadeInUp">
					<div class="popular-city-item effect-item">
						<div class="photo image-effect">
                                                    @if($row->listing_location_photo == 'images/sem-localizacao.png')
							<img src="{{ asset($row->listing_location_photo) }}" alt="">
							@else
                                                        <img src="{{ asset('uploads/listing_location_photos/'.$row->listing_location_photo) }}" alt="">
                                                        @endif
                                                </div>				
						<div class="text">
							<h4>{{ $row->listing_location_name }}</h4>
							@php
								$qty = 0;
								$locationListings = App\Models\Listing::where('listing_location_id', $row->id)->where('listing_status','Active')->get();
								foreach ($locationListings as $key => $brandListing) {
									if($brandListing->user_id != 0){
										$activePackage = App\Models\PackagePurchase::where('user_id',$brandListing->user_id)->where('currently_active',1)->first();
										if(isset($activePackage->package_end_date) && $activePackage->package_end_date >= date('Y-m-d')){
											$qty += 1;
										}
									}else{
										$qty += 1;
									}
								}
							@endphp
							<p>{{ $key }} {{ LISTINGS }}</p>
						</div>
						<a href="{{ route('front_listing_location_detail',$row->listing_location_slug) }}"></a>
					</div>
				</div>
			@endforeach
		</div>
	</div>
</div>
@endif

@endsection
