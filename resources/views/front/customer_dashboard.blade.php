@extends('front.app_front')

@section('content')

<div class="page-banner" style="background-image: url('{{ asset('uploads/page_banners/'.$page_other_item->customer_panel_page_banner) }}')">
	<div class="page-banner-bg"></div>
	<h1>{{ DASHBOARD }}</h1>
	<nav>
		<ol class="breadcrumb justify-content-center">
			<li class="breadcrumb-item"><a href="{{ url('/') }}">{{ HOME }}</a></li>
			<li class="breadcrumb-item active">{{ DASHBOARD }}</li>
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
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="dashboard-box" style='padding: 0px!important;'>
                                        <div class="listing-sidebar">
                                            <div class="ls-widget" style="padding-top: 0px!important;">
						<div class="agent">
                                                    <div class="photo">
							@if(empty($agent_detail->photo) && $agent_detail->photo == '' || !file_exists(public_path('uploads/user_photos/' . $agent_detail->photo)))
                                                            <img style="border: 1px solid #e1e1e1;" src="{{ asset('uploads/user_photos/default_photo.jpg') }}" alt="">
                                                        @else
                                                            <img style="border: 1px solid #e1e1e1;" src="{{ asset('uploads/user_photos/'.$agent_detail->photo) }}" alt="">
                                                        @endif
                                                    </div>
						</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                        <div class="dashboard-box dashboard-box-1">
                                                <div class="text">{{ ACTIVE_LISTING_ITEMS }}</div>
                                                <div class="number">{{ $total_active_listing }}</div>
                                        </div>
                                </div>
                                <div class="col-md-5">
                                        <div class="dashboard-box dashboard-box-2">
                                                <div class="text">{{ PENDING_LISTING_ITEMS }}</div>
                                                <div class="number">{{ $total_pending_listing }}</div>
                                        </div>
                                </div>

					@if(!$detail == null)
					<div class="col-md-12">
						<div class="dashboard-box dashboard-box-3">
							<div class="table-responsive">
								<table class="table table-bordered">
									<tr class="bg-dark text-light">
										<th colspan="2" style="text-align: center;">{{ $agent_detail->name }}</th>
									</tr>
									<tr>
										<td class="w-300">{{ ACTIVE_PACKAGE_NAME }}</td>
										<td>{{ $detail->rPackage->package_name }}</td>
									</tr>
									<tr>
										<td>{{ PACKAGE_START_DATE }}</td>
										<td>
											@php
											$good_format = date('d F, Y', strtotime($detail->package_start_date));
											@endphp
											{{ $good_format }}
										</td>
									</tr>
									<tr>
										<td>{{ PACKAGE_END_DATE }}</td>
										<td>
											@php
											$good_format = date('d F, Y', strtotime($detail->package_end_date));
											@endphp
											{{ $good_format }}
										</td>
									</tr>
									<tr>
										<td>{{ LISTING_ALLOWED }}</td>
										<td>
											{{ $detail->rPackage->total_listings }}
										</td>
									</tr>
									<tr>
										<td>{{ DAYS_REMAINING }}</td>
										<td>
											@php
											$dt1 = strtotime(date('Y-m-d'));
											$dt2 = strtotime($detail->package_end_date);
											$final_days = (int)(($dt2 - $dt1)/86400);
											@endphp

											@if($final_days < 0)
											<span class="badge badge-danger">{{ EXPIRED }}</span>
											@else
											{{ $final_days }}
											@endif
										</td>
									</tr>
									<tr>
										<td>{{ QUESTION_FEATURED_LISTING_ALLOWED }}</td>
										<td>
											{{ $detail->rPackage->allow_featured }}
										</td>
									</tr>
								</table>
							</div>
						</div>
					</div>
					@endif


				</div>

			</div>
		</div>
	</div>
</div>

@endsection
