@extends('admin.app_admin')
@section('admin_content')

    <div class="row">
        <div class="col-xl-12 col-md-12 mb-2">
            <h1 class="h3 mb-3 text-gray-800">Leads</h1>
        </div>
    </div>

<div class="row">
    <div class="col-md-12">
        <h2>Kanban de Leads</h2>
        <div class="kanban-board">
            <div class="kanban-column" id="new">
                <h3>Novos</h3>
                <div class="kanban-items">
                    <!-- Itens vão aqui -->
                </div>
            </div>
            <div class="kanban-column" id="in-progress">
                <h3>Em Progresso</h3>
                <div class="kanban-items">
                    <!-- Itens vão aqui -->
                </div>
            </div>
            <div class="kanban-column" id="completed">
                <h3>Concluídos</h3>
                <div class="kanban-items">
                    <!-- Itens vão aqui -->
                </div>
            </div>
        </div>  
    </div>
</div>

@endsection
