@extends('frontend.layouts.master')
@section('title')
    Blogs - {{ $blog->blog_titulo }}
@endsection
@section('og-meta-tags')
    <meta property="og:locale" content="es_ES" />
    {{-- <meta property="og:type" content="article" /> --}}
    <meta property="og:title" content="{{ $blog->blog_titulo }}" />
    <meta property="og:description" content="{{ Str::limit(strip_tags($blog->blog_descripcion), 300) }}" />
    <meta property="og:image" content="{{ asset('storage/blog/' . $blog->blog_imagen) }}" />
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
            background-color: rgba(32, 40, 46, 0.671);
            background-image: url('{{ asset('storage/blog/' . $blog->blog_imagen) }}');
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
    </style>
    <section class="breadcrumb-area d-flex  p-relative align-items-center">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-xl-12 col-lg-12">
                    <div class="breadcrumb-wrap text-left">
                        <div class="breadcrumb-title">
                            <h2>{{ $blog->blog_titulo }}</h2>
                        </div>
                    </div>
                </div>
                <div class="breadcrumb-wrap2">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Novedades</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section> <!-- breadcrumb-area-end -->
    <div class="inner-blog b-details-p pt-120 pb-120 blog-deatails-box02">
        <div class="container">
            <div class="row"> <!-- .blog -->
                <div class="col-sm-12 col-md-12 col-lg-8">
                    <div class="blog-deatails-box single">
                        <article id="post-2695"
                            class="post-2695 post type-post status-publish format-standard has-post-thumbnail hentry category-high-school tag-design tag-development tag-web-design">
                            <div class="bsingle__post mb-50">
                                <div class="bsingle__post-thumb">
                                    <a href="#">
                                        <img fetchpriority="high" width="1109" height="752"
                                            src="{{ asset('storage/blog/' . $blog->blog_imagen) }}"
                                            class="attachment-qeducato-featured-large size-qeducato-featured-large wp-post-image"
                                            alt="Planting Seeds in the Hearts of Preschooler" decoding="async" />
                                    </a>
                                </div>
                                <div class="bsingle__content">
                                    <div class="meta-info">
                                        <ul>
                                            {{-- <li><i class="far fa-user"></i> By admin</li> --}}
                                            <li><i class="fal fa-calendar-alt"></i>
                                                {{ Carbon::parse($blog->update_at)->translatedFormat('F d, Y') }}</li>
                                            {{-- <li><i class="far fa-comments"></i> No Comments</li> --}}
                                        </ul>
                                    </div>
                                    <h2 class="single">{{ $blog->blog_titulo }}</h2>
                                    <div>
                                        {!! $blog->blog_descripcion !!}
                                    </div>




                                </div>
                            </div>
                        </article><!-- #post-## -->

                    </div>
                </div> <!-- #right side -->
                <div class="col-sm-12 col-md-12 col-lg-4">
                    @include('frontend.pages.blog.partials.sidebar')
                </div> <!-- #right side end -->
            </div>
        </div>
    </div>
@endsection
