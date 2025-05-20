@extends('admin.app_admin')
@section('admin_content')
    <h1 class="h3 mb-3 text-gray-800">Adicionar banner</h1>

    <form action="{{ route('admin_banner_store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 mt-2 font-weight-bold text-primary"></h6>
                <div class="float-right d-inline">
                    <a href="{{ route('admin_banner_view') }}" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> {{ VIEW_ALL }}</a>
                </div>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="">{{ TITLE }} *</label>
                    <input type="text" name="title" id="title" class="form-control title" value="{{ old('title') }}">
                </div>
                
                <div class="form-group">
                    <label for="">{{ CONTENT }} *</label>
<!--                    <textarea name="post_content" class="form-control editor" cols="30" rows="10">{{ old('post_content') }}</textarea>-->
                    <textarea name="description" class="form-control" id="summernote" cols="30" rows="10">{{ old('description') }}</textarea>
                </div>
                
                <div class="form-group">
                    <label for="">{{ PHOTO }} *</label>
                    <div>
                        <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp">
                    </div>
                </div>
               
               
            </div>
            
            <div class="card-body">
                
                <button type="submit" class="btn btn-success">{{ SUBMIT }}</button>
            </div>
        </div>
    </form>
    
    

@endsection
