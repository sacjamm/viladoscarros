@extends('front.app_front')

@section('content')
<style>
    .listing{
        margin-bottom:0!important;padding-bottom:0!important;padding-top:15px;
    }
    @media (max-width: 768px) {
        .listing{
        padding-top:70px;
    }
    }
</style>
<div class="listing" style="">
	<div class="container">        
		<div class="row">	
                    <div class="col-md-12 mt-2">                                             
                       <!-- Carousel -->
<div class="owl-carousel owl-theme" id="brandspng" style="z-index:0!important;">
    @foreach($listing_brands as $index => $row)
    
    @php
                       
                            $imgMarca = asset('images/'.$row->listing_brand_slug.'.png');
                       
                    @endphp
                    
                     <div class="item">
        <form method="post" action="{{ route('busca_front_listing_result') }}">
            @csrf
            <input type="hidden" name="brand[]" value="{{ $row->id }}" />
            <button type="submit" style="border:0;"> 
                <img src="{{ $imgMarca }}?id={{ time() }}" alt="{{ $imgMarca }}" title="{{ $row->listing_brand_slug }}" style="width:74px;height:74px;margin:3px;padding:2px;">
                <div class="clearfix clear"></div>
            </button>
    </form>
    </div>
                    
    @endforeach
   
   
  
</div>

<!-- Inicialização do Owl Carousel -->
<script>
  $(document).ready(function(){
    $("#brandspng").owlCarousel({
        items: 10,              // Exibe 1 item por vez
        dots: false,            // Habilita a navegação por dots (pontos)
        loop: true,            // Loop contínuo
        autoplay: true,        // Autoplay ativado
        autoplayTimeout: 5000, // Intervalo entre cada slide
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
    
  });
</script>                       
                       
                    </div>
                    
                    
		</div>
	</div>
</div>

<!--<div class="listing" style="margin-bottom:0!important;padding-bottom:0!important;">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-9171096234708042"
     crossorigin="anonymous"></script>
 Horizontal-Vila-Dos-Carros 
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
</div>-->

<div class="page-content" style="padding-top:10px!important;">   
    
    <div class="container">
        <div class="row listing pt_0 pb_0">
            <div class="col-lg-3 col-md-6 col-sm-12" id="barra_de_rolagem">
                <div class="listing-filter">
                    <div class="lf-widget">
                    <h2 id="span-filter"><i class="fas fa-filter"></i> {{ FILTERS }}</h2>
                    <button type="button" class="btn btn-danger" id="btn-filter" onclick="$('#searchFormId').slideToggle('slow');" style="cursor: pointer;"><i class="fa fa-filter"></i> FILTRAR</button> 
                    <!--<button type="button" class="btn btn-danger" onclick="$('#searchFormId').slideToggle('slow');"><i class="fa fa-filter"></i>  {{ FILTERS }} </button>--> 
                   
                    <h2 style="font-size: 14px !important;color:#333;border-bottom: 2px solid #333;padding: 10px 0 8px 5px;">Ordenar por</h2>
    <select name="orders" class="form-control" id="orders" style="height: calc(2.0em + .75rem + 2px) !important;
    padding: .775rem .775rem !important;
    font-size: 1rem !important;
    font-weight: 400 !important;
    line-height: 3.5 !important;border:1px solid #777 !important;">
        <option value="" {{ request()->get('orders') == '' ? 'selected' : '' }}>Selecione para ordenar</option>
        <option value="relevancia" {{ request()->get('orders') == 'relevancia' ? 'selected' : '' }}>Relevância</option>
        <option value="price_asc" {{ request()->get('orders') == 'price_asc' ? 'selected' : '' }}>Menor Preço</option>
        <option value="price_desc" {{ request()->get('orders') == 'price_desc' ? 'selected' : '' }}>Maior Preço</option>
        <option value="km_asc" {{ request()->get('orders') == 'km_asc' ? 'selected' : '' }}>Menor KM</option>
        <option value="km_desc" {{ request()->get('orders') == 'km_desc' ? 'selected' : '' }}>Maior KM</option>
    </select>
                       
                        </div>
                        </div>
                <form id="searchFormId">
                    <div class="listing-filter">
                        <input type="hidden" id="order" name="order" value="" />
                        
                         @php
    $sort_loc = [];
    if(request()->has('location')){
        foreach(request()->get('location') as $cat){
            array_push($sort_loc, (int)$cat);
        }
    }
@endphp

<div class="lf-widget">
    <h2 style="font-size: 14px !important; color:#333;border-bottom: 2px solid #333; padding: 10px 0 8px 5px;">
        {{ LOCATIONS }}
    </h2>
    
    <select name="location[]" class="form-control select2 location" multiple style="width: 100%;" placeholder="Selecione uma cidade">
        <option value="" disabled selected>Selecione uma cidade</option>
        @foreach($listing_locations as $index => $row)
            @php
                $conta = $locationCounts[$row->id] ?? 0;
            @endphp

            @if(!empty($row->listing_location_slug))
                <option value="{{ $row->id }}" {{ in_array($row->id, $sort_loc) ? 'selected' : '' }}>
                    {{ $row->listing_location_name }} ({{ $conta }})
                </option>
            @endif
        @endforeach
    </select>
</div>
                        
                        
                        
                        <div class="lf-widget">
    <h2 style="font-size: 14px !important;color:#333;border-bottom: 2px solid #333;padding: 10px 0 8px 5px;">Condição do veículo</h2>
   
    
    <div class="row " style="margin:0 !important;padding: 0 !important;">
    <div class=" col-md-12" style="margin:0 !important;padding: 0 !important;">
        <div class="btn-group" role="group" style="width: 100%;">
            <input type="radio" class="btn-check" name="listing_type" id="type_all" value="" autocomplete="off" {{ !request()->has('listing_type') ? 'checked' : '' }}>
            <label class="btn btn-outline-dark form-check-radio-label" for="type_all" style="border-radius: .25rem;border-top-right-radius: 0;
    border-bottom-right-radius: 0;font-size: 0.800rem !important;">Todos</label>

            <input type="radio" class="btn-check" name="listing_type" id="type_novo" value="Novo" autocomplete="off" {{ request()->get('listing_type') == 'Novo' ? 'checked' : '' }}>
            <label class="btn btn-outline-dark form-check-radio-label" for="type_novo" style="font-size: 0.800rem !important;">Novos ({{ $listing_type_counts['Novo'] ?? 0 }})</label>

            <input type="radio" class="btn-check" name="listing_type" id="type_usado" value="Usado" autocomplete="off" {{ request()->get('listing_type') == 'Usado' ? 'checked' : '' }}>
            <label class="btn btn-outline-dark form-check-radio-label" for="type_usado" style="font-size: 0.800rem !important;">Usados ({{ $listing_type_counts['Usado'] ?? 0 }})</label>
        </div>
    </div>
</div>

</div>
                        
                        
                        <div class="lf-widget">
                            <h2 style="font-size: 14px !important;color:#333;border-bottom: 2px solid #333;padding: 10px 0 8px 5px;">Digite o nome do Veículo</h2>
                            <input type="text" id="text" name="text" class="form-control" style="height: calc(2.0em + .75rem + 2px) !important;
    padding: .775rem .775rem !important;
    font-size: 1rem !important;
    font-weight: 400 !important;
    line-height: 3.5 !important;border:1px solid #777 !important;" placeholder="{{ FIND_ANYTHING }}" value="{{ request()->has('text') ? request()->get('text') : '' }}">
                        </div>

                        
                      

                                               
                       <div class="lf-widget">
    @php
    $sort_cat = [];
    if(request()->has('brand')){
        foreach(request()->get('brand') as $cat){
            array_push($sort_cat,(int)$cat);
        }
    }
    @endphp

    <h2 style="font-size: 14px !important;color:#333;border-bottom: 2px solid #333;padding: 10px 0 8px 5px;" id="texto-marcas-modelos">{{ BRANDS }}</h2>
    <div class="row brands-list" id="marcas-div">
        @foreach($listing_brands as $index => $row)
        
        @php
        $contaBrands = $brandCounts[$row->id] ?? 0;
    @endphp
            <div class="form-check brand-item" style="padding-left: .750rem !important;display: {{ $index < 12 ? 'block' : 'none' }};">
                <input 
                    {{ in_array($row->id, $sort_cat) ? 'checked' : '' }} 
                    name="brand[]" 
                    class="form-check-input" 
                    type="checkbox" 
                    value="{{ $row->id }}" 
                    id="cat{{ $index }}" 
                    style="display: none;" 
                    data-brand-id="{{ $row->id }}" /> 
                    
                    @php
                        $imgMarca = '';
                        if($row->canal == 'dsautoestoque' || $row->canal == 'import') {
                            $imgMarca = asset('images/'.$row->listing_brand_slug.'.png');
                        } elseif ($row->canal == 'website') {
                            $imgMarca = asset('uploads/listing_brand_photos/'.$row->listing_brand_photo);
                        }
                    @endphp
                    
                <label class="form-check-label label-brand-check" for="cat{{ $index }}" onclick="show_modelos({{ $row->id }},'{{ $imgMarca }}','{{ $row->listing_brand_name }}');">
                    @if($row->canal == 'dsautoestoque')
                        <img 
                            src="{{ asset('images/'.$row->listing_brand_slug.'.png') }}" 
                            alt="{{ $row->listing_brand_name }}" 
                            style="width: 74px; height: 70px; margin: 2px; border-radius: 5px; display: block;" 
                            class="brand-logo"
                        >
                        @endif
                         @if($row->canal == 'import')                         
                        <img 
                            src="{{ asset('images/'.$row->listing_brand_slug.'.png') }}" 
                            alt="{{ $row->listing_brand_name }}" 
                            style="width: 74px; height: 70px; margin: 2px; border-radius: 5px; display: block;" 
                            class="brand-logo"
                        >
                        @endif
                    @if($row->canal == 'website')
                        <img 
                            src="{{ asset('uploads/listing_brand_photos/'.$row->listing_brand_photo) }}" 
                            alt="{{ $row->listing_brand_name }}" 
                            style="width: 74px; height: 70px; margin: 2px; border-radius: 5px; display: block;" 
                            class="brand-logo"
                        >
                    @endif
                    <small style="display: block; font-size: 8px; text-align: center; font-weight: 300;"> {{ $row->listing_brand_name }} ({{ $contaBrands }})</small>
                </label>
            </div>
        @endforeach
        <div class="col-md-12">
    <button class="btn btn-dark btn-block" id="toggleBrands">Ver mais</button>
    </div>
    </div>
    
    <div class="row modelos-list" style="display:none;">
        <div class="col-md-12">
            <div class="media" id="modelos-div">
                <div id="imageContainer"></div>
                <div class="media-body">
                    <h5 class="mt-3 " style="padding:6px 15px 7px 8px;">
                        <strong class="nome_marca"></strong>
                        <a class="btn btn-link float-right fechar-div-modelos" style="padding-top: 0 !important; margin-top: 0 !important;" href="#"><i class="fa-solid fa-xmark" style="font-size: 22px;color:#000;font-weight: 700;"></i></a>
                    </h5>
                </div>
            </div>          
        </div>
        <div class="col-md-12">
            <div class="card" style="border:none !important;">
                <div class="card-body" style="padding-left: 0px !important; padding-right: 0px !important;">
                  
                    <div class="list-group" id="list-group" style="border-radius:0 !important;">
                        
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    <script>
        function clearForm() {
    // Limpar todos os inputs do tipo texto, selects, radio buttons, checkboxes
    let form = document.getElementById('searchFormId'); // Ou selecione o formulário específico
    form.reset(); // Limpa todos os campos do formulário
}
        $(document).ready(function() {
            
            $('.fechar-div-modelos').click(function(e){
                e.preventDefault();
                $('.brands-list').show();
                $('.modelos-list').hide();
                
                $('#imageContainer').empty();
                $('#texto-marcas-modelos').text('MARCAS');
                
                document.getElementById('marcas-div').scrollIntoView({ behavior: 'smooth' });
            });
        });
        function show_modelos(id,img,nome_marca){
           $('.brands-list').hide();
           $('.modelos-list').show();
           $('#texto-marcas-modelos').text('MODELOS');
           
           var img = $('<img>', {
                src: img,  
                alt: img,               
                width: 65,                            
                height: 65,                           
                class: 'imgMarca'                  
            });
            $('#imageContainer').append(img);
            
            $('.nome_marca').html(nome_marca);
            
            document.getElementById('modelos-div').scrollIntoView({ behavior: 'smooth' });
            
            $.ajax({
                url: '/get-modelos/' + id, 
                type: 'GET',
                success: function(response) {
                    if (response.status === 'success') {
                        $('#list-group').empty();                                               

                        $.each(response.data, function(index, modelo) {                      
                            var isChecked = (response.selectedModelos && response.selectedModelos.includes(modelo.id)) ? 'checked' : '';

                    var listItem = `
                        <div class="form-check form-checkbox-${modelo.id} list-group-item list-group-item-action" 
style="margin-bottom:0px;border-left: none !important;border-right: none !important; padding-right: 5px !important;width:100%;position: relative;
    display: flex;
    align-items: center;" data-modelo-id="${modelo.id}">
                            <input 
                                name="modelo[]" 
                                class="form-check-input-modelos" 
                                type="checkbox" 
                                value="${modelo.id}" 
                                id="modelo${modelo.id}" 
                                ${isChecked} style="position: relative;margin-top: 0 !important;margin-left: 0 !important;display:none;" />
                            <label class="form-check-label-modelos" for="modelo${modelo.id}" style="margin-bottom:0 !important;display: block;width:100%;cursor:pointer;padding-left:10px;">
                                ${modelo.modelo_name} 
                            </label>
                        </div>
                    `;             
                            $('#list-group').append(listItem);
                        });                                               
                        
                $(".form-check-input-modelos").on('click', function() {
                    var idModelo = $(this).val();
                    $('.form-checkbox-'+idModelo).toggleClass('active');
                    submitSearchForm(); 
                });
                        
                    } else {
                        console.error('Nenhum modelo encontrado.');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Ocorreu um erro ao buscar os modelos:', error);
                }
            });
        }
        
        
    document.getElementById('toggleBrands').addEventListener('click', function() {
    const brandItems = document.querySelectorAll('.brand-item');
    const isExpanded = this.getAttribute('data-expanded') === 'true';

    if (isExpanded) {
        brandItems.forEach((item, index) => {
            item.style.display = index < 12 ? 'block' : 'none';
        });
        this.textContent = 'Ver mais';
        this.setAttribute('data-expanded', 'false');
    } else {
        brandItems.forEach(item => {
            item.style.display = 'block';
        });
        this.textContent = 'Ver menos';
        this.setAttribute('data-expanded', 'true');
    }
});
    </script>
</div>                        
                           <div class="lf-widget">
                        <div class="elementor-widget-container">
			<div class="jet-smart-filters-range jet-filter " data-indexer-rule="show" data-show-counter="" data-change-counter="always" jsf-filter="">
                            
                            <h2 style="font-size: 14px !important;color:#333;border-bottom: 2px solid #333;padding: 10px 0 8px 5px;">Ano</h2>

<div class="jet-range" data-query-type="meta_query" data-query-var="_km" data-smart-filter="range" data-filter-id="125" data-apply-type="ajax" data-content-provider="jet-engine" data-additional-providers="" data-query-id="result" data-active-label="Valor" data-layout-options="{&quot;show_label&quot;:true,&quot;display_options&quot;:{&quot;show_items_label&quot;:false,&quot;show_decorator&quot;:false,&quot;filter_image_size&quot;:&quot;full&quot;,&quot;show_counter&quot;:false}}" data-query-var-suffix="range" data-format="{&quot;decimal_num&quot;:0,&quot;decimal_sep&quot;:&quot;,&quot;,&quot;thousands_sep&quot;:&quot;.&quot;}">
	<fieldset class="jet-range__slider">
    <legend style="display:none;">Ano - slider</legend>
    <div class="jet-range__slider__track">
        <div class="jet-range__slider__track__range" style="--low: 0%; --high: 100%;"></div>
    </div>
    <!-- Quilometragem Mínima -->
    <input 
        type="tel" 
        class="jet-range__slider__input jet-range__slider__input--min range-ano-min float-left" 
        name="ano_min" 
        min="{{ $minAno }}" 
        max="{{ $maxAno }}" 
        value="{{ request()->get('ano_min') }}" 
        aria-label="Minimal value" 
        tabindex="0" 
        placeholder="ex.: {{ $minAno }}"
        style="width:120px;z-index: 21;height: calc(2.0em + .75rem + 2px) !important;
    padding: .775rem .775rem !important;
    font-size: 1rem !important;
    font-weight: 400 !important;
    line-height: 3.5 !important;border:1px solid #777 !important;">
    <!-- Quilometragem Máxima -->
    <input 
        type="tel" 
        class="jet-range__slider__input jet-range__slider__input--max range-ano-max float-right" 
        name="ano_max" 
        min="{{ $minAno }}" 
        max="{{ $maxAno }}" 
        value="{{ request()->get('ano_max') }}" 
        aria-label="Maximum value" 
        tabindex="0" 
        placeholder="ex.: {{ $maxAno }}"
        style="width:120px;z-index: 20;height: calc(2.0em + .75rem + 2px) !important;
    padding: .775rem .775rem !important;
    font-size: 1rem !important;
    font-weight: 400 !important;
    line-height: 3.5 !important;border:1px solid #777 !important;">
</fieldset>
		<div class="jet-range__values mt-4">
		<div class=" float-left badge badge-dark">
		<span class="jet-range__values-prefix"></span>
                <span class="jet-range__values-suffix">De:</span>
                <span class="jet-range__values-min ano-min">{{ $minAno }}</span> 
                </div>
                  
                 <div class=" float-right badge badge-dark">
                <span class="jet-range__values-prefix"></span>
                <span class="jet-range__values-suffix">Até:</span>
                <span class="jet-range__values-max ano-max">{{ $maxAno }}</span>
	</div>
                    <div class="clearfix"></div>
                    <div class="clear"></div>
	</div>
                    <div class="clearfix"></div>
                    <div class="clear"></div>
	</div>
                        </div>		
                        </div>
                        </div>
                        
                        
                        <div class="lf-widget">
                        <div class="elementor-widget-container">
			<div class="jet-smart-filters-range jet-filter " data-indexer-rule="show" data-show-counter="" data-change-counter="always" jsf-filter="">
                            
                            <h2 style="font-size: 14px !important;color:#333;border-bottom: 2px solid #333;padding: 10px 0 8px 5px;">Valor</h2>

<div class="jet-range" data-query-type="meta_query" data-query-var="_km" data-smart-filter="range" data-filter-id="125" data-apply-type="ajax" data-content-provider="jet-engine" data-additional-providers="" data-query-id="result" data-active-label="Valor" data-layout-options="{&quot;show_label&quot;:true,&quot;display_options&quot;:{&quot;show_items_label&quot;:false,&quot;show_decorator&quot;:false,&quot;filter_image_size&quot;:&quot;full&quot;,&quot;show_counter&quot;:false}}" data-query-var-suffix="range" data-format="{&quot;decimal_num&quot;:0,&quot;decimal_sep&quot;:&quot;,&quot;,&quot;thousands_sep&quot;:&quot;.&quot;}">
	<fieldset class="jet-range__slider">
    <legend style="display:none;">Valor - slider</legend>
    <div class="jet-range__slider__track">
        <div class="jet-range__slider__track__range" style="--low: 0%; --high: 100%;"></div>
    </div>
    <!-- Quilometragem Mínima -->
    <input 
        type="tel" 
        class="jet-range__slider__input jet-range__slider__input--min range-price-min float-left" 
        name="price_min" 
        min="{{ $minPrice }}" 
        max="{{ $maxPrice }}" 
        value="{{ request()->get('price_min') }}" 
        aria-label="Minimal value" 
        placeholder="ex.: {{ $minPrice }}"
        tabindex="0" 
        style="width:120px;z-index: 21;height: calc(2.0em + .75rem + 2px) !important;
    padding: .775rem .775rem !important;
    font-size: 1rem !important;
    font-weight: 400 !important;
    line-height: 3.5 !important;border:1px solid #777 !important;">
    <!-- Quilometragem Máxima -->
    <input 
        type="tel" 
        class="jet-range__slider__input jet-range__slider__input--max range-price-max float-right" 
        name="price_max" 
        min="{{ $minPrice }}" 
        max="{{ $maxPrice }}" 
        value="{{ request()->get('price_max') }}" 
        aria-label="Maximum value" 
        placeholder="ex.: {{ $maxPrice }}"
        tabindex="0" 
        style="width:120px;z-index: 21;height: calc(2.0em + .75rem + 2px) !important;
    padding: .775rem .775rem !important;
    font-size: 1rem !important;
    font-weight: 400 !important;
    line-height: 3.5 !important;border:1px solid #777 !important;">
</fieldset>
		<div class="jet-range__values mt-4">
                    <div class=" float-left badge badge-dark">
		<span class="jet-range__values-prefix"></span>
                <span class="jet-range__values-suffix">R$</span>
                <span class="jet-range__values-min price-min">{{ number_format($minPrice,0,'','.') }}</span>
                    </div>
                    
                    <div class=" float-right badge badge-dark">
                <span class="jet-range__values-prefix"></span>
                <span class="jet-range__values-suffix">R$</span>
                <span class="jet-range__values-max price-max">{{ number_format($maxPrice,0,'','.') }}</span>
	</div>
                    <div class="clearfix"></div>
                    <div class="clear"></div>
	</div>
                    <div class="clearfix"></div>
                    <div class="clear"></div>
	</div>
                        </div>		
                        </div>
                        </div>

 <div class="lf-widget">
                        <div class="elementor-widget-container">
			<div class="jet-smart-filters-range jet-filter " data-indexer-rule="show" data-show-counter="" data-change-counter="always" jsf-filter="">
                            
                            <h2 style="font-size: 14px !important;color:#333;border-bottom: 2px solid #333;padding: 10px 0 8px 5px;">Quilometragem</h2>

<div class="jet-range" data-query-type="meta_query" data-query-var="_km" data-smart-filter="range" data-filter-id="125" data-apply-type="ajax" data-content-provider="jet-engine" data-additional-providers="" data-query-id="result" data-active-label="Km" data-layout-options="{&quot;show_label&quot;:true,&quot;display_options&quot;:{&quot;show_items_label&quot;:false,&quot;show_decorator&quot;:false,&quot;filter_image_size&quot;:&quot;full&quot;,&quot;show_counter&quot;:false}}" data-query-var-suffix="range" data-format="{&quot;decimal_num&quot;:0,&quot;decimal_sep&quot;:&quot;,&quot;,&quot;thousands_sep&quot;:&quot;.&quot;}">
	<fieldset class="jet-range__slider">
    <legend style="display:none;">Quilometragem - slider</legend>
    <div class="jet-range__slider__track">
        <div class="jet-range__slider__track__range" style="--low: 0%; --high: 100%;"></div>
    </div>
    <!-- Quilometragem Mínima -->
    <input 
        type="tel" 
        class="jet-range__slider__input jet-range__slider__input--min range-min float-left" 
        name="km_min" 
        min="{{ $minMileage }}" 
        max="{{ $maxMileage }}" 
        value="{{ request()->get('km_min') }}" 
        aria-label="Minimal value" 
        placeholder="ex.: {{ $minMileage }}"
        tabindex="0" 
        style="width:120px;z-index: 21;height: calc(2.0em + .75rem + 2px) !important;
    padding: .775rem .775rem !important;
    font-size: 1rem !important;
    font-weight: 400 !important;
    line-height: 3.5 !important;border:1px solid #777 !important;">
    <!-- Quilometragem Máxima -->
    <input 
        type="tel" 
        class="jet-range__slider__input jet-range__slider__input--max range-max float-right" 
        name="km_max" 
        min="{{ $minMileage }}" 
        max="{{ $maxMileage }}"  
        value="{{ request()->get('km_max') }}" 
        aria-label="Maximum value" 
        placeholder="ex.: {{ $maxMileage }}"
        tabindex="0"  
        style="width:120px;z-index: 21;height: calc(2.0em + .75rem + 2px) !important;
    padding: .775rem .775rem !important;
    font-size: 1rem !important;
    font-weight: 400 !important;
    line-height: 3.5 !important;border:1px solid #777 !important;">
</fieldset>  
		<div class="jet-range__values mt-4" >
                    <div class=" float-left badge badge-dark"> 
		<span class="jet-range__values-prefix"></span>
                <span class="jet-range__values-min value-min">{{ number_format($minMileage,0,'','.') }}</span>
                <span class="jet-range__values-suffix">Km</span> 
                    </div>
                    <div class=" float-right badge badge-dark">
                <span class="jet-range__values-prefix"></span>
                <span class="jet-range__values-max value-max">{{ number_format($maxMileage,0,'','.') }}</span>
                <span class="jet-range__values-suffix">Km</span>
	</div>
                    <div class="clearfix"></div>
                    <div class="clear"></div>
	</div>
                    <div class="clearfix"></div>
                    <div class="clear"></div>
	</div>
                        </div>		
                        </div>
                        </div>

                        @php
                            $sort_aminity = [];
                            if(request()->has('amenity')){
                                foreach(request()->get('amenity') as $cat){
                                    array_push($sort_aminity,(int)$cat);
                                }
                            }
                        @endphp
                        <div class="lf-widget">
                            <h2 style="font-size: 14px !important;color:#333;border-bottom: 2px solid #333;padding: 10px 0 8px 5px;">Acessórios</h2>
                            @foreach($amenities as $index => $row)
                                <div class="form-check amenities-item" style="display: {{ $index < 5 ? 'block' : 'none' }};">
                                    <input {{ in_array($row->amenities_id ,$sort_aminity) ? 'checked' : '' }} name="amenity[]" class="form-check-input" type="checkbox" value="{{ $row->amenities_id }}" id="amn{{ $index }}" >
                                    <label class="form-check-label" for="amn{{ $index }}" style="font-size:12px;">
                                        {{ $row->amenity_name }} ({{ $row->listing_count }})
                                    </label>
                                </div>
                            @endforeach
                            <div class="row">
                            <div class="col-md-12">
    <button class="btn btn-dark btn-block" id="toggleAmenities">Ver mais</button>
    </div>
    </div>
                            <script>
    document.getElementById('toggleAmenities').addEventListener('click', function() {
    const brandItems = document.querySelectorAll('.amenities-item');
    const isExpanded = this.getAttribute('data-expanded') === 'true';

    if (isExpanded) {
        brandItems.forEach((item, index) => {
            item.style.display = index < 5 ? 'block' : 'none';
        });
        this.textContent = 'Ver mais';
        this.setAttribute('data-expanded', 'false');
    } else {
        brandItems.forEach(item => {
            item.style.display = 'block';
        });
        this.textContent = 'Ver menos';
        this.setAttribute('data-expanded', 'true');
    }
});
    </script>
                        </div>      
                        
                        
                        
                        @php
                            $sort_additional = [];
                            if(request()->has('additional')){
                                foreach(request()->get('additional') as $cat){
                                    array_push($sort_additional,(int)$cat);
                                }
                            }
                        @endphp
                        <div class="lf-widget">
                            <h2 style="font-size: 14px !important;color:#333;border-bottom: 2px solid #333;padding: 10px 0 8px 5px;">Itens do veículo</h2>
                            @foreach($additionals as $index => $row)
                                <div class="form-check additional-item {{ $row->additionals_id }}" style="display: {{ $index < 5 ? 'block' : 'none' }};">
                                    <input {{ in_array($row->additionals_id ,$sort_additional) ? 'checked' : '' }} name="additional[]" class="form-check-input" type="checkbox" value="{{ $row->additionals_id }}" id="additional{{ $index }}" >
                                    <label class="form-check-label" for="additional{{ $index }}" style="font-size:12px;">
                                        {{ $row->additional_name }} ({{ $row->listing_count }})
                                    </label>
                                </div>
                            @endforeach
                            <div class="row">
                            <div class="col-md-12">
    <button class="btn btn-dark btn-block" id="toggleAdditional">Ver mais</button>
    </div>
    </div>
                            <script>
    document.getElementById('toggleAdditional').addEventListener('click', function() {
    const additionalItems = document.querySelectorAll('.additional-item');
    const isExpanded = this.getAttribute('data-expanded') === 'true';

    if (isExpanded) {
        additionalItems.forEach((item, index) => {
            item.style.display = index < 5 ? 'block' : 'none';
        });
        this.textContent = 'Ver mais';
        this.setAttribute('data-expanded', 'false');
    } else {
        additionalItems.forEach(item => {
            item.style.display = 'block';
        });
        this.textContent = 'Ver menos';
        this.setAttribute('data-expanded', 'true');
    }
});
    </script>
                        </div>    
                        
                        
                        
                        
                                                                       
                        @php
    $sort_cambio = [];
    if (request()->has('cambio')) {
        foreach (request()->get('cambio') as $cambio) {
            array_push($sort_cambio, $cambio); 
        }
    }
@endphp
<div class="lf-widget">
    <h2 style="font-size: 14px !important;color:#333;border-bottom: 2px solid #333;padding: 10px 0 8px 5px;">CÂMBIO</h2>
    @foreach($listings_cambio as $index => $row)
        <div class="form-check cambio-item {{ $row->listing_transmission_id }}" style="display: {{ $index < 5 ? 'block' : 'none' }};">
            <input 
                {{ in_array($row->listing_transmission, $sort_cambio) ? 'checked' : '' }} 
                name="cambio[]" 
                class="form-check-input" 
                type="checkbox" 
                value="{{ $row->listing_transmission_id }}" 
                id="cambio{{ $index }}" />
            <label class="form-check-label" for="cambio{{ $index }}" style="font-size:12px;">
                {{ $row->listing_transmission }} ({{ $row->total }})
            </label>
        </div>
    @endforeach
    
     <div class="row">
                            <div class="col-md-12">
    <button class="btn btn-dark btn-block" id="toggleCambio">Ver mais</button>
    </div>
    </div>
                            <script>
    document.getElementById('toggleCambio').addEventListener('click', function() {
    const cambioItems = document.querySelectorAll('.cambio-item');
    const isExpanded = this.getAttribute('data-expanded') === 'true';

    if (isExpanded) {
        cambioItems.forEach((item, index) => {
            item.style.display = index < 5 ? 'block' : 'none';
        });
        this.textContent = 'Ver mais';
        this.setAttribute('data-expanded', 'false');
    } else {
        cambioItems.forEach(item => {
            item.style.display = 'block';
        });
        this.textContent = 'Ver menos';
        this.setAttribute('data-expanded', 'true');
    }
});
    </script>
</div>




@php
    $sort_carroceria = [];
    if (request()->has('carroceria')) {
        foreach (request()->get('carroceria') as $carroceria) {
            array_push($sort_carroceria, $carroceria); 
        }
    }
    function PT_limpaCPF_CNPJ($valor) {
        $valor = trim($valor);
        $valor = str_replace(",", "", $valor);
        $valor = str_replace(".", "", $valor);
        $valor = str_replace("-", "", $valor);
        $valor = str_replace("/", "", $valor);
        $valor = str_replace("(", "", $valor);
        $valor = str_replace(")", "", $valor);
        $valor = str_replace("%", "", $valor);
        $valor = str_replace("*", "", $valor);
        $valor = str_replace("&", "", $valor);
        $valor = str_replace("¨", "", $valor);
        $valor = str_replace("$", "", $valor);
        $valor = str_replace("#", "", $valor);
        $valor = str_replace("@", "", $valor);
        $valor = str_replace("!", "", $valor);
        $valor = str_replace(" ", "", $valor);
        $valor = str_replace(" ", "", $valor);
        return $valor;
    }
@endphp
<div class="lf-widget">
    <h2 style="font-size: 14px !important;color:#333;border-bottom: 2px solid #333;padding: 10px 0 8px 5px;">CARROCERIA</h2>
    @foreach($listings_carroceria as $index => $row)
    @php
    $listing_body = PT_limpaCPF_CNPJ($row->listing_body);
    @endphp
    @if(!empty($listing_body))
        <div class="form-check carroceria-item {{ $row->listing_body }}" style="display: {{ $index < 5 ? 'block' : 'none' }};">
            <input 
                {{ in_array($row->listing_body, $sort_carroceria) ? 'checked' : '' }} 
                name="carroceria[]" 
                class="form-check-input" 
                type="checkbox" 
                value="{{ $row->listing_body }}" 
                id="carroceria{{ $index }}" />
            <label class="form-check-label" for="carroceria{{ $index }}" style="font-size:12px;">
                {{ $row->listing_body }} ({{ $row->total }})
            </label>
        </div>
    @endif
    @endforeach
    
    <div class="row">
                            <div class="col-md-12">
    <button class="btn btn-dark btn-block" id="toggleCarroceria">Ver mais</button>
    </div>
    </div>
                            <script>
    document.getElementById('toggleCarroceria').addEventListener('click', function() {
    const carroceriaItems = document.querySelectorAll('.carroceria-item');
    const isExpanded = this.getAttribute('data-expanded') === 'true';

    if (isExpanded) {
        carroceriaItems.forEach((item, index) => {
            item.style.display = index < 5 ? 'block' : 'none';
        });
        this.textContent = 'Ver mais';
        this.setAttribute('data-expanded', 'false');
    } else {
        carroceriaItems.forEach(item => {
            item.style.display = 'block';
        });
        this.textContent = 'Ver menos';
        this.setAttribute('data-expanded', 'true');
    }
});
    </script>
</div>





@php
    $sort_cor = [];
    if (request()->has('cor')) {
        foreach (request()->get('cor') as $cor) {
            array_push($sort_cor, $cor); 
        }
    }
@endphp
<div class="lf-widget">
    <h2 style="font-size: 14px !important;color:#333;border-bottom: 2px solid #333;padding: 10px 0 8px 5px;">COR</h2>
    @foreach($listings_cor as $index => $row)
        <div class="form-check cor-item {{ $row->listing_exterior_color_id }}" style="display: {{ $index < 5 ? 'block' : 'none' }};">
            <input 
                {{ in_array($row->listing_exterior_color, $sort_cor) ? 'checked' : '' }} 
                name="cor[]" 
                class="form-check-input" 
                type="checkbox" 
                value="{{ $row->listing_exterior_color_id }}" 
                id="cor{{ $index }}" />
            <label class="form-check-label" for="cor{{ $index }}" style="font-size:12px;">
                {{ $row->listing_exterior_color }} ({{ $row->total }})
            </label>
        </div>
    @endforeach
    
    <div class="row">
                            <div class="col-md-12">
    <button class="btn btn-dark btn-block" id="toggleCor">Ver mais</button>
    </div>
    </div>
                            <script>
    document.getElementById('toggleCor').addEventListener('click', function() {
    const corItems = document.querySelectorAll('.cor-item');
    const isExpanded = this.getAttribute('data-expanded') === 'true';

    if (isExpanded) {
        corItems.forEach((item, index) => {
            item.style.display = index < 5 ? 'block' : 'none';
        });
        this.textContent = 'Ver mais';
        this.setAttribute('data-expanded', 'false');
    } else {
        corItems.forEach(item => {
            item.style.display = 'block';
        });
        this.textContent = 'Ver menos';
        this.setAttribute('data-expanded', 'true');
    }
});
    </script>
</div>




@php
    $sort_combustivel = [];
    if (request()->has('combustivel')) {
        foreach (request()->get('combustivel') as $combustivel) {
            array_push($sort_combustivel, $combustivel); 
        }
    }
@endphp
<div class="lf-widget">
    <h2 style="font-size: 14px !important;color:#333;border-bottom: 2px solid #333;padding: 10px 0 8px 5px;">COMBUSTÍVEL</h2>
    @foreach($listings_combustivel as $index => $row)
        <div class="form-check">
            <input 
                {{ in_array($row->listing_fuel_type, $sort_combustivel) ? 'checked' : '' }} 
                name="combustivel[]" 
                class="form-check-input" 
                type="checkbox" 
                value="{{ $row->listing_fuel_type_id }}" 
                id="combustivel{{ $index }}" />
            <label class="form-check-label" for="combustivel{{ $index }}" style="font-size:12px;">
                {{ $row->listing_fuel_type }} ({{ $row->total }})
            </label>
        </div>
    @endforeach
</div>


                        
                        
                        <div class="form-group">
                            <div class="buttons-filter" style="position: fixed;bottom:0 !important;z-index:1000 !important;width:100%;background-color:#ffffff;border-top:2px solid #ffffff;">
                            <div class="btn-group" role="group" aria-label="Basic example" style="width:100%;">
                                <button type="reset" class="btn btn-danger"><i class="fas fa-trash"></i> Limpar</button>
                                <button type="submit" class="btn btn-danger filter-button"><i class="fas fa-filter"></i> Filtrar</button>
                            </div>
                            </div>
                            
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-lg-9 col-md-6 col-sm-12" style="margin-top:5px;border-radius:6px;box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.3) !important;">
                <div class="right-area">
                    <div class="row">
                        <div class="col-12 mt-2 mb-3">
                            @php
                                
                                if ($total_registros > $quantidade_para_exibir) {
                                    $quantidade_para_exibir = $quantidade_para_exibir;
                                } else {
                                    $quantidade_para_exibir = $total_registros;
                                }
                            @endphp
                <span style="font-size:14px !important;font-weight: normal;" id="quantidade_para_exibir">Total de {{ $total_registros }} registros encontrados</span>
                <!--<span style="font-size:14px !important;font-weight: normal;" isd="quantidade_para_exibir"> Exibindo {{ $quantidade_para_exibir }} de {{ $total_registros }} Anúncios</span>-->
                    </div>
                    </div>
                </div>
                <div class="right-area">
                    <div class="row d-none" id="loader-area">
                        <div class="col-12 text-center mt-5">
                            <div>
                                <img src="{{ asset('loader.gif') }}" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="row" id="content-area" style="justify-content: center;">
                        <div class="col-12 text-center mt-5">
                            <div>
                                <img src="{{ asset('loader.gif') }}" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let loaderHtml = $("#loader-area").html();
    (function($) {
        "use strict";
        $(document).ready(function () {
            loadListingUsingAjax();
            $("#searchFormId").on('submit', function(e){
                e.preventDefault();
                submitSearchForm();
            })
            $("#listing_type").on('change', function(){
                submitSearchForm();
            })
            $("#orders").on('change', function(){
                $('#order').val($(this).val());
                submitSearchForm();
            })
            $("#type_all").on('change', function(){
                submitSearchForm();
            })
            $("#type_novo").on('change', function(){
                submitSearchForm();
            })
            $("#type_usado").on('change', function(){
                submitSearchForm();
            });
            $(".form-check-input").on('click', function(){
                submitSearchForm();
            });
            $(".location").on('change', function(){
                submitSearchForm();
            });
            /*$("#text").on('keyup', function(e){
                if(e.target.keyCode === '13'){
                    submitSearchForm()
                }
            });*/
            addTypingDelay("#text", 1000);
            addTypingDelay(".range-ano-min, .range-ano-max", 1000);
            addTypingDelay(".range-price-min, .range-price-max", 1000);
            addTypingDelay(".range-min, .range-max", 1000);
              
        });
    })(jQuery);

function addTypingDelay(selector, typingInterval) {
    let typingTimer; // Timer para controlar o intervalo

    $(selector).on('keyup', function() {
        clearTimeout(typingTimer); // Limpa o timer anterior
        typingTimer = setTimeout(submitSearchForm, typingInterval); // Inicia um novo timer
    });

    $(selector).on('keydown', function() {
        clearTimeout(typingTimer); // Reseta o timer se o usuário pressionar outra tecla
    });
}

function formatNumberWithThousandSeparator(number) {
    return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}


    function loadListingUsingAjax(){
        submitSearchForm()
    }

    function submitSearchForm(){
        $('#content-area').html(loaderHtml);

        var dados = $('#searchFormId').serialize();
        $.ajax({
            type: 'get',
            data: dados,
            url: "{{ route('search-front_listing_result') }}",
            success: function (response) {
                $('#content-area').html(response);
                $('#quantidade_para_exibir').show();
            },
            error: function(err) {}
        });
    }
    
    function loadAjaxListingNew(url) {
        url = url.replace(/^http:\/\//i, 'https://');
    $('#content-area').html(loaderHtml); // Mostra o loader enquanto a nova página carrega
    $.ajax({
        type: 'get',
        url: url,
        success: function (response) {
            $('#content-area').html(response); // Atualiza a área de conteúdo com os resultados da nova página
            $('#quantidade_para_exibir').show();
        },
        error: function(err) {
            console.error('Erro ao carregar a listagem:', err);
        }
    });
}

    function loadAjaxListing(url){
        url = url.replace(/^http:\/\//i, 'https://');
        console.log(url)
        $('#content-area').html(loaderHtml);
        $.ajax({
            type: 'get',
            url: url,
            success: function (response) {
                $('#content-area').html(response);
                $('#quantidade_para_exibir').show();
            },
            error: function(err) {}
        });
    }
</script>
@endsection