@extends('front.app_front')

@section('content')

<!--<script type='text/javascript' src='https://platform-api.sharethis.com/js/sharethis.js#property=5993ef01e2587a001253a261&product=inline-share-buttons' async='async'></script>-->

@if($detail->listing_status != 'Active')
<style>
    .listing{
        margin-bottom:0!important;padding-bottom:0!important;padding-top:50px;
    }
    @media (max-width: 768px) {
        .listing{
        padding-top:70px;
    }
    .img-responsive{
        width: 90%;
    }
    }
</style>
<div class="listing">
<div class="container">
<div class="row">
<div class="col-md-12" style="text-align:center;">
<h1>Esse anúncio está em processo de aprovação</h1>
<a href="{{ route('front_listing_result_veiculos') }}" class="btn btn-block btn-dark">Buscar anúncios</a>
<img src="{{ asset('images/aprovacao.jpg') }}" alt="{{ asset('images/aprovacao.jpg') }}" title="Aprovação" class="img-responsive" />
</div>
</div>
</div>
</div>
@else
 		                  
                    <style>  
                        .page-content{
                top: -47px;
            }
        .swiper-container {
            width: 100%;
            max-width: 100%;
    margin: auto; 
                height: 290px; 
            overflow: hidden; 
            position: relative;
            z-index: 1 !important;
            border-bottom: 1px solid #ccc;
        }
        .swiper-wrapper {
            display: flex;
            align-items: center;
        }
        .swiper-slide {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            flex-shrink: 0; 
        }
        .swiper-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover; 
        }
        .swiper-scrollbar {
            display: none;
        }
        .swiper-button-next, .swiper-button-prev {             
            color: #000;
            width: 60px;
            height: 60px; 
            line-height: 40px;
            text-align: center; 
            font-size: 18px; 
        }
        @media (max-width: 768px) {
             .swiper-container {
                padding-top: 50px;
                width:100%;
                height: 300px;              
            overflow: hidden; 
            }
            .swiper-slide {
            display: block; 
        }
            .swiper-button-next, .swiper-button-prev { 
                top:50%; 
            }
            .page-content{
                top: -60px;
            }
        }        
        @media (max-width: 991px) {
           
            .swiper-slide {
            display: block; 
        }
            .swiper-button-next, .swiper-button-prev { 
                top:50%; 
            }
            .page-content{
                top: -10px;
            }
        }        
    </style>                   
           
     <div class="swiper-container">
                            <div class="swiper-wrapper" id="swiper-container">
                                @if(!$listing_photos->isEmpty()) 
                                
                                 @foreach($listing_photos as $rowPhotos)
                                <div class="swiper-slide">                                     
                                    
                                    @if($rowPhotos->canal == 'dsautoestoque')                                       
                                        @if ($rowPhotos->listing_image_alterada_admin == 1)
                                            <a href="{{ asset('uploads/listing_photos/' . $rowPhotos->photo) }}" data-fancybox="gallery" class="magnific">
                                            <img src="{{ asset('uploads/listing_photos/' . $rowPhotos->photo) }}" 
                                                 alt="{{ asset('uploads/listing_photos/' . $rowPhotos->photo) }}" 
                                                 title="{{ asset('uploads/listing_photos/' . $rowPhotos->photo) }}" 
                                                 style="width: 100%; height: auto;">
                                            </a>
                                        @else
                                            <a href="{{ $rowPhotos->photo }}" data-fancybox="gallery" class="magnific">
                                            <img src="{{ $rowPhotos->photo }}" alt="{{ $rowPhotos->photo }}" title="{{ $rowPhotos->photo }}" style="width: 100%; height: auto;">
                                            </a>
                                        @endif                                        
                                            
                                    @else
                                    <a href="{{ asset('uploads/listing_photos/'.$rowPhotos->photo) }}" data-fancybox="gallery" class="magnific">
                                        <img src="{{ asset('uploads/listing_photos/'.$rowPhotos->photo) }}" 
                                             alt="{{ asset('uploads/listing_photos/'.$rowPhotos->photo) }}" 
                                             title="{{ asset('uploads/listing_photos/'.$rowPhotos->photo) }}" style="width: 100%; height: auto;">
                        </a>
                                    @endif
                    </div>
                                @endforeach
                                
                               
                                @else
                                
                                 <div class="swiper-slide">
                                    
                                @if($detail->canal == 'dsautoestoque')
                                
                                
                                @if ($detail->listing_featured_photo == 'images/sem-veiculo.jpg')
                                <img src="{{ asset('images/sem-veiculo.jpg') }}" alt="{{ asset('images/sem-veiculo.jpg') }}" title="Sem imagem">
            @else
                @if ($detail->listing_image_alterada_admin == 1)
                    <a href="{{ asset('uploads/listing_featured_photos/' . $detail->listing_featured_photo) }}" data-fancybox="gallery" class="magnific">
                                            <img src="{{ asset('uploads/listing_featured_photos/' . $detail->listing_featured_photo) }}" 
                                                 alt="{{ asset('uploads/listing_featured_photos/' . $detail->listing_featured_photo) }}" 
                                                 title="{{ $detail->listing_name }} - {{ asset('uploads/listing_featured_photos/' . $detail->listing_featured_photo) }}"
                                                 style="width: 100%; height: auto;">
                                            </a>
                @else
                   <a href="{{ $detail->listing_featured_photo }}" data-fancybox="gallery" class="magnific">
                                                <img src="{{ $detail->listing_featured_photo }}" alt="{{ $detail->listing_featured_photo }}" 
                                                 title="{{ $detail->listing_name }} - {{ $detail->listing_featured_photo }}" style="width: 100%; height: auto;">
                                                </a>
                @endif
            @endif
            
                                        
                                        @endif
                                </div>
                                
                                @endif
                                
                                
                            </div>
                            <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
        <div id="listing-page"></div>
                        </div>   
                                  
@if(!$listing_photos->isEmpty()) 

@if($listing_photos->count() <= 1)

<script id="swiper-js-js-after">
            document.addEventListener('DOMContentLoaded', function() {
            var swiper = new Swiper('.swiper-container', {   
                slidesPerView: 1,
                spaceBetween: 0,
                
                breakpoints: {
                    1024: {
                        slidesPerView: 1,
                        spaceBetween: 0,
                    },
                    600: {
                        slidesPerView: 1,
                        spaceBetween: 0,
                    },
                    480: {
                        slidesPerView: 1,
                        spaceBetween: 0,
                    },
                    320: {
                        slidesPerView: 1,
                        spaceBetween: 0,
                    }
                }
            });
        });
            </script>

@else

<script id="swiper-js-js-after">
            document.addEventListener('DOMContentLoaded', function() {
            var swiper = new Swiper('.swiper-container', {
                loop: true,        
                slidesPerView: 2,
                spaceBetween: 0,
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                breakpoints: {
                    1024: {
                        slidesPerView: 3,
                        spaceBetween: 0,
                    },
                    600: {
                        slidesPerView: 2,
                        spaceBetween: 0,
                    },
                    480: {
                        slidesPerView: 2,
                        spaceBetween: 0,
                    },
                    320: {
                        slidesPerView: 1,
                        spaceBetween: 0,
                    }
                }
            });
        });
            </script>

@endif

            @else
            
            <script id="swiper-js-js-after">
            document.addEventListener('DOMContentLoaded', function() {
            var swiper = new Swiper('.swiper-container', {   
                slidesPerView: 1,
                spaceBetween: 0,                
                breakpoints: {
                    1024: {
                        slidesPerView: 1,
                        spaceBetween: 0,
                    },
                    600: {
                        slidesPerView: 1,
                        spaceBetween: 0,
                    },
                    480: {
                        slidesPerView: 1,
                        spaceBetween: 0,
                    },
                    320: {
                        slidesPerView: 1,
                        spaceBetween: 0,
                    }
                }
            });
        });
            </script>
            
            @endif

    @if(isset($current_auth_user_id)) 
    @php
        $wishlist = \App\Models\Wishlist::where('user_id', $current_auth_user_id)->where('listing_id',$detail->id)->first();
    @endphp
@endif   
            <div class="page-content" style="position: relative;padding-top:0;z-index:8;">
	<div class="container">             
		<div class="row">
			<div class="col-lg-8 col-md-12 col-sm-12" style="padding: 0 !important;"> 
				<div class="listing-page" style="margin-left:4px;">
                                    <div style="box-shadow: 3px 3px 5px rgba(0, 0, 0, 0.3);border-radius: 8px;">
					<h2 class="ldORxL"><i class="fas fa-car"></i> {{ $detail->listing_name }} 
                                            
                                        </h2>
					 <div class="row">
					 <div class="col-md-12 d-block d-md-none">
					 <div style="padding-left:5px;padding-right:5px;width:100%;display:block;">                                                                                 
                                            <h2>
                                                <a class="d-block d-md-none float-left" style="font-weight: 700;cursor: none;color:#000;font-size:24px;">
                                                   R$ {{ number_format($detail->listing_price,0,'','.') }}                                               
                                                 </a>                                                      
                                                <a class="btn btn-danger float-right btn-sm d-md-none d-none" onclick="simular_parcelas();">    
         simular financiamento
    </a>                                                                                                      
                            
                            <a href="{{ route('front_add_wishlist',$detail->id) }}" class="btn btn-link btn-sm float-right d-block d-md-none">
                                @if(!empty($wishlist->id))
                                <i class="fas fa-heart" style="color:red;font-size: 35px;font-weight: 400;"></i>
                                @else
                                 <i class="fas fa-heart" style="font-size: 35px;font-weight: 400;color:#000;"></i>
                                @endif
                            </a>
                                                </h2>
                                        </div>
                                        </div>
                                        </div>                                           
                                                                                           
                                            <div class="row">
                                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 pb-4">
                                                <div style="padding-left:40px;padding-right:40px;">
                                                    <h1 style="font-weight: 700;font-size:34px;">
                                                        {{ $detail->rListingBrand->listing_brand_name }} 
                                                        <strong style="font-size:30px;color:red;">
                                                            {{ $detail->vehicleModel }}
                                                        </strong>
                                                    </h1>
                                                        <h2 style="color:#888;">
                                                            {{ $detail->versao }}
                                                        </h2>
                                                        
                                                </div>
                                            </div>
                                            </div>
                                            <div class="row">
                                            <div class="col-6 col-sm-6 col-md-6 col-lg-6">
                                            <ul style="list-style:none;list-style-type: none;padding-left:40px;padding-right:10px;" class="ulDetail">
                                                <li>
                                                    <div style="display:block;">  
                                                        <p style="display:block !important;width:100%;">
                                                    <span style="color:#888;font-size:12px !important;display:block !important;"> Carro</span>
                                                    <span style="display:block !important;font-weight: bold;font-size:15px !important;">
                                                       {{ $detail->listing_type }}
                                                    </span>
                                                    </p>
                                                    </div>
                                                </li> 
                                                
                                                @if($detail->listing_model_year != '')
                                                <li>
                                                    <div style="display:block;">
                                                    <p style="display:block !important;width:100%;">
                                                        
                                                        
                                                        <span class="title_nome 1" style="display:block !important;font-size:12px !important;color: #888;"> Ano</span>
                                                        <span style="display:block !important;font-weight: bold;font-size:15px !important;">
                                                            <i class="far fa-calendar-alt"></i> {{ $detail->anofabricacao }}/{{ $detail->listing_model_year }}
                                                        </span>
                                                    </p></div>
                                                </li>
                                                @endif
                                                @if($detail->listing_body != '')
                                                <li>
                                                    <div style="display:block;">
                                                    <p style="display:block !important;width:100%;">
                                                        
                                                    <span class="title_nome 2" style="display:block !important;font-size:12px !important;color: #888;"> 
                                                        Carroceria </span>
                                                    <span style="display:block !important;font-weight: bold;font-size:15px !important;">
                                                        <svg style="float:left;" width="17" height="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
<path d="M232 369.1v93.5C133.7 451.3 56.2 371.4 48.6 272H156.4L232 369.1zm48 0L355.6 272H463.4c-7.6 99.4-85 179.3-183.4 190.6V369.1zM352 224l-16-32H176l-16 32H50.4C65.8 124.3 152 48 256 48s190.2 76.3 205.6 176H352zM256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512z"></path>
</svg> 
{{ $detail->listing_body }}
                                                    </span>
                                                    </p>
                                                </div>
                                                </li>
                                                @endif
                                                @if($detail->listing_exterior_color != '')
                                                <li>
                                                    <div style="display:block;">
                                                    <p style="display:block !important;width:100%;">
                                                       
                                                    <span class="title_nome 3" style="display:block !important;font-size:12px !important;color: #888;"> Cor</span>
                                                    <span style="display:block !important;font-weight: bold;font-size:15px !important;"> <svg style="float:left;" width="16.75" height="19" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 13.75 20">
    <path id="Path_277" data-name="Path 277" d="M8.016-16.637A1.162,1.162,0,0,0,6.874-17.5a1.168,1.168,0,0,0-1.14.863C3.907-10.475,0-8.8,0-4.457A6.912,6.912,0,0,0,6.875,2.5,6.912,6.912,0,0,0,13.75-4.457C13.75-8.822,9.852-10.446,8.016-16.637ZM6.875,1.25A5.673,5.673,0,0,1,1.25-4.457c0-1.889.9-3.177,2.142-4.959a22.755,22.755,0,0,0,3.484-6.676,22.593,22.593,0,0,0,3.488,6.683C11.6-7.636,12.5-6.355,12.5-4.457A5.673,5.673,0,0,1,6.875,1.25Zm0-2.5A3.128,3.128,0,0,1,3.75-4.375.625.625,0,0,0,3.125-5a.625.625,0,0,0-.625.625A4.38,4.38,0,0,0,6.875,0,.625.625,0,0,0,7.5-.625.625.625,0,0,0,6.875-1.25Z" transform="translate(0 17.5)"></path>
</svg> {{ $detail->listing_exterior_color }}</span>
                                                    </p>
                                                </div>
                                                </li>
                                                @endif
                                                @if($km != '')
                                                <li>
                                                    <div style="display:block;">
                                                    <p style="display:block !important;width:100%;">
                                                        
                                                        <span class="title_nome 4" style="display:block !important;font-size:12px !important;color: #888;"> KM</span>
                                                        <span style="display:block !important;font-weight: bold;font-size:15px !important;"><svg style="float:left;" width="20.5" height="15.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 22.5 17.5">
    <path id="Path_275" data-name="Path 275" d="M4.687-5a.937.937,0,0,0-.937-.937A.937.937,0,0,0,2.812-5a.937.937,0,0,0,.937.937A.937.937,0,0,0,4.687-5Zm6.562-6.562a.937.937,0,0,0,.937-.937.937.937,0,0,0-.937-.937.937.937,0,0,0-.937.937A.937.937,0,0,0,11.25-11.562Zm-5.312.312A.937.937,0,0,0,5-10.312a.937.937,0,0,0,.937.937.937.937,0,0,0,.937-.937A.937.937,0,0,0,5.937-11.25Zm11.018.452a.625.625,0,0,0-.879.093L12.284-6.02a2.476,2.476,0,0,0-1.034-.23,2.5,2.5,0,0,0-2.5,2.5,2.5,2.5,0,0,0,2.5,2.5,2.5,2.5,0,0,0,2.5-2.5,2.48,2.48,0,0,0-.5-1.482L17.048-9.92A.625.625,0,0,0,16.955-10.8ZM12.5-3.75A1.25,1.25,0,0,1,11.25-2.5,1.25,1.25,0,0,1,10-3.75,1.25,1.25,0,0,1,11.25-5,1.25,1.25,0,0,1,12.5-3.75Zm6.25-2.187A.937.937,0,0,0,17.812-5a.937.937,0,0,0,.937.937A.937.937,0,0,0,19.687-5,.937.937,0,0,0,18.75-5.937ZM22.5-5A11.25,11.25,0,0,0,11.25-16.25,11.25,11.25,0,0,0,0-5,11.192,11.192,0,0,0,1.526.656,1.236,1.236,0,0,0,2.6,1.25H19.9A1.236,1.236,0,0,0,20.974.656,11.192,11.192,0,0,0,22.5-5ZM21.25-5A9.889,9.889,0,0,1,19.9,0L2.605.027A9.987,9.987,0,0,1,1.25-5a10.011,10.011,0,0,1,10-10A10.011,10.011,0,0,1,21.25-5Z" transform="translate(0 16.25)"></path>
</svg> {{ $km }}</span></p>
                                                </div>
                                                </li>
                                                @endif
                                            </ul>
                                            </div>
                                                <div class="col-6 col-sm-6 col-md-6 col-lg-6">
                                            <ul style="list-style:none;list-style-type: none;padding-left:10px;padding-right:40px;"class=" ulDetail">
                                                
                                                <li>
                                                    <div style="display:block;">
                                                        <p style="display:block !important;width:100%;">
                                                    <span style="color:#888;font-size:12px !important;display:block !important;"> Cidade</span>
                                                    <span style="display:block !important;font-weight: bold;font-size:15px !important;">                                                     
                                                        @if($city!='')								
                                                        {!! $city !!} - {!! $uf !!}
                                                        @endif
                                                    </span>
                                                    </p>
                                                    </div>
                                                </li> 
                                                @if($detail->listing_fuel_type != '')
                                                <li>
                                                    <div style="display:block;">
                                                    <p style="display:block !important;width:100%;">
                                                        
                                                        <span class="title_nome 5" style="display:block !important;font-size:12px !important;color: #888;"> Combustível</span>
                                                        <span style="display:block !important;font-weight: bold;font-size:15px !important;">
                                                            <svg style="float:left;" width="20" height="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
     <path id="Path_273" data-name="Path 273" d="M19.633-12.812l-3.656-3.66a.314.314,0,0,0-.441,0l-.441.441a.314.314,0,0,0,0,.441l1.156,1.16v3.18a1.875,1.875,0,0,0,1.875,1.875h.625v6.8a1.3,1.3,0,0,1-1.039,1.3A1.251,1.251,0,0,1,16.25-2.5V-4.375A3.124,3.124,0,0,0,13.125-7.5H12.5V-15A2.5,2.5,0,0,0,10-17.5H3.75A2.5,2.5,0,0,0,1.25-15V1.25H.312A.313.313,0,0,0,0,1.562v.625A.313.313,0,0,0,.312,2.5H13.437a.313.313,0,0,0,.312-.312V1.562a.313.313,0,0,0-.312-.312H12.5v-7.5h.625A1.875,1.875,0,0,1,15-4.375v1.734A2.6,2.6,0,0,0,17.078-.035,2.5,2.5,0,0,0,20-2.5v-9.43A1.248,1.248,0,0,0,19.633-12.812Zm-.883,2.187h-.625a.627.627,0,0,1-.625-.625v-1.93l1.25,1.25ZM11.25,1.25H2.5v-10h8.75Zm0-11.25H2.5v-5a1.254,1.254,0,0,1,1.25-1.25H10A1.254,1.254,0,0,1,11.25-15Z" transform="translate(0 17.5)"></path>
 </svg> {{ $detail->listing_fuel_type }}</span></p>
                                                </div>
                                                </li>
                                                @endif
                                                @if($detail->listing_transmission != '')
                                                <li>
                                                    <div style="display:block;">
                                                    <p style="display:block !important;width:100%;">
                                                        
                                                        <span class="title_nome 6" style="display:block !important;font-size:12px !important;color: #888;"> Câmbio</span>
                                                        <span style="display:block !important;font-weight: bold;font-size:15px !important;"><svg style="float:left;" width="25" height="16" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 25 16" xml:space="preserve">
    <path class="st0" d="M19.4,3c-0.8,0-1.5,0.7-1.5,1.5v3.3H15V4.5C15,3.7,14.3,3,13.5,3S12,3.7,12,4.5v3.3H8.7H8.6V6.6
        c0-0.4-0.7,0.1-1.5,0.1S5.7,6.4,5.7,6.6V14c0,0.8,0.7,1.5,1.5,1.5s1.5-0.7,1.5-1.5v-3.3h0.1H12V14c0,0.8,0.7,1.5,1.5,1.5
        S15,14.8,15,14v-3.3h2.9V14c0,0.8,0.7,1.5,1.5,1.5s1.5-0.7,1.5-1.5V4.5C20.9,3.7,20.3,3,19.4,3z"></path>
    <g>
        <circle cx="7.2" cy="3.6" r="1.5"></circle>
        <path class="st0" d="M7.2,0.5C5.4,0.5,4,1.9,4,3.6s1.4,3.1,3.1,3.1s3.1-1.4,3.1-3.1S8.9,0.5,7.2,0.5z"></path>
    </g>
</svg> {{ $detail->listing_transmission }}</span></p>
                                                </div>
                                                </li>
                                                @endif
                                                @if($detail->placa != '')
                                                <li>
                                                    <div style="display:block;">
                                                    <p style="display:block !important;width:100%;">
                                                        <span class="title_nome 7" style="display:block !important;font-size:12px !important;color: #888;"> Final de placa</span>
                                                        <span style="display:block !important;font-weight: bold;font-size:15px !important;">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" 
                                                                 fill="none" stroke="#444444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-credit-card"><rect x="2" y="5" width="20" height="14" rx="2"></rect><line x1="2" y1="10" x2="22" y2="10"></line></svg> {{ substr($detail->placa, -1) }}</span></p>
                                                </div>
                                                </li>
                                             @endif
                                             
                                             @if($detail->listing_door != '')
                                                    <div style="display:block;">
                                                    <p style="display:block !important;width:100%;">
                                                        
                                                        <span class="title_nome 8" style="display:block !important;font-size:12px !important;color: #888;"> {{ DOOR }}</span>
                                                        <span style="display:block !important;font-weight: bold;font-size:15px !important;">
                                                            <svg style="float:left;" width="25" height="25" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512">
    <path class="fa-primary" d="M347.4 234.9L329.7 288H534.3l-17.7-53.1c-2.2-6.5-8.3-10.9-15.2-10.9H362.6c-6.9 0-13 4.4-15.2 10.9zm-87.3 59.4l26.6-79.7C297.6 182 328.2 160 362.6 160H501.4c34.4 0 65 22 75.9 54.7l26.6 79.7C625.2 304.7 640 326.6 640 352v96h0v32c0 17.7-14.3 32-32 32H592c-17.7 0-32-14.3-32-32V448H304v32c0 17.7-14.3 32-32 32H256c-17.7 0-32-14.3-32-32V448h0V352c0-25.4 14.8-47.3 36.1-57.6zM328 368a24 24 0 1 0 -48 0 24 24 0 1 0 48 0zm232 24a24 24 0 1 0 0-48 24 24 0 1 0 0 48z"></path>
    <path class="fa-secondary" d="M138.6 64H277.4c6.9 0 13 4.4 15.2 10.9L310.3 128H105.7l17.7-53.1c2.2-6.5 8.3-10.9 15.2-10.9zM62.7 54.7L36.1 134.4C14.8 144.7 0 166.6 0 192v96H0v32c0 17.7 14.3 32 32 32H48c17.7 0 32-14.3 32-32V288H216.5c5.1-5.7 10.8-10.7 17.1-15.1l22.8-68.3c15.2-45.7 58-76.6 106.3-76.6h15.1L353.3 54.7C342.4 22 311.8 0 277.4 0H138.6c-34.4 0-65 22-75.9 54.7zM80 184a24 24 0 1 1 0 48 24 24 0 1 1 0-48z"></path>
</svg> {{ $detail->listing_door }}</span></p>
                                                </div>
								@endif
                                            </ul>                                    
                                    </div> 
                                    </div> 
                                        <div class="row">
                                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 pb-5">
					<div style="padding-left:40px;padding-right:40px;">					                                       
                                        <p>
  <a class="btn btn-outline-dark btn-block btn-lg" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
    clique para ver a descrição
  </a>
</p>
<div class="collapse" id="collapseExample">
  <div class="card card-body">
      <h5 class="card-title" style="font-size: 11px!important;color:#696977!important;font-weight: 400!important;">Sobre este {{ strtolower($detail->listing_tipo_veiculo) }}</h5>
      <span style="font-size:16px!important;color:#2e2d37!important;line-height: 24px!important;">
      {!! clean($detail->listing_description) !!}
      </span>
  </div>
</div>
                                    </div> 
                                    </div> 
                                    </div> 
                                    </div> 					 
 
					@if(!$listing_videos->isEmpty())
					<div class="gap"></div>
					<div class="video-all" style="border-radius: 8px;padding: 10px; box-shadow: 3px 3px 5px rgba(0, 0, 0, 0.3);">
					<h2><i class="fas fa-video"></i> {{ VIDEOS }}</h2>
						<div class="row">
							@foreach($listing_videos as $row)
							<div class="col-md-6 col-lg-4">
								<div class="item">
									<a class="video-button" href="http://www.youtube.com/watch?v={{ $row->youtube_video_id }}"
                                                                                     title="Vídeo YouTube - http://www.youtube.com/watch?v={{ $row->youtube_video_id }}">
										<img src="http://img.youtube.com/vi/{{ $row->youtube_video_id }}/0.jpg"
                                                                                     alt="http://img.youtube.com/vi/{{ $row->youtube_video_id }}/0.jpg"
                                                                                     title="Vídeo YouTube - http://www.youtube.com/watch?v={{ $row->youtube_video_id }}">
										<div class="icon">
											<i class="far fa-play-circle"></i>
										</div>
										<div class="bg"></div>
									</a>
								</div>
							</div>
							@endforeach
						</div>
					</div>
					@endif                                      

					<div class="gap"></div>				

					@if(!$listing_amenities->isEmpty())
					<div class="gap"></div>
					<div class="contact" style="border-radius: 8px;padding: 10px; box-shadow: 3px 3px 5px rgba(0, 0, 0, 0.3);">
					<h2><i class="fas fa-bullhorn"></i> {{ AMENITIES }}</h2>
						<ul class="duas-colunas">
							@foreach($listing_amenities as $row)
							@php
							$res = DB::table('amenities')->where('id',$row->amenity_id)->first();
							@endphp
							<li><i class="fas fa-check-square" style="color:#000 !important;"></i> {{ $res->amenity_name }}</li>
							@endforeach
						</ul>
					</div> 
					@endif

					@if(!$listing_additional_features->isEmpty())
					<div class="gap"></div>
					<div class="contact" style="border-radius: 8px;padding: 10px; box-shadow: 3px 3px 5px rgba(0, 0, 0, 0.3);">
					<h2><i class="far fa-id-card"></i> {{ ADDITIONAL_FEATURES }}</h2>
						<ul class="duas-colunas">
								@foreach($listing_additional_features as $row)
								<li><i class="fas fa-check-square"></i> {{ $row->additional_feature_name }}: {{ $row->additional_feature_value }}</li>
								@endforeach
							</ul>
					</div>
					@endif

					@if($detail->listing_map!='')
					<div class="gap"></div>
					<div class="map" style="border-radius: 8px;padding: 10px; box-shadow: 3px 3px 5px rgba(0, 0, 0, 0.3);">
					<h2><i class="fas fa-map-marker-alt"></i> {{ LOCATION_MAP }} 
                                            <small style="font-size:10px!important;">{{ $address }}</small></h2>
						{!! $detail->listing_map !!}
					</div>
					@endif

					<div class="gap"></div>
					<div class="contact" style="display:none;border-radius: 8px;padding: 10px; box-shadow: 3px 3px 5px rgba(0, 0, 0, 0.3);">
						<h2><i class="far fa-id-card"></i> {{ CONTACT_INFORMATION }}</h2>
                                        <div class="table-responsive">
							<table class="table table-condensed">
								@if($address!='')
								<tr>
									<td class="w-200">{{ ADDRESS }}</td>
									<td>
										{!! clean(nl2br($address)) !!}
									</td>
								</tr>
								@endif
                                                                
                                                                @if($agent_detail_phone!='')                                                       
                                                                <tr>
                                                                    <td>{{ PHONE_NUMBER }}</td>
                                                                    <td><a href="tel:{{ $agent_detail_phone }}"><i class="fas fa-phone-volume"></i> {{ $agent_detail_phone }}</a></td>
                                                                </tr>
                                                                @endif

								@if($detail->listing_email!='')
								<tr>
									<td>{{ EMAIL_ADDRESS }}</td>
									<td>
										{!! clean(nl2br($detail->listing_email)) !!}
									</td>
								</tr>
								@endif

								@if($detail->listing_website!='' && strlen($detail->listing_website) > 7)
								<tr>
									<td>{{ WEBSITE }}</td>
									<td class="website">
										<a href="{{ $detail->listing_website }}" target="_blank">{{ $detail->listing_website }}</a>
									</td>
								</tr>
								@endif

							</table>
						</div>
					</div>
                                        
                                        <div class="gap"></div>
                                        <div id="parcelas" style="border-radius: 8px;padding: 10px; box-shadow: 3px 3px 5px rgba(0, 0, 0, 0.3);">
                                            <h2 style="border-bottom: 1px solid #ccc;padding-bottom:10px;">
                                                <i class="far fa-credit-card"></i> Veja as parcelas desse veículo <br><small>Tudo sem compromisso, vamos começar com alguns dados :)</small></h2>
						<div class="clearfix clear"></div>
                                                
                                               <div id="credere-pnp"></div>
                                        </div>
            
<div class="modal fade" id="simulador-modal" aria-labelledby="simulador-modalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="simulador-modalLabel">Simular financiamento</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="zflow-container" style="margin-top: 20px !important;"></div>
      </div>
       <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>                                   
                                        
                                        
                                                
				</div>
			</div>
			<div class="col-lg-4 col-md-12 col-sm-12 div_sidebar">
                            <div class="listing-sidebar" style="margin-left:4px;">
 
                                    <div class="ls-widget" style=" box-shadow: 3px 3px 5px rgba(0, 0, 0, 0.3);padding:10px;" id="div_mensagem"> 
                                        <h2 style="font-weight: 700;">
                                                  R$ {{ number_format($detail->listing_price,0,'','.') }} 
                                                <a class="btn btn-danger float-right btn-sm d-none d-sm-block" onclick="simular_parcelas();">
        simular parcelas 
   </a>
                            <a href="{{ route('front_add_wishlist',$detail->id) }}" class="btn btn-link btn-sm float-right d-none d-sm-block">
                                @if(!empty($wishlist->id))
                                <i class="fas fa-heart" style="color:red;font-size: 24px;font-weight: 400;"></i>
                                @else
                                 <i class="fas fa-heart" style="font-size: 24px;font-weight: 400;color:#000;"></i>
                                @endif
                            </a>
                                                </h2>
						<div class="agent">						
                                                    <form action="{{ route('front_listing_detail_send_message') }}" class="form-contato" method="post">
                                        @csrf
                                        <input type="hidden" name="listing_name" value="{{ $detail->listing_name }}">
                                        <input type="hidden" name="listing_slug" value="{{ $detail->listing_slug }}">
                                        <input type="hidden" name="agent_name" value="{{ $agent_detail->name }}">
                                        <input type="hidden" name="agent_email" value="{{ $agent_detail->email }}">
                                        <input type="hidden" name="id" value="{{ $detail->id }}">
                                        <div class="form-group" style="margin-bottom: .2rem;">
                                            <label style="margin-bottom: .2rem;">{{ NAME }} <small class="badge badge-danger">*</small></label> 
                                            <div>
                                                <input type="text" name="name" class="form-custom" placeholder="Digite o seu nome*" required>
                                            </div>
                                        </div>
                                        <div class="form-group" style="margin-bottom: .2rem;">
                                            <label style="margin-bottom: .2rem;">{{ EMAIL }} <small class="badge badge-danger">*</small></label>
                                            <div>
                                                <input type="email" name="email" class="form-custom" placeholder="Digite seu e-mail*" required>
                                            </div>
                                        </div>
                                        <div class="form-group" style="margin-bottom: .2rem;">
                                            <label style="margin-bottom: .2rem;">{{ PHONE }}</label>
                                            <div>
                                                <input type="text" name="phone" class="form-custom" placeholder="Digite seu telefone">
                                            </div>
                                        </div>
                                        <div class="form-group" style="margin-bottom: .2rem;">
                                            <label style="margin-bottom: .2rem;">{{ MESSAGE }} <small class="badge badge-danger">*</small></label>
                                            <div>
                                                <textarea name="message" class="form-custom h-100" cols="30" rows="10" required>Olá, tenho interesse no veículo. Por favor entre em contato.</textarea>
                                            </div>
                                        </div>
                                        @if($g_setting->google_recaptcha_status == 'Show')
                                            <div class="form-group">
                                                <div class="g-recaptcha" data-sitekey="{{ $g_setting->google_recaptcha_site_key }}"></div>
                                            </div>
                                        @endif
                                        <div class="form-group">
                                            <div>
                                                <button type="submit" class="btn btn-outline-dark btn-block">{{ SEND_MESSAGE }}</button>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <a class="btn btn-dark btn-block btn-lg" id="share" style="">
                                                Compartilhar 
                                                <i class="fas fa-share-alt"></i>
                                            </a>
                                        </div>
                                    </form>
                                    </div>
					</div>
                                    
					<div class="ls-widget" style=" box-shadow: 3px 3px 5px rgba(0, 0, 0, 0.3);padding:10px;">
						<h2>Loja</h2>
						<div class="agent">
							<div class="photo">
								@if(empty($agent_detail->photo) && $agent_detail->photo == '' || !file_exists(public_path('uploads/user_photos/' . $agent_detail->photo)))
									<img src="{{ asset('uploads/user_photos/default_photo.jpg') }}" 
                                                                             alt="{{ asset('uploads/user_photos/default_photo.jpg') }}" 
                                                                             title="{{ $agent_detail->name }}">
								@else
									<img src="{{ asset('uploads/user_photos/'.$agent_detail->photo) }}" 
                                                                             alt="{{ asset('uploads/user_photos/'.$agent_detail->photo) }}" 
                                                                             title="{{ $agent_detail->name }}">
								@endif

							</div>
							<div class="text">
                                @if($detail->user_id == 0)
                                    @php $type = "admin"; @endphp
                                @else
                                    @php $type = "user"; @endphp
                                @endif
                                    <h3><a href="{{ route('front_listing_agent_detail',[$type,$agent_detail->id]) }}">{{ $agent_detail->name }}</a></h3>
								<!--<h4>{{ POSTED_ON }} {{ \Carbon\Carbon::parse($detail->created_at)->format('d M, Y') }}</h4>-->
							</div>
						</div>
						<div class="agent-contact">
							<ul>
								@if($agent_detail->address!='' || $agent_detail->city!='' || $agent_detail->state!='' || $agent_detail->country!='')
								<li>
									<i class="fas fa-map-marker-alt"></i> {{ $agent_detail->address }} {{ $agent_detail->city }} {{ $agent_detail->country }}
								</li>
								@endif
								@if($agent_detail_phone!='')                                                       
                                                                <li style="display:none;">
                                                                    <a href="tel:{{ $agent_detail_phone }}">
                                                                        <i class="fas fa-phone-volume"></i> {{ $agent_detail_phone }}</a></li>
                                                                @endif
								@if($agent_detail->email!='')
								<li style="display:none;">
                                                                    <i class="fas fa-envelope"></i> {{ $agent_detail->email }}</li>
								@endif
								@if (empty($agent_detail->website) || $agent_detail->website === 'http://' || $agent_detail->website === 'https://')
                                                                
                                                                @else
                                                                <li style="display:none;">
                                                                    <a href="{{ $agent_detail->website }}" target="_blank"><i class="fas fa-globe"></i> {{ $agent_detail->website }}</a></li>
								@endif
							</ul>
						</div>

						@if( ($agent_detail->facebook != '') ||
						($agent_detail->twitter != '') ||
						($agent_detail->linkedin != '') ||
						($agent_detail->pinterest != '') ||
						($agent_detail->youtube != '') )
						<div class="agent-social">
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

						<a href="{{ route('front_listing_agent_detail',[$type,$agent_detail->id]) }}" 
                                                   class="btn btn-outline-dark btn-block">Ver estoque do vendedor  </a>
					</div>

                    @if($detail->listing_oh_monday != '' || $detail->listing_oh_tuesday != '' || $detail->listing_oh_wednesday != '' || $detail->listing_oh_thursday != '' || $detail->listing_oh_friday != '' || $detail->listing_oh_saturday != '' || $detail->listing_oh_sunday != '')
					<div class="ls-widget" style=" box-shadow: 3px 3px 5px rgba(0, 0, 0, 0.3);padding:10px;">
						<h2>{{ OPENING_HOUR }}</h2>
						<div class="openning-hour">
							<div class="table-responsive">
								<table class="table table-bordered">
									<tr>
										<td>{{ MONDAY }}</td>
										<td>{{ $detail->listing_oh_monday }}</td>
									</tr>
									<tr>
										<td>{{ TUESDAY }}</td>
										<td>{{ $detail->listing_oh_tuesday }}</td>
									</tr>
									<tr>
										<td>{{ WEDNESDAY }}</td>
										<td>{{ $detail->listing_oh_wednesday }}</td>
									</tr>
									<tr>
										<td>{{ THURSDAY }}</td>
										<td>{{ $detail->listing_oh_thursday }}</td>
									</tr>
									<tr>
										<td>{{ FRIDAY }}</td>
										<td>{{ $detail->listing_oh_friday }}</td>
									</tr>
									<tr>
										<td>{{ SATURDAY }}</td>
										<td>{{ $detail->listing_oh_saturday }}</td>
									</tr>
									<tr>
										<td>{{ SUNDAY }}</td>
										<td>{{ $detail->listing_oh_sunday }}</td>
									</tr>
								</table>
							</div>
						</div>
					</div>
                    @endif

					<div class="ls-widget" style=" box-shadow: 3px 3px 5px rgba(0, 0, 0, 0.3);padding:10px;">
						<h2>{{ BRANDS }}</h2>
						<div class="category">
							<ul class="duas-colunas">
								@foreach($listing_brands as $row)
                                                                @if($row->rListing()->exists())
								<li><a href="{{ route('front_listing_brand_detail',$row->listing_brand_slug) }}" 
                                                                       title="{{ $row->listing_brand_name }}"><i class="fas fa-angle-right"></i> {{ $row->listing_brand_name }}</a></li>
								@endif
								@endforeach
							</ul>
						</div>
					</div>

					<div class="ls-widget" style=" box-shadow: 3px 3px 5px rgba(0, 0, 0, 0.3);padding:10px;">
						<h2>{{ LOCATIONS }}</h2>
						<div class="category">
							<ul>
								@foreach($listing_locations as $row)
                                                                @if($row->rListing()->exists())
								<li><a href="{{ route('front_listing_location_detail',$row->listing_location_slug) }}" 
                                                                       title="{{ $row->listing_location_name }}"><i class="fas fa-angle-right"></i> {{ $row->listing_location_name }}</a></li>
                                                                @endif
								@endforeach
							</ul>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>                                                                
           <!-- Send Message Modal -->
                    <div class="modal fade modal_listing_detail" id="send_message_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">{{ SEND_MESSAGE }}</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('front_listing_detail_send_message') }}" method="post">
                                        @csrf
                                        <input type="hidden" name="listing_name" value="{{ $detail->listing_name }}">
                                        <input type="hidden" name="listing_slug" value="{{ $detail->listing_slug }}">
                                        <input type="hidden" name="agent_name" value="{{ $agent_detail->name }}">
                                        <input type="hidden" name="agent_email" value="{{ $agent_detail->email }}">
                                        <input type="hidden" name="id" value="{{ $detail->id }}">
                                        <div class="form-group">
                                            <label for="">{{ NAME }}</label>
                                            <div>
                                                <input type="text" name="name" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="">{{ EMAIL }}</label>
                                            <div>
                                                <input type="email" name="email" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="">{{ PHONE }}</label>
                                            <div>
                                                <input type="text" name="phone" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="">{{ MESSAGE }}</label>
                                            <div>
                                                <textarea name="message" class="form-control h-100" cols="30" rows="10" required></textarea>
                                            </div>
                                        </div>
                                       
                                        @if($g_setting->google_recaptcha_status == 'Show')
                                            <div class="form-group">
                                                <div class="g-recaptcha" data-sitekey="{{ $g_setting->google_recaptcha_site_key }}"></div>
                                            </div>
                                        @endif
                                        <div class="form-group">
                                            <div>
                                                <button type="submit" class="btn btn-outline-success">{{ SEND_MESSAGE }}</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- // Send Message Modal -->                                                            
                    <!-- Report Modal -->
                    <div class="modal fade modal_listing_detail" id="report_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">{{ SUBMIT_REPORT }}</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('front_listing_detail_report_listing') }}" method="post">
                                        @csrf
                                        <input type="hidden" name="listing_name" value="{{ $detail->listing_name }}">
                                        <input type="hidden" name="listing_slug" value="{{ $detail->listing_slug }}">
                                        <input type="hidden" name="id" value="{{ $detail->id }}">
                                        <div class="form-group">
                                            <label for="">{{ NAME }}</label>
                                            <div>
                                                <input type="text" name="name" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="">{{ EMAIL }}</label>
                                            <div>
                                                <input type="email" name="email" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="">{{ PHONE }}</label>
                                            <div>
                                                <input type="text" name="phone" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="">{{ MESSAGE }}</label>
                                            <div>
                                                <textarea name="message" class="form-control h-100" cols="30" rows="10" required></textarea>
                                            </div>
                                        </div>
                                        @if($g_setting->google_recaptcha_status == 'Show')
                                            <div class="form-group">
                                                <div class="g-recaptcha" data-sitekey="{{ $g_setting->google_recaptcha_site_key }}"></div>
                                            </div>
                                        @endif
                                        <div class="form-group">
                                            <div>
                                                <button type="submit" class="btn btn-outline-success">{{ SUBMIT_REPORT }}</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- // Report Modal -->
</div>           
            <script>
               
            document.addEventListener("DOMContentLoaded", function () {
                //document.getElementById('listing-page').scrollIntoView({ behavior: 'smooth' });                  
            });
            function verFotos(){
                    document.getElementById('swiper-container').scrollIntoView({ behavior: 'smooth' });
                }
            </script>
            
            <script src="https://app.meucredere.com.br/simulador/loja/{{ str_replace([' ', '.', '-', ')', '(', '/'], '', $agent_detail->cnpj_credere) }}/veiculo/detectar.js?q={{ $detail->listing_name }}&value_cents={{ str_replace([' ', '.', '-', ')', '(', '/'], '', $detail->listing_price) }}00&manufacture_year={{ $detail->anofabricacao }}&model_year={{ $detail->listing_model_year }}"></script>
            
            @endif
@endsection
