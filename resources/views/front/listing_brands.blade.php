@extends('front.app_front')

@section('content')

<div class="page-banner" style="background-image: url('{{ asset('uploads/page_banners/'.$listing_brand_page_data->banner) }}')">
    <div class="page-banner-bg"></div>
    <h1>{{ $listing_brand_page_data->name }}</h1>
    <nav>
        <ol class="breadcrumb justify-content-center">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ HOME }}</a></li>
            <li class="breadcrumb-item active">{{ $listing_brand_page_data->name }}</li>
        </ol>
    </nav>
</div>


<div class="page-content popular-city">
    <div class="container">
        <div class="row">
            @foreach($orderwise_listing_brands as $row)
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
                                $brandProperties = App\Models\Listing::where('listing_brand_id', $row->id)->where('listing_status','Active')->get();
                                foreach ($brandProperties as $key => $brandProperty) {
                                    if($brandProperty->user_id != 0){

$qty += 1;
                                    }else{
                                        $qty += 1;
                                    }
                                }
                            @endphp
                            <p>{{ $qty }} {{ ITEMS }}</p>
                        </div>
                        <a href="{{ route('front_listing_brand_detail',$row->listing_brand_slug) }}"></a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection