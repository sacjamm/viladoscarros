@extends('admin.app_admin')
@section('admin_content')
    <h1 class="h3 mb-3 text-gray-800">Usuários do banco</h1>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="" method="post">
                @csrf
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">{{ NAME }} *</label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" autofocus>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">{{ EMAIL_ADDRESS}} *</label>
                                    <input type="text" name="email" class="form-control" value="{{ old('email') }}">
                                </div>
                            </div>
                        </div>

                        
                        <div class="row">
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">{{ PASSWORD }}</label>
                                    <input type="password" name="password" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                            <div class="form-group">
                            <label for="">{{ RETYPE_PASSWORD }} *</label>
                            <input type="password" name="re_password" class="form-control">
                        </div>
                        </div>
                            <input type="hidden" name="tipo_user" value="banco"/>
                        <button type="submit" class="btn btn-success">{{ SUBMIT }}</button>
                    </div>
                </div>
            </form>        
        </div>
    </div>
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>{{ SERIAL }}</th>
                        <th>{{ PHOTO }}</th>
                        <th>{{ NAME }}</th>
                        <th>{{ EMAIL }}</th>
                        <th>{{ STATUS }}</th>
                        <th>{{ ACTION }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($usuarios as $row)
                    
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                @if($row->photo == '')
                                    <img src="{{ asset('uploads/user_photos/default_photo.jpg') }}" class="w_100">
                                @else
                                    <img src="{{ asset('uploads/user_photos/'.$row->photo) }}" class="w_100">
                                @endif
                            </td>
                            <td>{{ $row->name }}</td>
                            <td>{{ $row->email }}</td>
                            <td>
                               
                                @if ($row->status == 'Active')
                                <a href="" onclick="adminStatus({{ $row->id }})"><input type="checkbox" checked data-toggle="toggle" data-on="Active" data-off="Pending" data-onstyle="success" data-offstyle="danger"></a>
                                @else
                                    <a href="" onclick="adminStatus({{ $row->id }})"><input type="checkbox" data-toggle="toggle" data-on="Active" data-off="Pending" data-onstyle="success" data-offstyle="danger"></a>
                                @endif
                            </td>
                            <td>                  
                                 </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function adminStatus(id){
            $.ajax({
                type:"get",
                url:"{{url('/admin/admin-status/')}}"+"/"+id,
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
