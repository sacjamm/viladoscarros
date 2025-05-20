@extends('front.app_front')

@section('content')

    <div class="page-banner" style="background-image: url('{{ asset('uploads/page_banners/'.$listing_location_page_data->banner) }}')">
        <div class="page-banner-bg"></div>
        <h1>{{ $listing_location_page_data->name }}</h1>
        <nav>
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ HOME }}</a></li>
                <li class="breadcrumb-item active">{{ $listing_location_page_data->name }}</li>
            </ol>
        </nav>
    </div>

    <div class="page-content popular-city">
        <div class="container">
            <div class="row">
                @foreach($orderwise_listing_locations as $row)
                    @if($row->total == '')
                        @php $row->total = 0; @endphp
                    @endif
                    @if(!empty($row->listing_location_slug))
                    <div class="col-lg-3 col-md-6 col-sm-6 wow fadeInUp">
                        <div class="popular-city-item effect-item">
                            <div class="photo image-effect">
                                <img src="{{ asset('uploads/listing_location_photos/'.$row->listing_location_photo) }}" alt="">
                            </div>
                            <div class="text">
                                <h4>{{ $row->listing_location_name }}</h4>
                                @php
                                    $qty = 0;
                                    $locationProperties = App\Models\Listing::where('listing_location_id', $row->id)->where('listing_status','Active')->get();
                                    foreach ($locationProperties as $key => $brandListing) {
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
                            <a href="{{ route('front_listing_location_detail',$row->listing_location_slug) }}"></a>
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

@endsection
