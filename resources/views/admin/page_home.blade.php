@extends('admin.app_admin')

@section('admin_content')
    <h1 class="h3 mb-3 text-gray-800">{{ EDIT_HOME_PAGE_INFO }}</h1>

    <form action="{{ route('admin_page_home_update') }}" method="post" enctype="multipart/form-data">
        @csrf

        <input type="hidden" name="current_search_background" value="{{ $page_home->search_background }}">
        <input type="hidden" name="current_testimonial_background" value="{{ $page_home->testimonial_background }}">
        <input type="hidden" name="current_video_background" value="{{ $page_home->video_background }}">

        <div class="card shadow mb-4 t-left">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 col-sm-12">
                        <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                            <a class="nav-link active" id="p1_tab" data-toggle="pill" href="#p1" role="tab" aria-controls="p1" aria-selected="true">{{ SEO_SECTION }}</a>
                            <a class="nav-link" id="p2_tab" data-toggle="pill" href="#p2" role="tab" aria-controls="p2" aria-selected="false">{{ SEARCH_SECTION }}</a>
                            <a class="nav-link" id="p3_tab" data-toggle="pill" href="#p3" role="tab" aria-controls="p3" aria-selected="false">{{ BRAND_SECTION }}</a>
                            <a class="nav-link" id="p6_tab" data-toggle="pill" href="#p6" role="tab" aria-controls="p6" aria-selected="false">{{ VIDEO_SECTION }}</a>
                            <a class="nav-link" id="p4_tab" data-toggle="pill" href="#p4" role="tab" aria-controls="p4" aria-selected="false">{{ LISTING_SECTION }}</a>
                            <a class="nav-link" id="p7_tab" data-toggle="pill" href="#p7" role="tab" aria-controls="p7" aria-selected="false">{{ TESTIMONIAL_SECTION }}</a>
                            <a class="nav-link" id="p5_tab" data-toggle="pill" href="#p5" role="tab" aria-controls="p5" aria-selected="false">{{ LOCATION_SECTION }}</a>
                        </div>
                    </div>
                    <div class="col-md-9 col-sm-12">
                        <div class="tab-content" id="v-pills-tabContent">

                            <div class="tab-pane fade show active" id="p1" role="tabpanel" aria-labelledby="p1_tab">

                                <!-- Tab Content -->
                                <div class="form-group">
                                    <label for="">{{ TITLE }}</label>
                                    <input type="text" name="seo_title" class="form-control" value="{{ $page_home->seo_title }}">
                                </div>
                                <div class="form-group">
                                    <label for="">{{ META_DESCRIPTION }}</label>
                                    <textarea name="seo_meta_description" class="form-control h_70" cols="30" rows="10">{{ $page_home->seo_meta_description }}</textarea>
                                </div>
                                <!-- // Tab Content -->

                            </div>

                            <div class="tab-pane fade" id="p2" role="tabpanel" aria-labelledby="p2_tab">

                                <!-- Tab Content -->
                                <div class="form-group">
                                    <label for="">{{ SEARCH_HEADING }}</label>
                                    <textarea name="search_heading" class="form-control h_70" cols="30" rows="10">{{ $page_home->search_heading }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label for="">{{ SEARCH_TEXT }}</label>
                                    <textarea name="search_text" class="form-control h_70" cols="30" rows="10">{{ $page_home->search_text }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label for="">{{ EXISTING_SEARCH_BACKGROUND }}</label>
                                    <div>
                                        <img src="{{ asset('uploads/site_photos/'.$page_home->search_background) }}" alt="" class="w_200">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="">{{ CHANGE_SEARCH_BACKGROUND }}</label>
                                    <div>
                                        <input type="file" name="search_background">
                                    </div>
                                </div>
                                <!-- // Tab Content -->

                            </div>




                            <div class="tab-pane fade" id="p3" role="tabpanel" aria-labelledby="p3_tab">

                                <!-- Tab Content -->
                                <div class="form-group">
                                    <label for="">{{ HEADING }}</label>
                                    <input type="text" name="brand_heading" class="form-control" value="{{ $page_home->brand_heading }}">
                                </div>

                                <div class="form-group">
                                    <label for="">{{ SUBHEADING }}</label>
                                    <input type="text" name="brand_subheading" class="form-control" value="{{ $page_home->brand_subheading }}">
                                </div>

                                <div class="form-group">
                                    <label for="">{{ TOTAL_ITEMS }}</label>
                                    <input type="text" name="brand_total" class="form-control" value="{{ $page_home->brand_total }}">
                                </div>

                                <div class="form-group">
                                    <label for="">{{ STATUS }}</label>
                                    <select name="brand_status" class="form-control">
                                        <option value="Show" {{ $page_home->brand_status == 'Show' ? 'selected' : '' }}>{{ SHOW }}</option>
                                        <option value="Hide" {{ $page_home->brand_status == 'Hide' ? 'selected' : '' }}>{{ HIDE }}</option>
                                    </select>
                                </div>
                                <!-- // Tab Content -->

                            </div>





                            <div class="tab-pane fade" id="p6" role="tabpanel" aria-labelledby="p6_tab">

                                <!-- Tab Content -->
                                <div class="form-group">
                                    <label for="">{{ VIDEO_HEADING }}</label>
                                    <input type="text" name="video_heading" class="form-control" value="{{ $page_home->video_heading }}">
                                </div>

                                <div class="form-group">
                                    <label for="">{{ VIDEO_TEXT }}</label>
                                    <textarea name="video_text" class="form-control h_70" cols="30" rows="10">{{ $page_home->video_text }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label for="">{{ VIDEO_YOUTUBE_ID }}</label>
                                    <input type="text" name="video_youtube_id" class="form-control" value="{{ $page_home->video_youtube_id }}">
                                </div>

                                <div class="form-group">
                                    <label for="">{{ EXISTING_VIDEO_BACKGROUND }}</label>
                                    <div>
                                        <img src="{{ asset('uploads/site_photos/'.$page_home->video_background) }}" alt="" class="w_200">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="">{{ CHANGE_VIDEO_BACKGROUND }}</label>
                                    <div>
                                        <input type="file" name="video_background">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="">{{ STATUS }}</label>
                                    <select name="video_status" class="form-control">
                                        <option value="Show" {{ $page_home->video_status == 'Show' ? 'selected' : '' }}>{{ SHOW }}</option>
                                        <option value="Hide" {{ $page_home->video_status == 'Hide' ? 'selected' : '' }}>{{ HIDE }}</option>
                                    </select>
                                </div>
                                <!-- // Tab Content -->

                            </div>







                            <div class="tab-pane fade" id="p4" role="tabpanel" aria-labelledby="p4_tab">
                                <!-- Tab Content -->
                                <div class="form-group">
                                    <label for="">{{ HEADING }}</label>
                                    <input type="text" name="listing_heading" class="form-control" value="{{ $page_home->listing_heading }}">
                                </div>

                                <div class="form-group">
                                    <label for="">{{ SUBHEADING }}</label>
                                    <input type="text" name="listing_subheading" class="form-control" value="{{ $page_home->listing_subheading }}">
                                </div>

                                <div class="form-group">
                                    <label for="">{{ TOTAL_ITEMS }}</label>
                                    <input type="text" name="listing_total" class="form-control" value="{{ $page_home->listing_total }}">
                                </div>

                                <div class="form-group">
                                    <label for="">{{ STATUS }}</label>
                                    <select name="listing_status" class="form-control">
                                        <option value="Show" {{ $page_home->listing_status == 'Show' ? 'selected' : '' }}>{{ SHOW }}</option>
                                        <option value="Hide" {{ $page_home->listing_status == 'Hide' ? 'selected' : '' }}>{{ HIDE }}</option>
                                    </select>
                                </div>
                                <!-- // Tab Content -->

                            </div>



                            <div class="tab-pane fade" id="p5" role="tabpanel" aria-labelledby="p5_tab">

                                <!-- Tab Content -->
                                <div class="form-group">
                                    <label for="">{{ HEADING }}</label>
                                    <input type="text" name="location_heading" class="form-control" value="{{ $page_home->location_heading }}">
                                </div>

                                <div class="form-group">
                                    <label for="">{{ SUBHEADING }}</label>
                                    <input type="text" name="location_subheading" class="form-control" value="{{ $page_home->location_subheading }}">
                                </div>

                                <div class="form-group">
                                    <label for="">{{ TOTAL_ITEMS }}</label>
                                    <input type="text" name="location_total" class="form-control" value="{{ $page_home->location_total }}">
                                </div>

                                <div class="form-group">
                                    <label for="">{{ STATUS }}</label>
                                    <select name="location_status" class="form-control">
                                        <option value="Show" {{ $page_home->location_status == 'Show' ? 'selected' : '' }}>{{ SHOW }}</option>
                                        <option value="Hide" {{ $page_home->location_status == 'Hide' ? 'selected' : '' }}>{{ HIDE }}</option>
                                    </select>
                                </div>
                                <!-- // Tab Content -->

                            </div>



                            <div class="tab-pane fade" id="p7" role="tabpanel" aria-labelledby="p7_tab">

                                <!-- Tab Content -->
                                <div class="form-group">
                                    <label for="">{{ HEADING }}</label>
                                    <input type="text" name="testimonial_heading" class="form-control" value="{{ $page_home->testimonial_heading }}">
                                </div>

                                <div class="form-group">
                                    <label for="">{{ SUBHEADING }}</label>
                                    <input type="text" name="testimonial_subheading" class="form-control" value="{{ $page_home->testimonial_subheading }}">
                                </div>

                                <div class="form-group">
                                    <label for="">{{ EXISTING_PHOTO }}</label>
                                    <div>
                                        <img src="{{ asset('uploads/site_photos/'.$page_home->testimonial_background) }}" alt="" class="w_200">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="">{{ CHANGE_PHOTO }}</label>
                                    <div>
                                        <input type="file" name="testimonial_background">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="">{{ STATUS }}</label>
                                    <select name="testimonial_status" class="form-control">
                                        <option value="Show" {{ $page_home->testimonial_status == 'Show' ? 'selected' : '' }}>{{ SHOW }}</option>
                                        <option value="Hide" {{ $page_home->testimonial_status == 'Hide' ? 'selected' : '' }}>{{ HIDE }}</option>
                                    </select>
                                </div>
                                <!-- // Tab Content -->

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-success btn-block mb_50">{{ UPDATE }}</button>
    </form>
@endsection
