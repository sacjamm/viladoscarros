@extends('front.app_front')

@section('content')

<div class="page-banner" style="background-image: url('{{ asset('uploads/page_banners/'.$listing_brand_page_data->banner) }}')">
    <div class="page-banner-bg"></div>
    <h1>{{ LISTING_BRAND_COLON }} {{ $listing_brand_detail->listing_brand_name }}</h1>
    <nav>
        <ol class="breadcrumb justify-content-center">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ HOME }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('front_listing_brand_all') }}">{{ $listing_brand_page_data->name }}</a></li>
            <li class="breadcrumb-item active">{{ $listing_brand_detail->listing_brand_name }}</li>
        </ol>
    </nav>
</div>

<div class="page-content">
    <div class="container">
        <div class="row listing pt_0 pb_0">

            @if($listing_items->isEmpty())
                <div class="text-danger">
                    {{ NO_RESULT_FOUND }}
                </div>
            @else
            @foreach($listing_items as $row)

            @if($row->user_id !=0)
                @php
                    $t_data = \App\Models\PackagePurchase::where('user_id',$row->user_id)->where('currently_active',1)->first();
                @endphp
                @if(isset($t_data->package_end_date) && $t_data->package_end_date < date('Y-m-d'))
                    @continue
                @endif
            @endif
            
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
            <div class="col-lg-3 col-md-6 col-sm-12 wow fadeInUp">
                <div class="listing-item effect-item">
					<div class="photo image-effect">
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

                <div class="col-md-12">
                    {{ $listing_items->links() }}
                </div>

            @endif

        </div>
    </div>
</div>

@endsection
