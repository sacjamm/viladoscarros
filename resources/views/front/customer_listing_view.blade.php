@extends('front.app_front')

@section('content')

<div class="page-banner" style="background-image: url('{{ asset('uploads/page_banners/'.$page_other_item->customer_panel_page_banner) }}')">
	<div class="page-banner-bg"></div>
	<h1>{{ ALL_LISTINGS }}</h1>
	<nav>
		<ol class="breadcrumb justify-content-center">
			<li class="breadcrumb-item"><a href="{{ url('/') }}">{{ HOME }}</a></li>
			<li class="breadcrumb-item active">{{ ALL_LISTINGS }}</li>
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

				@if($listing->isEmpty())
				<span class="text-danger">{{ NO_RESULT_FOUND }}</span>
				@else

				<div class="table-responsive-md">
					<table class="table table-bordered">
						<thead>
							<tr class="table-primary">
								<th scope="col">{{ SERIAL }}</th>
								<th scope="col">{{ FEATURED_PHOTO }}</th>
								<th scope="col">{{ LISTING_NAME }}</th>
								<th scope="col">{{ BRAND }}</th>
								<th scope="col">{{ LOCATION }}</th>
								<th scope="col">Criado em</th>
								<th scope="col">Alterado em</th>
								<th scope="col">{{ STATUS }}</th>
								<th scope="col" class="w-150">{{ ACTION }}</th>
							</tr>
						</thead>
						<tbody>
							@php $i=0; @endphp
                        	@foreach($listing as $row)
                                @php
                                $dataCadastro = \Carbon\Carbon::parse($row->created_at);
                                $diferencaDias = $dataCadastro->diffInDays(\Carbon\Carbon::now());
                                @endphp
							<tr>
								<td>{{ $loop->iteration }}</td>
								<td>
                                                                    
                                                                    @if($row->canal === 'dsautoestoque')
									<img src="{{ asset($row->listing_featured_photo) }}" alt="" class="w-100">
                                                                        @else
									<img src="{{ asset('uploads/listing_featured_photos/'.$row->listing_featured_photo) }}" alt="" class="w-100">
                                                                        @endif
                                                                   
								</td>
								<td>
                                                                    <a href="{{ route('front_listing_detail',[$row->id,$row->listing_slug]) }}" target="_blank">
                                    {{ $row->listing_name }} 
                                                                    </a>
                                                                    <br>
                                    @if($row->is_featured == 'Yes')
                                        <span class="badge badge-success">{{ FEATURED }}</span>
                                    @else
                                        <span class="badge badge-danger">{{ NOT_FEATURED }}</span>
                                    @endif
                                </td>
								<td>{{ $row->rListingBrand->listing_brand_name }}</td>
								<td>{{ $row->rListingLocation->listing_location_name }}</td>
								<td>
                                                                    {{ date('d/m/Y H:i',strtotime($row->created_at)) }} <br>
                                                               Cadastrado há {{ $diferencaDias }} dias atrás
                                                                </td>
								<td>{{ date('d/m/Y H:i',strtotime($row->updated_at)) }}</td>
								<td>
									@if($row->listing_status == 'Active')
	                                <h6><span class="badge badge-success">
	                                @else
	                                <h6><span class="badge badge-danger">
	                                @endif
	                                {{ $row->listing_status }}</span></h6>
								</td>
								<td>
									<a href="{{ route('customer_listing_view_detail',$row->id) }}" class="btn btn-success btn-sm"><i class="fas fa-eye"></i></a>

	                                <a href="{{ route('customer_listing_edit',$row->id) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>

	                                <a href="{{ route('customer_listing_delete',$row->id) }}" class="btn btn-danger btn-sm" onClick="return confirm('{{ ARE_YOU_SURE }}');"><i class="fas fa-trash-alt"></i></a>
								</td>
							</tr>
                        	@endforeach

						</tbody>
					</table>
				</div>
				@endif

			</div>
		</div>
	</div>
</div>

@endsection
