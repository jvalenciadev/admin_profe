@extends('frontend.layouts.master')
@section('title')
    Galeria - Profe
@endsection
@section('frontend-content')
    @php
        use Carbon\Carbon;
    @endphp
    <style id="qeducato_data-dynamic-css" title="dynamic-css" class="redux-options-output">
        .breadcrumb-area {
            background-color: rgba(32, 40, 46, 0);
            /* Fondo con un poco de transparencia */
            background-image: url('{{ asset('frontend/images/bannerminedu1.jpg') }}');
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
                            <h2>Nuestras galeria</h2>
                        </div>
                    </div>
                </div>
                <div class="breadcrumb-wrap2">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Galeria</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section> <!-- breadcrumb-area-end -->
    <!-- breadcrumb-area-end -->
    <article id="post-102" class="post-102 page type-page status-publish hentry">
        <div class="pages-content">
            <div data-elementor-type="wp-page" data-elementor-id="102" class="elementor elementor-102">
                <section
                    class="elementor-section elementor-top-section elementor-element elementor-section-full_width elementor-section-height-default"
                    data-id="eec9401" data-element_type="section">
                    <div class="elementor-container elementor-column-gap-no">
                        <div class="elementor-column elementor-col-100 elementor-top-column elementor-element"
                            data-id="92eb996" data-element_type="column">
                            <div class="elementor-widget-wrap elementor-element-populated">
                                <div class="elementor-widget-container">
                                    <!-- gallery-area -->
                                    <section id="portfolio" class="pt-120 pb-90">
                                        <div class="container">
                                            <div class="portfolio">
                                                <div class="row align-items-end mb-50">
                                                    <div class="col-lg-12">
                                                        <div class="my-masonry text-center wow fadeInDown animated"
                                                            data-animation="fadeInDown" data-delay=".4s">
                                                            <div class="button-group filter-button-group">
                                                                <button class="active" data-filter="*">Ver Todos</button>
                                                                @foreach ($galeriasPorPrograma as $pro_id => $galerias)
                                                                    <!-- Cambia esto -->
                                                                    <button
                                                                        data-filter=".{{ $pro_id }}">{{ $galerias->first()->pro_nombre_abre }}</button>
                                                                    <!-- Cambia esto -->
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="grid col3 wow fadeInUp animated" data-animation="fadeInUp"
                                                    data-delay=".4s">
                                                    @foreach ($galeriasPorPrograma as $galerias)
                                                        @foreach ($galerias as $galeria)
                                                            <div class="grid-item {{ $galeria->pro_id }}">
                                                                <a href="{{ asset('storage/galeria/' . $galeria->galeria_imagen) }}"
                                                                    class="image-popup">
                                                                    <figure class="gallery-image">
                                                                        <img fetchpriority="high" decoding="async"
                                                                            src="{{ asset('storage/galeria/' . $galeria->galeria_imagen) }}"
                                                                            class="attachment-post-thumbnail size-post-thumbnail wp-post-image"
                                                                            alt="" />
                                                                        <figcaption>
                                                                            <h4>{{ $galeria->dep_abreviacion }} - {{ $galeria->sede_nombre_abre }}</h4>
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

                                    <!-- gallery-area-end -->
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </article>


@section('scripts')
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
<!-- #post-2655 -->
@endsection
