@extends('front.app_front')

@section('content')

<div class="page-banner" style="background-image: url('{{ asset('uploads/page_banners/'.$page_other_item->customer_panel_page_banner) }}')">
	<div class="page-banner-bg"></div>
	<h1>{{ ADD_LISTING }}</h1>
	<nav>
		<ol class="breadcrumb justify-content-center">
			<li class="breadcrumb-item"><a href="{{ url('/') }}">{{ HOME }}</a></li>
			<li class="breadcrumb-item active">{{ ADD_LISTING }}</li>
		</ol>
	</nav>
</div>

<div class="page-content">
	<div class="container">
		<div class="row">
			<div class="col-md-3">
				<div class="user-sidebar">
					@include('front.customer_sidebar')
				</div>
			</div>
			<div class="col-md-9">

				@if($listing_error_message != '')

				<span class="text-danger">{{ $listing_error_message }}</span>

				@else
				<form action="{{ route('customer_listing_add_store') }}" method="post" enctype="multipart/form-data">
                                    
                    @csrf
                    
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
  <li class="nav-item" role="presentation"><button data-toggle="tab" class="nav-link active btn-dark" data-target="#dados" type="button" role="tab" aria-controls="dados" aria-selected="false">Dados do anúncio</button></li>
  <li class="nav-item" role="presentation"><button data-toggle="tab" class="nav-link btn-dark" data-target="#recursos" type="button" role="tab" aria-controls="recursos" aria-selected="false">Recursos</button></li>
  <li class="nav-item" role="presentation"><button data-toggle="tab" class="nav-link btn-dark" data-target="#endereco" type="button" role="tab" aria-controls="endereco" aria-selected="false">Endereço</button></li>
  <li class="nav-item" role="presentation"><button data-toggle="tab" class="nav-link btn-dark" data-target="#imagens" type="button" role="tab" aria-controls="imagens" aria-selected="false">Imagens</button></li>
  <li class="nav-item" role="presentation"><button data-toggle="tab" class="nav-link btn-dark" data-target="#videos" type="button" role="tab" aria-controls="videos" aria-selected="false">Vídeos</button></li>
  <li class="nav-item" role="presentation"><button data-toggle="tab" class="nav-link btn-dark" data-target="#comodidades" type="button" role="tab" aria-controls="comodidades" aria-selected="false">Comodidades</button></li>
  <li class="nav-item" role="presentation"><button data-toggle="tab" class="nav-link btn-dark" data-target="#adicionais" type="button" role="tab" aria-controls="adicionais" aria-selected="false">Adicionais</button></li>
  <li class="nav-item" role="presentation"><button data-toggle="tab" class="nav-link btn-dark" data-target="#horario" type="button" role="tab" aria-controls="horario" aria-selected="false">Horário</button></li>
  <li class="nav-item" role="presentation"><button data-toggle="tab" class="nav-link btn-dark" data-target="#midias" type="button" role="tab" aria-controls="midias" aria-selected="false">Mídias Sociais</button></li>
  <li class="nav-item" role="presentation"><button data-toggle="tab" class="nav-link btn-dark" data-target="#seo" type="button" role="tab" aria-controls="seo" aria-selected="false">SEO</button></li>
</ul>
                    
                    <div class="tab-content pt-3">
                        
                        <div class="row">
                                <div class="col-md-12 mb-2">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="submit" class="btn btn-success float-right btn-lg">Salvar anúncio</button>
                                </div>
                                </div>
                                </div>
                        
                        <div id="dados" class="tab-pane in active" role="tabpanel" aria-labelledby="dados-tab">
                            
                            <div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="" style="font-size:18px;padding-top:15px;">{{ FEATURED_PHOTO }} *</label>
								<div>
                                                                    <input type="file" name="listing_featured_photo" class="form-control" required="">
                                </div>
							</div>
						</div>
					</div>
                            
                            <div class="row">
                                            <div class="col-md-12">
                                                <input type="hidden" readonly="" name="canal" value="website" />
                                            </div>
                                <div class="col-md-6">
                            <div class="form-group">
                                <label for="">O que quer vender? *</label>
                                <select name="listing_tipo_veiculo" class="form-control" required="">
									<option value="" disabled selected>Selecione uma opção</option>
									<option value="Carro">Carro</option>
									<option value="Moto">Moto</option>
								</select>
                            </div>
                        </div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="">{{ LISTING_NAME }} *</label>
                                                                <input type="text" name="listing_name" id="listing_name" class="form-control listing_name" value="{{ old('listing_name') }}" oninput="generateSlug('listing_name','listing_slug')" required="">
							</div>
						</div>
					
						<div class="col-md-12" style="display:none;">
							<div class="form-group">
								<label for="">{{ LISTING_SLUG }}</label>
								<input type="text" name="listing_slug" id="listing_slug" class="form-control listing_slug" value="{{ old('listing_slug') }}" readonly="">
							</div>
						</div>
					</div>
                            <div class="row">
						<div class="col-md-12">
							<div class="form-group">
                                                            <label for="">{{ LISTING_DESCRIPTION }} * <small class="text-danger">Uma breve descrição</small></label>
                                                            <textarea name="listing_description" class="form-control editor" cols="30" rows="10" required="">{{ old('listing_description') }}</textarea>
							</div>
						</div>
					</div>
                            
                            <div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="">{{ BRAND }} *</label>
                                                                <select name="listing_brand_id" class="form-control select2 marcaId" required="">
                                                                    <option value="" selected disabled>Selecione uma opção</option>
                                    @foreach($listing_brand as $row)
                                    <option value="{{ $row->id }}">{{ $row->listing_brand_name }}</option>
                                    @endforeach
                                </select>
							</div>
						</div>
                                            <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Modelo *</label>
                                            <select name="listing_modelo_id" class="form-control select2 modeloId" required="" style="width: 100%!important;">
                                                <option value="" disabled selected>Selecione o Modelo</option>
                                            </select>
                                        </div>
                                    </div>
                                            <div class="col-md-6">
                                                <label for="">Versão * <small>Não encontrou a versão? Clique para adicionar a versão!</small></label>
                                                <div class="input-group mb-3">
  <!--<input type="text" class="form-control" placeholder="Recipient's username" aria-label="Recipient's username" aria-describedby="button-addon2">-->
                                                    <select name="versao_id" class="form-control select2 versaoId" aria-describedby="button-addon2" style="width:80%;" required="">
                                                <option value="" disabled selected>Selecione a versão</option>
                                            </select>
  <div class="input-group-append">
      <button class="btn btn-outline-dark" type="button" id="button-addon2" onclick="addVersao();"> 
          <i class="fas fa-plus-circle"></i>
      </button>
  </div>
</div>
<!--                                        <div class="form-group">
                                            <label for="">Versão *</label>
                                            <select name="versao_id" class="form-control select2 versaoId" required="">
                                                <option value="" disabled selected>Selecione a versão</option>
                                            </select>
                                        </div>-->
                                    </div>
                                            <div class="col-md-6">
							<div class="form-group">
								<label for="">{{ WEBSITE }}</label>
								<input type="text" name="listing_website" class="form-control" value="{{ old('listing_website') }}">
							</div>
						</div>
                                            
                                            
						
					</div>
                            
                            @if($allow_featured == 'Yes')
                    <h4 class="mt_30">{{ QUESTION_FEATURED }}</h4>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <select name="is_featured" class="form-control">
                                    <option value="Yes">{{ YES }}</option>
                                    <option value="No">{{ NO }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    @endif
                            
                        </div>
                        
                        <div id="endereco" class="tab-pane" role="tabpanel" aria-labelledby="endereco-tab">
                             <div class="row">
						
                                            <div class="col-md-4">
							<div class="form-group">
								<label for="">CEP</label>
								<input type="text" name="cep" class="form-control cep" value="{{ old('cep') }}" oninput="mascara_checkout(this, 'cep')" maxlength="9">
							</div>
						</div>
                                            <div class="col-md-4">
							<div class="form-group">
								<label for="">Estado/UF</label>
								<select name="listing_uf" class="form-control uf">
									<option id="optUF" value="" disabled selected>Selecione uma opção</option>
                                    @foreach($estados as $row)
                                    <option value="{{ $row->estado_id }}">{{ $row->estado_nome }} - {{ $row->estado_uf }}</option>
                                    @endforeach
                                </select>
							</div>
						</div>
                                            <div class="col-md-4">
							<div class="form-group">
								<label for="">Cidade</label>
								<select name="listing_location_id" class="form-control select2 cidade">
									<!--<option value="" disabled selected>Selecione uma opção</option>-->
<!--                                    @foreach($listing_location as $row)
                                    <option value="{{ $row->id }}">{{ $row->listing_location_name }}</option>
                                    @endforeach-->
                                </select>
							</div>
						</div>
					</div>
                    
                    
					<div class="row">
                                            
						<div class="col-md-6">
							<div class="form-group">
								<label for="">{{ ADDRESS }} </label>
                                                                <textarea name="listing_address" class="form-control h-70 listing_address" cols="30" rows="10">{{ old('listing_address') }}</textarea>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
                                <label for="">{{ MAP_IFRAME_CODE }}</label>
                                <textarea name="listing_map" class="form-control h-70" cols="30" rows="10">{{ old('listing_map') }}</textarea>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="">{{ EMAIL_ADDRESS }} *</label>
                                                                <input type="text" name="listing_email" class="form-control" value="{{ old('listing_email') }}" required="">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
                                                            <label for="">{{ PHONE_NUMBER }} * <small class="text-success">Informe o seu WhatsApp</small> </label>
                                <input type="text" name="listing_phone" class="form-control" value="{{ old('listing_phone') }}" oninput="mascara_checkout(this, 'cel')">
							</div>
						</div>
					</div>
                        </div>
                        
                        <div id="recursos" class="tab-pane" role="tabpanel" aria-labelledby="recursos-tab">
                            <h4 class="mt_30">{{ FEATURES }}</h4>
					<div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">{{ PRICE }} *</label>
                                <input type="text" name="listing_price" required="" class="form-control listing_price price preco valor" value="{{ old('listing_price') }}" oninput="formatReal(this)">
                            </div>
                        </div>
						<div class="col-md-6">
                            <div class="form-group">
                                <label for="">{{ TYPE }} *</label>
                                <select name="listing_type" class="form-control" required="">
									<option value="" disabled selected>Selecione uma opção</option>
									<option value="Novo">{{ NEW_CAR }}</option>
									<option value="Usado">{{ USED_CAR }}</option>
								</select>
                            </div>
                        </div>
						
						<div class="col-md-6">
                            <div class="form-group">
                                <label for="">{{ EXTERIOR_COLOR }}</label>
                                <select name="listing_exterior_color" class="form-control select2">
									<option value="" disabled selected>Selecione uma opção</option>
                                                                        @if($colors)
                                                                        @foreach($colors as $color)
                                                                        <option value="{{ $color->id }}">{{ $color->color_name }}</option>
                                                                        @endforeach
                                                                        @endif
									
								</select>
                            </div>
                        </div>
<!--						<div class="col-md-6">
                            <div class="form-group">
                                <label for="">{{ INTERIOR_COLOR }}</label>
                                <input type="text" name="listing_interior_color" class="form-control" value="{{ old('listing_interior_color') }}">
                            </div>
                        </div>-->
<!--                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">{{ CYLINDER }}</label>
                                <input type="text" name="listing_cylinder" class="form-control" value="{{ old('listing_cylinder') }}">
                            </div>
                        </div>-->
						<div class="col-md-6">
                            <div class="form-group">
                                <label for="">{{ FUEL_TYPE }}</label>
                                <select name="listing_fuel_type" class="form-control select2">
									<option value="" disabled selected>Selecione uma opção</option>
                                                                        @if($combustiveis)
                                                                        @foreach($combustiveis as $combustivel)
                                                                        <option value="{{ $combustivel->id }}">{{ $combustivel->combustivel_name }}</option>
                                                                        @endforeach
                                                                        @endif
									
								</select>
                            </div>
                        </div>
						<div class="col-md-6">
                            <div class="form-group">
                                <label for="">{{ TRANSMISSION }}/Câmbio</label>
                                <select name="listing_transmission" class="form-control select2">
									<option value="" disabled selected>Selecione uma opção</option>
                                                                        @if($transmissions)
                                                                        @foreach($transmissions as $transmission)
                                                                        <option value="{{ $transmission->id }}">{{ $transmission->transmission_name }}</option>
                                                                        @endforeach
                                                                        @endif
									
								</select>
                            </div>
                        </div>
<!--						<div class="col-md-6">
                            <div class="form-group">
                                <label for="">{{ ENGINE_CAPACITY }}</label>
                                <input type="text" name="listing_engine_capacity" class="form-control" value="{{ old('listing_engine_capacity') }}">
                            </div>
                        </div>
						<div class="col-md-6">
                            <div class="form-group">
                                <label for="">{{ VIN }}</label>
                                <input type="text" name="listing_vin" class="form-control" value="{{ old('listing_vin') }}">
                            </div>
                        </div>-->
						<div class="col-md-6">
                            <div class="form-group">
                                <label for="">{{ BODY }}</label>
                                <input type="text" name="listing_body" class="form-control" value="{{ old('listing_body') }}">
                            </div>
                        </div>
<!--						<div class="col-md-6">
                            <div class="form-group">
                                <label for="">{{ SEAT }}</label>
                                <input type="text" name="listing_seat" class="form-control" value="{{ old('listing_seat') }}">
                            </div>
                        </div>-->
<!--						<div class="col-md-6">
                            <div class="form-group">
                                <label for="">{{ WHEEL }}</label>
                                <input type="text" name="listing_wheel" class="form-control" value="{{ old('listing_wheel') }}">
                            </div>
                        </div>-->
						<div class="col-md-6">
                            <div class="form-group">
                                <label for="">Quantidade de portas</label>
                                <input type="number" name="listing_door" class="form-control" value="{{ old('listing_door') }}">
                            </div>
                        </div>
						<div class="col-md-6">
                            <div class="form-group">
                                <label for="">{{ MILEAGE }}</label>
                                <input type="number" name="listing_mileage" class="form-control" value="{{ old('listing_mileage') }}">
                            </div>
                        </div>
						<div class="col-md-6">
                            <div class="form-group">
                                <label for="">Ano de Fabricação</label>
                                <select name="anofabricacao" class="form-control select2">
                                    <option value="" disabled selected>Selecione uma opção</option>
    @foreach (array_reverse(range(1960, now()->year)) as $year)
        <option value="{{ $year }}">{{ $year }}</option>
    @endforeach
</select>
                            </div>
                        </div>
						<div class="col-md-6">
                            <div class="form-group">
                                <label for="">{{ MODEL_YEAR }} </label>
                                <select name="listing_model_year" class="form-control select2">
                                    <option value="" disabled selected>Selecione uma opção</option>
    @foreach (array_reverse(range(1960, now()->year)) as $year)
        <option value="{{ $year }}">{{ $year }}</option>
    @endforeach
</select>
                            </div>
                        </div>

<div class="col-md-6">
                            <div class="form-group">
                                <label for="">Placa</label>
                                <input type="text" name="placa" class="form-control" value="{{ old('placa') }}" id="placa" maxlength="7" placeholder="Ex: ABC1D23" oninput="applyCarPlateMask(this)">
                            </div>
                        </div>
                    </div>
                        </div>
                        <div id="horario" class="tab-pane" role="tabpanel" aria-labelledby="horario-tab">
                            <h4 class="mt_30">{{ OPENING_HOUR }}</h4>
					<div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">{{ MONDAY }}</label>
                                <input type="text" name="listing_oh_monday" class="form-control" value="{{ old('listing_oh_monday') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">{{ TUESDAY }}</label>
                                <input type="text" name="listing_oh_tuesday" class="form-control" value="{{ old('listing_oh_tuesday') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">{{ WEDNESDAY }}</label>
                                <input type="text" name="listing_oh_wednesday" class="form-control" value="{{ old('listing_oh_wednesday') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">{{ THURSDAY }}</label>
                                <input type="text" name="listing_oh_thursday" class="form-control" value="{{ old('listing_oh_thursday') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">{{ FRIDAY }}</label>
                                <input type="text" name="listing_oh_friday" class="form-control" value="{{ old('listing_oh_friday') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">{{ SATURDAY }}</label>
                                <input type="text" name="listing_oh_saturday" class="form-control" value="{{ old('listing_oh_saturday') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">{{ SUNDAY }}</label>
                                <input type="text" name="listing_oh_sunday" class="form-control" value="{{ old('listing_oh_sunday') }}">
                            </div>
                        </div>
                    </div>
                        </div>
                        <div id="midias" class="tab-pane" role="tabpanel" aria-labelledby="midias-tab">
                            <h4 class="mt_30">{{ SOCIAL_MEDIA }}
						<div class="limit">(Allowed={{ $total_social_items }})</div>
					</h4>
					<div class="social_item">
						<div class="row social_for_count">
							<div class="col-md-5">
								<div class="form-group">
									<select name="social_icon[]" class="form-control">
										<option value="Facebook">{{ FACEBOOK }}</option>
										<option value="Twitter">{{ TWITTER }}</option>
										<option value="LinkedIn">{{ LINKEDIN }}</option>
										<option value="YouTube">{{ YOUTUBE }}</option>
										<option value="Pinterest">{{ PINTEREST }}</option>
										<option value="GooglePlus">{{ GOOGLE_PLUS }}</option>
										<option value="Instagram">{{ INSTAGRAM }}</option>
									</select>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<input type="text" name="social_url[]" class="form-control" placeholder="{{ URL }}">
								</div>
							</div>
							<div class="col-md-1">
								<span class="btn btn-success add_social_more"><i class="fas fa-plus"></i></span>
							</div>
						</div>
					</div>
                        </div>
                        <div id="comodidades" class="tab-pane" role="tabpanel" aria-labelledby="comodidades-tab">
                            <h4 class="mt_30">{{ AMENITIES }}
						<div class="limit">({{ ALLOWED_EQUAL }}{{ $total_amenities }})</div>
					</h4>
					<div class="row">
                        @php $i=0; @endphp
                        @foreach($amenity as $row)
                        @php $i++; @endphp
                        <div class="col-md-4">
                            <div class="form-check mb_10">
                                <input class="form-check-input amenity_check" name="amenity[]" type="checkbox" value="{{ $row->id }}" id="amenities{{ $i }}">
                                <label class="form-check-label" for="amenities{{ $i }}">
                                    {{ $row->amenity_name }}
                                </label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                        </div>
                        <div id="imagens" class="tab-pane" role="tabpanel" aria-labelledby="imagens-tab">
                            <h4 class="mt_30">{{ PHOTOS }}
						<div class="limit">({{ ALLOWED_EQUAL }}{{ $total_photos }})</div>
					</h4>
					<div class="photo_item">
						<div class="row photo_for_count">
							<div class="col-md-5">
								<div class="form-group">
									<input type="file" name="photo_list[]">
								</div>
							</div>
							<div class="col-md-1">
								<span class="btn btn-success add_photo_more"><i class="fas fa-plus"></i></span>
							</div>
						</div>
					</div>
                        </div>
                        <div id="videos" class="tab-pane" role="tabpanel" aria-labelledby="videos-tab">
                            <h4 class="mt_30">{{ VIDEOS }}
						<div class="limit">({{ ALLOWED_EQUAL }}{{ $total_videos }})</div>
					</h4>
					<div class="video_item">
						<div class="row video_for_count">
							<div class="col-md-5">
								<div class="form-group">
									<input type="text" name="youtube_video_id[]" class="form-control" placeholder="{{ YOUTUBE_VIDEO_ID }}">
								</div>
							</div>
							<div class="col-md-1">
								<span class="btn btn-success add_video_more"><i class="fas fa-plus"></i></span>
							</div>
						</div>
					</div>
                        </div>
                        <div id="adicionais" class="tab-pane" role="tabpanel" aria-labelledby="adicionais-tab">
                            <h4 class="mt_30">{{ ADDITIONAL_FEATURES }}
						<div class="limit">({{ ALLOWED_EQUAL }}{{ $total_additional_features }})</div>
					</h4>
					<div class="additional_feature_item">
						<div class="row additional_feature_for_count">
							<div class="col-md-5">
								<div class="form-group">
									<input type="text" name="additional_feature_name[]" class="form-control" placeholder="{{ NAME }}">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<input type="text" name="additional_feature_value[]" class="form-control" placeholder="{{ VALUE }}">
								</div>
							</div>
							<div class="col-md-1">
								<span class="btn btn-success add_additional_feature_more"><i class="fas fa-plus"></i></span>
							</div>
						</div>
					</div>
                        </div>
                        <div id="seo" class="tab-pane" role="tabpanel" aria-labelledby="seo-tab">
                            <h4 class="mt_30">{{ SEO_SECTION }}</h4>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="">{{ TITLE }}</label>
								<input type="text" name="seo_title" class="form-control" value="{{ old('seo_title') }}">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="">{{ META_DESCRIPTION }}</label>
								<textarea name="seo_meta_description" class="form-control h-70" cols="30" rows="10">{{ old('seo_meta_description') }}</textarea>
							</div>
						</div>
					</div>

                        </div>
                    </div>
                    
                    <div class="row">
                                <div class="col-md-12">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="submit" class="btn btn-success btn-block btn-lg">Salvar anúncio</button>
                                    <!--<button type="submit" class="btn btn-success btn-block btn-lg">{{ SUBMIT }}</button>-->
                                </div>
                                </div>
                                </div>
					<input type="hidden" class="estado_UF"/>				
				</form>

				@endif

			</div>
		</div>
	</div>
</div>


<div class="d_n">
	<div id="add_social">
		<div class="delete_social">
			<div class="row social_for_count">
				<div class="col-md-5">
					<div class="form-group">
						<select name="social_icon[]" class="form-control">
							<option value="Facebook">{{ FACEBOOK }}</option>
							<option value="Twitter">{{ TWITTER }}</option>
							<option value="LinkedIn">{{ LINKEDIN }}</option>
							<option value="YouTube">{{ YOUTUBE }}</option>
							<option value="Pinterest">{{ PINTEREST }}</option>
							<option value="GooglePlus">{{ GOOGLE_PLUS }}</option>
							<option value="Instagram">{{ INSTAGRAM }}</option>
						</select>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<input type="text" name="social_url[]" class="form-control" placeholder="{{ URL }}">
					</div>
				</div>
				<div class="col-md-1">
					<span class="btn btn-danger remove_social_more"><i class="fas fa-minus"></i></span>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="d_n">
	<div id="add_photo">
		<div class="delete_photo">
			<div class="row photo_for_count">
				<div class="col-md-5">
					<div class="form-group">
						<input type="file" name="photo_list[]">
					</div>
				</div>
				<div class="col-md-1">
					<span class="btn btn-danger remove_photo_more"><i class="fas fa-minus"></i></span>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="d_n">
	<div id="add_video">
		<div class="delete_video">
			<div class="row video_for_count">
				<div class="col-md-5">
					<div class="form-group">
						<input type="text" name="youtube_video_id[]" class="form-control" placeholder="{{ YOUTUBE_VIDEO_ID }}">
					</div>
				</div>
				<div class="col-md-1">
					<span class="btn btn-danger remove_video_more"><i class="fas fa-minus"></i></span>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="d_n">
	<div id="add_additional_feature">
		<div class="delete_additional_feature">
			<div class="row additional_feature_for_count">
				<div class="col-md-5">
					<div class="form-group">
						<input type="text" name="additional_feature_name[]" class="form-control" placeholder="{{ NAME }}">
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<input type="text" name="additional_feature_value[]" class="form-control" placeholder="{{ VALUE }}">
					</div>
				</div>
				<div class="col-md-1">
					<span class="btn btn-danger remove_additional_feature_more"><i class="fas fa-minus"></i></span>
				</div>
			</div>
		</div>
	</div>
</div>


<script>
function countElements(class_name) {
    var numItems = $('.'+class_name).length
    return numItems;
}

// Social Item Check
$(document).on("click",".add_social_more",function() {
	if(countElements('social_for_count') > {{ $total_social_items }}) {
		toastr.error('{{ MAX_ALLOWED_SOCIAL_ITEMS_FOR_YOU }} {{ $total_social_items }}')
	} else {
		var add_social = $("#add_social").html();
  		$(this).closest(".social_item").append(add_social);
	}
});
$(document).on("click",".remove_social_more",function(event){
	$(this).closest(".delete_social").remove();
});


// Photo Check
$(document).on("click",".add_photo_more",function() {
	if(countElements('photo_for_count') > {{ $total_photos }}) {
		toastr.error('{{ MAX_ALLOWED_PHOTOS_FOR_YOU }} {{ $total_photos }}')
	} else {
		var add_photo = $("#add_photo").html();
  		$(this).closest(".photo_item").append(add_photo);
	}
});
$(document).on("click",".remove_photo_more",function(event){
	$(this).closest(".delete_photo").remove();
});


// Video Check
$(document).on("click",".add_video_more",function() {
	if(countElements('video_for_count') > {{ $total_videos }}) {
		toastr.error('{{ MAX_ALLOWED_VIDEOS_FOR_YOU }} {{ $total_videos }}')
	} else {
		var add_video = $("#add_video").html();
  		$(this).closest(".video_item").append(add_video);
	}
});
$(document).on("click",".remove_video_more",function(event){
	$(this).closest(".delete_video").remove();
});


// Additional Feature
$(document).on("click",".add_additional_feature_more",function() {
	if(countElements('additional_feature_for_count') > {{ $total_additional_features }}) {
		toastr.error('{{ MAX_ALLOWED_ADDITIONAL_FEATURES_FOR_YOU }} {{ $total_additional_features }}')
	} else {
		var add_additional_feature = $("#add_additional_feature").html();
  		$(this).closest(".additional_feature_item").append(add_additional_feature);
	}
});
$(document).on("click",".remove_additional_feature_more",function(event){
	$(this).closest(".delete_additional_feature").remove();
});


// Amenities
$('.amenity_check').on('click',function() {
  	if(this.checked) {
    	var total = $("input[name='amenity[]']:checked").length;
    	if(total > {{ $total_amenities }})
    	{
    		$(this).prop("checked", false);
    		toastr.error('{{ MAX_ALLOWED_AMENITIES_FOR_YOU }} {{ $total_amenities }}')
    	}
  	}
});
</script>
  <script>
    $(document).ready(function() {
        
        function slugCustomer(title) {
        
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
    
        let modeloSelect = $('.modeloId');
        let versaoSelect = $('.versaoId');
        let selectedBrandId = $('.marcaId').val();
        let selectedEstado_UFId = $('.estado_UF').val();
        let selectedModeloId = null; // ID do modelo salvo no banco
        let selectedVersaoId = null; // ID da versão salvo no banco
        
        let cidadeSelect = $('.cidade');
        let selectedUfId = $('.uf').val();
        let selectedCidadeId = null; // ID do modelo salvo no banco

        // Função para carregar modelos com base na marca selecionada
        function loadModelos(brandId, selectedModeloId = null) {
            modeloSelect.empty().append('<option value="">Selecione o Modelo</option>');

            if (brandId) {
                $.ajax({
                    url: '/combo-modelos/' + brandId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $.each(data, function(key, modelo) {
                            let isSelected = (selectedModeloId && modelo.id == selectedModeloId) ? 'selected' : '';
                            modeloSelect.append(`<option value="${modelo.id}" ${isSelected}>${modelo.modelo_name}</option>`);
                        });
                    },
                    error: function() {
                        alert('Erro ao buscar os modelos.');
                    }
                });
            }
        }
        function loadVersoes(modeloId, selectedVersaoId = null) {
            versaoSelect.empty().append('<option value="">Selecione a Versão</option>');

            if (modeloId) {
                $.ajax({
                    url: '/combo-versao/' + modeloId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $.each(data, function(key, versao) {
                            let isSelected = (selectedVersaoId && versao.id == selectedVersaoId) ? 'selected' : '';
                            versaoSelect.append(`<option value="${versao.versao_slug}" ${isSelected}>${versao.versao_name}</option>`);
                        });
                    },
                    error: function() {
                        alert('Erro ao buscar as versões.');
                    }
                });
            }
        }
        function loadCidades(ufId, selectedCidadeId = null) {
            cidadeSelect.empty().append('<option value="">Selecione a Cidade</option>');

            if (ufId) {
                $.ajax({
                    url: '/combo-cidades/' + ufId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $.each(data, function(key, cidade) {
                            let isSelected = (selectedCidadeId && cidade.cidade_id == selectedCidadeId) ? 'selected' : '';
                            var attrID = slugCustomer(cidade.cidade_nome);
                            cidadeSelect.append(`<option id="${attrID}" value="${cidade.cidade_id}" ${isSelected}>${cidade.cidade_nome}</option>`);
                        });
                    },
                    error: function() {
                        alert('Erro ao buscar as cidades.');
                    }
                });
            }
        }

        // Carrega os modelos quando a marca está predefinida (ao carregar a página)
        if (selectedBrandId) {
            loadModelos(selectedBrandId, selectedModeloId);
        }
        if (selectedModeloId) {
            loadVersoes(selectedModeloId, selectedVersaoId);
        }
        if (selectedUfId) {
            loadCidades(selectedUfId, selectedCidadeId);
        }
        if (selectedEstado_UFId) {
            loadCidades(selectedEstado_UFId);
        }

        // Carrega os modelos quando a marca é selecionada ou alterada
        $('.marcaId').change(function() {
            let brandId = $(this).val();
            $('.modal-marcaId').val(brandId);
            loadModelos(brandId);
        });
        $('.modeloId').change(function() {
            let modeloId = $(this).val();
            $('.modal-modeloId').val(modeloId);
            loadVersoes(modeloId);
        });
        $('.uf').change(function() {
            let uffId = $(this).val();
            loadCidades(uffId);
        });
    });
</script>
@endsection
