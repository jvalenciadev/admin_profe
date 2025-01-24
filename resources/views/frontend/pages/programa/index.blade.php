@extends('frontend.layouts.master')
@section('title')
    Ofertas Académicas
@endsection
@section('frontend-content')
    <style id="qeducato_data-dynamic-css" title="dynamic-css" class="redux-options-output">
        .breadcrumb-area {
            background-color: rgba(32, 40, 46, 0);
            background-image: url('{{ asset('frontend/images/bannerminedu1.jpg') }}');
            background-blend-mode: overlay;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        /* Estilo para los títulos del tipo */
        .tipo-section {
            margin-bottom: 40px;
        }

        .tipo-titulo {
            font-size: 28px;
            font-weight: bold;
            color: #0056b3; /* Azul llamativo */
            text-transform: uppercase;
            margin-bottom: 20px;
            border-left: 5px solid #0056b3; /* Línea decorativa */
            padding-left: 15px;
            background: #f9f9f9; /* Fondo claro */
            padding: 10px 15px;
        }

        /* Opcional: animación para los títulos */
        .tipo-titulo:hover {
            color: #002f6c; /* Color más oscuro al pasar el mouse */
            text-decoration: underline;
        }

        /* Ajustes a los cards de los programas */
        .courses-item {
            background: #fff;
            border: 1px solid #e6e6e6;
            border-radius: 8px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .courses-item:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .courses-content {
            text-align: center;
            padding: 15px;
        }

        .courses-content h3 {
            font-size: 20px;
            margin: 10px 0;
            color: #333;
        }

        .courses-content .cat {
            font-size: 14px;
        }

        .readmore {
            display: inline-block;
            margin-top: 10px;
            font-size: 14px;
            color: #0056b3;
            font-weight: bold;
            text-transform: uppercase;
        }

        .readmore:hover {
            color: #002f6c;
            text-decoration: underline;
        }
    </style>

    <section class="breadcrumb-area d-flex align-items-center">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-xl-12">
                    <div class="breadcrumb-wrap">
                        <div class="breadcrumb-title">
                            <h2>Ofertas Académicas</h2>
                        </div>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Ofertas Académicas</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <article id="post-78" class="post-78 page type-page status-publish hentry">
        <div class="pages-content">
            <div class="elementor elementor-78">
                <section class="elementor-section elementor-top-section">
                    <div class="elementor-container elementor-column-gap-no">
                        <div class="elementor-column elementor-top-column">
                            <div class="elementor-widget-wrap">
                                <section class="shop-area pt-120 pb-90 p-relative">
                                    <div class="container">
                                        @if(count($programas) > 0)
                                            @foreach ($programas as $tipo => $progs)
                                                <div class="tipo-section">
                                                    <h2 class="tipo-titulo">{{ $tipo }}</h2>
                                                    <div class="row align-items-center">
                                                        @foreach ($progs as $pro)
                                                            <div class="col-lg-4 col-md-6">
                                                                <div class="courses-item mb-30 hover-zoomin">
                                                                    <div class="thumb fix">
                                                                        <a href="{{ route('programa.show', $pro->pro_id) }}">
                                                                            <img decoding="async" width="1180" height="664"
                                                                                src="{{ asset('storage/programa_afiches/' . $pro->pro_afiche) }}"
                                                                                class="attachment-post-thumbnail size-post-thumbnail wp-post-image"
                                                                                alt="{{ $pro->pro_nombre }}" />
                                                                        </a>
                                                                    </div>
                                                                    <div class="courses-content">
                                                                        <div class="cat">
                                                                            <i class="fal fa-graduation-cap"></i>
                                                                            {{ $pro->pro_nombre_abre }}
                                                                        </div>
                                                                        <h3>
                                                                            <a href="{{ route('programa.show', $pro->pro_id) }}">
                                                                                {{ $pro->pro_nombre }}
                                                                            </a>
                                                                        </h3>
                                                                        <a href="{{ route('programa.show', $pro->pro_id) }}" class="readmore">
                                                                            Leer más <i class="fal fa-long-arrow-right"></i>
                                                                        </a>
                                                                    </div>
                                                                    <div class="icon"></div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <h4>
                                                No hay ofertas académicas
                                            </h4>
                                        @endif
                                    </div>
                                </section>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </article>
@endsection
@section('scripts')
    <script defer type="text/javascript" src="{{ asset('backend/assets/js/sweetalert2.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
           @if(session('error'))
                   Swal.fire({
                       icon: 'error',
                       title: '¡Error!',
                       text: '{{ session('error') }}', // Muestra el mensaje del error
                       confirmButtonText: 'Entendido'
                   });
           @endif
       });
        document.addEventListener('DOMContentLoaded', function () {
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: '{{ session('success') }}',
                    confirmButtonText: 'Aceptar'
                });
            @endif
        });
    </script>
@endsection
