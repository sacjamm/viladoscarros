@extends('admin.app_admin')
@section('admin_content')
    <h1 class="h3 mb-3 text-gray-800">{{ EDIT_LISTING_BRAND }}</h1>

    <form action="{{ route('admin_listing_brand_update',$listing_brand->id) }}" method="post" enctype="multipart/form-data">
        @csrf

        <input type="hidden" name="current_photo" value="{{ $listing_brand->listing_brand_photo }}">

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 mt-2 font-weight-bold text-primary"></h6>
                <div class="float-right d-inline">
                    <a href="{{ route('admin_listing_brand_view') }}" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> {{ VIEW_ALL }}</a>
                </div>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="">{{ NAME }} *</label>
                    <input type="text" name="listing_brand_name" id="listing_brand_name" class="form-control listing_brand_name" value="{{ $listing_brand->listing_brand_name }}" autofocus oninput="generateSlug('listing_brand_name','listing_brand_slug')">
                </div>
                <div class="form-group">
                    <label for="">{{ SLUG }}</label>
                    <input type="text" name="listing_brand_slug" id="listing_brand_slug" class="form-control listing_brand_slug" value="{{ $listing_brand->listing_brand_slug }}" readonly="">
                </div>
                <div class="form-group">
                    <label for="">{{ EXISTING_PHOTO }}</label>
                    <div>
                        @php
    $brandListingAlisson = App\Models\Listing::where('listing_brand_id', $listing_brand->id)
        ->where('listing_status', 'Active')
        ->first();
@endphp
                        
                        @if($brandListingAlisson && $brandListingAlisson->canal == 'dsautoestoque')
                        <img src="{{ asset('images/'.$listing_brand->listing_brand_slug.'.jpg') }}" alt="" class="w_200">
                        @else
                        <img src="{{ asset('uploads/listing_brand_photos/'.$listing_brand->listing_brand_photo) }}" alt="" class="w_200">
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <label for="">{{ CHANGE_PHOTO }}</label>
                    <div>
                        <input type="file" name="listing_brand_photo">
                    </div>
                </div>
            </div>
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">{{ SEO_INFORMATION }}</h6>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="">{{ TITLE }}</label>
                    <input type="text" name="seo_title" class="form-control" value="{{ $listing_brand->seo_title }}">
                </div>
                <div class="form-group">
                    <label for="">{{ META_DESCRIPTION }}</label>
                    <textarea name="seo_meta_description" class="form-control h_100" cols="30" rows="10">{{ $listing_brand->seo_meta_description }}</textarea>
                </div>
                <button type="submit" class="btn btn-success">{{ UPDATE }}</button>
            </div>
        </div>
    </form>

@endsection
