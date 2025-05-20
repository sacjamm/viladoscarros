@if($listings)
@foreach ($listings as $listing)

            @if($listing->user_id !=0)
                @php
                    $t_data = \App\Models\PackagePurchase::where('user_id',$listing->user_id)->where('currently_active',1)->first();
                @endphp
                @if(isset($t_data->package_end_date) && $t_data->package_end_date < date('Y-m-d'))
                    @continue
                @endif
            @endif
            
            

            <div class="col-lg-6 col-md-12 wow fadeInUp">
                <div class="listing-item effect-item">
					<div class="photo image-effect">
                        <a href="{{ route('front_listing_detail',[$listing->id,$listing->listing_slug]) }}">
                            @if($listing->canal == 'dsautoestoque')
                                <img src="{{ $listing->listing_featured_photo }}" alt="">
                            @else
                                <img src="{{ asset('uploads/listing_featured_photos/'.$listing->listing_featured_photo) }}" alt="">
                            @endif
                            
                        </a>
                        <div class="brand">
                            <a href="{{ route('front_listing_brand_detail',$listing->rListingBrand->listing_brand_slug) }}">{{ $listing->rListingBrand->listing_brand_name }}</a>
                        </div>
                        <div class="wishlist">
                            <a href="{{ route('front_add_wishlist',$listing->id) }}"><i class="fas fa-heart"></i></a>
                        </div>
                        @if($listing->is_featured == 'Yes')
                            <div class="featured-text">{{ FEATURED }}</div>
                        @endif
                    </div>
                    <div class="text">

                        <div class="type-price">
                            <div class="type">
                                @if($listing->listing_type == 'Novo')
                                <div class="inner-new">
                                    {{ NEW_CAR }}
                                </div>
                                @else
                                <div class="inner-used">
                                    {{ USED_CAR }}
                                </div>
                                @endif
                            </div>
                            <div class="price">
                                @if(!session()->get('currency_symbol'))
                                    ${{ number_format($listing->listing_price) }}
                                @else
                                    {{ session()->get('currency_symbol') }}{{ number_format($listing->listing_price*session()->get('currency_value')) }}
                                @endif
                            </div>
                        </div>


                        <h3><a href="{{ route('front_listing_detail',[$listing->id,$listing->listing_slug]) }}">{{ $listing->listing_name }}</a></h3>
                        <div class="location">
                            <a href="{{ route('front_listing_location_detail',$listing->rListingLocation->listing_location_slug) }}"><i class="fas fa-map-marker-alt"></i> {{ $listing->rListingLocation->listing_location_name }}</a>
                        </div>

                        @php
                            $count=0;
                            $total_number = 0;
                            $overall_rating = 0;
                            $reviews = \App\Models\Review::where('listing_id',$listing->id)->get();
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
                            <span>({{ $count }} {{ REVIEWS }})</span>
                        </div>

                        

                    </div>
                </div>
            </div>
        @endforeach
        @else
        <p style="text-align: center;font-size:20px;">Nada encontrado</p>
        @endif
</div>
<div id="load-more" class="text-center">
    <p>Carregando mais...</p>
</div>
{{ $listings->links('front.custom_paginator') }}