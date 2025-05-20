@extends('front.app_front')

@section('content')

<div class="page-banner" style="background-image: url('{{ asset('uploads/page_banners/'.$about_data->banner) }}')">
	<div class="page-banner-bg"></div>
	<h1>Todas as lojas</h1>
	<nav>
		<ol class="breadcrumb justify-content-center">
			<li class="breadcrumb-item"><a href="{{ url('/') }}">{{ HOME }}</a></li>
			<li class="breadcrumb-item active">Lojas</li>
		</ol>
	</nav>
</div>

<div class="page-content" style="padding-top:0px!important;padding-bottom:0px!important;">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<!--Listas de lojas-->
			</div>
		</div>
	</div>
</div>

<div class="page-content" style="padding-top:3%!important;">
	<div class="container">
            <div class="row">
                @foreach($lojas as $agent_detail)
                
                @php
        $isFollowing = auth()->check() ? auth()->user()->isFollowing($agent_detail->id) : false;
    @endphp
                
                @php $type = "user"; @endphp
			<div class="col-lg-4 col-md-12 col-sm-12">
                            <div class="listing-sidebar">
				<div class="ls-widget" style=" box-shadow: 3px 3px 3px 3px rgba(0, 0, 0, 0.3);padding:10px;">
						<!--<h2>{{ AGENT }}</h2>-->
						<div class="agent">
						<div class="row">
						<div class="col-md-3">
							<div class="photo">
								@if(empty($agent_detail->photo) && $agent_detail->photo == '' || !file_exists(public_path('uploads/user_photos/' . $agent_detail->photo)))
									<img style="border: 1px solid #e1e1e1;" src="{{ asset('uploads/user_photos/default_photo.jpg') }}" alt="">
								@else
									<img style="border: 1px solid #e1e1e1;" src="{{ asset('uploads/user_photos/'.$agent_detail->photo) }}" alt="">
								@endif

							</div>
							</div>
                                                    <div class="col-md-9">
							<div class="text">
                                
                                    <h3 style="margin-top: .8rem !important;">
                                        <a href="{{ route('front_listing_agent_detail',[$type,$agent_detail->id]) }}">
                                            {{ $agent_detail->name }}
                                        </a>
                                    </h3>
								
							</div>
						</div>
						</div>
						</div>
						<div class="agent-contact">
							<ul>
								@if($agent_detail->address!='' || $agent_detail->city!='' || $agent_detail->state!='' || $agent_detail->country!='')
								<li>
									<i class="fas fa-map-marker-alt"></i> 
                                                                        {{ $agent_detail->address }}, 
                                                                        {{ $agent_detail->city }}, 
                                                                        {{ $agent_detail->country }}
								</li>
								@endif
								@if($agent_detail->phone!='')                                                       
                                                                <li>
                                                                    <a href="tel:{{ $agent_detail->phone }}">
                                                                        <i class="fas fa-phone-volume"></i> 
                                                                        {{ $agent_detail->phone }}
                                                                    </a>
                                                                </li>
                                                                @endif
								@if($agent_detail->email!='')
								<li>
                                                                    <i class="fas fa-envelope"></i> 
                                                                    {{ $agent_detail->email }}
                                                                </li>
								@endif
								@if (empty($agent_detail->website) || $agent_detail->website === 'http://' || $agent_detail->website === 'https://')
                                                                
                                                                @else
                                                                <li>
                                                                    <a href="{{ $agent_detail->website }}" target="_blank">
                                                                        <i class="fas fa-globe"></i> 
                                                                        {{ $agent_detail->website }}
                                                                    </a>
                                                                </li>
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
<div class="btn-group" role="group" aria-label="Basic example" style="width:100%;">
    <a href="{{ route('front_listing_agent_detail',[$type,$agent_detail->id]) }}" class="btn btn-dark animated fadeInUpBig btn-sm">
        Ver estoque
    </a>
    <a class="btn btn-follow animated fadeInUpBig {{ $isFollowing ? 'btn-following' : '' }} btn-sm" onclick="follow({{ $agent_detail->id }},this);">
                                    @if($isFollowing)
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-plus">
                                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="8.5" cy="7" r="4"></circle>
                                        <polyline points="17 11 19 13 23 9"></polyline>
                                    </svg>
                                    @else
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-plus">
                                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="8.5" cy="7" r="4"></circle>
                                        <line x1="20" y1="8" x2="20" y2="14"></line>
                                        <line x1="23" y1="11" x2="17" y2="11"></line>
                                    </svg> 
                                    @endif
                                    <span>{{ $isFollowing ? 'Seguindo' : 'Seguir' }}</span>
                                </a>
                      
                      </div>
						
					</div>
			</div>
			</div>
		@endforeach
            </div>
		<div class="row">
			
              
                    <div class="col-md-12">
                 
                        <!-- Carousel -->
<div class="owl-carousel owl-theme" id="10-razoes">
    <div class="item">
        <img src="{{ asset('images/10-razoes/Economia-e-Eficiencia.png') }}" alt="Economia-e-Eficiencia.png">
 
    </div>
    <div class="item">
        <img src="{{ asset('images/10-razoes/Melhores-Revendas.png') }}" alt="Melhores-Revendas.png">

    </div>
    <div class="item">
        <img src="{{ asset('images/10-razoes/Variedade.png') }}" alt="Variedade.png">
   
    </div>
    <div class="item">
      
        <img src="{{ asset('images/10-razoes/Seguranca-Garantida.png') }}" alt="Seguranca-Garantida.png">
   
    </div>
    <div class="item">
        <img src="{{ asset('images/10-razoes/Suporte-Ativo-SAC.png') }}" alt="Suporte-Ativo-SAC.png">
    </div>
    <div class="item">
       <img src="{{ asset('images/10-razoes/Qualidade.png') }}" alt="Qualidade.png">
    </div>
    <div class="item">
      <img src="{{ asset('images/10-razoes/Garantia-Documentada.png') }}" alt="Garantia-Documentada.png">
    </div>
    <div class="item">
       <img src="{{ asset('images/10-razoes/Conveniencia-Digital.png') }}" alt="Conveniencia-Digital.png">
   </div>
    <div class="item">
       <img src="{{ asset('images/10-razoes/Compromisso-Contratual.png') }}" alt="Compromisso-Contratual.png">
    </div>
  
</div>

<!-- Inicialização do Owl Carousel -->
<script>
  $(document).ready(function(){
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
                    </div>
                    
                    
		</div>
	</div>
</div>

@endsection
