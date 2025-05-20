<?php

function carregar_jquery_ui() {
    wp_enqueue_script('jquery-ui-autocomplete');
    wp_enqueue_style('jquery-ui-css', 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css');
}

add_action('wp_enqueue_scripts', 'carregar_jquery_ui');

function carregar_bootstrap() {
// CDN para o CSS do Bootstrap
    wp_enqueue_style('bootstrap-css', 'https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css');

// CDN para o JavaScript do Bootstrap
    wp_enqueue_script('popper-js', 'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js', array('jquery'), null, true);
    wp_enqueue_script('bootstrap-js', 'https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js', array('jquery'), null, true);
}

add_action('wp_enqueue_scripts', 'carregar_bootstrap');

function formulario_cotacao_shortcode() {
    ob_start();
    ?>		
    <style>
        form label{
            font-weight: 500 !important;
        }
        #prev,#next{
            font-weight: 700 !important;
            font-size: .75rem!important;
            color: #000000 !important;
        }
        #yt0{
            font-weight: 700 !important;
            font-size: 1.25rem!important;
        }
        .col-md-1,.col-md-2,.col-md-3,.col-md-4,.col-md-5,.col-md-6,.col-md-7,.col-md-8,.col-md-9,.col-md-10,.col-md-11,.col-md-12{
            padding-right: 3px!important;
            padding-left: 3px!important;
        }
        .rows{
            margin-right: -40px !important;
        }
        .offs{
            margin-left: 7.1% !important;
            max-width: 85.33333% !important;
        }
        .box-passos {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .box-passos .icon-passos.active {
            background-color: #001A64;
            opacity: 1;
        }
        .box-passos .icon-passos {
            background-color: rgb(118, 150, 200, 1);
            opacity: .5;
            padding: .5rem;
            border-radius: 50%;
            font-size: 16px;
            font-weight: bold;
            margin: .5rem 0.3rem;
            width: 40px;
            height: 40px;
            text-align: center;
            line-height: 24px;
            color: #FDC200!important;
        }
        /*.offset-md-1{
            margin-left:7.4%!important;
        }
        .col-md-10{
            max-width: 85.333333%!important;
        }*/
    </style>
    <div class="container" id="budget-form">
        <div class="row">
            <div class="col-md-10 offset-md-1">
                <div class="wp-block-uagb-advanced-heading">
                    <h2 class="uagb-heading-text text-center mb-5 text-light" style="color: #FDC200!important">FAÇA SUA COTAÇÃO!</h2>
                </div>
                <div class="box-passos">
                    <a onclick="passo(1)" id="passo01Etapas01" class="icon-passos passo01 active">01</a>
                    <a onclick="passo(2)" id="passo01Etapas02" class="icon-passos passo02">02</a>
                    <a onclick="passo(3)" id="passo01Etapas03" class="icon-passos passo03">03</a>
                </div>
                <h3 style="display: none;">Passo <span id="passo"></span></h3>
                <form action="" method="post" id="budget-forms">
                    <div id="step_1" class="step">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="name">Nome completo *:</label>
                                    <input type="text" name="common_modules_transportbudget_models_BudgetForm[name]" id="name" class="form-control" placeholder="Digite seu nome completo" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="email">E-mail *:</label>
                                    <input type="email" name="common_modules_transportbudget_models_BudgetForm[email]" id="email" class="form-control" placeholder="Seu melhor E-mail" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="phone">Telefone *:</label>
                                    <input type="tel" name="common_modules_transportbudget_models_BudgetForm[phone]" id="phone" class="form-control phone" placeholder="Seu telefone" required>
                                </div>
                            </div>
                        </div>
                    </div>      



                    <div id="step_2" class="step">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="end">Tipo de pessoa *:</label>
                                    <select name="common_modules_transportbudget_models_BudgetForm[is_company]" id="is_company" class="form-control form-control-lg" required>
                                        <option value="" selected="selected" disabled="">Informe o tipo de pessoa</option>
                                        <option value="2">Pessoa Física</option>
                                        <option value="1">Pessoa Jurídica</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="originSity_term">Cidade de origem *:</label>
                                    <input type="text" name="common_modules_transportbudget_models_BudgetForm[originSity_term]" id="originSity_term" class="form-control" placeholder="Cidade de origem - Digite pelo menos 3 caracteres" required>
                                    <input name="common_modules_transportbudget_models_BudgetForm[originSity]" id="originSity" type="hidden" style="display:none;">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="destinySity_term">Cidade de destino *:</label>
                                    <input type="text" name="common_modules_transportbudget_models_BudgetForm[destinySity_term]" id="destinySity_term" class="form-control" placeholder="Cidade de destino - Digite pelo menos 3 caracteres" required>
                                    <input name="common_modules_transportbudget_models_BudgetForm[destinySity]" id="destinySity" type="hidden" style="display:none;">
                                </div>
                            </div>
                        </div>       
                    </div>



                    <div id="step_3" class="step">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="modelo">Informe o modelo do veículo *:</label>
                                    <input name="common_modules_transportbudget_models_BudgetForm[modelo_term]" id="modeloComplete" type="hidden">
                                    <input name="common_modules_transportbudget_models_BudgetForm[modelo]" id="modelo" type="text" autocomplete="off" placeholder="Modelo: CRUZE, ONIX, S-10 XRE-300 e etc" style="margin-bottom:3px !important;padding:.40rem!important;" class="form-control" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="data_transporte">Data do transporte:</label>

                                    <input name="common_modules_transportbudget_models_BudgetForm[data_transporte]" id="data_transporte" type="date" autocomplete="off" placeholder="Informe uma data aproximada" style="margin-bottom:3px !important;" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="vehicleSize">Porte do veículo *:</label>
                                    <select name="common_modules_transportbudget_models_BudgetForm[vehicleSize]" id="vehicleSize" class="form-control form-control-lg" required>
                                        <option value="" selected disabled>Porte do veiculo...</option>
                                        <option value="1">1. Automovel Pequeno</option>
                                        <option value="2">2. Automovel Medio</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="vehiclePrice">Valor do veículo *:</label>
                                    <input type="tel" name="common_modules_transportbudget_models_BudgetForm[vehiclePrice]" id="vehiclePrice" class="form-control mask-money" placeholder="Valor do veículo" required>
                                    <input type="hidden" name="origem_requisicao" value="Transk">

                                </div>
                            </div>
                        </div>			
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="tp_input" id="budget-form-actions">
                                        <input type="hidden" name="origem_requisicao" value="Transk">
                                        <button class="btn btn-block btn-primary btn1 btn-wave btn-lg" name="enviar_formulario" type="button" id="yt0">
                                            <i class="fa fa-check"></i> Calcule minha cotação!
                                        </button>									
                                    </div>
                                    <div id="budget-form-loading" style="display: none;">
                                        <img src="https://transktransportes.com.br/site/images/ajax-loader.gif" alt="Carregando...">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>             
            </div>
        </div>    
        
        <div class="row">
            <div class="col-md-10 offset-md-1">
                <div class="d-flex justify-content-between">
                    <button class="btn btn-info btn-lg" id="prev">Voltar</button>
                    <button class="btn btn-primary btn-lg" id="next">Avançar</button>
                </div>
            </div>
        </div>
    </div>

    <?php

    return ob_get_clean();
}

add_shortcode('formulario_cotacao', 'formulario_cotacao_shortcode');

function adicionar_script_formulario_etapas() {
    ?>
    <script src="https://transktransportes.com.br/transk/wp-content/plugins/js/core.min.js" id="my-core-script-js"></script>
    <script>

                        function passo(index) {
                            console.log(index);
                        }
                        jQuery(document).ready(function ($) {
                            $('.phone').mask('(00) 00000-0000');
                            $('.mask-money').mask('#.##0,00', {reverse: true});

                            // Oculta todos os passos e exibe o primeiro
                            $('.step').hide();
                            $('.step:first').show();

                            function atualizarPasso() {
                                var totalSteps = $(".step").length;
                                var index = $(".step:visible").index(".step"); // Obtém o índice correto do passo visível

                                $("#passo").html(index + 1);

                                // Controla a ativação/desativação dos botões
                                $("#prev").prop('disabled', index === 0);
                                $("#next").prop('disabled', index === totalSteps - 1);

                                if (index + 1 === 1) {
                                    $('#passo01Etapas01').addClass('active');

                                    $('#passo01Etapas02').removeClass('active');
                                    $('#passo01Etapas03').removeClass('active');

                                    $('#passo01Etapas02').removeClass('click');
                                    $('#passo01Etapas03').removeClass('click');
                                }

                                if (index + 1 === 2) {
                                    $('#passo01Etapas02').addClass('active');

                                    $('#passo01Etapas01').addClass('active');
                                    $('#passo01Etapas01').addClass('click');

                                    $('#passo01Etapas03').removeClass('active');
                                    $('#passo01Etapas03').removeClass('click');
                                }

                                if (index + 1 === 3) {

                                    $('#passo01Etapas01').addClass('active');
                                    $('#passo01Etapas01').addClass('click');

                                    $('#passo01Etapas02').addClass('active');
                                    $('#passo01Etapas02').addClass('click');

                                    $('#passo01Etapas03').addClass('active');

                                }

                                return (index + 1);
                            }

                            atualizarPasso();

                            // Avançar para o próximo passo
                            $("#next").click(function () {
                                var atual = $(".step:visible");
                                var proximo = atual.next(".step");

                                if (proximo.length > 0) {
                                    atual.hide();
                                    proximo.show();
                                    atualizarPasso();
                                }
                            });

                            // Voltar para o passo anterior
                            $("#prev").click(function () {
                                var atual = $(".step:visible");
                                var anterior = atual.prev(".step");

                                if (anterior.length > 0) {
                                    atual.hide();
                                    anterior.show();
                                    atualizarPasso();
                                }
                            });



                            $('#modelo').autocomplete({
                                'minLength': 3,
                                'change': function (event, ui) {

                                    if (!ui.item) {
                                        $(this).val("");
                                        $("#modelo").val("");
                                    }
                                },
                                'source': 'https://transktransportes.com.br/api/api?api=modelos',
                                'focus': function (event, ui) {

                                    $("#modelo").val(ui.item.label);
                                    return false;
                                },
                                'select': function (event, ui) {
                                    $("#modelo").val(ui.item.label);
                                    $("#modelo").data("autocomplete-selected-label", ui.item.label);
                                    $("#modeloComplete").val(ui.item.id);
                                    $("#modelo").trigger("autocomplete.select", ["modelo_term", ui]);
                                    return false;
                                }
                            });
                            $('#originSity_term').autocomplete({
                                'minLength': 3,
                                'change': function (event, ui) {

                                    if (!ui.item) {
                                        $(this).val("");
                                        $("#originSity").val("");
                                    }
                                },
                                'source': 'https://transktransportes.com.br/api/api?api=cidades',
                                'focus': function (event, ui) {

                                    $("#originSity_term").val(ui.item.label);
                                    return false;
                                },
                                'select': function (event, ui) {
                                    $("#originSity_term").val(ui.item.label);
                                    $("#originSity_term").data("autocomplete-selected-label", ui.item.label);
                                    $("#originSity").val(ui.item.id);
                                    $("#originSity_term").trigger("autocomplete.select", ["originSity_term", ui]);
                                    return false;
                                }
                            });
                            $("#originSity_term").keyup(function () {
                                if ($(this).data("autocomplete-selected-label") != null &&
                                        $(this).val() != $(this).data("autocomplete-selected-label"))
                                    $("#originSity").val("");
                            });
                            $('#destinySity_term').autocomplete({
                                'minLength': 3,
                                'change': function (event, ui) {
                                    if (!ui.item) {
                                        $(this).val("");
                                        $("#destinySity").val("");
                                    }
                                },
                                'source': 'https://transktransportes.com.br/api/api?api=cidades',
                                'focus': function (event, ui) {
                                    $("#destinySity_term").val(ui.item.label);
                                    return false;
                                },
                                'select': function (event, ui) {
                                    $("#destinySity_term").val(ui.item.label);
                                    $("#destinySity_term").data("autocomplete-selected-label", ui.item.label);
                                    $("#destinySity").val(ui.item.id);
                                    $("#destinySity_term").trigger("autocomplete.select", ["destinySity_term", ui]);
                                    return false;
                                }
                            });
                            $("#destinySity_term").keyup(function () {
                                if ($(this).data("autocomplete-selected-label") != null &&
                                        $(this).val() != $(this).data("autocomplete-selected-label"))
                                    $("#destinySity").val("");
                            });
                            $('body').on('click', '#yt0', function (e) {
                                e.preventDefault();
                                var originSity_term = $('#originSity_term').val();
                                if (!originSity_term) {
                                    alert('Informe a cidade de origem');
                                    return false;
                                }
                                var destinySity_term = $('#destinySity_term').val();
                                if (!destinySity_term) {
                                    alert('Informe a cidade de destino');
                                    return false;
                                }
                                $.ajax({
                                    'beforeSend': function () {
                                        $.fn.vm.hideShow('#budget-form-actions', '#budget-form-loading');
                                    },
                                    'success': function (obj) {
                                        $.fn.vm.hideShow('#budget-form-loading', '#budget-form-actions');
                                        if ($.isEmptyObject(obj) || obj.__error == '0') {
                                            alert(obj.mensagem);
                                            /*swal({
                                             title: 'Sua cotação!',
                                             content: $('<div style="text-align: left;">' + obj.mensagem + '</div>')[0],
                                             className: 'swal-wide',
                                             });*/
                                            $('#budget-forms')[0].reset();
                                        } else {
                                            alert(obj.mensagem);
                                            /*swal({
                                             title: 'Atenção!',
                                             content: $('<div style="text-align: left;">' + obj.mensagem + '</div>')[0],
                                             className: 'swal-wide',
                                             });*/
                                            /*$('#budget-forms')[0].reset();*/
                                            return false;
                                        }
                                    },
                                    'error': function (html) {
                                        $.fn.vm.hideShow('#budget-form-loading', '#budget-form-actions');
                                        $('#budget-forms').hideErrors();
                                        alert('Ocorreu um erro.');
                                    },
                                    'dataType': 'json',
                                    'type': 'POST',
                                    'url': 'https://transktransportes.com.br/api/api?api=cotacao',
                                    'cache': false,
                                    'data': $(this).parents("form").serialize()});
                                return false;
                            });

                        });
    </script>
    <?php

}

add_action('wp_footer', 'adicionar_script_formulario_etapas');

function carregar_jquery_mask() {
    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-mask', 'https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js', array('jquery'), '1.14.16', true);
}

add_action('wp_enqueue_scripts', 'carregar_jquery_mask');
