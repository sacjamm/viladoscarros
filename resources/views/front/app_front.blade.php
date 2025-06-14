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
                    <link rel="preload" href="{{ asset('uploads/banner_photos/'.$row->image) }}" as="image" />
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
            @php
                $imgDestaqueMeta = '';

                if ($item_row->canal === 'dsautoestoque') {
                    if ($item_row->listing_featured_photo === 'images/sem-veiculo.jpg') {
                        $imgDestaqueMeta = asset('images/sem-veiculo.jpg');
                    } else {
                        if ($item_row->listing_image_alterada_admin == 1) {
                            $imgDestaqueMeta = asset('uploads/listing_featured_photos_thumbs/thumb_' . $item_row->listing_featured_photo);
                        } else {
                            $isFromDsae = strpos($item_row->listing_featured_photo, 'dsae') !== false;

                            $imgDestaqueMeta = $isFromDsae
                                ? $item_row->listing_featured_photo
                                : asset('uploads/listing_featured_photos_thumbs/thumb_' . $item_row->listing_featured_photo);
                        }
                    }
                } else {
                    $imgDestaqueMeta = asset('uploads/listing_featured_photos_thumbs/thumb_' . $item_row->listing_featured_photo);
                }
            @endphp
            <meta property="og:image" content="{{ $imgDestaqueMeta }}" />           
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
        @php
            $imgDestaqueIcon = '';

            if ($item_row->canal === 'dsautoestoque') {
                if ($item_row->listing_featured_photo === 'images/sem-veiculo.jpg') {
                    $imgDestaqueIcon = asset('images/sem-veiculo.jpg');
                } else {
                    if ($item_row->listing_image_alterada_admin == 1) {
                        $imgDestaqueIcon = asset('uploads/listing_featured_photos_thumbs/thumb_' . $item_row->listing_featured_photo);
                    } else {
                        $isFromDsae = strpos($item_row->listing_featured_photo, 'dsae') !== false;

                        $imgDestaqueIcon = $isFromDsae
                            ? $item_row->listing_featured_photo
                            : asset('uploads/listing_featured_photos_thumbs/thumb_' . $item_row->listing_featured_photo);
                    }
                }
            } else {
                $imgDestaqueIcon = asset('uploads/listing_featured_photos_thumbs/thumb_' . $item_row->listing_featured_photo);
            }
        @endphp
            <link rel="icon" type="image/png" href="{{ $imgDestaqueIcon }}">
    @endif
@endif
		@include('front.app_styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="preload" href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" as="style">
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet" media="all" onload="this.media='all'">
<noscript>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</noscript>
<!--aqui ficava o script js-->
<!-- All Javascripts -->
<script src="{{ asset('frontend/js/jquery-3.7.1.min.js') }}"></script>

		@if($g_setting->tawk_live_chat_status == 'Show')
		<style>
		.scroll-top {bottom: 88px!important;}
		</style>
		@endif
        @if($g_setting->google_analytic_status == 'Show')
        @if($route != null)
            <script async src="https://www.googletagmanager.com/gtag/js?id={{ $g_setting->google_analytic_tracking_id }}"></script>
            <script>
                window.dataLayer = window.dataLayer || [];
                function gtag(){dataLayer.push(arguments);}
                gtag('js', new Date());
                gtag('config', '{{ $g_setting->google_analytic_tracking_id }}');
            </script>
        @endif
        @endif        
        <style>
    .mobile-nav.mean-container .mean-nav ul li a.active,.main-nav nav .navbar-nav .nav-item a:hover,.main-nav nav .navbar-nav .nav-item a:focus,.main-nav nav .navbar-nav .nav-item a.active,.main-nav nav .navbar-nav .nav-item:hover a,.main-nav nav .navbar-nav .nav-item .dropdown-menu li a:hover,.main-nav nav .navbar-nav .nav-item .dropdown-menu li a:focus,.main-nav nav .navbar-nav .nav-item .dropdown-menu li a.active,.main-nav nav .navbar-nav .nav-item .dropdown-menu li .dropdown-menu li a:hover,.main-nav nav .navbar-nav .nav-item .dropdown-menu li .dropdown-menu li a:focus,.main-nav nav .navbar-nav .nav-item .dropdown-menu li .dropdown-menu li a.active,.main-nav nav .navbar-nav .nav-item .dropdown-menu li:hover a,
    .main-nav nav .navbar-nav .nav-item .dropdown-menu li a:hover,
    .listing .listing-item .text .location,.listing .listing-item .text h3 a:hover,
    .footer-item h2,.footer-item ul li a:hover,.listing-filter .lf-heading,.listing .listing-item .text .location a,
    .listing-single-banner .listing-items a,.listing-page h2 i,.listing-page .amenities li i,.listing-page .contact a,.listing-page .review-overall .total,.listing-sidebar .ls-widget .agent-contact li,.listing-sidebar .ls-widget .agent-contact li a,.listing-sidebar .ls-widget .category ul li a,.faq h4.panel-title a,.sidebar .widget .type-1 ul li:before,.sidebar .widget .type-1 ul li a:hover,.contact-icon i,.reg-login-form .new-user a,.reg-login-form .link,.listing-page .room-all .item .price,.popular-city .popular-city-item:hover h4{
    color: #{{ $g_setting->theme_color }}
}
.main-nav nav .navbar-nav .nav-item .dropdown-menu li a:hover,.footer-social-link ul li a:hover{
    color:#000 !important
}
.mean-container a.meanmenu-reveal{
    color:#fff !important
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
.top,.agent-banner .social a:hover,
.mean-container a.meanmenu-reveal span,
.comment button{
    background:#{{ $g_setting->theme_color }}
}
.footer-social-link ul li a,.contact-form .btn{
    border-color:#{{ $g_setting->theme_color }}
}
.listing-filter .lf-heading{
    border-bottom-color:#{{ $g_setting->theme_color }}
}
.navbar-brand{
    padding-top:0;
    padding-bottom:0
}
.img-fade {
    opacity: 0;
    transition: opacity 0.5s ease-in-out;
}
.img-fade.loaded {
    opacity: 1;
}

.footer-area .footer-contact ul li p, .footer-area .footer-contact ul li, .footer-area .footer-contact ul{
    font-size: 0.650rem;
}
.listing .listing-item .text{
    padding: 20px 7px !important;
}
/* Estilos para as notificaÃ§Ãµes de atividade recente */
.recent-activity-container {
    position: fixed;
    bottom: 20px;
    left: 20px;
    z-index: 1000;
    width: 320px;
    max-width: calc(100% - 40px);
}

.activity-notification {
    background-color: var(--white);
    border-left: 4px solid var(--dark);
    border-radius: 12px;
    box-shadow: var(--card-shadow);
    padding: 16px;
    margin-bottom: 12px;
    transform: translateX(-120%);
    transition: transform 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    display: flex;
    align-items: center;
    gap: 14px;
}

.activity-notification.show {
    transform: translateX(0);
}

.activity-avatar {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: #212175;
    color: var(--white);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 18px;
    flex-shrink: 0;
    box-shadow: 0 2px 5px rgba(37, 99, 235, 0.2);
}

.activity-content {
    flex-grow: 1;
}

.activity-content p {
    margin: 0;
    font-size: 14px;
    line-height: 1.5;
    color: var(--text-light);
}

.activity-content .activity-name {
    font-weight: 700;
    color: var(--text);
}

.activity-content .activity-time {
    font-size: 12px;
    color: var(--text-light);
    margin-top: 6px;
    display: flex;
    align-items: center;
    gap: 5px;
}

.activity-content .activity-time::before {
    content: '';
    display: inline-block;
    width: 6px;
    height: 6px;
    background-color: var(--success);
    border-radius: 50%;
}

.activity-close {
    background: none;
    border: none;
    color: var(--text-light);
    cursor: pointer;
    font-size: 14px;
    padding: 4px;
    opacity: 0.7;
    transition: opacity 0.3s;
}

.activity-close:hover {
    opacity: 1;
}

.footer-area {
    margin-top: 10px !important;
    padding-top: 10px !important;
    padding-bottom: 10px !important;
}

.footer-item, .footer-social-link {
    margin-top: 10px !important;
}

.footer-item h2 {
    font-size: 16px;
    margin: 0 0 10px;
}

@media (max-width: 576px) {   
    .recent-activity-container {
        bottom: 10px;
        left: 10px;
        width: calc(100% - 20px);
    }
}


        </style>
@if(!empty($g_setting->google_tag_manager_status) && $g_setting->google_tag_manager_status == 'Show')
@if($route != null)
<script>
    (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','{{ $g_setting->google_tag_manager_body }}');
</script>
@endif
@endif
<link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"></noscript>
     <meta name="google-adsense-account" content="ca-pub-9171096234708042">
        
</head>
<body>
    @if($route != null)
<script type="text/javascript" async src="https://d335luupugsy2.cloudfront.net/js/loader-scripts/0fe66ecd-a6fd-49ea-aef3-80bbe2fcf695-loader.js"></script>
@endif
@if(!empty($g_setting->google_tag_manager_status) && $g_setting->google_tag_manager_status == 'Show')
@if($route != null)
<noscript>
<iframe src="https://www.googletagmanager.com/ns.html?id={{ $g_setting->google_tag_manager_body }}"
height="0" width="0" style="display:none;visibility:hidden"></iframe>
</noscript>
@endif
@endif  
        <div class="top">
            <div class="container">
                <div class="row">                    
                    <div class="col-md-3">
                        @if($g_setting->customer_listing_option == 'On')
                        
                                @if(Auth::user())
                                <a href="{{ route('customer_dashboard') }}" class="btn btn-outline-light btn-sm" style="border-radius:0;"><i class="fas fa-gear"></i> Minha Conta</a>                                
                                @else
                                <a href="{{ route('customer_login') }}" class="btn btn-outline-light btn-sm" style="border-radius:0;"><i class="fas fa-sign-in-alt"></i> Login</a>
                                @endif
                            
                                <a href="{{ route('customer_listing_add') }}" class="btn btn-outline-light btn-sm" style="border-radius:0;"><i class="fas fa-plus"></i> Anunciar</a>
                          
                        @endif
      
    </div>
    <div class="col-md-8">
     
    </div>
    <div class="col-md-1">
      @if(Auth::user())
                            <a href="{{ route('customer_logout') }}" class="btn btn-outline-danger btn-sm" style="border-radius:0;">
                                    <i class="fas fa-sign-out-alt"></i> {{ LOGOUT }}</a>
                            
                            @endif
    </div>     
                </div>
            </div> 
        </div>
		@include('front.app_nav')  
		@yield('content')   
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
                <div class="recent-activity-container" id="recent-activity-container"></div>
                @include('front.app_scripts')
                @if($route != null)
<script defer="defer" src="https://unpkg.com/swiper/swiper-bundle.min.js" id="swiper-js-js"></script>
@endif
                
<script>
            var w = window,
    d = document,
    e = d.documentElement,
    g = d.getElementsByTagName('body')[0],
    x = w.innerWidth || e.clientWidth || g.clientWidth,
    y = w.innerHeight|| e.clientHeight|| g.clientHeight;
    
    
 </script> 
 @if($route == '' || $route == 'front_listing_result_veiculos' || $route == 'front_about' || $route == 'front_lojas' || $route == 'index' || $route == 'home' || $route === 'busca_front_listing_result')
 <script>
  $(document).ready(function(){       
    $("#brandspng").owlCarousel({
        items: 10,  
        lazyLoad: true,       
        loop: false,           
        autoplay: true,        
        autoplayTimeout: 4000,
    autoplayHoverPause: true,
        nav: true,   
        dots:false,
        margin: 0,
        navText: [
                        "<i class='fa fa-caret-left'></i>",
                        "<i class='fa fa-caret-right'></i>"
                    ],        
        responsive: {
            0: {
                items: 3,           
            },
            600: {
                items: 3,            
            },
            1000: {
                items: 10            
            }
        }
    });
    $("#10-razoes").owlCarousel({
        items: 4,   
        lazyLoad: true,
        loop: false,            
        autoplay: true,        
        autoplayTimeout: 3000, 
        nav: true,   
        navText: [
                        "<i class='fa fa-caret-left'></i>",
                        "<i class='fa fa-caret-right'></i>"
                    ],
        margin: 1,
        responsive: {
            0: {
                items: 1,           
            },
            600: {
                items: 2,           
            },
            1000: {
                items: 4           
            }
        }
    });
  });
</script>
    @endif
                
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
document.addEventListener("DOMContentLoaded", function () {
    const buttons = document.querySelectorAll('.brand-button');
    buttons.forEach(button => {
        button.addEventListener('click', function () {
            const brandId = this.getAttribute('data-brand-id');

            fetch("{{ route('busca_front_listing_result') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    brand: [brandId]
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.redirect) {
                    window.location.href = data.redirect;
                } else {
                    alert("Busca realizada com sucesso!");
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                alert("Erro ao buscar.");
            });
        });
    });
});
</script>             
                    
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
document.addEventListener("DOMContentLoaded", function () {
    const navbar = document.getElementById("stickymenu");
    const stickyOffset = navbar.offsetTop;
    function stickyMenu() {
        if (window.pageYOffset > stickyOffset) {
            navbar.classList.add("stickymenu");
        } else {
            navbar.classList.remove("stickymenu");
        }
    }
    window.addEventListener('scroll', stickyMenu, { passive: true });
});
 $(function () {
  $('[data-toggle="tooltip"]').tooltip();
});        
    function applyCarPlateMask(input) {
    const value = input.value
        .toUpperCase() 
        .replace(/[^A-Z0-9]/g, '');     
    const formatted = value.replace(
        /^([A-Z]{0,3})([0-9]{0,1})([A-Z]{0,1})([0-9]{0,2})$/,
        (match, p1, p2, p3, p4) => `${p1}${p2}${p3}${p4}`
    );
    input.value = formatted;
}

function generateSlug(listing_name,listing_slug) {
        const title = document.getElementById(listing_name).value;
        const slug = title.toLowerCase()
            .normalize('NFD') 
            .replace(/[\u0300-\u036f]/g, '') 
            .replace(/[^a-z0-9\s-]/g, '') 
            .trim() 
            .replace(/\s+/g, '-') 
            .replace(/-+/g, '-');         
        document.getElementById(listing_slug).value = slug;
    }

function slug(title) {
        const slug = title.toLowerCase()
            .normalize('NFD') 
            .replace(/[\u0300-\u036f]/g, '') 
            .replace(/[^a-z0-9\s-]/g, '') 
            .trim() 
            .replace(/\s+/g, '-') 
            .replace(/-+/g, '-');        
        return slug;
    }
    function formatReal(input) {
            let value = input.value;
            value = value.replace(/\D/g, '');
            const formattedValue = parseInt(value || '0', 10).toLocaleString('pt-BR');
            input.value = formattedValue;
        }
        function mascara_checkout(i, t) {
    let v = i.value.replace(/\D/g, ''); 
    let x = i.value; 
    
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
        let selectedModeloIde = null;
        let selectedVersaoIde = null;         
        let cidadeSelecte = $('.cidade');
        let selectedUfIde = $('.uf').val();
        let selectedCidadeIde = null; 
            
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
                $(".rua").val("");
                $(".bairro").val("");
                $(".cidade").val("");
                $(".uf").val("");
                $(".listing_address").val("");
            }
            $(".cep").blur(function() {
                var cep = $(this).val().replace(/\D/g, '');
                if (cep != "") {
                    var validacep = /^[0-9]{8}$/;
                    if(validacep.test(cep)) {
                        $(".rua").val("...");
                        $(".bairro").val("...");
                        $(".cidade").val("...");
                        $(".uf").val("...");
                        $(".listing_address").val("Aguarde...");
                        $.getJSON("https://viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {
                            if (!("erro" in dados)) {                              
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
                                        return false; 
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
                                        return false; 
                                    }
                                });
                            } 
                            else {
                                limpa_formulário_cep();
                                console.log("CEP não encontrado.");
                            }
                        });
                    } 
                    else {
                        limpa_formulário_cep();
                        console.log("Formato de CEP inválido.");
                    }
                } 
                else {
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
                            @endif        
<script type="text/javascript">
endereco = "{{ asset('frontend/js/1449716988_snowflake.png') }}";
</script> 
<script> window.chtlConfig = { chatbotId: "3246166821" } </script>
<script async data-id="3246166821" id="chatling-embed-script" type="text/javascript" src="https://chatling.ai/js/embed.js" defer></script>
<!--<script type="text/javascript" src="{{ asset('frontend/js/neves.js') }}"></script>-->
   </body>  
</html> 