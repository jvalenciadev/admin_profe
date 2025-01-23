@extends('frontend.layouts.master')
@section('title')
    Ofertas Académicas
@endsection
@section('frontend-content')
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
                            <h2>Ofertas Académicas</h2>
                        </div>
                    </div>
                </div>
                <div class="breadcrumb-wrap2">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Programas</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>

    <article id="post-78" class="post-78 page type-page status-publish hentry">
        <div class="pages-content">
            <div data-elementor-type="wp-page" data-elementor-id="78" class="elementor elementor-78">
                <section
                    class="elementor-section elementor-top-section elementor-element elementor-element-ce034c9 elementor-section-full_width elementor-section-height-default elementor-section-height-default"
                    data-id="ce034c9" data-element_type="section">
                    <div class="elementor-container elementor-column-gap-no">
                        <div class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-c20593e"
                            data-id="c20593e" data-element_type="column">
                            <div class="elementor-widget-wrap elementor-element-populated">
                                <div class="elementor-element elementor-element-1e2c1f4 elementor-widget elementor-widget-Elementor-courses-widget"
                                    data-id="1e2c1f4" data-element_type="widget"
                                    data-widget_type="Elementor-courses-widget.default">
                                    <div class="elementor-widget-container"> <!-- courses-area -->
                                        <section class="shop-area pt-120 pb-90 p-relative "
                                            data-animation="fadeInUp animated" data-delay=".2s">
                                            <div class="container">
                                                <div class="row align-items-center">
                                                    @foreach ($programas as $pro)
                                                        <div class="col-lg-4 col-md-6 ">
                                                            <div class="courses-item mb-30 hover-zoomin">
                                                                <div class="thumb fix">
                                                                    <a href="{{ route('programa.show', $pro->pro_id) }}">
                                                                        <img decoding="async" width="1180" height="664"
                                                                            src="{{ asset('storage/programa_afiches/' . $pro->pro_afiche) }}"
                                                                            class="attachment-post-thumbnail size-post-thumbnail wp-post-image"
                                                                            alt="" />
                                                                    </a>
                                                                </div>
                                                                <div class="courses-content">
                                                                    <div class="cat">
                                                                        <i class="fal fa-graduation-cap"></i>
                                                                        {{ $pro->pro_nombre_abre }}
                                                                    </div>
                                                                    <h3>
                                                                        <a
                                                                            href="{{ route('programa.show', $pro->pro_id) }}">
                                                                            {{ $pro->pro_nombre }}
                                                                        </a>
                                                                    </h3>
                                                                    {{-- <p>
                                                                        {!! Str::words($pro->pro_contenido, 20, '...') !!}
                                                                    </p> --}}
                                                                    <a href="{{ route('programa.show', $pro->pro_id) }}"
                                                                        class="readmore">Leer mas
                                                                        <i class="fal fa-long-arrow-right"></i>
                                                                    </a>
                                                                </div>
                                                                <div class="icon"></div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </section> <!-- courses-area-end -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div><!-- .entry-content -->
    </article>
@endsection
