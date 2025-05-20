<link rel="stylesheet" href="{{ asset('backend/vendor/fontawesome-free/css/all.min.css?id=1') }}">
<link rel="stylesheet" href="{{ asset('backend/css/fontawesome-iconpicker.min.css?id=1') }}">
<link rel="stylesheet" href="{{ asset('backend/css/sb-admin-2.min.css') }}?id={{ time() }}">
<link rel="stylesheet" href="{{ asset('backend/vendor/datatables/dataTables.bootstrap4.min.css?id=1') }}">
<link rel="stylesheet" href="{{ asset('backend/css/toastr.min.css?id=1') }}">
<link rel="stylesheet" href="{{ asset('backend/css/jquery-ui.css?id=1') }}">
<link rel="stylesheet" href="{{ asset('backend/css/jquery.timepicker.css?id=1') }}">
<link rel="stylesheet" href="{{ asset('backend/css/select2.min.css?id=1') }}">
<link rel="stylesheet" href="{{ asset('backend/css/bootstrap4-toggle.min.css?id=1') }}">
<link rel="stylesheet" href="{{ asset('backend/css/spacing.css?id=1') }}">
<link rel="stylesheet" href="{{ asset('backend/css/style.css') }}?id={{ time() }}">
@php
$g_settings = \App\Models\GeneralSetting::where('id',1)->first();
@endphp
@if($g_settings->layout_direction == 'rtl')
    <link rel="stylesheet" href="{{ asset('backend/css/rtl.css?id=1') }}">
@endif