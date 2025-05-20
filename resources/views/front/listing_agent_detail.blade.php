@extends('front.app_front')

@section('content')

@if($agent_detail->banner == '')
	@php $banner = 'default_banner.jpg'; @endphp
@else
	@php $banner = $agent_detail->banner; @endphp
@endif

<div class="agent-banner" style="background-image: url('{{ asset('uploads/user_photos/'.$banner) }}');">
	<div class="bg"></div>
	<div class="container">
		<div class="row">
			<div class="col-lg-6 col-md-12">
				<div class="agent">
					<div class="photo">
						@if($agent_detail->photo == '')
							<img style="border: 1px solid #333;" src="{{ asset('uploads/user_photos/default_photo.jpg') }}" alt="">
						@else
							<img style="border: 1px solid #333;" src="{{ asset('uploads/user_photos/'.$agent_detail->photo) }}" alt="">
						@endif
					</div>
					<div class="text">
						<h3>{{ $agent_detail->name }}</h3>
						<h4>{{ REGISTERED_ON }} {{ \Carbon\Carbon::parse($agent_detail->created_at)->format('d M, Y') }}</h4>
					</div>
				</div>
                            <div class="agent" style="margin-top: 25px;">
                                
                                )
    @php
        $isFollowing = auth()->check() ? auth()->user()->isFollowing($agent_detail->id) : false;
    @endphp

    <button class="btn btn-follow animated fadeInUpBig {{ $isFollowing ? 'btn-following' : '' }}" onclick="follow({{ $agent_detail->id }},this);">
                                    @if($isFollowing)
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-plus">
                                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="8.5" cy="7" r="4"></circle>
                                        <polyline points="17 11 19 13 23 9"></polyline>
                                    </svg>
                                    @else
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-plus">
                                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="8.5" cy="7" r="4"></circle>
                                        <line x1="20" y1="8" x2="20" y2="14"></line>
                                        <line x1="23" y1="11" x2="17" y2="11"></line>
                                    </svg> 
                                    @endif
                                    <span>{{ $isFollowing ? 'Seguindo' : 'Seguir' }}</span>
                                </button>

                                
                                
                                
                            </div>

				@if( ($agent_detail->facebook != '') ||
				($agent_detail->twitter != '') ||
				($agent_detail->linkedin != '') ||
				($agent_detail->pinterest != '') ||
				($agent_detail->youtube != '') )
				<div class="social">
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
			</div>
			<div class="col-lg-6 col-md-12">
				<div class="contact">
					@if($agent_detail->address != '')
					<div class="item"><i class="fas fa-map-marker-alt"></i> {{ $agent_detail->address }}</div>
					@endif

					@if($agent_detail->phone != '')
					<div class="item"><i class="fas fa-phone-volume"></i> {{ $agent_detail->phone }}</div>
					@endif

					@if($agent_detail->email != '')
					<div class="item"><i class="fas fa-envelope"></i> {{ $agent_detail->email }}</div>
					@endif

					@if($agent_detail->website != '')
					<div class="item"><i class="fas fa-globe"></i> {{ $agent_detail->website }}</div>
					@endif
				</div>
			</div>
		</div>
	</div>
</div>

<div class="page-content" style="padding-top:0px;">
	<div class="container">
		<div class="row listing pb_0">

			@foreach($all_listings as $row)
                        
                        @if(isset($user_data->id)) 
            @php 
            $wishlist = \App\Models\Wishlist::where('user_id', $user_data->id)->where('listing_id',$row->id)->first();
            @endphp
            @endif

            @if($row->user_id == 0)
                                    @php $type = "admin"; @endphp
                                @else
                                    @php $type = "user"; @endphp
                                @endif
			<div class="col-lg-3 col-md-6 col-sm-12">
				<div class="listing-item">
					<div class="photo">
						<a href="{{ route('front_listing_detail',[$row->id,$row->listing_slug]) }}">
@if($row->canal == 'dsautoestoque')
                                
                                @if ($row->listing_featured_photo == 'images/sem-veiculo.jpg')
                <img src="{{ asset('images/sem-veiculo.jpg') }}" alt="">
            @else
                @if ($row->listing_image_alterada_admin == 1)
                    <img src="{{ asset('uploads/listing_featured_photos/' . $row->listing_featured_photo) }}" alt="">
                @else
                    <img src="{{ $row->listing_featured_photo }}" alt="">
                @endif
            @endif
                            @else
                                <img src="{{ asset('uploads/listing_featured_photos/'.$row->listing_featured_photo) }}" alt="">
                            @endif

</a>
						<div class="brand">
							<a href="{{ route('front_listing_brand_detail',$row->rListingBrand->listing_brand_slug) }}">{{ $row->rListingBrand->listing_brand_name }}</a>
						</div>
						<div class="model">
                            <a href="javascript:void(0);">{{ $row->vehicleModel }}</a>
                        </div>
                                              <div class="cambio" style="position: absolute;
    top: 10px;
    left: 10px;">
                            <a href="#" style="color: #fff; 
    padding: 2px 8px;
    font-size: 14px;
    border-radius: 6px;background: #000000;">Câmbio: {{ $row->listing_transmission }}</a>
                        </div>
                        <div class="wishlist">
                            <a href="{{ route('front_add_wishlist',$row->id) }}">
                                @if(!empty($wishlist->id))
                                <i class="fas fa-heart" style="color:red;"></i>
                                @else
                                 <i class="fas fa-heart"></i>
                                @endif
                            </a>
                        </div>
                        @if($row->is_featured == 'Yes')
                        <div class="featured-text">{{ FEATURED }}</div>
                        @endif
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
                            <div class="price" style="font-size: 16px;">
                                @if(!session()->get('currency_symbol'))
                                    R${{ number_format($row->listing_price,0,'','.') }}
                                @else
                                    {{ session()->get('currency_symbol') }}{{ number_format($row->listing_price*session()->get('currency_value'),0,'','.') }}
                                @endif
                            </div>
                        </div>
                        
						<h3 style="font-size: 13px;"><a href="{{ route('front_listing_detail',[$row->id,$row->listing_slug]) }}">{{ $row->listing_name }}</a></h3>
						<div class="location">
							<a href="{{ route('front_listing_location_detail',$row->rListingLocation->listing_location_slug) }}"><i class="fas fa-map-marker-alt"></i> {{ $row->rListingLocation->listing_location_name }}</a>
						</div>

                        <div class="location">
                            <span class="float-left" style="margin:5px 0 15px 0;">{{ $row->anofabricacao }}/{{ $row->vehicleModelYear }}</span>
                            <span class="float-right" style="margin:5px 0 15px 0;">{{ number_format($row->listing_mileage,0,'','.') }} Km</span>
                        </div>

                      <div class="btn-group" role="group" aria-label="Basic example" style="width:100%;">
<a type="button" href="{{ route('front_listing_agent_detail',[$type,$row->user_id]) }}" class="btn btn-dark btn-sm">ver loja</a>
<a type="button" href="{{ route('front_listing_detail',[$row->id,$row->listing_slug]) }}/#parcelas" class="btn btn-danger btn-sm">
    <img src="{{ asset('images/logo-itau.png') }}" style="width:18px;margin-right:3px;">parcelas
</a>
                      
                      </div>
<div class="clear clearfix"></div>    

					</div>
				</div>
			</div>
			@endforeach


		</div>
	</div>
</div>

@endsection
