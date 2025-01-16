@extends('frontend.layouts.master')
@section('title')
    Evento - {{ $evento->eve_nombre }}
@endsection
@section('og-meta-tags')
    <meta property="og:locale" content="es_ES" />
    {{-- <meta property="og:type" content="article" /> --}}
    <meta property="og:title" content="{{ $evento->eve_nombre }}" />
    <meta property="og:description"
        content="Participa en nuestros eventos educativos y descubre nuevas herramientas y estrategias para enriquecer tu enseñanza. ¡Inscríbete ahora!" />
    <meta property="og:image" content="{{ asset('storage/evento_afiches/' . $evento->eve_afiche) }}" />
    {{-- <meta property="og:url" content="{{ url()->current() }}" /> --}}
    <meta property="og:image:width" content="545" />
    <meta property="og:image:height" content="493" />
    <meta property="og:image:type" content="image/jpeg" />
@endsection
@section('frontend-content')
    @php
        use Carbon\Carbon;
    @endphp
    <style id="qeducato_data-dynamic-css" title="dynamic-css" class="redux-options-output">
        .breadcrumb-area {
            background-color: rgba(32, 40, 46, 0.966);
            background-image: url('{{ asset('storage/evento_banners/' . $evento->eve_banner) }}');
            background-blend-mode: overlay;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        .evento-descripcion {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        .evento-descripcion h3 {
            color: #333;
            font-size: 24px;
            margin-bottom: 15px;
        }

        .evento-descripcion p {
            line-height: 1.6;
            color: #555;
        }

        .info-column {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        .project-info li {
            margin-bottom: 10px;
        }

        .project-info span.icon {
            margin-right: 10px;
            color: #007bff;
            /* Cambia el color del icono */
        }

        .slider-btn {
            text-align: center;
        }

        .slider-btn .btn {
            background-color: #007bff;
            /* Color del botón */
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .slider-btn .btn:hover {
            background-color: #0056b3;
            /* Color al pasar el mouse */
        }

        .styled-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 16px;
            color: #333;
        }

        .styled-table thead tr {
            background-color: #125875;
            color: #ffffff;
        }

        .styled-table th,
        .styled-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #dddddd;
        }

        .styled-table tbody tr {
            background-color: #f9f9f9;
        }

        .styled-table tbody tr:nth-of-type(even) {
            background-color: #f2f2f2;
            /* Color de fondo para filas pares */
        }

        .styled-table tbody tr:hover {
            background-color: #e0e0e0;
            /* Color de fondo al pasar el mouse */
        }

        .mobile-only {
            display: none;
        }

        .desktop-only {
            display: block;
        }

        /* Mostrar afiche en pantallas pequeñas (móviles) */
        @media only screen and (max-width: 767px) {
            .mobile-only {
                display: block;
            }

            .desktop-only {
                display: none;
            }
        }
    </style>
    <section class="breadcrumb-area d-flex  p-relative align-items-center">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-xl-12 col-lg-12">
                    <div class="breadcrumb-wrap text-left">
                        <div class="breadcrumb-title">
                            <h2>{{ $evento->eve_nombre }}</h2>
                        </div>
                    </div>
                </div>
                <div class="breadcrumb-wrap2">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Evento</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>
    <section class="project-detail">
        <div class="container">
            <div class="single-item-carousel owl-carousel owl-theme">
                <!-- Imagen para dispositivos móviles (afiche) -->
                <figure class="image mobile-only" id="mobile-only">
                    <img fetchpriority="high"
                        src="{{ asset('storage/evento_afiches/' . $evento->eve_afiche) }}"
                        class="attachment-post-thumbnail size-post-thumbnail wp-post-image" alt="Afiche del evento"
                        decoding="async" />
                </figure>
            
                <!-- Imagen para tabletas y computadoras (banner) -->
                <figure class="image desktop-only" id="desktop-only">
                    <img fetchpriority="high"
                        src="{{ asset('storage/evento_banners/' . $evento->eve_banner) }}"
                        class="attachment-post-thumbnail size-post-thumbnail wp-post-image" alt="Banner del evento"
                        decoding="async" />
                </figure>
            </div>
            <div class="lower-content2">
                <div class="row">
                    <div class="text-column col-lg-9 col-md-12 col-sm-12">

                        <div class="s-about-content wow fadeInRight" data-animation="fadeInRight" data-delay=".2s">
                            <h2>{{ $evento->eve_nombre }}</h2>
                            
                            <div class="evento-descripcion styled-table">
                                {!! $evento->eve_descripcion !!}
                            </div>
                            <div countdown class="conterdown wow fadeInDown animated" data-animation="fadeInDown animated"
                                data-delay=".2s"
                                data-date="{{ $evento->eve_fecha }} {{ $evento->eve_ins_hora_asis_habilitado }}">
                                <div class="timer">
                                    <div class="timer-outer bdr1">
                                        <span class="days" data-days>0</span>
                                        <div class="smalltext">Dias</div>
                                        <div class="value-bar"></div>
                                    </div>
                                    <div class="timer-outer bdr2">
                                        <span class="hours" data-hours>0</span>
                                        <div class="smalltext">Horas</div>
                                    </div>
                                    <div class="timer-outer bdr3">
                                        <span class="minutes" data-minutes>0</span>
                                        <div class="smalltext">Minutos</div>
                                    </div>
                                    <div class="timer-outer bdr4">
                                        <span class="seconds" data-seconds>0</span>
                                        <div class="smalltext">Segundos</div>
                                    </div>
                                    <p id="time-up"></p>
                                </div>
                            </div>

                            {{-- <div class="upper-box">
                                <div class="single-item-carousel owl-carousel owl-theme d-flex justify-content-center">
                                    <a href="{{ asset('storage/evento_afiches/' . $evento->eve_afiche) }}" target="_blank">
                                        <img fetchpriority="high" width="500" height="1200"
                                            src="{{ asset('storage/evento_afiches/' . $evento->eve_afiche) }}"
                                            class="attachment-post-thumbnail size-post-thumbnail wp-post-image"
                                            alt="" decoding="async" />
                                    </a>
                                </div>
                            </div> --}}
                            {{-- <div class="two-column mt-30">
                                <div class="row aling-items-center">
                                    <div class="image-column col-xl-6 col-lg-12 col-md-12">
                                        <div class="footer-social mt-10">
                                            <a href="#"><i class="fab fa-facebook-f"></i></a>
                                            <a href="#"><i class="fab fa-instagram"></i></a>
                                            <a href="#"><i class="fab fa-twitter"></i></a>
                                        </div>
                                    </div>
                                    <div class="text-column col-xl-6 col-lg-12 col-md-12 text-right"></div>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                    <div class="info-column col-lg-3 col-md-12 col-sm-12">
                        <div class="inner-column3">
                            <h3> Detalles</h3>
                            <ul class="project-info clearfix">
                                @if ($evento->eve_ins_hora_asis_habilitado != '00:00:00')
                                    <li>
                                        <span class="icon fal fa-clock"></span>
                                        <strong>
                                            {{ Carbon::createFromFormat('H:i:s', $evento->eve_ins_hora_asis_habilitado)->format('H:i A') }}
                                            -
                                            {{ Carbon::createFromFormat('H:i:s', $evento->eve_ins_hora_asis_deshabilitado)->format('H:i A') }}
                                        </strong>
                                    </li>
                                @endif
                                <li> <span class="icon fal fa-calendar-alt"></span>
                                    <strong>{{ Carbon::parse($evento->eve_fecha)->translatedFormat('d F, Y') }} </strong>
                                </li>
                                <li> <span class="icon fal fa-map-marker-check"></span> <strong>
                                        {{ $evento->eve_lugar }}</strong></li>
                                <li> <span class="icon fal fa-envelope"></span> <strong> profe@iipp.edu.bo</strong>
                                </li>
                                {{-- <li> <span class="icon fal fa-phone"></span> <strong> +591</strong></li> --}}
                                @if ($evento->eve_inscripcion == 1)
                                    <li>
                                        <div class="slider-btn">
                                            <a href="{{ route('eventoInscripcion', $evento->eve_id) }}"
                                                class="btn ss-btn smoth-scroll">
                                                Inscribirme
                                                <i class="fal fa-long-arrow-right"></i>
                                            </a>
                                        </div>
                                    </li>
                                @endif

                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Obtén la fecha y hora del evento desde los atributos data
            var dateStr = document.getElementById('countdown').getAttribute('data-date');
            var timeStr = document.getElementById('countdown').getAttribute('data-time');

            // Combina fecha y hora en un solo string y crea un objeto Date
            var eventDateTimeStr = dateStr + 'T' + timeStr;
            var eventDateTime = new Date(eventDateTimeStr);

            var x = setInterval(function() {
                var now = new Date().getTime();
                var distance = eventDateTime - now;

                // Calcula el tiempo restante
                var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                // Actualiza el contenido del contador
                document.querySelector('#countdown .days').innerText = days;
                document.querySelector('#countdown .hours').innerText = hours;
                document.querySelector('#countdown .minutes').innerText = minutes;
                document.querySelector('#countdown .seconds').innerText = seconds;

                // Si el tiempo se ha agotado
                if (distance < 0) {
                    clearInterval(x);
                    document.querySelector('#countdown').innerHTML = "EXPIRED";
                }
            }, 1000);
        });
    </script>
@endsection
