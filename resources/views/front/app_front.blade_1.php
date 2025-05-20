@if(!session()->get('currency_name'))
@php
$name1 = $sess_arr->name;
$symbol1 = $sess_arr->symbol;
$value1 = $sess_arr->value;
session()->put('currency_name',$name1);
session()->put('currency_symbol',$symbol1);
session()->put('currency_value',$value1);
@endphp
@endif

<!DOCTYPE html>
<html lang="pt-br">
   	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="icon" type="image/png" href="{{ asset('uploads/site_photos/'.$g_setting->favicon) }}">
        

        @if($route == null)
		    <title>{{ (!empty($item_row->seo_title) ? $item_row->seo_title : $item_row->generic_seo_meta_description) }}</title>
		    <meta name="description" content="{{ $item_row->generic_seo_meta_description }}">
                    @if($banners)
      @foreach($banners as $row)   
       <link rel="preload" as="{{ asset('uploads/banner_photos/'.$row->image) }}" href="{{ asset('uploads/banner_photos/'.$row->image) }}" />
        @endforeach
       @endif  
                    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Attorney",
        "name": "{{ $item_row->generic_seo_meta_description }}",
        "image": "{{ asset('uploads/site_photos/'.$g_setting->logo) }}",
        "url": "{{ request()->url() }}",
        "telephone": "",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "",
            "addressLocality": "",
            "postalCode": "",
            "addressCountry": ""
        },
        "sameAs": [
            @if($social_media_items->isNotEmpty())
                {!! implode(',', array_map(function($media_item) {
                    return '"' . $media_item['social_url'] . '"';
                }, $social_media_items->toArray())) !!}
            @endif
        ]
    }
</script>  
@elseif($route == 'front_lojas')
<title>Lojas</title>
            <meta name="description" content="Todos os lojistas do nosso Shopping!">
            @elseif($route == 'front_listing_detail')
            
            <title>{{ !empty($item_row->seo_title) ? $item_row->seo_title : $item_row->listing_name }}</title>
        <meta name="description" content="{{ $item_row->generic_seo_meta_description }}">
        <meta property="og:title" content="{{ !empty($item_row->seo_title) ? $item_row->seo_title : $item_row->listing_name }}" />
        
        @if($item_row->canal == 'dsautoestoque')                                
                                @if($item_row->listing_featured_photo == 'images/sem-veiculo.jpg')
                <meta property="og:image" content="{{ asset('images/sem-veiculo.jpg') }}" />
            @else
                @if($item_row->listing_image_alterada_admin == 1)
                <meta property="og:image" content="{{ asset('uploads/listing_featured_photos/' . $item_row->listing_featured_photo) }}" />
                @else
                    <meta property="og:image" content="{{ $item_row->listing_featured_photo }}" />
                @endif
            @endif
                            @else
                            <meta property="og:image" content="{{ asset('uploads/listing_featured_photos/' . $item_row->listing_featured_photo) }}" />
                            @endif        
                <meta property="og:image:width" content="400" />
        <meta property="og:image:height" content="400" />
        <meta property="og:url" content="{{ request()->url() }}" />
        <meta property="og:description" content="{{ $item_row->generic_seo_meta_description }}" />
        <meta property="og:type" content="website" />
            
            @else
            <title>{{ (!empty($item_row->seo_title) ? $item_row->seo_title : $item_row->name) }}</title>
            <meta name="description" content="{{ $item_row->generic_seo_meta_description }}">
        @endif

        @if($route == 'customer_login')
            @php $item_row = \App\Models\PageOtherItem::where('id',1)->first(); @endphp
            <title>{{ $item_row->login_page_seo_title }}</title>
            <meta name="description" content="{{ $item_row->login_page_seo_meta_description }}">
        @endif

        @if($route == 'customer_registration')
            @php $item_row = \App\Models\PageOtherItem::where('id',1)->first(); @endphp
            <title>{{ $item_row->registration_page_seo_title }}</title>
            <meta name="description" content="{{ $item_row->registration_page_seo_meta_description }}">
        @endif

        @if($route == 'customer_forget_password')
            @php $item_row = \App\Models\PageOtherItem::where('id',1)->first(); @endphp
            <title>{{ $item_row->forget_password_page_seo_title }}</title>
            <meta name="description" content="{{ $item_row->forget_password_page_seo_meta_description }}">
        @endif

        @if($route == 'front_terms_and_conditions')
            @php $item_row = \App\Models\PageTermItem::where('id',1)->first(); @endphp
            <title>{{ $item_row->seo_title }}</title>
            <meta name="description" content="{{ $item_row->seo_meta_description }}">
        @endif

        @if($route == 'front_privacy_policy')
            @php $item_row = \App\Models\PagePrivacyItem::where('id',1)->first(); @endphp
            <title>{{ $item_row->seo_title }}</title>
            <meta name="description" content="{{ $item_row->seo_meta_description }}">
        @endif
        @if($route == 'front_quero_vender')
            @php $item_row = \App\Models\PageVenderItem::where('id',1)->first(); @endphp
            <title>{{ $item_row->seo_title }}</title>
            <meta name="description" content="{{ $item_row->seo_meta_description }}">
        @endif

        @if($route == 'customer_dashboard'||$route == 'customer_package'||$route == 'customer_package_purchase_history'||$route == 'customer_listing_view'||$route == 'customer_listing_view_detail'||$route == 'customer_listing_add'||$route == 'customer_listing_edit'||$route == 'customer_my_reviews'||$route == 'customer_my_review_edit'||$route == 'customer_wishlist'||$route == 'customer_update_profile'||$route == 'customer_update_password'||$route == 'customer_update_photo'||$route == 'customer_update_banner'||$route == 'customer_package_purchase_invoice'||$route == 'customer_package_purchase_history_detail')
            @php $item_row = \App\Models\PageOtherItem::where('id',1)->first(); @endphp
            <title>{{ $item_row->customer_panel_page_seo_title }}</title>
            <meta name="description" content="{{ $item_row->customer_panel_page_seo_meta_description }}">
        @endif
        
        <link rel="canonical" href="{{ request()->url() }}">   
@if($route == 'front_listing_detail')
    @php
        $id = request()->route('id');
        $slug = request()->route('slug');
        $item_row = \App\Models\Listing::where('id', $id)->where('listing_slug', $slug)->first();
    @endphp

    @if($item_row)

@if($item_row->canal == 'dsautoestoque')                                
                                @if($item_row->listing_featured_photo == 'images/sem-veiculo.jpg')
                <link rel="icon" type="image/png" href="{{ asset('images/sem-veiculo.jpg') }}">
            @else
                @if($item_row->listing_image_alterada_admin == 1)
                <link rel="icon" type="image/png" href="{{ asset('uploads/listing_featured_photos/'.$item_row->listing_featured_photo) }}">
                @else
                    <link rel="icon" type="image/png" href="{{ $item_row->listing_featured_photo }}">
                @endif
            @endif
                            @else
                            <link rel="icon" type="image/png" href="{{ asset('uploads/listing_featured_photos/'.$item_row->listing_featured_photo) }}">
                            @endif
@endif
@endif
		@include('front.app_styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

<!--<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">-->

		@include('front.app_scripts')

		@if($g_setting->tawk_live_chat_status == 'Show')
		<style>
		.scroll-top {
			bottom: 88px!important;
		}
		</style>
		@endif

        @if($g_setting->cookie_consent_status == 'Show') 
            <!--<script src="https://cdnapp.websitepolicies.net/widgets/cookies/ite9r25c.js" defer></script>-->
        @endif

        @if($g_setting->google_analytic_status == 'Show')
        <!-- Global site tag (gtag.js) - Google Analytics -->
            <script async src="https://www.googletagmanager.com/gtag/js?id={{ $g_setting->google_analytic_tracking_id }}"></script>
            <script>
                window.dataLayer = window.dataLayer || [];
                function gtag(){dataLayer.push(arguments);}
                gtag('js', new Date());
                gtag('config', '{{ $g_setting->google_analytic_tracking_id }}');
            </script>
        @endif
        
        <style>
            #brandspng{
                margin-top: 40px;
            }
            .mobile-nav.mean-container .mean-nav ul li a.active,
            .main-nav nav .navbar-nav .nav-item a:hover,
            .main-nav nav .navbar-nav .nav-item a:focus,
            .main-nav nav .navbar-nav .nav-item a.active,
            .main-nav nav .navbar-nav .nav-item:hover a,
            .main-nav nav .navbar-nav .nav-item .dropdown-menu li a:hover,
            .main-nav nav .navbar-nav .nav-item .dropdown-menu li a:focus,
            .main-nav nav .navbar-nav .nav-item .dropdown-menu li a.active,
            .main-nav nav .navbar-nav .nav-item .dropdown-menu li .dropdown-menu li a:hover,
            .main-nav nav .navbar-nav .nav-item .dropdown-menu li .dropdown-menu li a:focus,
            .main-nav nav .navbar-nav .nav-item .dropdown-menu li .dropdown-menu li a.active,
            .main-nav nav .navbar-nav .nav-item .dropdown-menu li:hover a,
            .main-nav nav .navbar-nav .nav-item .dropdown-menu li a:hover,
            .listing .listing-item .text .location,
            .listing .listing-item .text h3 a:hover,
            .footer-item h2,
            .footer-item ul li a:hover,
            .listing-filter .lf-heading,
            .listing .listing-item .text .location a,
            .listing-single-banner .listing-items a,
            .listing-page h2 i,
            .listing-page .amenities li i,
            .listing-page .contact a,
            .listing-page .review-overall .total,
            .listing-sidebar .ls-widget .agent-contact li,
            .listing-sidebar .ls-widget .agent-contact li a,
            .listing-sidebar .ls-widget .category ul li a,
            .faq h4.panel-title a,
            .sidebar .widget .type-1 ul li:before,
            .sidebar .widget .type-1 ul li a:hover,
            .contact-icon i,
            .reg-login-form .new-user a,
            .reg-login-form .link,
            .listing-page .room-all .item .price,
            .popular-city .popular-city-item:hover h4 {
                color: #{{ $g_setting->theme_color }};
            }
            .main-nav nav .navbar-nav .nav-item .dropdown-menu li a:hover,
            .mean-container a.meanmenu-reveal,
            .footer-social-link ul li a:hover {
               /* color: #{{ $g_setting->theme_color }}!important;*/
                color: #ffffff!important;
            }
            .search-section .input-group-append button,
            .listing .listing-item .photo .brand a,
            .popular-city-carousel .owl-nav .owl-prev,
            .popular-city-carousel .owl-nav .owl-next,
            .footer-social-link ul li a,
            .scroll-top,
            .page-banner,
            .filter-button,
            .listing-sidebar .ls-widget .agent-social ul li a,
            .listing-sidebar .ls-widget a.agent-view-profile,
            .pricing .btn,
            .contact-form .btn,
            .reg-login-form button,
            .listing .owl-nav .owl-prev, 
            .listing .owl-nav .owl-next,
            .listing-single-banner .social a:hover,
            .top,
            .agent-banner .social a:hover,
            .mean-container a.meanmenu-reveal span,
            .comment button {
                background: #{{ $g_setting->theme_color }};
            }
            .footer-social-link ul li a,
            .contact-form .btn {
                border-color: #{{ $g_setting->theme_color }};
            }
            .listing-filter .lf-heading {
                border-bottom-color: #{{ $g_setting->theme_color }};
            }
            .navbar-brand {
    padding-top: 0;
    padding-bottom: 0;
   
}
#myTab .nav-link{
    padding: .2rem .3rem !important;
    font-size: .775rem !important;
}
/*meu estilo CSS - Alisson*/
  
#stickymenuGeral{
    box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.3) !important;
}

#stickymenu{
    width: 100%;
    background-color: #000!important;
    /*box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.3) !important;*/
    box-shadow: 0px 0px 10px rgba(255, 255, 255, 0.3) !important;
   
}
.stickymenu{
    position: fixed !important;
    top: 0;
    width: 100%;
    z-index: 1000; 
    background-color: #fff;
    box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.3) !important;
  
}
.search-section{
    height: 152px !important;
}
/* Ocultar na versão mobile */
    #searchFormId {
        display: none;
    }
    #btn-filter{
            display: inline;
        }
    #span-filter{
            display: none;
        }
        
        .form-check-input:checked + .form-check-label {
    transition: transform 0.3s, border-color 0.3s;
}
    .form-check-input-modelos:checked + .form-check-label-modelos {
    transition: transform 0.3s, border-color 0.3s; 
}
.brand-logo {
    transition: transform 0.3s !important;
}
.form-check-input:checked + .form-check-label .brand-logo {
   border-color: #00d6a6 !important;    
}
.form-check-input-modelos:checked + .form-check-label-modelos .brand-logo {
   border-color: #00d6a6 !important;    
}
.form-check-label:hover {
    transform: scale(0.9);
    transition: transform 0.3s, border-color 0.3s;
}
.form-check-label-modelos:hover {
    transform: scale(0.9); 
    transition: transform 0.3s, border-color 0.3s;
}
label.label-brand-check{
   -webkit-justify-content: center;
    justify-content: center;
    -webkit-box-shadow: 0 4px 8px -4px rgba(0, 0, 0, .3);
    box-shadow: 0 4px 8px -4px rgba(0, 0, 0, .3);text-align:center;cursor: pointer; border: 1px solid #ccc; border-radius: 5px; transition: border-color 0.3s;float:left; 
}
.form-check-radio-input {
    display: none; 
}
.form-check-radio-label {
    cursor: pointer;
}
.btn-check:checked + .form-check-radio-label {
    background-color: #333; 
    border-color: #333;
    color: #fff;
}
.form-check-radio-label:hover {
    background-color: #333;
    border-color: #333;
}
.two-columns {
    columns: 3;
    -webkit-columns: 3;
    -moz-columns: 3;
    list-style: none;
    padding: 0;
}
.two-columns li {
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    font-size: 14px !important;
}
.two-columns li i {
    margin-right: 5px;
    color: black;
}
.duas-colunas {
    columns: 2;
    -webkit-columns: 2;
    -moz-columns: 2;
    list-style: none;
    padding: 0;
}
.duas-colunas li {
    margin-bottom: 5px !important;
    display: flex;
    align-items: center;
}
.duas-colunas li span {
    font-size: 14px;
}
.ulDetail li p span{
    font-size: 14px !important;
}
.ulDetail li p span.title_nome{
    font-size: 12px !important;
}
.duas-colunas li a {
    font-size: 14px !important;
}
.duas-colunas li i {
    margin-right: 5px;
    color: black;
}
.btn-check {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }    
    #barra_de_rolagem {
    overflow-y: auto; 
    overflow-x: hidden;
    padding: 5px;
    box-sizing: border-box;
}
#barra_de_rolagem::-webkit-scrollbar {
    width: 7px;
}
#barra_de_rolagem::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 5px;
}
#barra_de_rolagem::-webkit-scrollbar-thumb {
    background-color: #888;
    border-radius: 5px;
}
#barra_de_rolagem::-webkit-scrollbar-thumb:hover {
    background: #555;
}
.form-contato .form-custom:focus{
    border:2px solid #000;
}
.form-contato .form-custom {
    display: block;
    width: 100%;
    height: calc(1.5em + .75rem + 2px);
    padding: .375rem .75rem;
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.5;
    color: #495057;
    background-color: #fff;
    background-clip: padding-box;
    border:2px solid #333;
    border-radius: .25rem;
    transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
}
.ldORxL {
    color: #ffffff !important;
    display: flex;
    -webkit-box-align: center;
    align-items: center;
    background: rgb(46, 46, 55);
    padding: 15px 14px 13px 14px;
    border-radius: 8px 8px 0px 0px;
    font-weight: 400 !important;
}
.ldORxL i{
    color: #ffffff !important;
    margin-right: 5px; 
}
.e-font-icon-svg {
    height: 100px;
}
.elementor-icon i, .elementor-icon svg {
    width: 1em;
    height: 1em;
    position: relative;
    display: block;
}
.elementor-icon {
 fill: #000000;
    color: #000000;
    border-color: #000000;
    display: inline-block;
    line-height: 1;
    transition: all .3s;
    color: #69727d;
    font-size: 50px;
    text-align: center;
}
.elementor-icon-box-icon{
    text-align:center!important;
}
.elementor-icon-box-wrapper {
    min-height: 300px; 
}
.page-content .table-bordered td, .table-bordered th {
    border: none!important;
}
.page-content .table td, .table th {
    width: 49%!important;
}
.owl-nav {
    position: absolute;
    top: 1%;
    width: 100%;
    transform: translateY(-50%);
    display: flex;
    justify-content: space-between;
}

.owl-nav .owl-prev,
.owl-nav .owl-next {
    width:30px;
    height:30px;
    background-color: rgba(0, 0, 0, 0.5); /* Cor de fundo semi-transparente */
    color: #fff;                           /* Cor do texto */
    padding: 2px .9%;
    border-radius: 50%;
    cursor: pointer;
    font-size: 18px;
}
.owl-nav .owl-prev {
    left: -15px; /* Ajuste a posição para a esquerda */
}
.owl-nav .owl-next {
    right: -15px; /* Ajuste a posição para a direita */
}
.navbar-brand img{
    min-height: 31px;
}
    /* Exibir na versão desktop */
    @media (max-width: 768px) {
        .navbar-brand img, .mobile-nav img{
    min-height: 20px;
}
        .owl-nav .owl-prev,
.owl-nav .owl-next {
    padding: 2.1px 2.7%;
}
        .select-filter-home{
            width:100% !important;
            min-width: 100% !important;
        }
      
.ldORxL {
    padding: 20px 14px 20px 14px;
}
        
    }
    @media (min-width: 768px) {
        #searchFormId {
            display: block;
        }
        #btn-filter{
            display: none;
        }
        #span-filter{
            display: block;
        }
        .buttons-filter{
            display:none;
        }
    }
    
    @media (max-width: 991px) {
        div#stickymenu > div.mobile-nav > a.logo > img{
            width: 150px !important;
            height: auto !important;
        }
        .select-filter-home{
            width:100% !important;
            min-width: 100% !important;
        }
        .search-section{
            height:342px !important;
        }        
    }
    @media (min-width: 991px) {
        #searchFormId {
            display: block;
        }
        #btn-filter{
            display: none;
        }
        #span-filter{
            display: block;
        }
        .buttons-filter{
            display:none;
        }
    }
    #brandspng.carousel-marcas .item form button{
        border: none !important;
    }
    #brandspng.carousel-marcas .item form button img{
        width: 84px !important;
  min-height: 86px !important;
        margin:3px !important;
        padding:2px !important;
    }
     .btn-follow {
    background-color: #c50f0f;
    color: #fff;
    border: 1px solid #c50f0f;
}
   .btn-follow:hover {
    background-color: #580303fa;
    color: #e1e1e1;
    border: 1px solid #580303fa;
}
.banner-img {
    display: block;
    width: 100%;
    height: 68vh;
    object-fit: cover;
    max-height: 800px;
}
@media (min-width: 1400px) {
    .banner-img {
        height: 70vh;
    }
}
@media (max-width: 991px) {
    .banner-img {
        height: 50vh;
    }
}
@media (max-width: 768px) {
    .banner-img {
        height: 40vh;
    }
}
@media (max-width: 480px) {
    .banner-img {
        height: 30vh;
    }
}
.search-section {
    background-color: rgba(13, 38, 59, 0.8);
    position: relative;
    left: 50%;
    transform: translate(-50%, -16%);
    z-index: 99;
    width: 90%;
    border-radius: 10px;
}
/* Mobile médio */
@media (max-width: 768px) {
    .search-section {
        background-color: #0d263b;
        top: 0;
        left: 0;
        transform: none;
        width: 100%;
        border-radius: 0;
        margin-top: 0;
    }
}
@media (max-width: 480px) {
    #slideshow{
        margin-top: 60px;
    }
    .search-section {
        background-color: #0d263b;
        top: 0;
        left: 0;
        transform: none;
        width: 100%;
        border-radius: 0;
        margin-top: 0;
    }
}
</style> 

@if(!empty($g_setting->google_tag_manager_status) && $g_setting->google_tag_manager_status == 'Show')
<!-- Google Tag Manager -->
<script>
    (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','{{ $g_setting->google_tag_manager_body }}');
</script>
<!-- End Google Tag Manager -->
@endif
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script defer="defer" src="https://unpkg.com/swiper/swiper-bundle.min.js" id="swiper-js-js"></script>
     <meta name="google-adsense-account" content="ca-pub-9171096234708042">
        <script>
            var w = window,
    d = document,
    e = d.documentElement,
    g = d.getElementsByTagName('body')[0],
    x = w.innerWidth || e.clientWidth || g.clientWidth,
    y = w.innerHeight|| e.clientHeight|| g.clientHeight;
  $(document).ready(function(){
      $("#slideshow").owlCarousel({
        items: 1,              // Exibe 1 item por vez
        dots: true,            // Habilita a navegação por dots (pontos)
        loop: true,            // Loop contínuo
        autoplay: true,        // Autoplay ativado
        autoplayTimeout: 5000, // Intervalo entre cada slide
        margin:0,
        singleItem:true,
        nav:true,
        navText: [
            "<i class='fa fa-caret-left'></i>",
            "<i class='fa fa-caret-right'></i>"
        ],
        responsive:{
            0:{
                items:1
            },
            600:{
                items:1
            },
            1000:{
                items:1
            },
            1200:{
                items:1
            },
            1400:{
                items:1
            },
            1600:{
                items:1
            },
            1800:{
                items:1
            }
        }
    });
    $("#brandspng").owlCarousel({
        items: 10,              // Exibe 1 item por vez
        dots: false,            // Habilita a navegação por dots (pontos)
        loop: true,            // Loop contínuo
        autoplay: true,        // Autoplay ativado
        autoplayTimeout: 4000, // Intervalo entre cada slide
        nav: true,   
        margin: 0,// Ativa as setas de navegação
        navText: ["<", ">"],         // Define o símbolo das setas, você pode personalizar com ícones ou HTML
        responsive: {
            0: {
                items: 4,            // Itens visíveis em telas menores
            },
            600: {
                items: 4,            // Itens em tablets
            },
            1000: {
                items: 10            // Itens em desktops
            }
        }
    });
    $("#10-razoes").owlCarousel({
        items: 4,              // Exibe 1 item por vez
        dots: false,            // Habilita a navegação por dots (pontos)
        loop: true,            // Loop contínuo
        autoplay: true,        // Autoplay ativado
        autoplayTimeout: 3000, // Intervalo entre cada slide
        nav: false,    
        margin: 1,// Ativa as setas de navegação
        responsive: {
            0: {
                items: 1,            // Itens visíveis em telas menores
            },
            600: {
                items: 2,            // Itens em tablets
            },
            1000: {
                items: 4            // Itens em desktops
            }
        }
    });
  });
</script>
</head>
<body>
<script type="text/javascript" async src="https://d335luupugsy2.cloudfront.net/js/loader-scripts/0fe66ecd-a6fd-49ea-aef3-80bbe2fcf695-loader.js"></script>
@if(!empty($g_setting->google_tag_manager_status) && $g_setting->google_tag_manager_status == 'Show')
<!-- Google Tag Manager -->
<noscript>
<iframe src="https://www.googletagmanager.com/ns.html?id={{ $g_setting->google_tag_manager_body }}"
height="0" width="0" style="display:none;visibility:hidden"></iframe>
</noscript>
<!-- End Google Tag Manager -->
@endif
<!--<div id="mySidepanel" class="sidepanel">
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
    <a href="#">About</a>
    <a href="#">Services</a>
    <a href="#">Clients</a>
    <a href="#">Contact</a>
</div>     -->    
        <div class="top">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-md-5 col-sm-12">
                        <ul class="top-left" style="margin-top: 0 !important;margin-bottom: 0 !important;">                            
                           @if($g_setting->top_phone!='')
                           <li style="padding-top:2px!important;display:none;">
                               <a class="btn btn-success btn-sm" href="https://api.whatsapp.com/send?phone=55{{ str_replace([' ', '.', '-', ')', '('], '', $g_setting->top_phone) }}">
                                   <i class="fab fa-whatsapp"></i> 
                                   chamar no whatsapp
                               </a>
                           </li>
                            @endif
                            @if($g_setting->top_email!='')
                            <li><i class="fas fa-envelope"></i> {{ $g_setting->top_email }}</li>
                            @endif
                        </ul>
                    </div>
                    <div class="col-lg-6 col-md-7 col-sm-12">
                        @if($g_setting->customer_listing_option == 'On')
                        <ul class="top-right" style="margin-bottom: 5px !important;">
                            <li>
                                <form action="{{ route('front_currency') }}" method="post">
                                    @csrf
                                    <select name="currency_name" class="nav-link" onchange="this.form.submit()" style="display:none;">
                                        @foreach($currency_list as $row)
                                            <option value="{{ $row->name }}" @if($row->name == session()->get('currency_name')) selected @endif>{{ $row->name }}</option>
                                        @endforeach
                                    </select>
                                </form>
                            </li>
                            @if(Auth::user())
                            <li><a href="{{ route('customer_logout') }}" class="nav-link">
                                    <i class="fas fa-sign-out-alt"></i> {{ LOGOUT }}</a>
                            </li>
                            @endif
                            <li>
                                @if(Auth::user())
                                <a href="{{ route('customer_dashboard') }}" class="nav-link"><i class="fas fa-sign-in-alt"></i> Minha Conta</a>                                
                                @else
                                <a href="{{ route('customer_login') }}" class="nav-link"><i class="fas fa-sign-in-alt"></i> Login</a>
                                @endif
                            </li>
                            
                            <li class="currency">
                                <a href="{{ route('customer_listing_add') }}" class="nav-link"><i class="fas fa-plus"></i> Anunciar</a>
                            </li>                            
                        </ul>
                        @endif
                    </div>
                </div>
            </div> 
        </div>
		@include('front.app_nav')  
		@yield('content')   
            <!-- Modal -->
<div class="modal fade" id="add-versao" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel" style="font-size: 16px;">Adicionar versão do veículo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <form action="" method="post" id="form-add-versao">
            @csrf
      <div class="modal-body">
        <input type="hidden" class="form-control modal-marcaId" name="marcaId"/>
        <input type="hidden" class="form-control modal-modeloId" name="modeloId"/>
        <input type="hidden" class="form-control" name="canal" value="website"/>
        <div class="form-group">
            <label>Nome da versão *</label>
            <input type="text" name="versao_name" class="form-control" placeholder="Digite o nome da versão" required=""/>
        </div>
      </div>
      <div class="modal-footer">
          <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
          <div class="btn-group btn-group-lg" role="group" aria-label="Basic example">
  <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
        <button type="submit" class="btn btn-success">Salvar</button>
</div>
</div>
        
      </div>
        </form>
    </div>
  </div>
</div>                               
                @include('front.app_footer')
      	<div class="scroll-top">
		  	<i class="fas fa-long-arrow-alt-up"></i>
	    </div>
	    @include('front.app_scripts_footer')              
                    @if($route == 'front_listing_detail')
<div style="position: fixed !important;bottom:0 !important;z-index:9 !important;width:100%;background-color:#ffffff;border-radius:0;">
<div class="btn-group btn-flat" role="group" aria-label="Basic example" style="width:100%;border-radius:0;">
    <a type="button" onclick="verFotos();return false;" class="btn btn-dark btn-flat" style="padding: .675rem .8rem !important;font-size: .950rem;border-radius:0;">
                                                <i class="fas fa-images" style="font-weight: 300;"></i> galeria
                            </a>
  <a type="button" class="btn btn-danger btn-flat" onclick="simular_parcelas();" style="padding: .675rem .8rem !important;font-size: .950rem;border-radius:0;">
      simular parcelas 
  </a>
  @if($agent_detail->phone!='')
	<a href="tel:{{ $agent_detail->phone }}" class="btn btn-dark btn-flat" style="padding: .675rem .8rem !important;font-size: .950rem;border-radius:0;">
            <i class="fas fa-phone-volume"></i> ligar</a>                                                              
    @endif
  <a type="button" class="btn btn-danger btn-flat" onclick="enviar_mensagens();" style="padding: .675rem .8rem !important;font-size: .950rem;border-radius:0;">
      <i class="far fa-envelope"></i> mensagem</a>
</div>
</div>
    
<script>    
    function simular_parcelas(){
        document.getElementById('parcelas').scrollIntoView({ behavior: 'smooth' });
    }    
    function enviar_mensagens(){
        document.getElementById('div_mensagem').scrollIntoView({ behavior: 'smooth' });
    }    
</script>
@endif
       <script>
           
       // Espera o DOM ser carregado para garantir que o elemento foi encontrado
document.addEventListener("DOMContentLoaded", function () {
    // Seleciona o menu que será fixado
    const navbar = document.getElementById("stickymenu");
    const stickyOffset = navbar.offsetTop; // Define o ponto em que o menu se tornará fixo

    // Função para adicionar/remover a classe sticky
    function stickyMenu() {
        if (window.pageYOffset > stickyOffset) {
            navbar.classList.add("stickymenu");
        } else {
            navbar.classList.remove("stickymenu");
        }
    }

    // Escuta o evento de scroll para chamar a função stickyMenu
    window.addEventListener("scroll", stickyMenu);
});
 $(function () {
  $('[data-toggle="tooltip"]').tooltip();
});
    
    
    function applyCarPlateMask(input) {
    const value = input.value
        .toUpperCase() // Converte para maiúsculas
        .replace(/[^A-Z0-9]/g, ''); // Remove caracteres inválidos
    
    const formatted = value.replace(
        /^([A-Z]{0,3})([0-9]{0,1})([A-Z]{0,1})([0-9]{0,2})$/,
        (match, p1, p2, p3, p4) => `${p1}${p2}${p3}${p4}`
    );

    input.value = formatted;
}

function generateSlug(listing_name,listing_slug) {
        const title = document.getElementById(listing_name).value;
        
        // Convert to lowercase and remove any characters that are not alphanumeric, hyphens, or spaces
        const slug = title.toLowerCase()
            .normalize('NFD') // Normalize letters with accents
            .replace(/[\u0300-\u036f]/g, '') // Remove accent marks
            .replace(/[^a-z0-9\s-]/g, '') // Remove invalid characters
            .trim() // Remove leading and trailing spaces
            .replace(/\s+/g, '-') // Replace spaces with hyphens
            .replace(/-+/g, '-'); // Ensure single hyphens between words
        
        document.getElementById(listing_slug).value = slug;
    }

function slug(title) {
        
        // Convert to lowercase and remove any characters that are not alphanumeric, hyphens, or spaces
        const slug = title.toLowerCase()
            .normalize('NFD') // Normalize letters with accents
            .replace(/[\u0300-\u036f]/g, '') // Remove accent marks
            .replace(/[^a-z0-9\s-]/g, '') // Remove invalid characters
            .trim() // Remove leading and trailing spaces
            .replace(/\s+/g, '-') // Replace spaces with hyphens
            .replace(/-+/g, '-'); // Ensure single hyphens between words
        
        return slug;
    }
    function formatReal(input) {
            let value = input.value;

            // Remove todos os caracteres que não sejam números
            value = value.replace(/\D/g, '');

            // Formata o número com separador de milhar
            const formattedValue = parseInt(value || '0', 10).toLocaleString('pt-BR');

            input.value = formattedValue;
        }
        function mascara_checkout(i, t) {
    let v = i.value.replace(/\D/g, ''); // Remove tudo que nÃ£o for dÃ­gito
    let x = i.value; // Remove tudo que nÃ£o for dÃ­gito
    
    if (t === "cpf") {
        i.setAttribute("maxlength", "14");
        if (v.length > 3 && v.length <= 6) {
            v = v.replace(/(\d{3})(\d+)/, "$1.$2");
        } else if (v.length > 6 && v.length <= 9) {
            v = v.replace(/(\d{3})(\d{3})(\d+)/, "$1.$2.$3");
        } else if (v.length > 9) {
            v = v.replace(/(\d{3})(\d{3})(\d{3})(\d+)/, "$1.$2.$3-$4");
        }
        i.value = v;
    }
    if (t === "cep") {
        i.setAttribute("maxlength", "9");
        if (v.length > 0) {
            v = v.replace(/(\d{5})(\d{3})/, "$1-$2");
        }
        i.value = v.substring(0, 15);
    }
    if (t === "data") {
        i.setAttribute("maxlength", "10");
        if (v.length >= 2 && v.length < 4) {
            v = v.replace(/(\d{2})(\d+)/, "$1/$2");
        } else if (v.length >= 4) {
            v = v.replace(/(\d{2})(\d{2})(\d+)/, "$1/$2/$3");
        }
        i.value = v;
    }
    if (t === "cel") {
        if(v.length <= 10){
            i.setAttribute("maxlength", "14");
             i.value = v.substring(0, 14);
             v = v.replace(/(\d{1})(\d{1})(\d{4})(\d{4})/, "($1$2) $3-$4");
        }
        if (v.length >= 11) {
            i.setAttribute("maxlength", "15");
            i.value = v.substring(0, 15);
            v = v.replace(/(\d{1})(\d{1})(\d{5})(\d{4})/, "($1$2) $3-$4");
        }
         // Limita a string para o tamanho mÃ¡ximo
    }
}

    function loadVersoesFront(modeloId, selectedVersaoId = null) {
            $('.versaoId').empty().append('<option value="">Selecione a Versão</option>');

            if (modeloId) {
                $.ajax({
                    url: '/combo-versao/' + modeloId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $.each(data, function(key, versao) {
                            let isSelected = (selectedVersaoId && versao.id == selectedVersaoId) ? 'selected' : '';
                            $('.versaoId').append(`<option value="${versao.versao_slug}" ${isSelected}>${versao.versao_name}</option>`);
                        });
                    },
                    error: function() {
                        alert('Erro ao buscar as versões.');
                    }
                });
            }
        }
    function addVersao(){
               var modal_marcaId = $('.marcaId').val();
               var modal_modeloId = $('.modeloId').val();
               if(modal_marcaId && modal_modeloId){
               $('#add-versao').modal('show'); 
           }else{
               alert('Você precisa selecionar uma marca e um modelo para adicionar uma versão!');
               }
           }

        $(document).ready(function() {
            
            $('#form-add-versao').submit(function(e){
                e.preventDefault();
                var rota = '{{ route('customer_listing_add_versao') }}';
                var dados= $(this).serialize();
                
                $.ajax({
                    url: rota,
                    type: 'POST',
                    data:dados,
                    dataType: 'json',
                    success: function(data) {
                        if(data.status == 'error'){
                            toastr.error(data.msg);
                        }else{
                             toastr.success(data.msg);
                             $('#add-versao').modal('hide'); 
                             loadVersoesFront(data.modeloId,data.id);
                             //$('.versaoId option').attr('selected',true).val(data.slug).text(data.name);
                        }
                    },
                    error: function() {
                        alert('Erro');
                    }
                });
            });
            
            let modeloSelecte = $('.modeloId');
        let versaoSelecte = $('.versaoId');
        let selectedBrandIde = $('.marcaId').val();
        let selectedEstado_UFIde = $('.estado_UF').val();
        let selectedModeloIde = null; // ID do modelo salvo no banco
        let selectedVersaoIde = null; // ID da versão salvo no banco
        
        let cidadeSelecte = $('.cidade');
        let selectedUfIde = $('.uf').val();
        let selectedCidadeIde = null; // ID do modelo salvo no banco
            
            function loadCidadesUF(ufId, selectedCidadeIde = null) {
            cidadeSelecte.empty().append('<option value="">Selecione a Cidade</option>');

            if (ufId) {
                $.ajax({
                    url: '/combo-cidades/' + ufId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $.each(data, function(key, cidade) {
                            
                            var attrID = slug(cidade.cidade_nome);
                            let isSelected = (selectedCidadeIde && attrID == selectedCidadeIde) ? 'selected' : '';
                            
                            cidadeSelecte.append(`<option id="${attrID}" value="${cidade.cidade_id}" ${isSelected}>${cidade.cidade_nome}</option>`);
                        });
                    },
                    error: function() {
                        alert('Erro ao buscar as cidades.');
                    }
                });
            }
        }

            function limpa_formulário_cep() {
                // Limpa valores do formulário de cep.
                $(".rua").val("");
                $(".bairro").val("");
                $(".cidade").val("");
                $(".uf").val("");
                $(".listing_address").val("");
            }
            
            //Quando o campo cep perde o foco.
            $(".cep").blur(function() {

                //Nova variável "cep" somente com dígitos.
                var cep = $(this).val().replace(/\D/g, '');

                //Verifica se campo cep possui valor informado.
                if (cep != "") {

                    //Expressão regular para validar o CEP.
                    var validacep = /^[0-9]{8}$/;

                    //Valida o formato do CEP.
                    if(validacep.test(cep)) {

                        //Preenche os campos com "..." enquanto consulta webservice.
                        $(".rua").val("...");
                        $(".bairro").val("...");
                        $(".cidade").val("...");
                        $(".uf").val("...");
                        $(".listing_address").val("Aguarde...");

                        //Consulta o webservice viacep.com.br/
                        $.getJSON("https://viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {

                            if (!("erro" in dados)) {
                              
                                //Atualiza os campos com os valores da consulta.
                                $(".rua").val(dados.logradouro);
                                $(".bairro").val(dados.bairro);
                                $(".cidade").val(dados.localidade);
                                $(".uf").val(dados.uf);
                                $(".listing_address").val(dados.logradouro+', '+dados.localidade+', '+dados.uf+', '+$('.cep').val());
                                
                                let localidadeCity = slug(dados.localidade.trim());
                                var estado = slug(dados.estado.trim()+' - '+dados.uf.trim());                               
                                
                                $(".cidade option").each(function () {
                                                                           
                                    if (slug($(this).text().trim()) === localidadeCity) {                                       
                                        $(this).prop("selected", true);
                                        $(this).attr("selected", true);
                                        return false; // Para o loop ao encontrar a cidade
                                    }
                                });
                                $(".uf option").each(function () {
                                    
                                    if(slug($(this).text().trim()) == 'selecione-uma-opcao'){
                                        $('#optUF').removeAttr('selected').remove();
                                    }
                                    if (slug($(this).text().trim()) === estado) {                                        
                                        $(this).prop("selected", true);
                                        $(this).attr("selected", true);
                                        loadCidadesUF($(this).val(),localidadeCity);
                                        $('.estado_UF').val($(this).val());
                                        return false; // Para o loop ao encontrar a cidade
                                    }
                                });
                            } //end if.
                            else {
                                //CEP pesquisado não foi encontrado.
                                limpa_formulário_cep();
                                console.log("CEP não encontrado.");
                            }
                        });
                    } //end if.
                    else {
                        //cep é inválido.
                        limpa_formulário_cep();
                        console.log("Formato de CEP inválido.");
                    }
                } //end if.
                else {
                    //cep sem valor, limpa formulário.
                    limpa_formulário_cep();
                }
            });
        });
        
        
@if($route == 'front_listing_detail')
    @php
        $id = request()->route('id');
        $slug = request()->route('slug');
        $item_row = \App\Models\Listing::where('id', $id)->where('listing_slug', $slug)->first();
    @endphp
$(document).ready(function() {
        if (navigator.canShare) {
            $('#share').show();
        }
        $(document).on('click', '#share', function(){
            var title = '{{ !empty($item_row->seo_title) ? $item_row->seo_title : $item_row->listing_name }}';
            var text = '{{ !empty($item_row->seo_title) ? $item_row->seo_title : $item_row->listing_name }}';
            var url = '{{ route('front_listing_detail',[$item_row->id,$item_row->listing_slug]) }}';
        if (navigator.share) {
                navigator.share({
                    title: title,
                    text: text,
                    url: url,
                })
                    .then(() => console.log('Successful share'))
                    .catch((error) => console.log('Error sharing', error));
            }
        });
    });
@endif
    </script>
        @if($route == 'front_listing_agent_detail' || $route == 'front_lojas')
    <script>
function follow(user_id,object){
		if (!user_id || !object) { return false; }
                
                @if($current_auth_user_id == 0)
                    alert('Para seguir você deve fazer login');
                    return false;
                @endif
                
		object = $(object);               

		if (object.hasClass('btn-following') == false) {
			object.find('span').text("Seguindo");
			object.find('svg').html('<path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><polyline points="17 11 19 13 23 9"></polyline>');

			if (!object.hasClass('btn-following')) {
				object.addClass('btn-following');
			}
		}
		else if(object.hasClass('btn-following') == true){
			object.find('span').text("Seguir");
			object.find('svg').html('<path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line>');

			if (object.hasClass('btn-following')) {
				object.removeClass('btn-following');
			}
		}
		else{
			return false;
		}
		$.ajax({
                    url: '{{ url('/follow') }}', 
                    type: 'GET',
                    dataType: 'json',
                    data: {user_id: user_id},
                    success: function(data) {
                        if (data.status === 'following') {
                            object.addClass('btn-following');
                            object.find('span').text("Seguindo");
                            object.find('svg').html('<path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><polyline points="17 11 19 13 23 9"></polyline>');
                        } else {
                            object.removeClass('btn-following');
                            object.find('span').text("Seguir");
                            object.find('svg').html('<path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line>');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                    }
                });
	}
                </script>
        @endif
        @if($g_setting->top_phone!='')                           
                           <!--<a href="https://wa.me/55{{ str_replace([' ', '.', '-', ')', '('], '', $g_setting->top_phone) }}?text=Olá tudo bem? Quero saber mais!" 
                              style="position:fixed;width:60px;height:60px;bottom:100px;right:17px;background-color:#25d366;color:#FFF;border-radius:50px;text-align:center;font-size:30px;box-shadow: 1px 1px 2px #888;
  z-index:1000;" target="_blank">
<i style="margin-top:16px" class="fab fa-whatsapp"></i>
</a>-->
                            @endif
        
<script type="text/javascript">
endereco = "{{ asset('frontend/js/1449716988_snowflake.png') }}";
</script> 
<script> window.chtlConfig = { chatbotId: "3246166821" } </script>
<script async data-id="3246166821" id="chatling-embed-script" type="text/javascript" src="https://chatling.ai/js/embed.js"></script>
<!--<script type="text/javascript" src="{{ asset('frontend/js/neves.js') }}"></script>-->
   </body>  
</html> 