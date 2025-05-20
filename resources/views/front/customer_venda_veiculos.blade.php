@extends('front.app_front')

@section('content')

<style>
    table.table tfoot span{padding-top: .45rem;}
    table.table tfoot h6{color: #ffffff;padding-top: .25rem;padding-bottom: .25rem;line-height: 1.1;}
    .card-body{padding: 1.05rem .50rem !important;}
</style>
<div class="page-banner" style="background-image: url('{{ asset('uploads/page_banners/'.$page_other_item->customer_panel_page_banner) }}')">
	<div class="page-banner-bg"></div>
	<h1>Venda de veículos</h1>
	<nav>
		<ol class="breadcrumb justify-content-center">
			<li class="breadcrumb-item"><a href="{{ url('/') }}">{{ HOME }}</a></li>
			<li class="breadcrumb-item active">Venda de veículos</li>
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
    <div class="card shadow mb-4">
        <div class="card-header py-3">
        <div class="row">
        <div class="col-md-8">
            <h6 class="m-0 mt-2 font-weight-bold text-primary">PLANILHA MENSAL PARA REEMBOLSO DOS ACORDOS </h6>
             </div>
             <div class="col-md-4">            
       <div class="btn-group" role="group" aria-label="Basic example">
           <button type="button" class="btn btn-success btn-sm" onclick="ShowHide('form-cadastro');return false;"><i class="fa fa-plus"></i> Add Venda</button>
           <div class="btn-group" role="group">
  <button class="btn btn-danger btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">Importação</button>
  <div class="dropdown-menu">
    <a class="dropdown-item" type="button" href="{{ url('planilha-modelo.xlsx') }}" target="_blank"><i class="fa fa-file-excel"></i> Modelo arquivo importação</a>
    <a class="dropdown-item" type="button" onclick="ShowHide('form-import');return false;"><i class="fa fa-upload"></i> Upload do arquivo</a>    
  </div>
</div>
</div>            
        </div>
        </div>
        </div>
        <div class="card-body" id="form-import" style="display: none;">
             <form action="{{ route('import.vendas') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="file">Selecione o arquivo XLS/XLSX</label>
                        <input type="file" class="form-control" name="file" accept=".xlsx, .xls" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-primary">Importar</button>
                </div>
            </form>
        </div>
        <div class="card-body" id="form-cadastro" style="display: none;">            
            @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif
                       <form action="{{ route('front_customer_cadastro_vendas') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div id="input_id"></div>
        <div class="row">                                   
                                            <input type="hidden" name="user_id" class="user_id" value="{{ $user_id }}">
                                                                         <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="como_nos_conheceu">Como nos conheceu?</label>
                                            <input name="como_nos_conheceu" class="form-control como_nos_conheceu" id="como_nos_conheceu" maxlength="15" value="{{ old('como_nos_conheceu') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="nomeCliente">Cliente</label>
                                            <input type="text" name="nomeCliente" id="nomeCliente" class="form-control nomeCliente" value="{{ old('nomeCliente') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="cpfCliente">CPF Cliente</label>
                                            <input type="tel" name="cpfCliente" id="cpfCliente" class="cpf form-control cpfCliente" value="{{ old('cpfCliente') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="dataPagamentoFinanciamento">Data pagamento financiamento</label>
                                            <input type="date" name="dataPagamentoFinanciamento" id="dataPagamentoFinanciamento" class="form-control dataPagamentoFinanciamento" value="{{ old('dataPagamentoFinanciamento') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="veiculo">Veículo</label>
                                            <input type="text" name="veiculo" id="veiculo" class="form-control veiculo" value="{{ old('veiculo') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="placa">Placa</label>
                                            <input type="text" name="placa" id="placa" class="form-control placa" value="{{ old('placa') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="valorTotalFinanciamento">Valor total financiamento</label>
                                            <input type="tel" name="valorTotalFinanciamento" id="valorTotalFinanciamento" class="valor form-control valorTotalFinanciamento" value="{{ old('valorTotalFinanciamento') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="financeira">Financeira</label>
                                            <select name="financeira" id="financeira" class="form-control financeira">
                                                <option value="" selected disabled>Selecione uma opção</option>
                                                @foreach($bancos as $banco)
                                                <option value="{{ $banco->id }}">{{ $banco->banco }}</option>
                                                @endforeach
                                            </select>                                          
                                        </div>                                        
                                    </div>      
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">&nbsp</label>
                                            <button type="submit" class="btn btn-primary btn-block"><i class="fa fa-save"></i> Salvar</button>
                                        </div>
                                    </div>
                                </div>
           </form>
        </div>            
    </div>    
    <div class="card shadow mb-4">
       <div class="card-header py-3 bg-dark" onclick="ShowHide('form-filtro');return false;">
        <div class="row">
        <div class="col-md-10"><h6 class="m-0 mt-2 font-weight-bold text-light">FILTRO </h6></div>
             <div class="col-md-2">      
             <div class="btn-group" role="group">
                <button class="btn btn-dark btn-sm btn-block" type="button">
                  FILTRAR <i class="fa fa-filter"></i>
                </button>  
              </div>                        
        </div>
        </div>
        </div>       
        <div class="card-body" id="form-filtro" style="{{ $display_style }}">
    <form method="GET" action="{{ route('customer_vendadeveiculos') }}" class="mb-4"> 
        <div class="row">            
            <div class="col-sm-12 col-md-12">
                        <div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">               
                        <div class="dataTables_length" id="dataTable_length">
                            <label>Mostrar 
                                <select name="limite" aria-controls="dataTable" class="custom-select custom-select-sm form-control form-control-sm" onchange="this.form.submit()">
                                   <option value="2" {{ request('limite') == 2 ? ' selected ' : '' }}>2</option>
                                   <option value="3" {{ request('limite') == 3 ? ' selected ' : '' }}>3</option>
                                   <option value="4" {{ request('limite') == 4 ? ' selected ' : '' }}>4</option>
                                   <option value="5" {{ request('limite') == 5 ? ' selected ' : '' }}>5</option>
                                   <option value="10" {{ request('limite') == 10 ? ' selected ' : '' }}>10</option>
                        <option value="25" {{ request('limite') == 25 ? ' selected ' : '' }}>25</option>
                        @for ($i = 50; $i <= 500; $i += 50)
    <option value="{{ $i }}" {{ request('limite') == $i ? ' selected ' : '' }}>{{ $i }}</option>
@endfor
@for ($i = 500; $i <= 5000; $i += 500)
    <option value="{{ $i }}" {{ request('limite') == $i ? ' selected ' : '' }}>{{ $i }}</option>
@endfor

                                </select> 
                                registros
                            </label>
                        </div>
                    </div>             
                </div>            
            <div class="col-md-3">
                <label>Como nos conheceu</label>
                <input type="text" name="como_nos_conheceu" class="form-control" value="{{ request('como_nos_conheceu') }}">
            </div>
            <div class="col-md-3">
                <label>Nome Cliente</label>
                <input type="text" name="nomeCliente" class="form-control" value="{{ request('nomeCliente') }}">
            </div>
            <div class="col-md-3">
                <label>CPF Cliente</label>
                <input type="text" name="cpfCliente" class="form-control cpf" value="{{ request('cpfCliente') }}">
            </div>
            <div class="col-md-3">
                <label>Veículo</label>
                <input type="text" name="veiculo" class="form-control" value="{{ request('veiculo') }}">
            </div>
            <div class="col-md-3">
                <label>Placa</label>
                <input type="text" name="placa" class="form-control" value="{{ request('placa') }}">
            </div>
            <div class="col-md-3">
                <label>Data Pagamento Financiamento</label>
                <input type="date" name="dataPagamentoFinanciamento" class="form-control" value="{{ request('dataPagamentoFinanciamento') }}">
            </div>
            <div class="col-md-3">
                <label>Financeira</label>
                <select name="financeira" class="form-control">
                    <option value="">Selecione</option>
                    @foreach($bancos as $banco)
                        <option value="{{ $banco->id }}" {{ request('financeira') == $banco->id ? ' selected ' : '' }}>
                            {{ $banco->banco }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="">Selecione</option>
                    <option value="aprovado" {{ request('status') == 'aprovado' ? ' selected ' : '' }}>Aprovado</option>
                    <option value="pendente" {{ request('status') == 'pendente' ? ' selected ' : '' }}>Pendente</option>
                    <option value="cancelado" {{ request('status') == 'cancelado' ? ' selected ' : '' }}>Cancelado</option>
                </select>
            </div>
        </div>
        <div class="mt-3">
            <button type="submit" name="q" value="s" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
            <button type="submit" name="export" value="xls" class="btn btn-info"><i class="fa fa-file-excel"></i> Exportar para XLS</button>
            <button type="submit" name="export" value="xlsx" class="btn btn-danger"><i class="fa fa-file-excel"></i> Exportar para XLSX</button>
            <a href="{{ route('customer_vendadeveiculos') }}" class="btn btn-secondary"><i class="fa fa-eraser"></i> Limpar</a>
        </div>
    </form>
        </div>        
        <div class="clear clearfix"></div>        
        <div class="card-header py-3 bg-dark">
        <div class="row">
        <div class="col-md-12"><h6 class="m-0 mt-2 font-weight-bold text-light"><i class="fa fa-list"></i> LISTAR VENDAS </h6></div>           
        </div>
        </div>        
        <div class="clear clearfix"></div>        
        <div class="card-body">            
            <div class="table-responsive">                
            @php
                $estilo_thead = 'color:#ffffff;font-size:10px!important;border:1px solid #dee2e6!important;padding:.25rem!important;vertical-align: middle!important;';
            @endphp
                <table class="table table-bordered table-condensed display nowrap" style="width:100%;">
                    <thead class="thead-dark">
                    <tr>
                        <th style="{{ $estilo_thead }}" title="Como Nos Conheceu?">Como Conheceu?</th>
                        <th style="{{ $estilo_thead }}" title="Nome Do Cliente">Cliente</th>
                        <th style="{{ $estilo_thead }}" title="CPF Do Cliente">CPF</th>
                        <th style="{{ $estilo_thead }}" title="Veículo">Veículo</th>
                        <th style="{{ $estilo_thead }}" title="Placa Do Veículo">Placa</th>
                        <th style="{{ $estilo_thead }}" title="Valor Total Do Financiamento">Valor Total Finan.</th>
                        <th style="{{ $estilo_thead }}" title="Previsão De Recebimento De Plus - Total Líquido">Previ. Receb. +</th>
                        <th style="{{ $estilo_thead }}" title="Data Do Pagamento Do Financiamento">Data Pag. Finan.</th>
                        <th style="{{ $estilo_thead }}" title="Financeira - Banco">Financeira</th>
                        <th style="{{ $estilo_thead }}" title="Status">Status</th>
                    </tr>
                    </thead>
                    <tbody>
                        @php $i=0; $total_liquido=0; $total_liquido_geral=0; $total_liquido_aprovado=0; $total_liquido_pendente=0; @endphp
                        
                        @foreach($vendas as $row)
                        
                            @php
                                $placa = \App\Helpers\Helper::limpa_string($row->placa);
                                $placa_formatada = \App\Helpers\Helper::mask($placa, '###-####');
                            @endphp
                            @php
                                $estilo = '';        
                                $porcentagem = ($row->venda->porcentagem ?? 0) / 100;
                                $desconto = ($row->venda->desconto ?? 0) / 100;            
                                $total_liquido = ($row->valorTotalFinanciamento * $porcentagem) * (1 - $desconto);          

                                if ($row->status === 'aprovado') {
                                    $estilo = 'background-color: #e2f6e2;color:#000;font-size:10px!important;border:1px solid #dee2e6!important;padding:.25rem!important;vertical-align: middle!important;white-space: nowrap!important;';
                                    $total_liquido_aprovado += $total_liquido;            
                                } elseif ($row->status === 'pendente') {
                                    $estilo = 'background-color: #eac5c5;color:#000;font-size:10px!important;border:1px solid #dee2e6!important;padding:.25rem!important;vertical-align: middle!important;white-space: nowrap!important;';
                                    $total_liquido_pendente+=$total_liquido;            
                                }

                                $total_liquido_geral += $total_liquido; 
                            @endphp
                        <tr>
                            <td style="{{ $estilo }}">{{ $row->como_nos_conheceu }}</td>
                            <td style="{{ $estilo }}">{{ $row->nomeCliente }}</td>
                            <td style="{{ $estilo }}">{{ $row->cpfCliente }}</td>
                            <td style="{{ $estilo }}">{{ $row->veiculo }}</td>
                            <td style="{{ $estilo }}">{{ $placa_formatada }}</td>
                            <td style="{{ $estilo }}">R$ {{ number_format($row->valorTotalFinanciamento,2,',','.') }}</td>
                            <td style="{{ $estilo }}">R$ {{ number_format($total_liquido,2,',','.') }}</td>
                            <td style="{{ $estilo }}">{{ \Carbon\Carbon::parse($row->dataPagamentoFinanciamento)->format('d/m/Y') }}</td>
                            <td style="{{ $estilo }}">{{ $row->venda->banco ?? 'N/A' }}</td>
                            <td style="{{ $estilo }}">{{ $row->status }}</td>
                        </tr>                            
                        @endforeach
                    </tbody>                 
                    <tfoot>
                        <tr>
                            <th colspan="7">
                                &nbsp;
                            </th>
                            <th colspan="1">
                                <span class="badge badge-primary"> Total Líquido Geral: <h6>R$ {{ number_format($total_liquido_geral,2,',','.') }}</h6></span>
                            </th>
                            <th colspan="1">
                                <span class="badge badge-danger"> Total Líquido Pendente: <h6>R$ {{ number_format($total_liquido_pendente,2,',','.') }}</h6></span>
                            </th>
                            <th colspan="1">
                                <span class="badge badge-success"> Total Líquido Aprovado: <h6>R$ {{ number_format($total_liquido_aprovado,2,',','.') }}</h6></span>
                            </th>
                        </tr>                        
                    </tfoot>  
                </table>        
        <!-- Paginação -->   
        <div class="pagination">
    {{ $vendas->appends(request()->input())->links() }}
</div>          
            </div>
        </div>
    </div>
    
    <script>
        function ShowHide(id){
            $('#'+id).toggle('slow');
            
            if(id === 'form-cadastro'){
                $('#form-import').hide('slow');
            }else if(id === 'form-import'){
                $('#form-cadastro').hide('slow');
            }
        }
    function preencheForm(id,user_id,como_nos_conheceu,nomeCliente,cpfCliente,dataVenda,valorVendaAvista,dataPagamentoFinanciamento,
    veiculo,placa,valorTotalVeiculo,valorTotalFinanciamento,financeira,status){
       if(id){
           $('#input_id').html('<input type="hidden" name="id" value="'+id+'">');
           $('.user_id').val(user_id);
           $('.como_nos_conheceu').val(como_nos_conheceu);
           $('.nomeCliente').val(nomeCliente);
           $('.cpfCliente').val(cpfCliente);
           $('.dataVenda').val(dataVenda);
           $('.valorVendaAvista').val(valorVendaAvista);
           $('.dataPagamentoFinanciamento').val(dataPagamentoFinanciamento);
           $('.veiculo').val(veiculo);
           $('.placa').val(placa);
           $('.valorTotalVeiculo').val(valorTotalVeiculo);
           $('.valorTotalFinanciamento').val(valorTotalFinanciamento);
           $('.financeira').val(financeira);
           $('.status').val(status);
       }
    }   
    </script>
			</div>
		</div>
	</div>
</div>

@endsection
