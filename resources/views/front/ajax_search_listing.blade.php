<script>
$(document).ready(function(){
    var quantidade_para_exibir = {{ $quantidade_para_exibir }};
    var total_registros = {{ $total_registros }};
    $('#quantidade_para_exibir').text('Total de '+total_registros+' registros encontrados');
});
</script>


@foreach ($listings as $listing)

            @if($listing->user_id !=0)
                @php
                    $t_data = \App\Models\PackagePurchase::where('user_id',$listing->user_id)->where('currently_active',1)->first();
                @endphp
                @if(isset($t_data->package_end_date) && $t_data->package_end_date < date('Y-m-d'))
                    @continue
                @endif
            @endif
            @if(isset($user_data->id)) 
            @php
            $wishlist = \App\Models\Wishlist::where('user_id', $user_data->id)->where('listing_id',$listing->id)->first();
            @endphp
            @endif
            

            @if($listing->user_id == 0)
                                    @php $type = "admin"; @endphp
                                @else
                                    @php $type = "user"; @endphp
                                @endif
                                
                               
            <div class="col-lg-4 col-md-12 wow fadeInUp ">
                <div class="listing-item effect-item">
					<div class="photo image-effect">
                        <a href="{{ route('front_listing_detail',[$listing->id,$listing->listing_slug]) }}"
                                     title="{{ $listing->listing_name }}">
                            @if($listing->canal == 'dsautoestoque')
                                
                                @if ($listing->listing_featured_photo == 'images/sem-veiculo.jpg')
                <img src="{{ asset('images/sem-veiculo.jpg') }}" alt="{{ asset('images/sem-veiculo.jpg') }}"
                                     title="{{ $listing->listing_name }}">
            @else
                @if ($listing->listing_image_alterada_admin == 1)
                    <img src="{{ asset('uploads/listing_featured_photos/' . $listing->listing_featured_photo) }}" alt="{{ asset('uploads/listing_featured_photos/' . $listing->listing_featured_photo) }}"
                                     title="{{ $listing->listing_name }}">
                @else
                    <img src="{{ $listing->listing_featured_photo }}" alt="{{ $listing->listing_featured_photo }}"
                                     title="{{ $listing->listing_name }}">
                @endif
            @endif
                            @else
                                <img src="{{ asset('uploads/listing_featured_photos/'.$listing->listing_featured_photo) }}" alt="{{ asset('uploads/listing_featured_photos/'.$listing->listing_featured_photo) }}"
                                     title="{{ $listing->listing_name }}">
                            @endif
                            
                        </a>
                        <div class="brand">
                            <a style="border:1px solid #fff;" href="{{ route('front_listing_brand_detail',$listing->rListingBrand->listing_brand_slug) }}">{{ $listing->rListingBrand->listing_brand_name }}</a>
                        </div>
                        <div class="model">
                            <a href="javascript:void(0);" style="border:1px solid #fff;">{{ $listing->vehicleModel }}</a>
                        </div>
                                            <div class="cambio" style="position: absolute;
    top: 10px;
    left: 10px;">
                            <a href="#" style="border:1px solid #fff;color: #fff; 
    padding: 2px 8px;
    font-size: 14px;
    border-radius: 6px;background: #000000;">Câmbio: {{ $listing->listing_transmission }}</a>
                        </div>
                        <div class="wishlist">
                            <a href="{{ route('front_add_wishlist',$listing->id) }}">
                                @if(!empty($wishlist->id))
                                <i class="fas fa-heart" style="color:red;"></i>
                                @else
                                 <i class="fas fa-heart"></i>
                                @endif
                            </a>
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
                                    {{ $listing->listing_type }}
                                </div>
                                @else
                                <div class="inner-used">
                                    {{ $listing->listing_type }}
                                </div>
                                @endif
                            </div>
                            <div class="price" style="font-size: 16px;">
                                @if(!session()->get('currency_symbol'))
                                    R${{ number_format($listing->listing_price,0,',','.') }}
                                @else
                                    {{ session()->get('currency_symbol') }}{{ number_format($listing->listing_price*session()->get('currency_value'),0,',','.') }}
                                @endif
                            </div>
                        </div>


                        <h3 style="font-size: 13px;"><a href="{{ route('front_listing_detail',[$listing->id,$listing->listing_slug]) }}">{{ $listing->listing_name }}</a></h3>
                        <div class="location">
                            <a href="{{ route('front_listing_location_detail',$listing->rListingLocation->listing_location_slug) }}">
                                <i class="fas fa-map-marker-alt"></i> {{ $listing->rListingLocation->listing_location_name }}
                            </a>                           
                        </div>
                        <div class="location">
                            <span class="float-left" style="margin:5px 0 15px 0;">{{ $listing->anofabricacao }}/{{ $listing->vehicleModelYear }}</span>
                            <span class="float-right" style="margin:5px 0 15px 0;">{{ number_format($listing->listing_mileage,0,'','.') }} Km</span>
                        </div>

                      <div class="btn-group" role="group" aria-label="Basic example" style="width:100%;">
<a type="button" href="{{ route('front_listing_agent_detail',[$type,$listing->user_id]) }}" class="btn btn-dark btn-sm">ver loja</a>
<a type="button" href="{{ route('front_listing_detail',[$listing->id,$listing->listing_slug]) }}/#parcelas" class="btn btn-danger btn-sm">
    simular parcelas 
</a>
                      
                      </div>
<div class="clear clearfix"></div>
                        

                    </div>
                </div>
            </div>
        @endforeach
       
{{ $listings->links('front.custom_paginator') }}