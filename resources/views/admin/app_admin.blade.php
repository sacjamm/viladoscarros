@php
$user = Auth::user();
$g_setting = \App\Models\GeneralSetting::where('id',1)->first(); 
@endphp

@php
            $route = Route::currentRouteName();
        @endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="icon" type="image/png" href="{{ asset('uploads/site_photos/'.$g_setting->favicon) }}">

    <title>{{ ADMIN_PANEL }}</title>

    @include('admin.app_styles')

    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <script>        
    const admin_listing_view = '{{ route("admin_listing_view") }}';    
    const admin_customer_view = '{{ route("admin_customer_view") }}';    
    </script>
    
    @include('admin.app_scripts')

    <!-- Include Summernote CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote.min.css" rel="stylesheet">
</head>

<body id="page-top">

<!-- Page Wrapper -->
<div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
        <!-- Sidebar - Brand -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('admin_dashboard') }}">
            <div class="sidebar-brand-text mx-3 ttn">
                <div class="right">
                    {{ env('APP_NAME') }}
                </div>
            </div>
        </a>

        <!-- Divider -->
        <hr class="sidebar-divider my-0">

        <!-- Dashboard -->
        <li class="nav-item {{ $route == 'admin_dashboard' ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin_dashboard') }}">
                <i class="fas fa-fw fa-home"></i>
                <span>{{ DASHBOARD }}</span>
            </a>
        </li>

        
        <li class="nav-item {{ $route == 'admin_setting_general'||$route =='admin_payment'||$route =='admin_social_media_view'||$route =='admin_social_media_create'||$route =='admin_social_media_store'||$route =='admin_social_media_edit' ? 'active' : '' }}">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseSetting" aria-expanded="true" aria-controls="collapseSetting">
                <i class="fas fa-folder"></i>
                <span>{{ SETTINGS }}</span>
            </a>
            <div id="collapseSetting" class="collapse {{ $route == 'admin_setting_general'||$route == 'admin_payment'||$route == 'admin_social_media_view'||$route =='admin_social_media_create'||$route =='admin_social_media_store'||$route =='admin_social_media_edit'||$route == 'admin_currency_view'||$route == 'admin_currency_create'||$route == 'admin_currency_edit' ? 'show' : '' }}" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item" href="{{ route('admin_setting_general') }}">{{ GENERAL_SETTING }}</a>
                    <a class="collapse-item" href="{{ route('admin_payment') }}">{{ PAYMENT_SETTING }}</a>
                    <a class="collapse-item" href="{{ route('admin_currency_view') }}">{{ CURRENCY }}</a>
                    <a class="collapse-item" href="{{ route('admin_social_media_view') }}">{{ SOCIAL_MEDIA }}</a>
                </div>
            </div>
        </li>

        <!-- Language Settings -->
        <li class="nav-item {{ $route =='admin_language_menu_text'||$route =='admin_language_website_text'||$route =='admin_language_notification_text'||$route =='admin_language_admin_panel_text' ? 'active' : '' }}">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseLanguage" aria-expanded="true" aria-controls="collapseLanguage">
                <i class="fas fa-folder"></i>
                <span>{{ LANGUAGE_SETTINGS }}</span>
            </a>
            <div id="collapseLanguage" class="collapse {{ $route =='admin_language_menu_text'||$route =='admin_language_website_text'||$route =='admin_language_notification_text'||$route =='admin_language_admin_panel_text' ? 'show' : '' }}" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item " href="{{ route('admin_language_menu_text') }}">{{ MENU_TEXT }}</a>
                    <a class="collapse-item " href="{{ route('admin_language_website_text') }}">{{ WEBSITE_TEXT }}</a>
                    <a class="collapse-item " href="{{ route('admin_language_notification_text') }}">{{ NOTIFICATION_TEXT }}</a>
                    <a class="collapse-item " href="{{ route('admin_language_admin_panel_text') }}">{{ ADMIN_PANEL_TEXT }}</a>
                </div>
            </div>
        </li>
<li class="nav-item {{ $route == 'admin_banner_view'||$route == 'admin_banner_create'||$route == 'admin_banner_edit' ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin_banner_view') }}">
                <i class="far fa-caret-square-right"></i>
                <span>Seção de Banners</span>
            </a>
        </li>

        <!-- Page Settings -->
        <li class="nav-item {{ $route == 'admin_page_home_edit'||$route == 'admin_page_about_edit'||$route == 'admin_page_blog_edit'||$route == 'admin_page_faq_edit'||$route == 'admin_page_contact_edit'||$route == 'admin_page_term_edit'||$route == 'admin_page_privacy_edit'||$route == 'admin_page_other_edit'||$route == 'admin_page_pricing_edit'||$route == 'admin_page_listing_brand_edit'||$route == 'admin_page_listing_location_edit'||$route == 'admin_page_listing_edit' ? 'active' : '' }}">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePageSettings" aria-expanded="true" aria-controls="collapsePageSettings">
                <i class="fas fa-folder"></i>
                <span>{{ PAGE_SETTINGS }}</span>
            </a>
            <div id="collapsePageSettings" class="collapse {{ $route == 'admin_page_home_edit'||$route == 'admin_page_about_edit'||$route == 'admin_page_blog_edit'||$route == 'admin_page_faq_edit'||$route == 'admin_page_contact_edit'||$route == 'admin_page_term_edit'||$route == 'admin_page_privacy_edit'||$route == 'admin_page_other_edit'||$route == 'admin_page_pricing_edit'||$route == 'admin_page_listing_brand_edit'||$route == 'admin_page_listing_location_edit'||$route == 'admin_page_listing_edit' ? 'show' : '' }}" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item" href="{{ route('admin_page_home_edit') }}">{{ HOME }}</a>
                    <a class="collapse-item" href="{{ route('admin_page_about_edit') }}">{{ ABOUT }}</a>
                    <a class="collapse-item" href="{{ route('admin_page_blog_edit') }}">{{ BLOG }}</a>
                    <a class="collapse-item" href="{{ route('admin_page_faq_edit') }}">{{ FAQ }}</a>
                    <a class="collapse-item" href="{{ route('admin_page_contact_edit') }}">{{ CONTACT }}</a>
                    <a class="collapse-item" href="{{ route('admin_page_pricing_edit') }}">{{ PRICING }}</a>
                    <a class="collapse-item" href="{{ route('admin_page_listing_brand_edit') }}">{{ LISTING_BRAND }}</a>
                    <a class="collapse-item" href="{{ route('admin_page_listing_location_edit') }}">{{ LISTING_LOCATION }}</a>
                    <a class="collapse-item" href="{{ route('admin_page_listing_edit') }}">{{ LISTING }}</a>
                    <a class="collapse-item" href="{{ route('admin_page_term_edit') }}">{{ TERMS_AND_CONDITIONS }}</a>
                    <a class="collapse-item" href="{{ route('admin_page_privacy_edit') }}">{{ PRIVACY_POLICY }}</a>
                    <a class="collapse-item" href="{{ route('admin_page_vender_edit') }}">Quero Vender</a>
                    <a class="collapse-item" href="{{ route('admin_page_other_edit') }}">{{ OTHER }}</a>
                </div>
            </div>
        </li>


        <!-- Blog Settings -->
        <li class="nav-item {{ $route == 'admin_category_view'||$route == 'admin_category_create'||$route == 'admin_category_edit'||$route =='admin_blog_view'||$route =='admin_blog_create'||$route =='admin_blog_edit'||$route =='admin_comment_approved'||$route =='admin_comment_pending' ? 'active' : '' }}">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseBlog" aria-expanded="true" aria-controls="collapseBlog">
                <i class="fas fa-folder"></i>
                <span>{{ BLOG_SECTION }}</span>
            </a>
            <div id="collapseBlog" class="collapse {{ $route == 'admin_category_view'||$route == 'admin_category_create'||$route == 'admin_category_edit'||$route =='admin_blog_view'||$route =='admin_blog_create'||$route =='admin_blog_edit'||$route =='admin_comment_approved'||$route =='admin_comment_pending' ? 'show' : '' }}" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">

                    <a class="collapse-item" href="{{ route('admin_category_view') }}">{{ CATEGORIES }}</a>
                    <a class="collapse-item" href="{{ route('admin_blog_view') }}">{{ BLOGS }}</a>
                    <a class="collapse-item" href="{{ route('admin_comment_approved') }}">{{ APPROVED_COMMENTS }}</a>
                    <a class="collapse-item" href="{{ route('admin_comment_pending') }}">{{ PENDING_COMMENTS }}</a>

                </div>
            </div>
        </li>

        <!-- Website Settings -->
        <li class="nav-item {{ $route == 'admin_faq_view'||$route == 'admin_faq_create'||$route == 'admin_faq_edit'||$route == 'admin_testimonial_view'||$route == 'admin_testimonial_create'||$route == 'admin_testimonial_edit' ? 'active' : '' }}">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseWebsite" aria-expanded="true" aria-controls="collapseWebsite">
                <i class="fas fa-folder"></i>
                <span>{{ WEBSITE_SECTION }}</span>
            </a>
            <div id="collapseWebsite" class="collapse {{ $route == 'admin_faq_view'||$route == 'admin_faq_create'||$route == 'admin_faq_edit'||$route == 'admin_testimonial_view'||$route == 'admin_testimonial_create'||$route == 'admin_testimonial_edit' ? 'show' : '' }}" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item" href="{{ route('admin_faq_view') }}">{{ FAQ }}</a>
                    <a class="collapse-item" href="{{ route('admin_testimonial_view') }}">{{ TESTIMONIAL }}</a>
                </div>
            </div>
        </li>


        <!-- Listing Settings -->
        <li class="nav-item {{ $route == 'admin_amenity_view'||$route == 'admin_amenity_create'||$route == 'admin_amenity_edit'||$route == 'admin_listing_brand_view'||$route == 'admin_listing_brand_create'||$route == 'admin_listing_brand_edit'||$route == 'admin_listing_location_view'||$route == 'admin_listing_location_create'||$route == 'admin_listing_location_edit'||$route == 'admin_listing_view'||$route == 'admin_listing_create'||$route == 'admin_listing_edit' ? 'active' : '' }}">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseListing" aria-expanded="true" aria-controls="collapseListing">
                <i class="fas fa-folder"></i>
                <span>{{ LISTING_SECTION }}</span>
            </a>
            <div id="collapseListing" class="collapse {{ $route == 'admin_amenity_view'||$route == 'admin_amenity_create'||$route == 'admin_amenity_edit'||$route == 'admin_listing_brand_view'||$route == 'admin_listing_brand_create'||$route == 'admin_listing_brand_edit'||$route == 'admin_listing_location_view'||$route == 'admin_listing_location_create'||$route == 'admin_listing_location_edit'||$route == 'admin_listing_view'||$route == 'admin_listing_create'||$route == 'admin_listing_edit' ? 'show' : '' }}" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item" href="{{ route('admin_listing_brand_view') }}">{{ LISTING_BRAND }}</a>
                    <a class="collapse-item" href="{{ route('admin_listing_location_view') }}">{{ LISTING_LOCATION }}</a>
                    <a class="collapse-item" href="{{ route('admin_amenity_view') }}">{{ LISTING_AMENITY }}</a>
                    <a class="collapse-item" href="{{ route('admin_listing_view') }}">{{ LISTING }}</a>
                </div>
            </div>
        </li>


        <!-- Review Section -->
        <li class="nav-item {{ $route == 'admin_view_admin_review'||$route == 'admin_view_customer_review' ? 'active' : '' }}">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseReview" aria-expanded="true" aria-controls="collapseReview">
                <i class="fas fa-folder"></i>
                <span>{{ REVIEW_SECTION }}</span>
            </a>
            <div id="collapseReview" class="collapse {{ $route == 'admin_view_admin_review'||$route == 'admin_view_customer_review' ? 'show' : '' }}" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">

                    <a class="collapse-item" href="{{ route('admin_view_admin_review') }}">{{ ADMIN_REVIEW }}</a>
                    <a class="collapse-item" href="{{ route('admin_view_customer_review') }}">{{ CUSTOMER_REVIEW }}</a>

                </div>
            </div>
        </li>


        <!-- Package Section -->
        <li class="nav-item {{ $route == 'admin_package_view'||$route == 'admin_package_create'||$route == 'admin_package_edit' ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin_package_view') }}">
                <i class="far fa-caret-square-right"></i>
                <span>{{ PACKAGE_SECTION }}</span>
            </a>
        </li>


        <!-- Dynamic Pages -->
        <li class="nav-item {{ $route == 'admin_dynamic_page_view'||$route == 'admin_dynamic_page_create'||$route == 'admin_dynamic_page_edit' ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin_dynamic_page_view') }}">
                <i class="far fa-caret-square-right"></i>
                <span>{{ DYNAMIC_PAGES }}</span>
            </a>
        </li>


        <!-- Purchase History -->
        <li class="nav-item {{ $route == 'admin_purchase_history_view'||$route == 'admin_purchase_history_detail'||$route == 'admin_purchase_history_invoice' ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin_purchase_history_view') }}">
                <i class="far fa-caret-square-right"></i>
                <span>{{ PURCHASE_HISTORY }}</span>
            </a>
        </li>


        <!-- Customer -->
<!--        <li class="nav-item {{ $route == 'admin_customer_view' ? 'active' : '' }}">
            <a class="nav-link" href="">
                <i class="far fa-caret-square-right"></i>
                <span>Clientes</span>
            </a>
        </li>-->
        
        <li class="nav-item {{ $route == 'admin_administrador_view' ? 'active' : '' }}">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseAdmin" aria-expanded="true" aria-controls="collapseAdmin">
                <i class="far fa-caret-square-right"></i>
                <span>Administradores</span>
            </a>
            <div id="collapseAdmin" class="collapse {{ $route == 'admin_administrador_view' ? 'show' : '' }}" aria-labelledby="headingAdmin" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item" href="{{ route('admin_administrador_view') }}">Listar administradores</a>
                </div>
            </div>
        </li>
        <li class="nav-item {{ $route == 'admin_customer_view' ? 'active' : '' }}">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseClientes" aria-expanded="true" aria-controls="collapseClientes">
                <i class="far fa-caret-square-right"></i>
                <span>Lojistas</span>
            </a>
            <div id="collapseClientes" class="collapse {{ $route == 'admin_customer_view' ? 'show' : '' }}" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item" href="{{ route('admin_customer_view') }}">Listar lojistas</a>
                    <a class="collapse-item" href="{{ route('admin_config_vendas') }}">Configurações de vendas</a>
                    <a class="collapse-item" href="{{ route('admin_cadastro_vendas') }}">Cadastro de vendas</a>
                </div>
            </div>
        </li>


        <!-- Email Template -->
        <li class="nav-item {{ $route == 'admin_email_template_view' ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin_email_template_view') }}">
                <i class="far fa-caret-square-right"></i>
                <span>{{ EMAIL_TEMPLATE }}</span>
            </a>
        </li>

        <!-- Home Advertisements -->
        <li class="nav-item {{ $route == 'admin_home_advertisement' ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin_home_advertisement') }}">
                <i class="far fa-caret-square-right"></i>
                <span>{{ HOME_ADVERTISEMENTS }}</span>
            </a>
        </li>


        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Sidebar Toggler (Sidebar) -->
        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>
    </ul>
    <!-- End of Sidebar -->


    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
        <!-- Main Content -->
        <div id="content">
            <!-- Topbar -->
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                <!-- Sidebar Toggle (Topbar) -->
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars"></i>
                </button>

                <!-- Topbar Navbar -->
                <ul class="navbar-nav ml-auto">


                    <!-- Nav Item - Alerts -->
                    <li class="nav-item dropdown no-arrow mx-1">
                        <a class="btn btn-danger btn-sm mt-3" href="javascript:void(0)" id="limparCacheBtn">
                            <i class="fa fa-eraser"></i> Limpar cache
                        </a>
                    </li>
                    <li class="nav-item dropdown no-arrow mx-1">
                        <a class="btn btn-info btn-sm mt-3" href="{{ url('/') }}" target="_blank">
                            {{ VISIT_WEBSITE }}
                        </a>
                    </li>

                    <div class="topbar-divider d-none d-sm-block"></div>
                    <!-- Nav Item - User Information -->
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="mr-2 d-none d-lg-inline text-gray-600">{{ $user->name }}</span>
                            <img class="img-profile rounded-circle" src="{{ asset('uploads/user_photos/'.$user->photo) }}">
                        </a>
                        <!-- Dropdown - User Information -->
                        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">

                            <a class="dropdown-item" href="{{ route('admin_profile_change') }}">
                                <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i> {{ CHANGE_PROFILE }}
                            </a>
                            <a class="dropdown-item" href="{{ route('admin_password_change') }}">
                                <i class="fas fa-unlock-alt fa-sm fa-fw mr-2 text-gray-400"></i> {{ CHANGE_PASSWORD }}
                            </a>
                            <a class="dropdown-item" href="{{ route('admin_photo_change') }}">
                                <i class="fas fa-image fa-sm fa-fw mr-2 text-gray-400"></i> {{ CHANGE_PHOTO }}
                            </a>
                            <a class="dropdown-item" href="{{ route('admin_banner_change') }}">
                                <i class="fas fa-image fa-sm fa-fw mr-2 text-gray-400"></i> {{ CHANGE_BANNER }}
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ route('admin_logout') }}">
                                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i> {{ LOGOUT }}
                            </a>
                        </div>
                    </li>
                </ul>
            </nav>
            <!-- End of Topbar -->
            <!-- Begin Page Content -->
            <div class="container-fluid">

                @yield('admin_content')

            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- End of Main Content -->

    </div>
    <!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

@include('admin.app_scripts_footer')
<div id="mensagemCache" style="margin-top: 10px;"></div>

<script>
document.getElementById('limparCacheBtn').addEventListener('click', function () {
    if (!confirm('Tem certeza que deseja limpar o cache do sistema?')) return;

    fetch("{{ route('admin.limpar.cache') }}", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "Accept": "application/json",
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
    location.reload();
            
        }
    })
    .catch(() => {
        alert('Erro ao limpar o cache.');
    });
});
</script>
<script>
    function generateSlug(title_parameter,slug_parameter) {
        const title = document.getElementById(title_parameter).value;
        
        // Convert to lowercase and remove any characters that are not alphanumeric, hyphens, or spaces
        const slug = title.toLowerCase()
            .normalize('NFD') // Normalize letters with accents
            .replace(/[\u0300-\u036f]/g, '') // Remove accent marks
            .replace(/[^a-z0-9\s-]/g, '') // Remove invalid characters
            .trim() // Remove leading and trailing spaces
            .replace(/\s+/g, '-') // Replace spaces with hyphens
            .replace(/-+/g, '-'); // Ensure single hyphens between words
        
        document.getElementById(slug_parameter).value = slug;
    }
    function formatReal(input) {
            let value = input.value;

            // Remove todos os caracteres que não sejam números
            value = value.replace(/\D/g, '');

            // Formata o número com separador de milhar
            const formattedValue = parseInt(value || '0', 10).toLocaleString('pt-BR');

            input.value = formattedValue;
        }
</script>
<script>
$(document).ready(function() {
    $('[data-toggle="tooltip"]').tooltip();
   $('#summernote').summernote({
       callbacks: {
        onInit: function() {
            // Permitir a tag <div> com a classe 'table-responsive'
            var editor = $(this);
            editor.summernote('editor.insertHTML', '<div class="table-responsive">');
        }
    },
    disableDragAndDrop: true,
    popover: {
        image: [],
        link: [],
        air: []
    },
    allowedTags: ['p', 'b', 'i', 'u', 'strong', 'em', 'ul', 'ol', 'li', 'a', 'img', 'br', 'hr','div','span','h2','h1','h3','h4','div.table-responsive'], // Adicione as tags HTML que deseja permitir
    allowedClasses: {
        'div': ['table-responsive'] // Permitindo div com a classe 'table-responsive'
    },
    height: 300,
    focus: true,
    placeholder: 'Escreva o conteúdo aqui...'
});

function sendFile(file) {
    let data = new FormData();
    data.append("file", file);
    
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
    $.ajax({
        url: '/upload-image', // URL para seu endpoint de upload
        cache: false,
        contentType: false,
        processData: false,
        data: data,
        type: "POST",
        success: function(url) {
            $('#summernote').summernote("insertImage", url.location);
        },
        error: function(data) {
            console.log("error uploading file", data);
        }
    });
}
});
</script>
</body>
</html>
