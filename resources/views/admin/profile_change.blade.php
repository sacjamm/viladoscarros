@extends('admin.app_admin')
@section('admin_content')
    <h1 class="h3 mb-3 text-gray-800">{{ EDIT_PROFILE }}</h1>

    <div class="row">
        <div class="col-md-12">
            <form action="{{ route('admin_profile_change_update') }}" method="post">
                @csrf
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">{{ NAME }} *</label>
                                    <input type="text" name="name" class="form-control" value="{{ $admin_data->name }}" autofocus>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">{{ EMAIL_ADDRESS}} *</label>
                                    <input type="text" name="email" class="form-control" value="{{ $admin_data->email }}">
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success">{{ UPDATE }}</button>
                    </div>
                </div>
            </form>        
        </div>
    </div>

@endsection
