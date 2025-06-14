@extends('admin.app_admin')
@section('admin_content')
    <h1 class="h3 mb-3 text-gray-800">{{ LISTING_BRAND }}</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <!--<h6 class="m-0 mt-2 font-weight-bold text-primary"></h6>-->
            <div class="float-left d-inline">
                <form action="{{ route('import_marcas') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <table class="border-0">
                        <tr>
                            <td><input type="file" name="marcas" class="form-control" /></td>
                            <td><button type="submit" class="btn btn-success">Importar CSV</button></td>
                        </tr>
                    </table>                   
                </form>
            </div>
            <div class="float-right d-inline">
                <a href="{{ route('admin_listing_brand_create') }}" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> {{ ADD_NEW }}</a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>{{ SERIAL }}</th>
                        <th>{{ PHOTO }}</th>
                        <th>{{ NAME }}</th>
                        <th>{{ SLUG }}</th>
                        <th>{{ ACTION }}</th>
                    </tr>
                    </thead>
                    <tbody>
                        @php $i=0; @endphp
                        @foreach($listing_brand as $row)
                        
                        @php
    $brandListingAlisson = App\Models\Listing::where('listing_brand_id', $row->id)
        ->where('listing_status', 'Active')
        ->first();
@endphp


                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                
    <img src="{{ asset('uploads/listing_brand_photos/'.$row->listing_brand_photo_png) }}" alt="" class="w_100">

                            </td>
                            <td>{{ $row->listing_brand_name }}</td>
                            <td>{{ $row->listing_brand_slug }}</td>
                            <td>
                                <a href="{{ route('admin_listing_brand_edit',$row->id) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                <a href="{{ route('admin_listing_brand_delete',$row->id) }}" class="btn btn-danger btn-sm" onClick="return confirm('{{ ARE_YOU_SURE }}');"><i class="fas fa-trash-alt"></i></a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
