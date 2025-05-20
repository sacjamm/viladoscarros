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

    $('.my').iconpicker();

    if ($(window).width() > 767) {
        /*$("#sticky_sidebar").stickit({
         top: 80,
         });*/
    }

    /*tinymce.init({
        selector: '.editor',
        height: '480'
    });

    tinymce.init({
        selector: '.editor',
        height: 480,
        plugins: [
            'advlist autolink lists link image charmap print preview anchor',
            'searchreplace visualblocks code fullscreen',
            'insertdatetime media table paste code help wordcount'
        ],
        toolbar: 'undo redo | formatselect | bold italic backcolor | \
              alignleft aligncenter alignright alignjustify | \
              bullist numlist outdent indent | removeformat | help | image',
        automatic_uploads: true,
        images_upload_url: '/upload-image', // A rota Laravel para onde será enviado o upload da imagem
        file_picker_types: 'image',
        images_upload_handler: function (blobInfo, success, failure) {
            // Custom handler para enviar a imagem usando AJAX
            let xhr, formData;

            xhr = new XMLHttpRequest();
            xhr.withCredentials = false;
            xhr.open('POST', '/upload-image'); // A rota Laravel para upload da imagem

            xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));

            xhr.onload = function () {
                let json;
                if (xhr.status !== 200) {
                    failure('Erro: ' + xhr.status);
                    return;
                }
                json = JSON.parse(xhr.responseText);

                if (!json || typeof json.location != 'string') {
                    failure('Erro ao fazer upload');
                    return;
                }
                success(json.location);
            };

            formData = new FormData();
            formData.append('file', blobInfo.blob(), blobInfo.filename());

            xhr.send(formData);
        }
    });*/



})(jQuery);
