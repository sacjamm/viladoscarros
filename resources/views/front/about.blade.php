@extends('front.app_front')

@section('content')

<div class="page-banner" style="background-image: url('{{ asset('uploads/page_banners/'.$about_data->banner) }}')">
	<div class="page-banner-bg"></div>
	<h1>{{ $about_data->name }}</h1>
	<nav>
		<ol class="breadcrumb justify-content-center">
			<li class="breadcrumb-item"><a href="{{ url('/') }}">{{ HOME }}</a></li>
			<li class="breadcrumb-item active">{{ $about_data->name }}</li>
		</ol>
	</nav>
</div>

<div class="page-content" style="padding-top:0px!important;padding-bottom:0px!important;">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				{!! ($about_data->detail) !!}
			</div>
		</div>
	</div>
</div>

<div class="page-content" style="padding-top:0px!important;">
	<div class="container">
            <div class="row">
			<div class="col-md-12">
				<div class="heading">
					<h2 style="text-align: center;padding-bottom:25px;">10 Razões para comprar na Vila dos Carros</h2>
				</div>
			</div>
		</div>
		<div class="row">
			
              
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
                        <!-- Carousel -->
<div class="owl-carousel owl-theme" id="10-razoes">
    @foreach ($imagens as $nomeImagem)
            @php
                $original = 'images/10-razoes/' . $nomeImagem . '.png';
                $optimized = 'images/10-razoes/' . $nomeImagem . '.webp';
                $imgPath = file_exists(public_path($optimized)) ? asset($optimized) : asset($original);
            @endphp
            <div class="item">
                <a href="{{ route('front_about') }}">
                    <img src="{{ $imgPath }}" alt="{{ $nomeImagem }}" 
                         title="{{ $nomeImagem }}" width="277" height="415" loading="lazy">
                </a>
            </div>
        @endforeach
  
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
