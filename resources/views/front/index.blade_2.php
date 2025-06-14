@extends('front.app_front')
           
@section('content')
    @if($banners)  
    <div class="banner-lcp" id="banner-lcp">
        <img src="{{ asset('uploads/banner_photos/'.$banners[0]->image) }}" alt="Banner Principal" loading="eager" fetchpriority="high">
    </div>
<div class="owl-carousel owl-theme banner-slideshow" id="slideshow" style="display: none;">
    @foreach($banners as $row)       
    <div class="item">
        <img 
            src="{{ asset('uploads/banner_photos/'.$row->image) }}" 
            alt="Banner VilaDosCarros" 
            style="width: 100%; height: auto; object-fit: cover;" 
            title="Banner Slideshow VilaDosCarros.Com.Br" 
            onerror="this.onerror=null;this.src='{{ asset('images/sem-veiculo.jpg') }}';"/>
    </div>
    @endforeach   
</div>
    <script>
       window.addEventListener('touchstart', function () {}, { passive: true });
        window.addEventListener('load', function () {
            setTimeout(function () {
                // Esconde o banner LCP
                const lcpBanner = document.getElementById('banner-lcp');
                if (lcpBanner) {
                    lcpBanner.remove(); // Remove completamente do DOM
                }

                // Mostra o slideshow
                const slideshow = document.getElementById('slideshow');
                if (slideshow) {
                    slideshow.style.display = 'block';

                    // Inicializa o Owl Carousel
                    $(".banner-slideshow").owlCarousel({
                        items: 1,            
                        dots: false,          
                        mouseDrag: true,          
                        touchDrag: true,          
                        pullDrag: true,          
                        freeDrag: true,          
                        loop: true,           
                        autoplay: true,       
                        autoplayTimeout: 4000, 
                        margin: 0,
                        singleItem: true,
                        nav: true,
                        navText: [
                            "<i class='fa fa-caret-left'></i>",
                            "<i class='fa fa-caret-right'></i>"
                        ],
                        responsive: {
                            0: { items: 1 },
                            600: { items: 1 },
                            1000: { items: 1 },
                            1200: { items: 1 },
                            1400: { items: 1 },
                            1600: { items: 1 },
                            1800: { items: 1 }
                        }
                    });
                }
            }, 10000); // 4 segundos
        });
    </script>
    @endif     

<div class="search-section">	
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>{{ $page_home_items->search_heading }}</h1>
                <div class="box">
                    <form action="{{ route('busca_front_listing_result') }}" method="POST">
                        @csrf
                        <div class="input-group input-box mb-3">
                            <input type="text" class="form-control" placeholder="{{ FIND_ANYTHING }}" name="text">
                            <select name="location[]" class="form-control select2 select-filter-home" style="height: auto;">
                                <option value="">{{ SELECT_LOCATION }}</option>                                                               	
                                @foreach($listing_locations as $row)
                                @if(!empty($row->listing_location_slug))
                                <option value="{{ $row->id }}">{{ $row->listing_location_name }} ({{ $row->r_listing_count }})</option>
                                @endif
                                @endforeach
                            </select> 
                            <select name="brand[]" class="form-control select2 select-filter-home" style="height: auto;">
                                <option value="">{{ SELECT_BRAND }}</option>
                                @foreach($listing_brands as $row)
                                <option value="{{ $row->id }}">{{ $row->listing_brand_name }} ({{ $row->r_listing_count }})</option>
                                @endforeach 
                            </select> 
                            <div class="input-group-append">
                                <button type="submit"><i class="fa fa-search-plus"></i> {{ SEARCH }} +500 Ofertas</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container">       
    <div class="row">
        <div class="col-md-12 mt-2">
            <div class="heading">
                <h2 style="text-align: center;font-weight:600;">Filtrar por marca</h2>
            </div>
        </div>	
        <div class="col-md-12 mt-2">   
            <div id="brandspng" class="owl-carousel owl-theme carousel-marcas">
                @foreach($listing_brands as $index => $row)
                @php
                $imgMarca = '';
                if($row->canal == 'dsautoestoque' || $row->canal == 'import') {
                $imgMarca = asset('images/'.$row->listing_brand_slug.'.png');
                } elseif ($row->canal == 'website') {
                $imgMarca = asset('uploads/listing_brand_photos/'.$row->listing_brand_photo);
                }
                @endphp
                <div class="item"> 
                    <form method="post" action="{{ route('busca_front_listing_result') }}">
                        @csrf
                        <input type="hidden" name="brand[]" value="{{ $row->id }}" />
                        <button type="submit"> 
                            <img src="{{ $imgMarca }}" alt="{{ $imgMarca }}" title="{{ $row->listing_brand_slug }}" width="84" height="84">
                            <div class="clearfix clear"></div>
                        </button>
                    </form>
                </div>        
                @endforeach  
            </div>
        </div>                    
    </div>
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

            @if($row->user_id == 0)
            @php $type = "admin"; @endphp
            @else
            @php $type = "user"; @endphp
            @endif
            <div class="col-lg-3 col-md-6 col-sm-12 wow {{ $fade_val }}">
                <div class="listing-item effect-item">
                    <div class="photo image-effect">
                        <a href="{{ route('front_listing_detail',[$row->id,$row->listing_slug]) }}" title="{{ $row->listing_name }}">
                            @if($row->canal == 'dsautoestoque')
                            @if ($row->listing_featured_photo == 'images/sem-veiculo.jpg')
                            <img src="{{ asset('images/sem-veiculo.jpg') }}" alt="{{ asset('images/sem-veiculo.jpg') }}" title="{{ $row->listing_name }}" width="255" height="191">
                            @else
                            @if ($row->listing_image_alterada_admin == 1)
                            <img src="{{ asset('uploads/listing_featured_photos/' . $row->listing_featured_photo) }}" alt="{{ asset('uploads/listing_featured_photos/' . $row->listing_featured_photo) }}" title="{{ $row->listing_name }}" width="255" height="191">
                            @else
                            <img src="{{ $row->listing_featured_photo }}" alt="{{ $row->listing_featured_photo }}" title="{{ $row->listing_name }}" width="255" height="191">
                            @endif
                            @endif
                            @else
                            <img src="{{ asset('uploads/listing_featured_photos/'.$row->listing_featured_photo) }}" alt="{{ asset('uploads/listing_featured_photos/'.$row->listing_featured_photo) }}" title="{{ $row->listing_name }}" width="255" height="191">
                            @endif
                        </a>
                        <div class="brand">
                            <a href="{{ route('front_listing_brand_detail',$row->rListingBrand->listing_brand_slug) }}" title="{{ $row->rListingBrand->listing_brand_name }}">{{ $row->rListingBrand->listing_brand_name }}</a>
                        </div>
                        <div class="model"> 
                            <a href="javascript:void(0);">{{ $row->vehicleModel }}</a>
                        </div> 
                        <div class="cambio" style="position: absolute;
                             top: 10px;
                             left: 10px;">
                            <a href="javascript:void(0);" style="color: #fff;
                               padding: 2px 8px;
                               font-size: 14px;
                               border-radius: 6px;background: #000000;">Câmbio: {{ $row->listing_transmission }}</a>
                        </div>                                                                                      
                        <div class="wishlist">
                            <a href="{{ route('front_add_wishlist',$row->id) }}">
                                @if(isset($user_data) && !empty($user_data->id))
                                @php
                                $wishlist = \App\Models\Wishlist::where('user_id', $user_data->id)
                                ->where('listing_id', $row->id)
                                ->first();
                                @endphp

                                @if(isset($wishlist) && !empty($wishlist->id)) 
                                <i class="fas fa-heart" style="color:red;"></i>
                                @else
                                <i class="fas fa-heart"></i>
                                @endif

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
                            <div class="price" style="font-size: 16px;">
                                @if(!session()->get('currency_symbol'))
                                R${{ number_format($row->listing_price,0,'','.') }}
                                @else
                                {{ session()->get('currency_symbol') }}{{ number_format($row->listing_price*session()->get('currency_value'),0,'','.') }}
                                @endif
                            </div>
                        </div>
                        <h3 style="font-size: 13px;"><a href="{{ route('front_listing_detail',[$row->id,$row->listing_slug]) }}" title="{{ $row->listing_name }}">{{ $row->listing_name }}</a></h3>
                        <div class="location">
                            <i class="fas fa-map-marker-alt"></i> {{ $row->rListingLocation->listing_location_name }}
                        </div>
                        <div class="location">
                            <span class="float-left" style="margin:5px 0 15px 0;">{{ $row->anofabricacao }}/{{ $row->vehicleModelYear }}</span>
                            <span class="float-right" style="margin:5px 0 15px 0;">{{ number_format($row->listing_mileage,0,'','.') }} Km</span>
                        </div> 
                        <div class="btn-group" role="group" aria-label="Basic example" style="width:100%;">
                            <a type="button" href="{{ route('front_listing_agent_detail',[$type,$row->user_id]) }}" class="btn btn-dark btn-sm">ver loja</a>
                            <a type="button" href="{{ route('front_listing_detail',[$row->id,$row->listing_slug]) }}/#parcelas" class="btn btn-danger btn-sm">
                                simular parcelas
                            </a>                      
                        </div>
                        <div class="clear clearfix"></div>
                    </div>
                </div>
            </div>
            @endforeach				
        </div><div style="min-height: 60px;">
            @if($page_listing_item->status == 'Show')
            <a href="{{ route('front_listing_result_veiculos') }}" class="btn btn-dark btn-block">VER TODOS OS VEÍCULOS</a>
            @endif
        </div>
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
                    <img src="{{ asset('uploads/advertisements/'.$adv_home_data->above_brand_1) }}" alt="{{ asset('uploads/advertisements/'.$adv_home_data->above_brand_1) }}" title="{{ asset('uploads/advertisements/'.$adv_home_data->above_brand_1) }}">
                    @else
                    <a href="{{ $adv_home_data->above_brand_1_url }}" target="_blank">
                        <img src="{{ asset('uploads/advertisements/'.$adv_home_data->above_brand_1) }}" alt="{{ asset('uploads/advertisements/'.$adv_home_data->above_brand_1) }}" title="{{ asset('uploads/advertisements/'.$adv_home_data->above_brand_1) }}"></a>
                    @endif
                </div>
            </div>
            <div class="col-md-6 col-sm-12 wow fadeInUp">
                <div class="inner">
                    @if($adv_home_data->above_brand_2_url == '')
                    <img src="{{ asset('uploads/advertisements/'.$adv_home_data->above_brand_2) }}" alt="{{ asset('uploads/advertisements/'.$adv_home_data->above_brand_2) }}" title="{{ asset('uploads/advertisements/'.$adv_home_data->above_brand_2) }}">
                    @else
                    <a href="{{ $adv_home_data->above_brand_2_url }}" target="_blank">
                        <img src="{{ asset('uploads/advertisements/'.$adv_home_data->above_brand_2) }}" alt="{{ asset('uploads/advertisements/'.$adv_home_data->above_brand_2) }}" title="{{ asset('uploads/advertisements/'.$adv_home_data->above_brand_2) }}"></a>
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
                    <h3 style="color: #000 !important;">{{ $page_home_items->brand_subheading }}</h3>
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
                        <img src="{{ asset('images/'.$row->listing_brand_slug.'.jpg') }}" alt="{{ asset('images/'.$row->listing_brand_slug.'.jpg') }}" 
                             title="{{ $row->listing_brand_name }}" width="255" height="170">
                        @else
                        <img src="{{ asset('uploads/listing_brand_photos/'.$row->listing_brand_photo) }}" 
                             alt="{{ asset('uploads/listing_brand_photos/'.$row->listing_brand_photo) }}" 
                             title="{{ $row->listing_brand_name }}" width="255" height="170">
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
        <div style="min-height: 60px;">
            @if($page_home_items->brand_status == 'Show')
            <a href="{{ url('listing/brands/all') }}" class="btn btn-dark btn-block">VER TODAS AS MARCAS</a>
            @endif
        </div>
    </div>
</div>
@endif
<div class="page-content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="heading">
                    <h2 style="text-align: center;padding-bottom:25px;">{{ MENU_BLOG }}</h2>
                </div>
            </div>
        </div>
        <div class="row">
            @foreach($blog_items as $row)
            <div class="col-md-4">
                <div class="blog-item">
                    <div class="featured-photo">
                        <a href="{{ route('front_post',$row->post_slug) }}">
                            <img src="{{ asset('uploads/post_photos/'.$row->post_photo) }}" 
                                 alt="{{ asset('uploads/post_photos/'.$row->post_photo) }}" title="{{ $row->post_title }}" width="350" height="230"></a>
                    </div>
                    <div class="text">
                        <h2>
                            <a href="{{ route('front_post',$row->post_slug) }}">{{ $row->post_title }}</a>
                        </h2>
                        <div class="short-description">
                            <p>
                                {!! clean(nl2br($row->post_content_short)) !!}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div style="min-height: 60px;">
            <a href="{{ url('blog') }}" class="btn btn-dark btn-block">VER MAIS DO BLOG</a>
        </div>
    </div>
</div>
<div class="page-content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="heading">
                    <h2 style="text-align: center;padding-bottom:25px;">10 Razões para comprar na Vila dos Carros</h2>
                </div>
            </div>                   
            <div class="col-md-12">    
                <div class="owl-carousel owl-theme" id="10-razoes" style="z-index:0!important;">
                    <div class="item">
                        <a href="{{ route('front_about') }}">
                            <img src="{{ asset('images/10-razoes/Economia-e-Eficiencia.png') }}" alt="{{ asset('images/10-razoes/Economia-e-Eficiencia.png') }}" 
                                 title="Economia-e-Eficiencia.png" width="277" height="415">
                        </a>
                    </div>
                    <div class="item">
                        <a href="{{ route('front_about') }}">
                            <img src="{{ asset('images/10-razoes/Melhores-Revendas.png') }}" alt="{{ asset('images/10-razoes/Melhores-Revendas.png') }}" 
                                 title="Melhores-Revendas.png" width="277" height="415">
                        </a>
                    </div>
                    <div class="item">
                        <a href="{{ route('front_about') }}">
                            <img src="{{ asset('images/10-razoes/Variedade.png') }}" alt="{{ asset('images/10-razoes/Variedade.png') }}" title="Variedade.png" width="277" height="415">
                        </a>
                    </div>
                    <div class="item">
                        <a href="{{ route('front_about') }}">
                            <img src="{{ asset('images/10-razoes/Seguranca-Garantida.png') }}" alt="{{ asset('images/10-razoes/Seguranca-Garantida.png') }}" 
                                 title="Seguranca-Garantida.png" width="277" height="415">
                        </a>
                    </div>
                    <div class="item">
                        <a href="{{ route('front_about') }}">
                            <img src="{{ asset('images/10-razoes/Suporte-Ativo-SAC.png') }}" alt="{{ asset('images/10-razoes/Suporte-Ativo-SAC.png') }}" 
                                 title="Suporte-Ativo-SAC.png" width="277" height="415">
                        </a>
                    </div>
                    <div class="item">
                        <a href="{{ route('front_about') }}">
                            <img src="{{ asset('images/10-razoes/Qualidade.png') }}" alt="{{ asset('images/10-razoes/Qualidade.png') }}" title="Qualidade.png" width="277" height="415">
                        </a>
                    </div>
                    <div class="item">
                        <a href="{{ route('front_about') }}">
                            <img src="{{ asset('images/10-razoes/Garantia-Documentada.png') }}" alt="{{ asset('images/10-razoes/Garantia-Documentada.png') }}" 
                                 title="Garantia-Documentada.png" width="277" height="415">
                        </a>
                    </div>
                    <div class="item">
                        <a href="{{ route('front_about') }}">
                            <img src="{{ asset('images/10-razoes/Conveniencia-Digital.png') }}" alt="{{ asset('images/10-razoes/Conveniencia-Digital.png') }}" 
                                 title="Conveniencia-Digital.png" width="277" height="415">
                        </a>
                    </div>
                    <div class="item">
                        <a href="{{ route('front_about') }}">
                            <img src="{{ asset('images/10-razoes/Compromisso-Contratual.png') }}" alt="{{ asset('images/10-razoes/Compromisso-Contratual.png') }}" 
                                 title="Compromisso-Contratual.png" width="277" height="415">
                        </a>
                    </div>

                </div>                                 
            </div>
        </div>
    </div>
</div>
@if($adv_home_data->above_featured_listing_status == 'Show')
<div class="ad-section">
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-sm-12 wow fadeInUp">
                <div class="inner">
                    @if($adv_home_data->above_featured_listing_1_url == '')
                    <img src="{{ asset('uploads/advertisements/'.$adv_home_data->above_featured_listing_1) }}" 
                         alt="{{ asset('uploads/advertisements/'.$adv_home_data->above_featured_listing_1) }}"
                         title="{{ asset('uploads/advertisements/'.$adv_home_data->above_featured_listing_1) }}">
                    @else
                    <a href="{{ $adv_home_data->above_featured_listing_1_url }}" target="_blank">
                        <img src="{{ asset('uploads/advertisements/'.$adv_home_data->above_featured_listing_1) }}" 
                             alt="{{ asset('uploads/advertisements/'.$adv_home_data->above_featured_listing_1) }}"
                             title="{{ asset('uploads/advertisements/'.$adv_home_data->above_featured_listing_1) }}"></a>
                    @endif
                </div>
            </div>
            <div class="col-md-6 col-sm-12 wow fadeInUp">
                <div class="inner">
                    @if($adv_home_data->above_featured_listing_2_url == '')
                    <img src="{{ asset('uploads/advertisements/'.$adv_home_data->above_featured_listing_2) }}" 
                         alt="{{ asset('uploads/advertisements/'.$adv_home_data->above_featured_listing_2) }}" 
                         title="{{ asset('uploads/advertisements/'.$adv_home_data->above_featured_listing_2) }}">
                    @else
                    <a href="{{ $adv_home_data->above_featured_listing_2_url }}" target="_blank">
                        <img src="{{ asset('uploads/advertisements/'.$adv_home_data->above_featured_listing_2) }}" 
                             alt="{{ asset('uploads/advertisements/'.$adv_home_data->above_featured_listing_2) }}" 
                             title="{{ asset('uploads/advertisements/'.$adv_home_data->above_featured_listing_2) }}"></a>
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
                            <img src="{{ asset('uploads/testimonials/'.$row->photo) }}" alt="{{ asset('uploads/testimonials/'.$row->photo) }}" title="{{ $row->name }}">
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

@php
date_default_timezone_set('America/Sao_Paulo');
$hora_atual = date('H');
@endphp
@if($hora_atual >= 20 || $hora_atual < 4)
<div class="listing" style="margin-bottom:0!important;padding-bottom:0!important;"> 
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div id="ad-container">
                    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-9171096234708042"
                    crossorigin="anonymous"></script>
                    <ins class="adsbygoogle"
                         style="display:block"
                         data-ad-client="ca-pub-9171096234708042"
                         data-ad-slot="1712465164"
                         data-ad-format="auto"
                         data-full-width-responsive="true"></ins>
                    <script>
(adsbygoogle = window.adsbygoogle || []).push({});
                    </script>
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
                    <img src="{{ asset('uploads/advertisements/'.$adv_home_data->above_location_1) }}" 
                         alt="{{ asset('uploads/advertisements/'.$adv_home_data->above_location_1) }}" 
                         title="{{ asset('uploads/advertisements/'.$adv_home_data->above_location_1) }}"
                         >
                    @else
                    <a href="{{ $adv_home_data->above_location_1_url }}" target="_blank">
                        <img src="{{ asset('uploads/advertisements/'.$adv_home_data->above_location_1) }}" 
                             alt="{{ asset('uploads/advertisements/'.$adv_home_data->above_location_1) }}" 
                             title="{{ asset('uploads/advertisements/'.$adv_home_data->above_location_1) }}"></a>
                    @endif
                </div>
            </div>
            <div class="col-md-6 col-sm-12 wow fadeInUp">
                <div class="inner">
                    @if($adv_home_data->above_location_2_url == '')
                    <img src="{{ asset('uploads/advertisements/'.$adv_home_data->above_location_2) }}" 
                         alt="{{ asset('uploads/advertisements/'.$adv_home_data->above_location_2) }}" 
                         title="{{ asset('uploads/advertisements/'.$adv_home_data->above_location_2) }}">
                    @else
                    <a href="{{ $adv_home_data->above_location_2_url }}" target="_blank">
                        <img src="{{ asset('uploads/advertisements/'.$adv_home_data->above_location_2) }}" 
                             alt="{{ asset('uploads/advertisements/'.$adv_home_data->above_location_2) }}" 
                             title="{{ asset('uploads/advertisements/'.$adv_home_data->above_location_2) }}"></a>
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
                        <img src="{{ asset($row->listing_location_photo) }}" alt="{{ asset($row->listing_location_photo) }}" 
                             title="{{ $row->listing_location_name }}">
                        @else
                        <img src="{{ asset('uploads/listing_location_photos/'.$row->listing_location_photo) }}" alt="{{ asset('uploads/listing_location_photos/'.$row->listing_location_photo) }}" title="{{ $row->listing_location_name }}">
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