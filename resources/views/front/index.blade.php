@extends('front.app_front')

@section('content')
@if($banners)  
<div class="banner-lcp" id="banner-lcp">
    @php
    $banner = $banners[0];
    $baseName = pathinfo($banner->image, PATHINFO_FILENAME);
@endphp
    <img 
    src="{{ asset('uploads/banner_photos/' . $banner->image) }}" 
    srcset="
        {{ asset('uploads/banner_photos/' . $baseName . '-480.webp') }} {{ $banner->largura_480 }}w,
        {{ asset('uploads/banner_photos/' . $baseName . '-768.webp') }} {{ $banner->largura_768 }}w,
        {{ asset('uploads/banner_photos/' . $banner->image) }} {{ $banner->largura }}w
    "
    sizes="(max-width: 480px) {{ $banner->largura_480 }}px, (max-width: 768px) {{ $banner->largura_768 }}px, {{ $banner->largura }}px"
    width="{{ $banner->largura }}"
    height="{{ $banner->altura }}"
    alt="Banner Principal"
    loading="eager"
    fetchpriority="high"
/>
</div>
<div class="owl-carousel owl-theme banner-slideshow" id="slideshow" style="display: none;"></div>
<script>
    window.addEventListener('load', function () {
        setTimeout(() => {
            fetch('/ajax/banners')
                    .then(response => response.json())
                    .then(data => {
                        const slideshow = document.getElementById('slideshow');

                        if (data.banners && data.banners.length > 0) {
                            data.banners.forEach(src => {
                                const div = document.createElement('div');
                                
                                div.classList.add('item');
                                div.innerHTML = `
  <img  class="owl-lazy" 
    data-src="${src.url}" 
    srcset="
      ${src.url_480w} ${src.largura_480}w,
      ${src.url_768w} ${src.largura_768}w,
      ${src.url} ${src.largura}w
    " sizes="(max-width: 480px) ${src.largura_480}px, (max-width: 768px) ${src.largura_768}px, ${src.largura}px"
    width="${src.largura}" 
    height="${src.altura}" 
    alt="Banner VilaDosCarros"
     loading="lazy" 
  />
`;
                                slideshow.appendChild(div);
                            });
                            // Remove o banner LCP
                            const lcpBanner = document.getElementById('banner-lcp');
                            if (lcpBanner)
                                lcpBanner.remove();
                            // Mostra o slideshow
                            slideshow.style.display = 'block';
                            // Inicializa o Owl Carousel
                            $(".banner-slideshow").owlCarousel({
                                items: 1,
                                lazyLoad: true,
                                loop: false,
                                autoplay: true,
                                autoplayTimeout: 4000,
                                nav: true,
                                margin: 0,
                                navText: [
                                    "<i class='fa fa-caret-left'></i>",
                                    "<i class='fa fa-caret-right'></i>"
                                ],
                                responsive: {
                                    0: {
                                        items: 1,
                                    },
                                    600: {
                                        items: 1,
                                    },
                                    1000: {
                                        items: 1
                                    }
                                }
                            });
                        }
                    });
        }, 10000); // 4 segundos após load
    });
</script>
@endif     
<div class="search-section" style="overflow: hidden !important;padding-top: 25px !important;">	
    <div class="container">
        <div class="row">
            <div class="col-md-12 ">
                 <div class="heading">
                <h1 style="text-align: center;">{{ $page_home_items->search_heading }}</h1>
            </div>	
           
                <form action="{{ route('busca_front_listing_result') }}" method="POST">
                    @csrf
                    <div class="row">  
                        <div class="col-lg-4 col-md-4 col-sm-12 form-group remove-padding">
                            <label for="filter-text-input" style="margin:0;padding:0;display:none;"></label>
                            <input type="text" class="form-control form-control-lg" placeholder="Digite marca, modelo e versão" name="text" id="filter-text-input">
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 form-group remove-padding">
                            <label for="filter-location-select" style="margin:0;padding:0;display:none;"></label>
                            <select name="location[]" class="form-control form-control-lg" id="filter-location-select">
                                <option value="">{{ SELECT_LOCATION }}</option>                                                               	
                                @foreach($listing_locations as $row)
                                @if(!empty($row->listing_location_slug))
                                <option value="{{ $row->id }}">{{ $row->listing_location_name }} ({{ $row->r_listing_count }})</option>
                                @endif
                                @endforeach
                            </select> 
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 form-group remove-padding">
                            <label for="filter-brand-select" style="margin:0;padding:0;display:none;"></label>
                            <select name="brand[]" class="form-control form-control-lg" id="filter-brand-select">
                                <option value="">{{ SELECT_BRAND }}</option>
                                @foreach($listing_brands as $row)
                                <option value="{{ $row->id }}">{{ $row->listing_brand_name }} ({{ $row->r_listing_count }})</option>
                                @endforeach 
                            </select> 
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 remove-padding">                            
                            <button type="submit" class="btn btn-dark btn-block btn-lg"><i class="fa fa-search-plus"></i> {{ SEARCH }} +{{ $total_estoque }} Ofertas</button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

@if($page_home_items->listing_status == 'Show')
<div class="listing" style="overflow: hidden !important;padding-top: 30px !important;">
    <div class="container">
        <div class="row">
            <div class="col-md-12 mt-2">
                <div class="heading">
                    <h1 style="text-align: center;">{{ $page_home_items->listing_heading }}</h1>
                    <h2 style="text-align: center;">{{ $page_home_items->listing_subheading }}</h2>
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
            <div class="col-lg-3 col-md-6 col-sm-12 {{ $fade_val }}">
                <div class="listing-item effect-item">
                    <div class="photo image-effect">
                        <a href="{{ route('front_listing_detail',[$row->id,$row->listing_slug]) }}" title="{{ $row->listing_name }}">
                            @php
                            $imgDestaque = '';
                            if ($row->canal === 'dsautoestoque') {
                            if ($row->listing_featured_photo === 'images/sem-veiculo.jpg') {
                            $imgDestaque = asset('images/sem-veiculo.jpg');
                            } else {
                            if ($row->listing_image_alterada_admin == 1) {
                            $imgDestaque = asset('uploads/listing_featured_photos_thumbs/thumb_' . $row->listing_featured_photo);
                            } else {
                            $isFromDsae = strpos($row->listing_featured_photo, 'dsae') !== false;
                            $imgDestaque = $isFromDsae
                            ? $row->listing_featured_photo
                            : asset('uploads/listing_featured_photos_thumbs/thumb_' . $row->listing_featured_photo);
                            }
                            }
                            } else {
                            $imgDestaque = asset('uploads/listing_featured_photos_thumbs/thumb_' . $row->listing_featured_photo);
                            }
                            
                            @endphp
                            <img  data-src="{{ $imgDestaque }}"
                                  src="{{ $imgDestaque }}" 
                                  alt="{{ $row->listing_featured_photo }}" 
                                  title="{{ $row->listing_name }}" 
                                  width="255" 
                                  height="191" loading="lazy" class="img-fade" onload="this.classList.add('loaded')">
                        </a>
                        <div class="brand">
                            <a href="{{ route('front_listing_marca_detail',$row->rListingBrand->listing_brand_slug) }}" title="{{ $row->rListingBrand->listing_brand_name }}">{{ $row->rListingBrand->listing_brand_name }}</a>
                        </div>
                        <div class="model"> 
                            <a href="#">{{ $row->vehicleModel }}</a>
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
                            <div class="price" style="font-size: 13px;">
                                @if(!session()->get('currency_symbol'))
                                R${{ number_format($row->listing_price,0,'','.') }}
                                @else
                                {{ session()->get('currency_symbol') }}{{ number_format($row->listing_price*session()->get('currency_value'),0,'','.') }}
                                @endif
                            </div>
                        </div>
                        <h3><a style="font-size: 11px;" href="{{ route('front_listing_detail',[$row->id,$row->listing_slug]) }}" title="{{ $row->listing_name }}">{{ $row->listing_name }}</a></h3>
                        <div class="location" style="font-size: 12px;">
                            <i class="fas fa-map-marker-alt" style="font-size: 12px;"></i> {{ $row->rListingLocation->listing_location_name }}
                        </div>
                        <div class="location">
                            <span class="float-left" style="margin:5px 0 10px 0;font-size: 11px;">{{ $row->anofabricacao }}/{{ $row->vehicleModelYear }}</span>
                            <span class="float-right" style="margin:5px 0 10px 0;font-size: 11px;">{{ number_format($row->listing_mileage,0,'','.') }} Km</span>
                        </div> 
                        <div class="btn-group" role="group" aria-label="Basic example" style="width:100%;">
                            <a type="button" href="{{ route('front_listing_agent_detail',[$type,$row->user->slug_user ?? $row->user_id]) }}" class="btn btn-dark btn-sm">ver loja</a>
                            <a type="button" href="{{ route('front_listing_detail',[$row->id,$row->listing_slug]) }}/#parcelas" class="btn btn-danger btn-sm">
                                simular parcelas
                            </a>                      
                        </div>
                        <div class="clear clearfix"></div>
                    </div>
                </div>
            </div>
            @endforeach				
        </div>
        <div style="min-height: 50px;">
            @if($page_listing_item->status == 'Show')
            <a href="{{ route('front_listing_result_veiculos') }}" class="btn btn-dark btn-block btn-lg">VER TODOS OS VEÍCULOS</a>
            @endif
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
                    <h2 style="text-align: center;">{{ $page_home_items->brand_heading }}</h2>
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
            <div class="col-lg-3 col-md-6 col-sm-6 fadeInUp">
                <div class="popular-city-item effect-item">
                    <div class="photo image-effect" style="border-bottom: 1px solid #ccc;">                                                                                                   
                        @php
                        $brandListingAlisson = App\Models\Listing::where('listing_brand_id', $row->id)
                        ->where('listing_status', 'Active')
                        ->first();

                        $original = 'uploads/listing_brand_photos/' . $row->listing_brand_photo;
                        $filename = pathinfo($row->listing_brand_photo, PATHINFO_FILENAME);
                        $optimized = 'uploads/listing_brand_photos/webp/' . $filename . '-255x170.webp';

                        $imgPath = file_exists(public_path($optimized)) ? asset($optimized) : asset($original);
                        @endphp
                        <img src="{{ $imgPath }}" 
                             alt="{{ $row->listing_brand_name }}" 
                             title="{{ $row->listing_brand_name }}" width="255" height="170"
                             loading="lazy">

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
                    <a href="{{ route('front_listing_marca_detail',$row->listing_brand_slug) }}"></a>
                </div>
            </div>
            @endforeach
        </div>
        <div style="min-height: 60px;">
            @if($page_home_items->brand_status == 'Show')
            <a href="{{ route('front_listing_marcas_all') }}" class="btn btn-dark btn-block btn-lg">VER TODAS AS MARCAS</a>
            @endif
        </div>
    </div>
</div>
@endif
<div class="page-content" style="overflow: hidden !important;padding-top: 50px !important;">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="heading">
                    <h2 style="text-align: center;">{{ MENU_BLOG }}</h2>
                </div>
            </div>
        </div>
        <div class="row">
            @foreach($blog_items as $row)

            @php
            $original = 'uploads/post_photos/' . $row->post_photo;
            $filename = pathinfo($row->post_photo, PATHINFO_FILENAME);
            $optimized = 'uploads/post_photos/webp/' . $filename . '-350x230.webp';

            $imgPath = file_exists(public_path($optimized)) ? asset($optimized) : asset($original);
            @endphp

            <div class="col-md-4">
                <div class="blog-item">
                    <div class="featured-photo">
                        <a href="{{ route('front_post',$row->post_slug) }}">
                            <img src="{{ $imgPath }}" 
                                 alt="{{ $row->post_title }}" title="{{ $row->post_title }}" width="350" height="230" loading="lazy"></a>
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
            <a href="{{ url('blog') }}" class="btn btn-dark btn-block btn-lg">VER MAIS DO BLOG</a>
        </div>
    </div>
</div>
<div class="page-content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="heading">
                    <h2 style="text-align: center;">10 Razões para comprar na Vila dos Carros</h2>
                </div>
            </div>                   
            <div class="col-md-12">    
                @php
                $imagens = [
                'Economia-e-Eficiencia',
                'Melhores-Revendas',
                'Variedade',
                'Seguranca-Garantida',
                'Suporte-Ativo-SAC',
                'Qualidade',
                'Garantia-Documentada',
                'Conveniencia-Digital',
                'Compromisso-Contratual'
                ];
                @endphp                               
                <div class="owl-carousel owl-theme" id="10-razoes" style="z-index:0!important;">
                    @foreach ($imagens as $nomeImagem)
                    @php
                    $original = 'images/10-razoes/' . $nomeImagem . '.png';
                    $optimized = 'images/10-razoes/' . $nomeImagem . '.webp';
                    $imgPath = file_exists(public_path($optimized)) ? asset($optimized) : asset($original);
                    @endphp
                    <div class="item">
                        <a href="{{ route('front_about') }}">
                            <img class="owl-lazy" data-src="{{ $imgPath }}" alt="{{ $nomeImagem }}" 
                                 title="{{ $nomeImagem }}" width="277" height="415" loading="lazy">
                        </a>
                    </div>
                    @endforeach

                </div>                                 
            </div>
        </div>
    </div>
</div>
@php
date_default_timezone_set('America/Sao_Paulo');
$hora_atual = date('H');
@endphp
@if($hora_atual >= 13 || $hora_atual < 9)
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
            <div class="col-lg-3 col-md-6 col-sm-6 fadeInUp">
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