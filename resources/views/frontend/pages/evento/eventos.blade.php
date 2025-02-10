@extends('frontend.layouts.master')
@section('title')
    Eventos - Profe
@endsection


@section('frontend-content')
    @php
        use Carbon\Carbon;
    @endphp
    <style id="qeducato_data-dynamic-css" title="dynamic-css" class="redux-options-output">
        .breadcrumb-area {
            background-color: rgba(32, 40, 46, 0);
            /* Fondo con un poco de transparencia */
            background-image: url('{{ asset('frontend/images/eventos.jpg') }}');
            background-blend-mode: overlay;
            /* Mezcla el color de fondo y la imagen */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            /* Añade sombra */
        }
    </style>

    <section class="breadcrumb-area d-flex  p-relative align-items-center">
        <div class="container">
            <div class="row align-items-center">
                {{-- <div class="col-xl-12 col-lg-12">
                    <div class="breadcrumb-wrap text-left">
                        <div class="breadcrumb-title">
                            <h2>Nuestros eventos</h2>
                        </div>
                    </div>
                </div> --}}
                <div class="breadcrumb-wrap2">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Eventos</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section> <!-- breadcrumb-area-end -->
    <article id="post-100" class="post-100 page type-page status-publish hentry">
        <div class="pages-content">
            <div data-elementor-type="wp-page" data-elementor-id="100" class="elementor elementor-100">
                <section
                    class="elementor-section elementor-top-section elementor-element elementor-element-e52485b elementor-section-full_width elementor-section-height-default elementor-section-height-default"
                    data-id="e52485b" data-element_type="section">
                    <div class="elementor-container elementor-column-gap-no">
                        <div class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-c511688"
                            data-id="c511688" data-element_type="column">
                            <div class="elementor-widget-wrap elementor-element-populated">
                                <div class="elementor-element elementor-element-f75cd75 elementor-widget elementor-widget-Elementor-events-widget"
                                    data-id="f75cd75" data-element_type="widget"
                                    data-widget_type="Elementor-events-widget.default">
                                    <div class="elementor-widget-container"> <!-- event-area -->
                                        <section class="event pt-120 pb-90 p-relative fix">
                                            <div class="animations-06"><img decoding="async"
                                                    src="{{ asset('frontend/wp-content/uploads/2023/03/an-img-06.png') }}"
                                                    alt="an-img-06.png">
                                            </div>
                                            <div class="animations-07"><img decoding="async"
                                                    src="{{ asset('frontend/wp-content/uploads/2023/03/an-img-07.png') }}"
                                                    alt="an-img-07.png">
                                            </div>
                                            <div class="animations-08"><img decoding="async"
                                                    src="{{ asset('frontend/wp-content/uploads/2023/03/an-img-08.png') }}"
                                                    alt="an-img-08.png">
                                            </div>
                                            <div class="animations-09"><img decoding="async"
                                                    src="{{ asset('frontend/wp-content/uploads/2023/03/an-img-09.png') }}"
                                                    alt="an-img-09.png">
                                            </div>
                                            <div class="container">
                                                <div class="row">
                                                    @foreach ($eventos as $eve)
                                                        <div class="col-lg-4 col-md-6  wow fadeInUp animated"
                                                            data-animation="fadeInUp" data-delay=".4s">
                                                            <div class="event-item mb-30 hover-zoomin">
                                                                <div class="thumb">
                                                                    <a href="{{ route('eventoDetalle', $eve->eve_id) }}">
                                                                        <img fetchpriority="high" decoding="async"
                                                                            width="1180" height="787"
                                                                            src="{{ asset('storage/evento_afiches/' . $eve->eve_afiche) }}"
                                                                            class="attachment-post-thumbnail size-post-thumbnail wp-post-image"
                                                                            alt="" />
                                                                    </a>
                                                                </div>
                                                                <div class="event-content">
                                                                    <div class="date">
                                                                        <strong>{{ Carbon::parse($eve->eve_fecha)->translatedFormat('d') }}</strong>
                                                                        {{ Carbon::parse($eve->eve_fecha)->translatedFormat('F, Y') }}
                                                                    </div>
                                                                    <h3>
                                                                        <a
                                                                            href="{{ route('eventoDetalle', $eve->eve_id) }}">
                                                                            {{ $eve->eve_nombre }}
                                                                        </a>
                                                                    </h3>
                                                                    <p>
                                                                        {!! Str::words($eve->eve_descripcion, 20, '...') !!}
                                                                    </p>
                                                                    <div class="time">
                                                                        {{ Carbon::parse($eve->eve_fecha)->translatedFormat('d F, Y') }}
                                                                        <i class="fal fa-long-arrow-right"></i>
                                                                        <strong>{{ $eve->tipoEvento->et_nombre }}</strong>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach

                                                </div>
                                                {{-- <div class="container">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="pagination-wrapper d-flex justify-content-center">
                                                                {{ $eventos->links() }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> --}}
                                                <!-- Paginación adaptada al formato de tu template -->
                                                <div class="col-sm-12 col-md-12">
                                                    <div class="row">
                                                        <div class="pagination-wrap mb-50">
                                                            <ul class="pagination">
                                                                <!-- Generar enlaces de paginación dinámicamente -->
                                                                @if ($eventos->onFirstPage())
                                                                    <li class="disabled"><span>&laquo;</span></li>
                                                                @else
                                                                    <li><a href="{{ $eventos->previousPageUrl() }}"
                                                                            rel="prev">&laquo;</a></li>
                                                                @endif

                                                                @foreach ($eventos->getUrlRange(1, $eventos->lastPage()) as $page => $url)
                                                                    @if ($page == $eventos->currentPage())
                                                                        <li class="active"><a
                                                                                href="#">{{ $page }}</a></li>
                                                                    @else
                                                                        <li><a
                                                                                href="{{ $url }}">{{ $page }}</a>
                                                                        </li>
                                                                    @endif
                                                                @endforeach

                                                                @if ($eventos->hasMorePages())
                                                                    <li><a href="{{ $eventos->nextPageUrl() }}"
                                                                            rel="next">&raquo;</a></li>
                                                                @else
                                                                    <li class="disabled"><span>&raquo;</span></li>
                                                                @endif
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>

                                        </section> <!-- courses-area -->
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
