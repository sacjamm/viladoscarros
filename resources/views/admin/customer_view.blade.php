@extends('admin.app_admin')
@section('admin_content')
    <h1 class="h3 mb-3 text-gray-800">{{ CUSTOMERS }}</h1>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTableCustomer" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>{{ SERIAL }}</th>
                        <th>{{ PHOTO }}</th>
                        <th>{{ NAME }}</th>
                        <th>{{ EMAIL }}</th>
                        <th>Total de veículos</th>
                        <th>{{ STATUS }}</th>
                        <th>{{ ACTION }}</th>
                    </tr>
                    </thead>
                    <tbody>
                        @if($customers)
                    @foreach($customers as $row)
                    
                        <tr>
                            <td>{{ $row->UserID }}</td>
                            <td>
                                @if(!empty($row->photo) && $row->photo != '')
                                    <img src="{{ asset('uploads/user_photos/'.$row->photo) }}" class="w_100">
                                @else
                                    <img src="{{ asset('uploads/user_photos/default_photo.jpg') }}" class="w_100">
                                @endif
                            </td>
                            <td>{{ $row->name }}</td>
                            <td>{{ $row->email }}</td>
                            <td>{{ $row->total_veiculos }}</td>
                            <td>
                               
                                @if ($row->status == 'Active')
                                <a href="" onclick="customerStatus({{ $row->UserID }})"><input type="checkbox" checked data-toggle="toggle" data-on="Active" data-off="Pending" data-onstyle="success" data-offstyle="danger"></a>
                                @else
                                    <a href="" onclick="customerStatus({{ $row->UserID }})"><input type="checkbox" data-toggle="toggle" data-on="Active" data-off="Pending" data-onstyle="success" data-offstyle="danger"></a>
                                @endif
                            </td>
                            <td>    
                                <div class="btn-group">
  <button class="btn btn-danger btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
    Ações
  </button>
  <div class="dropdown-menu">
    
    <a href="{{ route('admin_customer_acessar',['id' => $row->UserID]) }}" target="_blank" class="dropdown-item text-warning">
        <i class="fa fa-external-link-alt"></i> Área do lojista
    </a>
                                
                                <a href="{{ route('admin_customer_editar',['id' => $row->UserID]) }}" class="dropdown-item text-info">
                                    <i class="fa fa-edit"></i> Editar
                                </a>
                                <a href="{{ route('admin_customer_detail',$row->UserID) }}" class="dropdown-item text-primary">
                                    <i class="fa fa-info-circle"></i> Detalhes
                                </a>
                                <a href="{{ route('admin_customer_delete',$row->UserID) }}" class="dropdown-item text-danger" onClick="return confirm('{{ ARE_YOU_SURE }}');">
                                    <i class="fa fa-trash"></i> {{ DELETE }}
                                </a>
  </div>
</div>
                                
                            </td>
                        </tr>
                    @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function customerStatus(id){
            $.ajax({
                type:"get",
                url:"{{url('/admin/customer-status/')}}"+"/"+id,
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
