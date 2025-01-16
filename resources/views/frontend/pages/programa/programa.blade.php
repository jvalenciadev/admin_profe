@extends('frontend.layouts.master')
@section('title')
    Programas
@endsection
@section('frontend-content')
    @php
        use Carbon\Carbon;
    @endphp
    <style id="qeducato_data-dynamic-css" title="dynamic-css" class="redux-options-output">
        body,
        .widget_categories a {
            color: #6e6e6e;
        }

        .site-content {
            background-color: #ffffff;
        }

        .breadcrumb-area {
            background-color: rgba(32, 40, 46, 0.8);
            /* Fondo con un poco de transparencia */
            background-image: url('{{ asset('storage/programa_banners/' . $programa->pro_banner) }}');
            background-blend-mode: overlay;
            /* Mezcla el color de fondo y la imagen */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            /* Añade sombra */
        }

        
    </style>
    <section class="breadcrumb-area d-flex  p-relative align-items-center">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-xl-12 col-lg-12">
                    <div class="breadcrumb-wrap text-left">
                        <div class="breadcrumb-title">
                            <h2>{{ $programa->pro_nombre }}</h2>
                        </div>
                    </div>
                </div>
                <div class="breadcrumb-wrap2">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Programa</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section> <!-- breadcrumb-area-end --><!-- Project Detail -->
    <section class="project-detail">
        <div class="container"> <!-- Lower Content -->
            <div class="lower-content">
                <div class="row">
                    <div class="text-column col-lg-9 col-md-9 col-sm-12">
                        <div class="upper-box">
                            <div class="single-item-carousel owl-carousel owl-theme">
                                    <img 
                                        src="{{ asset('storage/programa_banners/' . $programa->pro_banner) }}"
                                       />
                            </div>
                        </div>
                        <div class="inner-column">
                           
                            <div>
                                {!! $programa->pro_contenido !!}
                            </div>
                            
                        </div>
                        
                    </div>
                    <div class="col-lg-3">
                        <aside class="sidebar-widget info-column">
                            <div class="inner-column3">
                                <h3> Detalle</h3>
                                <ul class="project-info clearfix">
                                    <li>
                                        <div class="priceing"> <strong> Bs. {{ $programa->pro_costo }} </strong>
                                            {{-- <sub> $129.00 </sub>
                                            <span class="discont"> 55% OFF </span> --}}
                                        </div>
                                    </li>
                                    <li> <span class="icon fal fa-calendar-alt"></span> <strong>Inscripcion:</strong>
                                        <span class="class-size">
                                            Del
                                            {{ Carbon::parse($programa->pro_fecha_inicio_inscripcion)->translatedFormat('d') }}
                                            de
                                            {{ Carbon::parse($programa->pro_fecha_inicio_inscripcion)->translatedFormat('F') }}
                                            al
                                            {{ Carbon::parse($programa->pro_fecha_fin_inscripcion)->translatedFormat('d') }}
                                            de
                                            {{ Carbon::parse($programa->pro_fecha_fin_inscripcion)->translatedFormat('F') }}
                                            de
                                            {{ Carbon::parse($programa->pro_fecha_fin_inscripcion)->translatedFormat('Y') }}
                                        </span>
                                    </li>
                                    <li> <span class="icon fal fa-book"></span> <strong>Carga horaria:</strong>
                                        <span>{{ $programa->pro_carga_horaria }} hrs.</span>
                                    </li>
                                    <li> <span class="icon fal fa-clock"></span> <strong>Tiempo: </strong>
                                        <span>
                                            {{ $programa->duracion->pd_semana == 1 ? '1 semana' : $programa->duracion->pd_semana . ' semanas' }}
                                            <br>
                                            {{ $programa->pro_horario }}
                                        </span>
                                    </li>
                                    {{-- <li> <span class="icon fal fa-user"></span> <strong>Inscritos: </strong> <span>20
                                            students</span></li> --}}
                                    <li> <span class="icon fal fa-users"></span> <strong>Versión: </strong>
                                        <span>{{ $programa->version->pv_nombre }}
                                            {{ $programa->version->pv_numero }}</span>
                                    </li>
                                    <li> <span class="icon fal fa-users"></span> <strong>Tipo: </strong>
                                        <span>{{ $programa->tipo->pro_tip_nombre }}</span>
                                    </li>
                                    <li> <span class="icon fal fa-globe"></span> <strong>Modalidad: </strong>
                                        <span>{{ $programa->modalidad->pm_nombre }}</span>
                                    </li>
                                    {{-- <li>
                                        <div class="slider-btn"> <a href="../../contact/index.html"
                                                class="btn ss-btn smoth-scroll"> Inscríbete <i
                                                    class="fal fa-long-arrow-right"></i> </a></div>
                                    </li> --}}
                                </ul>
                            </div>
                        </aside>
                    </div>
                </div>
            </div>
        </div>
    </section> <!--End Project Detail -->
@endsection
