<!-- All CSS -->
<link rel="preload" as="style" href="{{ asset('frontend/css/bootstrap.min.css') }}" onload="this.onload=null;this.rel='stylesheet'">

@if($route != null)
<link rel="stylesheet" href="{{ asset('frontend/css/jquery-ui.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/css/animate.min.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/css/magnific-popup.css') }}">
@endif

<link rel="stylesheet" href="{{ asset('frontend/css/owl.carousel.min.css') }}">

@if($route === 'customer_vendadeveiculos')
<link rel="stylesheet" href="{{ asset('frontend/css/dataTables.bootstrap4.min.css') }}">
<!-- CSS do DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.2/css/buttons.dataTables.min.css">
@endif 

@if($route != null)
<link rel="stylesheet" href="{{ asset('frontend/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/css/select2-bootstrap.min.css') }}">
@endif 
<link rel="stylesheet" href="{{ asset('frontend/css/meanmenu.css') }}"> 

@if($route != null)
<link rel="stylesheet" href="{{ asset('frontend/css/spacing.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/css/jquery.timepicker.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/css/toastr.min.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/css/sweetalert2.min.css') }}">
@endif 

<link rel="stylesheet" href="{{ asset('frontend/css/style.min.css') }}">

@php
$g_settings = \App\Models\GeneralSetting::where('id',1)->first();
@endphp
@if($g_settings->layout_direction == 'rtl')
    <link rel="stylesheet" href="{{ asset('frontend/css/rtl.css') }}">
@endif
