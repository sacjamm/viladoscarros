@extends('admin.app_admin')
@section('admin_content')
    <h1 class="h3 mb-3 text-gray-800">{{ LISTING }}</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 mt-2 font-weight-bold text-primary"></h6>
            <div class="float-right d-inline">
                <a href="{{ route('admin_listing_create') }}" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> {{ ADD_NEW }}</a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTableListing" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>{{ SERIAL }}</th>
                            <th>{{ FEATURED_PHOTO }}</th>
                            <th>{{ NAME }}, {{ BRAND }}, {{ LOCATION }}</th>
                            <th>Loja</th>
                            <th>{{ STATUS }}</th>
                            <th>{{ QUESTION_IS_FEATURED }}</th>
                            <th class="w_200">{{ ACTION }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Modal -->
<div class="modal fade modal_listing_detail" id="detail_info_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ LISTING_DETAIL }} &nbsp;&nbsp;&nbsp;</h5>
                <button type="button" class="btn btn-primary rota">
                    <i class="fa fa-edit"></i> Editar anúncio
                </button>
                <button type="button" class="close btn btn-danger" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="form-group listing_name_label">
                    <label for="">{{ NAME }}</label>
                    <div class="listing_name"></div>
                </div>

                <div class="form-group listing_slug_label">
                    <label for="">{{ SLUG }}</label>
                    <div class="listing_slug"></div>
                </div>

                <div class="form-group listing_description_label">
                    <label for="">{{ DESCRIPTION }}</label>
                    <div class="listing_description"></div>
                </div>

                <div class="form-group listing_brand_name_label">
                    <label for="">{{ LISTING_BRAND }}</label>
                    <div class="listing_brand_name"></div>
                </div>

                <div class="form-group listing_location_name_label">
                    <label for="">{{ LISTING_LOCATION }}</label>
                    <div class="listing_location_name"></div>
                </div>

                <div class="form-group listing_address_label">
                    <label for="">{{ ADDRESS }}</label>
                    <div class="listing_address"></div>
                </div>

                <div class="form-group listing_phone_label">
                    <label for="">{{ PHONE }}</label>
                    <div class="listing_phone"></div>
                </div>

                <div class="form-group listing_email_label">
                    <label for="">{{ EMAIL }}</label>
                    <div class="listing_email"></div>
                </div>

                <div class="form-group listing_map_label">
                    <label for="">{{ MAP }}</label>
                    <div class="listing_map"></div>
                </div>

                <div class="form-group listing_website_label">
                    <label for="">{{ WEBSITE }}</label>
                    <div class="listing_website"></div>
                </div>

                <div class="form-group listing_featured_photo_label">
                    <label for="">{{ FEATURED_PHOTO }}</label>
                    <div class="listing_featured_photo">
                        
                        
                    </div>
                </div>


                <div class="form-group">
                    <label for="">{{ FEATURES }}</label>

                    <div class="row bdb bdt listing_price_label">
                        <div class="col-md-3"><b>{{ PRICE }}</b>:</div>
                        <div class="col-md-9 listing_price"></div>
                    </div>

                    <div class="row bdb listing_type_label">
                        <div class="col-md-3"><b>{{ TYPE }}</b>:</div>
                        <div class="col-md-9 listing_type"></div>
                    </div>

                    <div class="row bdb listing_exterior_color_label">
                        <div class="col-md-3"><b>{{ EXTERIOR_COLOR }}</b>:</div>
                        <div class="col-md-9 listing_exterior_color"></div>
                    </div>

                    <div class="row bdb listing_interior_color_label">
                        <div class="col-md-3"><b>{{ INTERIOR_COLOR }}</b>:</div>
                        <div class="col-md-9 listing_interior_color"></div>
                    </div>

                    <div class="row bdb listing_cylinder_label">
                        <div class="col-md-3"><b>{{ CYLINDER }}</b>:</div>
                        <div class="col-md-9 listing_cylinder"></div>
                    </div>

                    <div class="row bdb listing_fuel_type_label">
                        <div class="col-md-3"><b>{{ FUEL_TYPE }}</b>:</div>
                        <div class="col-md-9 listing_fuel_type"></div>
                    </div>

                    <div class="row bdb listing_transmission_label">
                        <div class="col-md-3"><b>{{ TRANSMISSION }}</b>:</div>
                        <div class="col-md-9 listing_transmission"></div>
                    </div>

                    <div class="row bdb listing_engine_capacity_label">
                        <div class="col-md-3"><b>{{ ENGINE_CAPACITY }}</b>:</div>
                        <div class="col-md-9 listing_engine_capacity"></div>
                    </div>
                    
                    <div class="row bdb listing_vin_label">
                        <div class="col-md-3"><b>{{ VIN }}</b>:</div>
                        <div class="col-md-9 listing_vin"></div>
                    </div>

                    <div class="row bdb listing_body_label">
                        <div class="col-md-3"><b>{{ BODY }}</b>:</div>
                        <div class="col-md-9 listing_body"></div>
                    </div>

                    <div class="row bdb listing_seat_label">
                        <div class="col-md-3"><b>{{ SEAT }}</b>:</div>
                        <div class="col-md-9 listing_seat"></div>
                    </div>

                    <div class="row bdb listing_wheel_label">
                        <div class="col-md-3"><b>{{ WHEEL }}</b>:</div>
                        <div class="col-md-9 listing_wheel"></div>
                    </div>

                    
                    <div class="row bdb listing_door_label">
                        <div class="col-md-3"><b>{{ DOOR }}</b>:</div>
                        <div class="col-md-9 listing_door"></div>
                    </div>

                    
                    <div class="row bdb listing_mileage_label">
                        <div class="col-md-3"><b>{{ MILEAGE }}</b>:</div>
                        <div class="col-md-9 listing_mileage"></div>
                    </div>

                    
                    <div class="row bdb listing_model_year_label">
                        <div class="col-md-3"><b>{{ MODEL_YEAR }}</b>:</div>
                        <div class="col-md-9 listing_model_year"></div>
                    </div>
                    
                    
                </div>



                <div class="form-group">
                    <label for="">{{ OPENING_HOUR }}</label>

                    <div class="row bdb bdt listing_oh_monday_label">
                        <div class="col-md-3">
                            <b>{{ MONDAY }}</b>:
                        </div>
                        <div class="col-md-9 listing_oh_monday">
                        </div>
                    </div>

                    <div class="row bdb listing_oh_tuesday_label">
                        <div class="col-md-3">
                            <b>{{ TUESDAY }}</b>:
                        </div>
                        <div class="col-md-9 listing_oh_tuesday">
                        </div>
                    </div>

                    <div class="row bdb listing_oh_wednesday_label">
                        <div class="col-md-3">
                            <b>{{ WEDNESDAY }}</b>:
                        </div>
                        <div class="col-md-9 listing_oh_wednesday">
                        </div>
                    </div>

                    <div class="row bdb listing_oh_thursday_label">
                        <div class="col-md-3">
                            <b>{{ THURSDAY }}</b>:
                        </div>
                        <div class="col-md-9 listing_oh_thursday">
                        </div>
                    </div>

                    <div class="row bdb listing_oh_friday_label">
                        <div class="col-md-3">
                            <b>{{ FRIDAY }}</b>:
                        </div>
                        <div class="col-md-9 listing_oh_friday">
                        </div>
                    </div>

                    <div class="row bdb listing_oh_saturday_label">
                        <div class="col-md-3">
                            <b>{{ SATURDAY }}</b>:
                        </div>
                        <div class="col-md-9 listing_oh_saturday">
                        </div>
                    </div>

                    <div class="row bdb listing_oh_sunday_label">
                        <div class="col-md-3">
                            <b>{{ SUNDAY }}</b>:
                        </div>
                        <div class="col-md-9 listing_oh_sunday">
                        </div>
                    </div>

                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">{{ CLOSE }}</button>
            </div>
        </div>
    </div>
</div>
<!-- // Modal -->


    <script>
        function rotaFunc(rota){
            window.location.href=rota;
        }
        function abreModal(id) {
    $.ajax({
        url: '/admin/listingDetalhe/' + id,  // Rota para buscar os dados detalhados da listagem
        method: 'GET',
        success: function(response) {
            // Preencher o conteúdo da modal com os dados
            $('#detail_info_modal .listing_name').text(response.listing_name);
            $('#detail_info_modal .listing_slug').text(response.listing_slug);
            $('#detail_info_modal .listing_description').html(response.listing_description);
            $('#detail_info_modal .listing_brand_name').text(response.listing_brand_name);
            $('#detail_info_modal .listing_location_name').text(response.listing_location_name);
            $('#detail_info_modal .listing_price').text(response.listing_price);
            $('#detail_info_modal .listing_phone').text(response.listing_phone);
            $('#detail_info_modal .listing_address').text(response.listing_address);
            $('#detail_info_modal .listing_email').text(response.listing_email);
            $('#detail_info_modal .listing_type').text(response.listing_type);
            $('#detail_info_modal .listing_exterior_color').text(response.listing_exterior_color);
            $('#detail_info_modal .listing_interior_color').text(response.listing_interior_color);
            $('#detail_info_modal .listing_cylinder').text(response.listing_cylinder);
            $('#detail_info_modal .listing_fuel_type').text(response.listing_fuel_type);
            $('#detail_info_modal .listing_transmission').text(response.listing_transmission);
            $('#detail_info_modal .listing_engine_capacity').text(response.listing_engine_capacity);
            $('#detail_info_modal .listing_vin').text(response.listing_vin);
            $('#detail_info_modal .listing_seat').text(response.listing_seat);
            $('#detail_info_modal .listing_wheel').text(response.listing_wheel);
            $('#detail_info_modal .listing_door').text(response.listing_door);
            $('#detail_info_modal .listing_mileage').text(response.listing_mileage);
            $('#detail_info_modal .listing_model_year').text(response.listing_model_year);
            
            $('#detail_info_modal .listing_oh_monday').text(response.listing_oh_monday);
            $('#detail_info_modal .listing_oh_tuesday').text(response.listing_oh_tuesday);
            $('#detail_info_modal .listing_oh_wednesday').text(response.listing_oh_wednesday);
            $('#detail_info_modal .listing_oh_thursday').text(response.listing_oh_thursday);
            $('#detail_info_modal .listing_oh_friday').text(response.listing_oh_friday);
            $('#detail_info_modal .listing_oh_saturday').text(response.listing_oh_saturday);
            $('#detail_info_modal .listing_oh_sunday').text(response.listing_oh_sunday);
            
            $('#detail_info_modal .listing_featured_photo').html(response.listing_featured_photo);
            $('#detail_info_modal .listing_body').html(response.listing_body);
            $('#detail_info_modal .listing_map').html(response.listing_map);
            
            $('#detail_info_modal .listing_website').html(response.listing_website);
            
            var rotaUrl = response.rota;
            $('.rota').attr('onClick', `rotaFunc('${rotaUrl}'); return false;`);
            
            $('#detail_info_modal').modal('show');  // Exibir a modal
        },
        error: function() {
            alert('Ocorreu um erro ao carregar os detalhes.');
        }
    });
}
        function listingStatus(id){
            $.ajax({
                type:"get",
                url:"{{url('/admin/listing-status/')}}"+"/"+id,
                success:function(response){
                   toastr.success(response)
                },
                error:function(err){
                    console.log(err);
                }
            })
        }
    </script>
@endsection
