@extends('front.app_front')

@section('content')

<div class="page-banner" style="background-image: url('{{ asset('uploads/page_banners/'.$privacy_data->banner) }}')">
    <div class="page-banner-bg"></div>
    <h1>{{ $privacy_data->name }}</h1>
    <nav>
        <ol class="breadcrumb justify-content-center">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ HOME }}</a></li>
            <li class="breadcrumb-item active">{{ $privacy_data->name }}</li>
        </ol>
    </nav>
</div>

<div class="page-content">
    <div class="container">
        <div class="row">
            <!--<div class="col-md-12">
                    {!! clean($privacy_data->detail) !!}
            </div>-->
            <div class="col-md-6">
                <h1 style="    color: #000000;
                    font-size: 4.5vw;
                    font-weight: 600;
                    font-style: normal;
                    text-decoration: none;
                    line-height: 1.1em;
                    letter-spacing: -0.28px;
                    word-spacing: 0em;">Venda Fácil Seu Carro</h1>
                <h4>Transforme o momento de vender o seu carro em uma experiência emocionante e descomplicada com a {{ config('app.name') }}</h4>
                <a class="btn btn-lg btn-dark btn-block d-block d-md-inline-block mt-5 mb-5" href="https://web.whatsapp.com/send?phone=5513991482493&text=Quero%20vender%20meu%20carro">avaliar meu carro</a>
            </div>
            <div class="col-md-6">
                <img src="{{ asset('uploads/site_photos/ShoppingVilaDosCarros-1.jpg') }}" class="img-fluid" />
            </div>
        </div>


        <div class="row">
            <div class="col-md-12">
                <h2 class="elementor-heading-title elementor-size-default elementor-inline-editing pen mt-4 mb-4" style="color: #000000; text-align: center;" data-elementor-setting-key="title" data-pen-placeholder="Digite aqui..."><span style="color: #000000;">Aqui você <span style="color: #ff0000;">ganha</span> na venda, <span style="color: #ff0000;">ganha</span> na experiência, </span><br><span style="color: #000000;"><span style="color: #ff0000;">ganha</span> no suporte e <span style="color: #ff0000;">ganha</span> no tempo</span></h2>
            </div>                
        </div>
        <div class="row mt-5">
            <div class="col-md-3" style="text-align:center;">
                <svg aria-hidden="true" class="e-font-icon-svg e-fas-hand-holding-heart" viewBox="0 0 576 512" xmlns="http://www.w3.org/2000/svg" fill="#A71717"><path d="M275.3 250.5c7 7.4 18.4 7.4 25.5 0l108.9-114.2c31.6-33.2 29.8-88.2-5.6-118.8-30.8-26.7-76.7-21.9-104.9 7.7L288 36.9l-11.1-11.6C248.7-4.4 202.8-9.2 172 17.5c-35.3 30.6-37.2 85.6-5.6 118.8l108.9 114.2zm290 77.6c-11.8-10.7-30.2-10-42.6 0L430.3 402c-11.3 9.1-25.4 14-40 14H272c-8.8 0-16-7.2-16-16s7.2-16 16-16h78.3c15.9 0 30.7-10.9 33.3-26.6 3.3-20-12.1-37.4-31.6-37.4H192c-27 0-53.1 9.3-74.1 26.3L71.4 384H16c-8.8 0-16 7.2-16 16v96c0 8.8 7.2 16 16 16h356.8c14.5 0 28.6-4.9 40-14L564 377c15.2-12.1 16.4-35.3 1.3-48.9z"></path></svg>
            </div>                
            <div class="col-md-3">
                <p style="text-align: left;font-size:20px;">{{ config('app.name') }} oferece propostas de 20 lojas para seu veículo. Garantimos segurança e suporte na venda.</p>
            </div>                
            <div class="col-md-3" style="text-align:center;">
                <svg aria-hidden="true" class="e-font-icon-svg e-fas-funnel-dollar" viewBox="0 0 640 512" xmlns="http://www.w3.org/2000/svg" fill="#A71717"><path d="M433.46 165.94l101.2-111.87C554.61 34.12 540.48 0 512.26 0H31.74C3.52 0-10.61 34.12 9.34 54.07L192 256v155.92c0 12.59 5.93 24.44 16 32l79.99 60c20.86 15.64 48.47 6.97 59.22-13.57C310.8 455.38 288 406.35 288 352c0-89.79 62.05-165.17 145.46-186.06zM480 192c-88.37 0-160 71.63-160 160s71.63 160 160 160 160-71.63 160-160-71.63-160-160-160zm16 239.88V448c0 4.42-3.58 8-8 8h-16c-4.42 0-8-3.58-8-8v-16.29c-11.29-.58-22.27-4.52-31.37-11.35-3.9-2.93-4.1-8.77-.57-12.14l11.75-11.21c2.77-2.64 6.89-2.76 10.13-.73 3.87 2.42 8.26 3.72 12.82 3.72h28.11c6.5 0 11.8-5.92 11.8-13.19 0-5.95-3.61-11.19-8.77-12.73l-45-13.5c-18.59-5.58-31.58-23.42-31.58-43.39 0-24.52 19.05-44.44 42.67-45.07V256c0-4.42 3.58-8 8-8h16c4.42 0 8 3.58 8 8v16.29c11.29.58 22.27 4.51 31.37 11.35 3.9 2.93 4.1 8.77.57 12.14l-11.75 11.21c-2.77 2.64-6.89 2.76-10.13.73-3.87-2.43-8.26-3.72-12.82-3.72h-28.11c-6.5 0-11.8 5.92-11.8 13.19 0 5.95 3.61 11.19 8.77 12.73l45 13.5c18.59 5.58 31.58 23.42 31.58 43.39 0 24.53-19.04 44.44-42.67 45.07z"></path></svg>
            </div>                
            <div class="col-md-3">
                <p style="text-align: left;font-size:20px;">Possuímos mais de 500 veículos e 20 lojas. Asseguramos a melhor oferta na venda.</p>
            </div>                
        </div>

        <div class="row mt-5">
            <div class="col-md-3" style="text-align:center;"><svg aria-hidden="true" class="e-font-icon-svg e-far-clock" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg" fill="#A71717"><path d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm0 448c-110.5 0-200-89.5-200-200S145.5 56 256 56s200 89.5 200 200-89.5 200-200 200zm61.8-104.4l-84.9-61.7c-3.1-2.3-4.9-5.9-4.9-9.7V116c0-6.6 5.4-12 12-12h32c6.6 0 12 5.4 12 12v141.7l66.8 48.6c5.4 3.9 6.5 11.4 2.6 16.8L334.6 349c-3.9 5.3-11.4 6.5-16.8 2.6z"></path></svg></div>
            <div class="col-md-3"><p style="text-align: left;font-size:20px;">Aqui tem bancos, despachante, financeiro, seguradora, vistoria, restaurante e etc. Tudo fácil e rápido.</p></div>
            <div class="col-md-3" style="text-align:center;"><svg aria-hidden="true" class="e-font-icon-svg e-fas-headset" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg" fill="#A71717"><path d="M192 208c0-17.67-14.33-32-32-32h-16c-35.35 0-64 28.65-64 64v48c0 35.35 28.65 64 64 64h16c17.67 0 32-14.33 32-32V208zm176 144c35.35 0 64-28.65 64-64v-48c0-35.35-28.65-64-64-64h-16c-17.67 0-32 14.33-32 32v112c0 17.67 14.33 32 32 32h16zM256 0C113.18 0 4.58 118.83 0 256v16c0 8.84 7.16 16 16 16h16c8.84 0 16-7.16 16-16v-16c0-114.69 93.31-208 208-208s208 93.31 208 208h-.12c.08 2.43.12 165.72.12 165.72 0 23.35-18.93 42.28-42.28 42.28H320c0-26.51-21.49-48-48-48h-32c-26.51 0-48 21.49-48 48s21.49 48 48 48h181.72c49.86 0 90.28-40.42 90.28-90.28V256C507.42 118.83 398.82 0 256 0z"></path></svg></div>
            <div class="col-md-3"><p style="text-align: left;font-size:20px;">Garantimos segurança, rapidez e conforto na venda do seu carro, com atendimento e suporte nos pós-venda.</p></div>
        </div>
    </div>






</div>

<div class="page-content" style="margin-top: 25px;padding-top:50px;padding-bottom:50px;background-color:#A71717;">
    <div class="container">
        <div class="row">

            <div class="col-md-3">
                <h1 style="color:white">Fácil - Rápido - Seguro</h1>
                <p style="color:white;margin-top:20px;">Com a {{ config('app.name') }}, você pode fazer tudo do conforto da sua casa, desde receber as melhores ofertas até receber o pagamento. Só precisa sair de casa para fazer a inspeção.</p>
                <a class="btn btn-lg btn-light btn-block d-block d-md-inline-block mt-2 mb-4" style="border: 1px solid #fff;" href="https://web.whatsapp.com/send?phone=5513991482493&text=Quero%20vender%20meu%20carro">avaliar meu carro</a>
            </div>



            <div class="col-md-3">
                <div class="elementor-icon-box-wrapper flex-fill" style="background-color:#ffffff;padding:20px;">

                    <div class="elementor-icon-box-icon">
                        <span class="elementor-icon elementor-animation-" style="text-align:center;">
                            <svg aria-hidden="true" class="e-font-icon-svg e-fas-comments-dollar" viewBox="0 0 576 512" xmlns="http://www.w3.org/2000/svg"><path d="M416 192c0-88.37-93.12-160-208-160S0 103.63 0 192c0 34.27 14.13 65.95 37.97 91.98C24.61 314.22 2.52 338.16 2.2 338.5A7.995 7.995 0 0 0 8 352c36.58 0 66.93-12.25 88.73-24.98C128.93 342.76 167.02 352 208 352c114.88 0 208-71.63 208-160zm-224 96v-16.29c-11.29-.58-22.27-4.52-31.37-11.35-3.9-2.93-4.1-8.77-.57-12.14l11.75-11.21c2.77-2.64 6.89-2.76 10.13-.73 3.87 2.42 8.26 3.72 12.82 3.72h28.11c6.5 0 11.8-5.92 11.8-13.19 0-5.95-3.61-11.19-8.77-12.73l-45-13.5c-18.59-5.58-31.58-23.42-31.58-43.39 0-24.52 19.05-44.44 42.67-45.07V96c0-4.42 3.58-8 8-8h16c4.42 0 8 3.58 8 8v16.29c11.29.58 22.27 4.51 31.37 11.35 3.9 2.93 4.1 8.77.57 12.14l-11.75 11.21c-2.77 2.64-6.89 2.76-10.13.73-3.87-2.43-8.26-3.72-12.82-3.72h-28.11c-6.5 0-11.8 5.92-11.8 13.19 0 5.95 3.61 11.19 8.77 12.73l45 13.5c18.59 5.58 31.58 23.42 31.58 43.39 0 24.53-19.05 44.44-42.67 45.07V288c0 4.42-3.58 8-8 8h-16c-4.42 0-8-3.58-8-8zm346.01 123.99C561.87 385.96 576 354.27 576 320c0-66.94-53.49-124.2-129.33-148.07.86 6.6 1.33 13.29 1.33 20.07 0 105.87-107.66 192-240 192-10.78 0-21.32-.77-31.73-1.88C207.8 439.63 281.77 480 368 480c40.98 0 79.07-9.24 111.27-24.98C501.07 467.75 531.42 480 568 480c3.2 0 6.09-1.91 7.34-4.84 1.27-2.94.66-6.34-1.55-8.67-.31-.33-22.42-24.24-35.78-54.5z"></path></svg>				</span>
                    </div>

                    <div class="elementor-icon-box-content" style="text-align: center;">

                        <h3 class="elementor-icon-box-title">
                            <span style="margin-block-start: .5rem;
                                  margin-block-end: 1rem;font-size: 18px;font-weight: 500;line-height: 1.2;text-align:center;">
                                Receba as melhores ofertas						</span>
                        </h3>

                        <p class="elementor-icon-box-description" style="font-size:15px;">
                            Só quem tem as 20 melhores lojas em um só shopping pode direcionar as melhores ofertas para você escolher					</p>

                    </div>

                </div>
            </div>

            <div class="col-md-3">
                <div class="elementor-icon-box-wrapper flex-fill" style="background-color:#ffffff;padding:20px;">

                    <div class="elementor-icon-box-icon">
                        <span class="elementor-icon elementor-animation-" style="text-align:center;">
                            <svg aria-hidden="true" class="e-font-icon-svg e-far-calendar-alt" viewBox="0 0 448 512" xmlns="http://www.w3.org/2000/svg"><path d="M148 288h-40c-6.6 0-12-5.4-12-12v-40c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12zm108-12v-40c0-6.6-5.4-12-12-12h-40c-6.6 0-12 5.4-12 12v40c0 6.6 5.4 12 12 12h40c6.6 0 12-5.4 12-12zm96 0v-40c0-6.6-5.4-12-12-12h-40c-6.6 0-12 5.4-12 12v40c0 6.6 5.4 12 12 12h40c6.6 0 12-5.4 12-12zm-96 96v-40c0-6.6-5.4-12-12-12h-40c-6.6 0-12 5.4-12 12v40c0 6.6 5.4 12 12 12h40c6.6 0 12-5.4 12-12zm-96 0v-40c0-6.6-5.4-12-12-12h-40c-6.6 0-12 5.4-12 12v40c0 6.6 5.4 12 12 12h40c6.6 0 12-5.4 12-12zm192 0v-40c0-6.6-5.4-12-12-12h-40c-6.6 0-12 5.4-12 12v40c0 6.6 5.4 12 12 12h40c6.6 0 12-5.4 12-12zm96-260v352c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V112c0-26.5 21.5-48 48-48h48V12c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v52h128V12c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v52h48c26.5 0 48 21.5 48 48zm-48 346V160H48v298c0 3.3 2.7 6 6 6h340c3.3 0 6-2.7 6-6z"></path></svg>   
                        </span>
                    </div>

                    <div class="elementor-icon-box-content" style="text-align: center;">

                        <h3 class="elementor-icon-box-title">
                            <span style="margin-block-start: .5rem;
                                  margin-block-end: 1rem;font-size: 18px;font-weight: 500;line-height: 1.2;text-align:center;">
                                Agende uma inspeção						</span>
                        </h3>

                        <p class="elementor-icon-box-description" style="font-size:15px;">
                            Agende o seu melhor dia e horário para realização da inspeção.					</p>

                    </div>

                </div>
            </div>

            <div class="col-md-3 ">
                <div class="elementor-icon-box-wrapper flex-fill" style="background-color:#ffffff;padding:20px;margin-bottom:10px">

                    <div class="elementor-icon-box-icon">
                        <span class="elementor-icon elementor-animation-" style="text-align:center;">
                            <svg aria-hidden="true" class="e-font-icon-svg e-fas-money-check-alt" viewBox="0 0 640 512" xmlns="http://www.w3.org/2000/svg"><path d="M608 32H32C14.33 32 0 46.33 0 64v384c0 17.67 14.33 32 32 32h576c17.67 0 32-14.33 32-32V64c0-17.67-14.33-32-32-32zM176 327.88V344c0 4.42-3.58 8-8 8h-16c-4.42 0-8-3.58-8-8v-16.29c-11.29-.58-22.27-4.52-31.37-11.35-3.9-2.93-4.1-8.77-.57-12.14l11.75-11.21c2.77-2.64 6.89-2.76 10.13-.73 3.87 2.42 8.26 3.72 12.82 3.72h28.11c6.5 0 11.8-5.92 11.8-13.19 0-5.95-3.61-11.19-8.77-12.73l-45-13.5c-18.59-5.58-31.58-23.42-31.58-43.39 0-24.52 19.05-44.44 42.67-45.07V152c0-4.42 3.58-8 8-8h16c4.42 0 8 3.58 8 8v16.29c11.29.58 22.27 4.51 31.37 11.35 3.9 2.93 4.1 8.77.57 12.14l-11.75 11.21c-2.77 2.64-6.89 2.76-10.13.73-3.87-2.43-8.26-3.72-12.82-3.72h-28.11c-6.5 0-11.8 5.92-11.8 13.19 0 5.95 3.61 11.19 8.77 12.73l45 13.5c18.59 5.58 31.58 23.42 31.58 43.39 0 24.53-19.05 44.44-42.67 45.07zM416 312c0 4.42-3.58 8-8 8H296c-4.42 0-8-3.58-8-8v-16c0-4.42 3.58-8 8-8h112c4.42 0 8 3.58 8 8v16zm160 0c0 4.42-3.58 8-8 8h-80c-4.42 0-8-3.58-8-8v-16c0-4.42 3.58-8 8-8h80c4.42 0 8 3.58 8 8v16zm0-96c0 4.42-3.58 8-8 8H296c-4.42 0-8-3.58-8-8v-16c0-4.42 3.58-8 8-8h272c4.42 0 8 3.58 8 8v16z"></path></svg>   
                        </span>
                    </div>

                    <div class="elementor-icon-box-content" style="text-align: center;">

                        <h3 class="elementor-icon-box-title">
                            <span style="margin-block-start: .5rem;
                                  margin-block-end: 1rem;font-size: 18px;font-weight: 500;line-height: 1.2;text-align:center;">
                                Receba via PIX						</span>
                        </h3>

                        <p class="elementor-icon-box-description" style="font-size:15px;">
                            Estando tudo ok na inspeção, você recebe o pagamento diretamente na sua conta, via PIX				</p>

                    </div>

                </div>
            </div>
        </div>


    </div>
</div>






<div class="page-content" style="padding-top:50px;padding-bottom:50px;background-color:#FFD1D1;">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>Porque escolher a {{ config('app.name') }} para vender seu carro?</h1>
                <ul>
                    <li>
                        <strong>Rápido e eficiente</strong> – Na {{ config('app.name') }}, o processo de venda do seu carro é rápido e eficiente. Com uma avaliação prévia online e uma avaliação presencial em uma das lojas, você pode receber uma proposta de compra em tempo recorde.
                    </li>
                    <li><strong>Fácil e prático</strong> – Não se preocupe em criar anúncios ou ir até lojistas e concessionárias. A {{ config('app.name') }} cuida de tudo para você, tornando o processo de venda fácil e prático. Além disso, você só precisará sair de casa quando todos os passos anteriores forem resolvidos, poupando tempo e esforço.</li>
                    <li><strong>Seguro e transparente</strong> – Com a {{ config('app.name') }}, você não lida com terceiros. Tudo é resolvido pela própria empresa, garantindo total segurança e transparência na negociação. A {{ config('app.name') }} garante a oferta e paga à vista, sem nenhuma dor de cabeça para você.</li>
                    <li><strong>Sem custos</strong> – Não se preocupe com nenhum tipo de custo. Todo o processo de avaliação e proposta de compra é por conta da {{ config('app.name') }}, e você pode receber a oferta sem compromisso.</li>
                    <li><strong>Suporte pós-venda</strong> – A {{ config('app.name') }} também oferece suporte pós-venda, garantindo que você tenha uma experiência completa e satisfatória na venda do seu carro.</li>
                    <li><strong>Variedade de opções</strong> – Além de vender seu carro, na {{ config('app.name') }} você também pode encontrar uma grande variedade de veículos para compra, desde seminovos até carros usados com mais de 100km rodados.</li>
                </ul>
            </div>
        </div>


    </div>
</div>

<div class="page-content" style="margin-top: 25px;padding-top:50px;padding-bottom:50px;background-color:#FFF;">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h4 style="text-align:center;">FAQ</h4>
                <h1 style="text-align:center;">PERGUNTAS FREQUENTES</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 faq">

                <div class="panel-group" id="accordion1" role="tablist" aria-multiselectable="true">

                    @php $i=0; @endphp
                    @foreach ($faqs as $row)
                    @php $i++; @endphp

                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="heading{{ $i }}">
                            <h4 class="panel-title">
                                <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion1" href="#collapse{{ $i }}" aria-expanded="false" aria-controls="collapse{{ $i }}">
                                    {{ $row->faq_title }}
                                </a>
                            </h4>
                        </div>
                        <div id="collapse{{ $i }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading{{ $i }}">
                            <div class="panel-body">
                                {!! clean($row->faq_content) !!}
                            </div>
                        </div>
                    </div>

                    @endforeach

                </div>
            </div>
        </div>
    </div>
</div>

@endsection
