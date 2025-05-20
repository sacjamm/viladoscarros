@extends('admin.app_admin')
@section('admin_content')
    <h1 class="h3 mb-3 text-gray-800">Bancos</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 mt-2 font-weight-bold text-primary">Adicionar banco</h6>
            
        </div>
        <div class="card-body">
           <form action="{{ route('admin_customer_config_vendas') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div id="input_id"></div>
        <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="banco">Nome Banco</label>
                                            <input type="text" name="banco" id="banco" class="form-control banco" value="{{ old('banco') }}" >
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="totalFinanciado">Total Financiado</label>
                                            <input type="text" name="totalFinanciado" id="totalFinanciado" class="valor form-control totalFinanciado" value="{{ old('totalFinanciado') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="porcentagem">Porcentagem</label>
                                            <input type="tel" name="porcentagem" id="porcentagem" class="form-control porcentagem" value="{{ old('porcentagem') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="totalBruto">Total Bruto</label>
                                            <input type="text" name="totalBruto" id="totalBruto" class="valor form-control totalBruto" value="{{ old('totalBruto') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="totalLiquido">Total Liquido</label>
                                            <input type="text" name="totalLiquido" id="totalLiquido" class="valor form-control totalLiquido" value="{{ old('totalLiquido') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="desconto">Desconto</label>
                                            <input type="tel" name="desconto" id="desconto" class="form-control desconto" value="{{ old('desconto') }}">
                                        </div>
                                    </div>
        </div><div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary btn-block"><i class="fa fa-save"></i> Salvar</button>
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
                        <th>ID</th>
                        <th>Banco</th>
                        <th>Total financiado</th>
                        <th>Porcentagem</th>
                        <th>Total bruto</th>
                        <th>Total liquido</th>
                        <th>Desconto</th>
                        <th>{{ ACTION }}</th>
                    </tr>
                    </thead>
                    <tbody>
                        @php $i=0; @endphp
                        @foreach($bancos as $row)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $row->banco }}</td>
                            <td>{{ number_format($row->totalFinanciado,2,',','.') }}</td>
                            <td>{{ $row->porcentagem }}</td>
                            <td>{{ number_format($row->totalBruto,2,',','.') }}</td>
                            <td>{{ number_format($row->totalLiquido,2,',','.') }}</td>
                            <td>{{ $row->desconto }}</td>
                            <td>
                                <a href="javascript:;" onclick="preencheForm(
                                            {{ $row->id }},
                                            '{{ $row->banco }}',                                            
                                            '{{ $row->totalFinanciado }}',                                            
                                            {{ $row->porcentagem }},                                            
                                            '{{ $row->totalBruto }}',                                            
                                            '{{ $row->totalLiquido }}',                                            
                                            {{ $row->desconto }},                                            
                                            );return false;" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                <a href="{{ route('admin_configvendas_delete',$row->id) }}" class="btn btn-danger btn-sm" onClick="return confirm('{{ ARE_YOU_SURE }}');"><i class="fas fa-trash-alt"></i></a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <script>
    function preencheForm(id,banco,totalFinanciado,porcentagem,totalBruto,totalLiquido,desconto){
       if(id){
           $('#input_id').html('<input type="hidden" name="id" value="'+id+'">');
           $('.banco').val(banco);
           $('.totalFinanciado').val(totalFinanciado);
           $('.porcentagem').val(porcentagem);
           $('.totalBruto').val(totalBruto);
           $('.totalLiquido').val(totalLiquido);
           $('.desconto').val(desconto);
       }
    }
   
    </script>
@endsection
