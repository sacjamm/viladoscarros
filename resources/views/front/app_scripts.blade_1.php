<!-- All Javascripts -->
<script src="{{ asset('frontend/js/jquery-3.6.0.min.js') }}" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.bundle.min.js" integrity="sha512-igl8WEUuas9k5dtnhKqyyld6TzzRjvMqLC79jkgT3z02FvJyHAuUtyemm/P/jYSne1xwFI06ezQxEwweaiV7VA==" crossorigin="anonymous" referrerpolicy="no-referrer" defer></script>
<!--<script src="{{ asset('frontend/js/bootstrap.min.js') }}"></script>-->

<script src="{{ asset('frontend/js/jquery-ui.js') }}"></script>
@if($route != null)
@endif
<script src="{{ asset('frontend/js/jquery.magnific-popup.min.js') }}"></script>

<script src="{{ asset('frontend/js/owl.carousel.min.js') }}"></script>

<script src="{{ asset('frontend/js/wow.min.js') }}"></script>

<script src="{{ asset('frontend/js/jquery.meanmenu.js') }}"></script>

@if($route === 'customer_vendadeveiculos')
<script src="{{ asset('frontend/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('frontend/js/dataTables.bootstrap4.min.js') }}"></script>
<!-- Extens達o Buttons -->
<script src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.print.min.js"></script>
@endif

<script src="{{ asset('frontend/js/datatables-demo.js') }}"></script>

@if($route != null)
<script src="{{ asset('frontend/js/select2.full.js') }}"></script>
<script src="{{ asset('frontend/js/jquery.timepicker.js') }}"></script>
<script src="{{ asset('frontend/js/fontawesome-iconpicker.js') }}"></script>
<script src="{{ asset('frontend/js/toastr.min.js') }}"></script> 
<script src="{{ asset('frontend/js/sweetalert2.min.js') }}"></script>  
@endif

<script src="{{ asset('frontend/js/sticky_sidebar.js') }}"></script>

@if($route === 'customer_listing_add')
<script src="{{ asset('tinymce/tinymce.min.js') }}"></script>
@endif

@if($route === 'front_contact' || $route === 'customer_login' || $route === 'customer_registration')
<script src='https://www.google.com/recaptcha/api.js'></script>
@endif


