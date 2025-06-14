@extends('admin.app_admin')
@section('admin_content')
<h1 class="h3 mb-3 text-gray-800">Administradores</h1>

<div class="card shadow mb-4">
    <div class="card-body">
        <form action="{{ route('admin_profile_create_create') }}" method="post">
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
                        <div class="col-md-12">
                            <input type="hidden" name="id" id="adminId">
                            <button type="submit" class="btn btn-success">{{ SUBMIT }}</button>
                    </div>
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
                    @foreach($admin_data as $row)
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
                            <a href="#" onclick="adminStatus({{ $row->id }})"><input type="checkbox" checked data-toggle="toggle" data-on="Active" data-off="Pending" data-onstyle="success" data-offstyle="danger"></a>
                            @else
                            <a href="#" onclick="adminStatus({{ $row->id }})"><input type="checkbox" data-toggle="toggle" data-on="Active" data-off="Pending" data-onstyle="success" data-offstyle="danger"></a>
                            @endif
                        </td>
                        <td>     
                            <a href="#" class="text-primary" onclick="editar({{ $row->id }})"><i class="fa fa-edit"></i> Editar</a>
                            <a href="#" class="text-danger" onclick="excluir({{ $row->id }})"><i class="fa fa-trash"></i> Excluir</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal de Atualização de Status -->
<div class="modal fade" id="modalStatus" tabindex="-1" role="dialog" aria-labelledby="modalStatusLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="formUpdateStatus">
            @csrf
            <input type="hidden" name="admin_id" id="statusAdminId">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Alterar Status do Admin</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label>Status:</label>
                    <select class="form-control" name="status" id="newStatus">
                        <option value="Active">Active</option>
                        <option value="Pending">Pending</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Salvar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function excluir(id){
        Swal.fire({
            title: 'Tem certeza?',
            text: 'Esta ação não poderá ser desfeita!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sim, deletar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/admin/admin-excluir/' + id,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function (resp) {
                        toastr.success(resp.message);
                        location.reload();
                    },
                    error: function (xhr) {
                        toastr.error(xhr.responseJSON?.message ?? 'Erro ao deletar');
                    }
                });
            }
        });
    }
    function editar(id){
        $.ajax({
            type:"get",
            url:"{{url('/admin/admin-editar/')}}" + "/" + id,
            success: function(data){
                $('input[name="name"]').val(data.name);
                $('input[name="email"]').val(data.email);
                $('#adminId').val(data.id);
                toastr.success('Dados carregados para edição');
            },
            error: function(){
                toastr.error('Erro ao carregar dados do administrador');
            }
        })
    }
    function adminStatus(id){
        $('#statusAdminId').val(id);
        $('#modalStatus').modal('show');
    }

    $('#formUpdateStatus').submit(function(e){
        e.preventDefault();
        let id = $('#statusAdminId').val();
        let status = $('#newStatus').val();
        $.ajax({
            url: '/admin/admin-status-update/' + id,
            type: 'POST',
            data: {
            _token: '{{ csrf_token() }}',
                status: status
            },
            success: function(response){
                $('#modalStatus').modal('hide');
                toastr.success('Status atualizado com sucesso');
                location.reload();
            },
            error: function(xhr){
                toastr.error('Erro ao atualizar status');
            }
        });
    });
</script>
@endsection
