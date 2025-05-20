// Call the dataTables jQuery plugin
$(document).ready(function() {
  $('#dataTableListing').DataTable({
      "order": [[0, "desc"]],
        processing: true, // Exibe o carregamento 
        serverSide: true, // Habilita o processamento no servidor 
        ajax: {
            url: admin_listing_view, // URL que chama o método index
            type: 'GET', // Método da requisição
            data: function (d) {
                // Envia os parâmetros necessários do DataTables (como busca, paginação)
                d.search = d.search;
                d.length = d.length;
                d.draw = d.draw;
            }
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'listing_featured_photo', name: 'listing_featured_photo' },
            { data: 'listing_name', name: 'listing_name' },
            { data: 'name', name: 'name' },
            { data: 'listing_status', name: 'listing_status' },
            { data: 'is_featured', name: 'is_featured' },
            { data: 'action', name: 'action' }
        ],
    "drawCallback": function () {
        $('input[data-toggle="toggle"]').bootstrapToggle(); // Re-inicializa os toggles
    }
    });
  $('#dataTable').DataTable();
  
  $('#dataTable1').DataTable();
  
  $('#dataTableCustomer').DataTable({
      "destroy": true,
      "order": [[4, "desc"]],
      processing: true,
      serverSide: true,
      ajax: {
         url: admin_customer_view, 
         type: 'GET',
         data: function(d) {
            d.search = d.search;
            d.length = d.length;
            d.draw = d.draw;
         }
      },
      "columns": [
            { data: 'UserID', name: 'UserID' },
            { 
                data: 'photo', 
                name: 'photo',
                orderable: false,
                searchable: false,
                render: function(data) {
                    return `<img src="${data}" alt="${data}" class="w_100">`;
                }
            },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'total_veiculos', name: 'total_veiculos', orderable: true },
            { data: 'status', name: 'status', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
      "drawCallback": function() {
         $('input[data-toggle="toggle"]').bootstrapToggle(); // Re-inicializa os toggles
      },
        "pageLength": 10
   });
});
