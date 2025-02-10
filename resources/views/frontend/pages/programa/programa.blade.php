@extends('frontend.layouts.master')
@section('title')
    Oferta Académica - {{ $programa->pro_nombre }}
@endsection
@section('og-meta-tags')
    <meta property="og:locale" content="es_ES" />
    {{-- <meta property="og:type" content="article" /> --}}
    <meta property="og:title" content="{{ $programa->pro_nombre }}" />
    <meta name="og:description" content="Participa en los diplomados, ciclos formativos y especialidades del Programa PROFE y descubre nuevas herramientas y estrategias para enriquecer tu enseñanza. ¡Inscríbete ahora!" />
    <meta property="og:image" content="{{ asset('storage/programa_afiches/' . $programa->pro_afiche) }}" />
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
                    <div class="col-lg-3 col-md-4 col-sm-12 order-first order-md-last">
                        <aside class="sidebar-widget  info-column">
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

                                    <li> <span class="icon fal fa-users"></span> <strong>Tipo: </strong>
                                        <span>{{ $programa->tipo->pro_tip_nombre }}</span>
                                    </li>
                                    <li> <span class="icon fal fa-globe"></span> <strong>Modalidad: </strong>
                                        <span>{{ $programa->modalidad->pm_nombre }}</span>
                                    </li>


                                    <li> <span class="icon fal fa-users"></span> <strong>Versión: </strong>
                                        <span>
                                            {{ $programa->version->pv_romano }} - {{ $programa->version->pv_gestion }}  </span>
                                    </li>
                                    @if (now()->between(
                                            Carbon::parse($programa->pro_fecha_inicio_inscripcion),
                                            Carbon::parse($programa->pro_fecha_fin_inscripcion)->addDay(1) // Asegura que el último día esté incluido
                                        ))
                                        <li>
                                            <div class="slider-btn">
                                                <a href="{{ route('programaInscripcion', $programa->pro_id) }}"
                                                class="btn ss-btn smoth-scroll">
                                                Inscríbete <i class="fal fa-long-arrow-right"></i>
                                                </a>
                                            </div>
                                        </li>
                                        @if ($programa->pro_tip_id == 2 && ($programa->pm_id == 1 || $programa->pm_id == 2) )
                                            <li>
                                                <div class="slider-btn">
                                                    <a href="{{ route ('programa.solicitarSede', $programa->pro_id ) }}" class="btn ss-btn smoth-scroll btn-secondary solicitar-envio">
                                                        Solicitar para Mi Sede <i class="fal fa-map-marker-alt"></i>
                                                    </a>
                                                </div>
                                            </li>
                                        @endif
                                    @endif
                                </ul>
                            </div>
                        </aside>
                        
                    </div>
                    
                    <div class="col-lg-9 col-md-8 col-sm-12">
                        <div class="">
                            <div class="">
                                    <img
                                        src="{{ asset('storage/programa_banners/' . $programa->pro_banner) }}"
                                       />
                            </div>
                        </div>
                        <div class="inner-column">

                            <div>
                                {!! $programa->pro_contenido !!}
                            </div>
                            <h3> <strong> Dirigido a: </strong></h3>
                            <div>
                                {!! $restriccion->res_descripcion !!}
                            </div>

                        </div>
                         <!-- Tabla de Sedes y Turnos -->
                         <div class="inner-column">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover text-center">
                                    <thead class="table-primary">
                                        <tr>
                                            <th class="text-center">📍 Sede y Contactos</th>
                                            <th class="text-center">🕒 Turnos Habilitados</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($programa_sede_turno as $pst)
                                            <tr>
                                                <!-- Sede y contactos en una celda -->
                                                <td class="text-start align-middle">
                                                    <h7 class="mb-1">
                                                        <i class="bi bi-geo-alt-fill"></i> 
                                                        <strong class="fw-bold">{{ $pst->dep_nombre }}</strong> - {{ $pst->sede_nombre }}
                                                    </h7>
                                                    <div class="d-flex">
                                                        @if($pst->sede_contacto_1)
                                                            <a href="https://wa.me/591{{ $pst->sede_contacto_1 }}" target="_blank" class="text-success me-1">
                                                                <i class="fab fa-whatsapp"></i> {{ $pst->sede_contacto_1 }}
                                                            </a>
                                                        @endif
                                                        @if($pst->sede_contacto_2)
                                                            <a href="https://wa.me/591{{ $pst->sede_contacto_2 }}" target="_blank" class="text-success">
                                                                <i class="fab fa-whatsapp"></i> {{ $pst->sede_contacto_2 }}
                                                            </a>
                                                        @endif
                                                    </div>
                                                </td>

                                                <!-- Turnos habilitados -->
                                                <td class="align-middle">
                                                    @php
                                                        $turnos = DB::table('programa_turno')
                                                            ->when(!is_null($pst->pro_tur_ids), function($query) use ($pst) {
                                                                $turIds = json_decode($pst->pro_tur_ids);
                                                                if (!empty($turIds)) {
                                                                    $query->whereIn('programa_turno.pro_tur_id', $turIds);
                                                                }
                                                            })
                                                            ->get();
                                                    @endphp

                                                    @if($turnos->isNotEmpty())
                                                        <ul class="list-unstyled">
                                                            @foreach($turnos as $turno)
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                <i class="bi bi-clock-history text-primary"></i>
                                                                <strong class="me-2">
                                                                    {{ strstr($turno->pro_tur_nombre, '-', true) }}
                                                                </strong>
                                                                <span class="badge bg-info">{{ $turno->pro_tur_descripcion ?? '' }}</span>
                                                            </li>
                                                            @endforeach
                                                        </ul>
                                                    @else
                                                        <span class="text-danger fw-bold"><i class="bi bi-exclamation-circle"></i> Sin turnos</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <br>
                        <div class="inner-column">
                            <div class="portfolio">
                                <div class="row align-items-end mb-50">
                                    <div class="col-lg-12">
                                        <div class="my-masonry text-center">
                                            <div class="button-group filter-button-group">
                                                <button class="active" data-filter="*">Ver Todos</button>
                                                @foreach($galeriasPorPrograma as $sede_id => $galerias)
                                                    <button data-filter=".{{ $sede_id }}">{{ $galerias->first()->sede_nombre_abre }}</button>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="grid col3">
                                    @foreach($galeriasPorPrograma as $galerias)
                                        @foreach($galerias as $galeria)
                                            <div class="grid-item {{ $galeria->sede_id }}">
                                                <a href="{{ asset('storage/galeria/' . $galeria->galeria_imagen) }}" class="image-popup">
                                                    <figure class="gallery-image">
                                                        <img src="{{ asset('storage/galeria/' . $galeria->galeria_imagen) }}" alt="{{ $galeria->pro_nombre_abre }}" />
                                                        <figcaption>
                                                            <h4>{{ $galeria->sede_nombre_abre }}</h4>
                                                        </figcaption>
                                                    </figure>
                                                </a>
                                            </div>
                                        @endforeach
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </section> <!--End Project Detail -->
@endsection

@section('scripts')
    <script defer type="text/javascript" src="{{ asset('backend/assets/js/sweetalert2.js') }}"></script>
    <script>
        // Seleccionar todos los botones con la clase "solicitar-envio"
        document.querySelectorAll(".solicitar-envio").forEach(button => {
            button.addEventListener("click", function (e) {
                e.preventDefault(); // Evita que el enlace se ejecute automáticamente

                const url = this.getAttribute("href"); // Obtiene la URL del botón

                // Mostrar mensaje de confirmación
                Swal.fire({
                    title: 'Confirma tu solicitud',
                    html: `
                        <p>Estamos encantados de que desees habilitar este curso en tu sede.</p>
                        <p>Para hacerlo posible, necesitamos contar con al menos <b>30 participantes</b> inscritos.</p>
                        <p>¿Deseas enviar la solicitud?</p>
                    `,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, enviar solicitud',
                    cancelButtonText: 'No, cancelar',
                    reverseButtons: true,
                    customClass: {
                        confirmButton: 'btn-confirmar', // Clase personalizada para el botón confirmar
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Redirige al usuario a la URL si confirma
                        window.location.href = url;
                    }
                });
            });
        });

    </script>
    <style>
        .btn-confirmar {
            background-color: #1474a6 !important; /* Color verde */
            color: #fff !important;              /* Texto blanco */
            border: none !important;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.image-popup').magnificPopup({
                type: 'image',
                gallery: {
                    enabled: true // Habilitar la galería para navegar entre las imágenes
                }
            });
        });
    </script>
@endsection

