<!--<script src="{{ asset('frontend/js/custom.js') }}"></script>-->
<script>
(function ($) {

    "use strict";

    // Scroll-Top
    $(".scroll-top").hide();
    $(window).on("scroll", function () {
        if ($(this).scrollTop() > 300) {
            $(".scroll-top").fadeIn();
        } else {
            $(".scroll-top").fadeOut();
        }
    });
    $(".scroll-top").on("click", function () {
        $("html, body").animate({
            scrollTop: 0,
        }, 700)
    });
@if($route != null)
    $(document).ready(function () {

        if ($('#timepicker').length) {  // Verifica se a tabela existe
            $("#timepicker").timepicker();
        }

        if ($('#example').length) {  // Verifica se a tabela existe
            $('#example').DataTable();
        }
        if ($('.select2').length) {
            $('.select2').select2({
                theme: "bootstrap"
            });
        }

        $('.paypal').hide();
        $('.stripe').hide();
        $('.bank').hide();
        $('.cash-on-delivery').hide();

        $('#paymentMethodChange').on('change', function () {

            if ($('#paymentMethodChange').val() == 'PayPal')
            {
                $('.paypal').show();
                $('.stripe').hide();
                $('.bank').hide();
                $('.cash-on-delivery').hide();
            } else if ($('#paymentMethodChange').val() == 'Stripe')
            {
                $('.paypal').hide();
                $('.stripe').show();
                $('.bank').hide();
                $('.cash-on-delivery').hide();
            } else if ($('#paymentMethodChange').val() == 'Bank')
            {
                $('.paypal').hide();
                $('.stripe').hide();
                $('.bank').show();
                $('.cash-on-delivery').hide();
            } else if ($('#paymentMethodChange').val() == 'Cash On Delivery')
            {
                $('.paypal').hide();
                $('.stripe').hide();
                $('.bank').hide();
                $('.cash-on-delivery').show();
            } else if ($('#paymentMethodChange').val() == '')
            {
                $('.paypal').hide();
                $('.stripe').hide();
                $('.bank').hide();
                $('.cash-on-delivery').hide();
            }

        });
    });
        @endif


    // Wow Active
    new WOW().init();

    // Mean Menu

    jQuery('.mean-menu').meanmenu({
        meanScreenWidth: "991"
    });

    // Video Popup
    $('.video-button').magnificPopup({
        type: 'iframe',
        gallery: {
            enabled: true
        }
    });

    $('.magnific').magnificPopup({
        type: 'image',
        gallery: {
            enabled: true
        }
    });
@if($route != null)
    $('.my').iconpicker();
 @endif
    if ($(window).width() > 767) {
       
    }

})(jQuery);

</script>
<script src="{{ asset('js/jquery.maskMoney.min.js') }}"></script>
<script src="{{ asset('js/jquery.maskedinput.min.js') }}"></script>
<script>
$(document).ready(function(){
    $(".cpf").mask("999.999.999-99");
    $(".valor").maskMoney({thousands:'', decimal:'.', allowZero:true, suffix: ''});
});
</script>
@php
    $g_settings = \App\Models\GeneralSetting::where('id',1)->first();
@endphp
@if($g_settings->layout_direction == 'ltr')
@if($route != 'front_listing_detail' || $route != null)
    <script src="{{ asset('frontend/js/ltr_1.js') }}"></script>
@else
    <script src="{{ asset('frontend/js/ltr.js') }}"></script>
@endif
@endif
@if($g_settings->layout_direction == 'rtl')
@if($route != 'front_listing_detail' || $route != null)
    <script src="{{ asset('frontend/js/rtl.js') }}"></script>
@endif
@endif


@if ($errors->any())
	@php $all_error = '';  @endphp
	@foreach ($errors->all() as $error)
		@php $all_error .= $error.'<br>';  @endphp
	@endforeach
	<script>Swal.fire({icon: 'error',title: '',html: '{!! clean($all_error) !!}'})</script>
@endif

@if(session()->get('error'))
	<script>Swal.fire({icon: 'error',title: '',html: '{!! clean(session()->get('error')) !!}'})</script>
@endif

@if(session()->get('success'))
	<script>Swal.fire({icon: 'success',title: '',html: '{!! clean(session()->get('success')) !!}'})</script>
@endif

@if($g_setting->tawk_live_chat_status == 'Show')
<!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/{{ $g_setting->tawk_live_chat_property_id }}/1fapclhaj';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->
@endif
