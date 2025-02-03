@extends('frontend.layouts.master')
@section('title')
    {{ $sede->dep_nombre }} - {{ $sede->sede_nombre }}
@endsection
@section('og-meta-tags')
    <meta property="og:locale" content="es_ES" />
    {{-- <meta property="og:type" content="article" /> --}}
    <meta property="og:title" content="{{ $sede->dep_nombre }} - {{ $sede->sede_nombre }}" />
    <meta name="og:description" content="{{ Str::limit(strip_tags($sede->sede_descripcion), 100) }}" />
    <meta property="og:image" content="{{ asset('storage/sede_imagen/' . $sede->sede_imagen) }}" />
    {{-- <meta property="og:url" content="{{ url()->current() }}" /> --}}
    <meta property="og:image:width" content="545" />
    <meta property="og:image:height" content="493" />
    <meta property="og:image:type" content="image/jpeg" />
@endsection
@section('frontend-content')
    @php
        use Carbon\Carbon;
    @endphp
    <style>
        .breadcrumb-area {
            background-color: rgba(32, 40, 46, 0);
            background-image: url('{{ asset('frontend/images/bannerminedu1.jpg') }}');
            background-blend-mode: overlay;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }
    </style>

    <!-- Breadcrumb Area -->
    <section class="breadcrumb-area d-flex p-relative align-items-center">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-xl-12 col-lg-12">
                    <div class="breadcrumb-wrap text-left">
                        <div class="breadcrumb-title">
                            <h2>{{ $sede->sede_nombre }}</h2>
                            {{-- <p>Departamento: {{ $sede->dep_nombre }}</p> --}}
                        </div>
                    </div>
                </div>
                <div class="breadcrumb-wrap2">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $sede->sede_nombre_abre }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>

    <section class="project-detail">
        <div class="container">
            <div class="row align-items-center">
                <!-- Image Section -->
                <div class="col-lg-6 col-md-6 col-sm-12 mb-4">
                    <img
                        src="{{ asset('storage/sede_imagen/' . $sede->sede_imagen) }}"
                        alt="Imagen de {{ $sede->sede_nombre }}"
                        class="img-fluid rounded shadow">
                </div>

                <!-- Information Section -->
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <h2 class="mb-3">{{ $sede->dep_nombre }} - {{ $sede->sede_nombre }}</h2>
                    <p class="text-justify">{!! $sede->sede_descripcion !!}</p>

                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <strong>Horario:</strong> {{ $sede->sede_horario }}
                        </li>
                        <li class="list-group-item">
                            <strong>Turno:</strong> {{ $sede->sede_turno }}
                        </li>
                        <li class="list-group-item">
                            <strong>Contacto 1:</strong> <a href="tel:{{ $sede->sede_contacto_1 }}">{{ $sede->sede_contacto_1 }}</a>
                        </li>
                        @if($sede->sede_contacto_2)
                            <li class="list-group-item">
                                <strong>Contacto 2:</strong> <a href="tel:{{ $sede->sede_contacto_2 }}">{{ $sede->sede_contacto_2 }}</a>
                            </li>
                        @endif
                        @if($sede->sede_facebook)
                            <li class="list-group-item">
                                <strong>Facebook:</strong>
                                <a href="{{ $sede->sede_facebook }}" target="_blank" rel="noopener noreferrer">
                                    Visitar página de Facebook
                                </a>
                            </li>
                        @endif
                        @if($sede->sede_tiktok)
                            <li class="list-group-item">
                                <strong>TikTok:</strong>
                                <a href="{{ $sede->sede_tiktok }}" target="_blank" rel="noopener noreferrer">
                                    Visitar perfil de TikTok
                                </a>
                            </li>
                        @endif
                        @if($sede->sede_grupo_whatssap)
                            <li class="list-group-item">
                                <strong>Grupo de WhatsApp:</strong>
                                <a href="{{ $sede->sede_grupo_whatssap }}" target="_blank" rel="noopener noreferrer">
                                    Unirse al grupo
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>

            <!-- Ubicación Section -->
            <div class="row mt-5">
                <div class="col-12">
                    <h3 class="mb-3">Ubicación</h3>
                    <div class="embed-responsive embed-responsive-16by9 shadow rounded">
                        {!! $sede->sede_ubicacion !!}
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Gallery Area -->
    <section id="portfolio" class="pt-0 pb-5">
        <div class="container">
            <div class="portfolio">
                <div class="row align-items-end mb-50">
                    <div class="col-lg-12">
                        <div class="my-masonry text-center">
                            <div class="button-group filter-button-group">
                                <button class="active" data-filter="*">Ver Todos</button>
                                @foreach($galeriasPorPrograma as $pro_id => $galerias)
                                    <button data-filter=".{{ $pro_id }}">{{ $galerias->first()->pro_nombre_abre }}</button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid col3">
                    @foreach($galeriasPorPrograma as $galerias)
                        @foreach($galerias as $galeria)
                            <div class="grid-item {{ $galeria->pro_id }}">
                                <a href="{{ asset('storage/galeria/' . $galeria->galeria_imagen) }}" class="image-popup">
                                    <figure class="gallery-image">
                                        <img src="{{ asset('storage/galeria/' . $galeria->galeria_imagen) }}" alt="{{ $galeria->pro_nombre_abre }}" />
                                        <figcaption>
                                            <h4>{{ $galeria->pro_nombre_abre }}</h4>
                                        </figcaption>
                                    </figure>
                                </a>
                            </div>
                        @endforeach
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.image-popup').magnificPopup({
                type: 'image',
                gallery: {
                    enabled: true
                }
            });
        });
    </script>
@endsection
