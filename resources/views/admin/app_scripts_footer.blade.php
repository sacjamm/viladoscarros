<script src="{{ asset('backend/js/sb-admin-2.js') }}?id={{ time() }}"></script>
<script src="{{ asset('backend/js/custom.js') }}?id={{ time() }}"></script>
<script src="{{ asset('js/jquery.maskMoney.min.js') }}"></script>
<script src="{{ asset('js/jquery.maskedinput.min.js') }}"></script>
<script>
$(document).ready(function(){
    $(".cpf").mask("999.999.999-99");
    $(".valor").maskMoney({thousands:'', decimal:'.', allowZero:true, suffix: ''});
});
</script>
<script>
    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": true,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "3000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }
</script>

@if ($errors->any())
    @foreach ($errors->all() as $error)
        <script>
            toastr.error('{{ $error }}');
        </script>
    @endforeach
@endif

@if(session()->get('error'))
    <script>
        toastr.error('{{ session()->get('error') }}');
    </script>
@endif

@if(session()->get('success'))
    <script>
        toastr.success('{{ session()->get('success') }}');
    </script>
@endif