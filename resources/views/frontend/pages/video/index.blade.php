@extends('frontend.layouts.master')
@section('title')
    Videos - Profe
@endsection
@section('frontend-content')
    <style>
        .video-container {
            margin-bottom: 30px;
        }

        .video-title {
            font-weight: bold;
            text-align: center;
            margin-top: 15px;
        }

        .video-iframe {
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .video-section {
            margin-bottom: 50px;
        }

        .video-section-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
            color: #333;
        }
    </style>
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
    <section class="breadcrumb-area d-flex p-relative align-items-center">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-xl-12 col-lg-12">
                    <div class="breadcrumb-wrap text-left">
                        <div class="breadcrumb-title">
                            <h2>Galería de Videos</h2>
                        </div>
                    </div>
                </div>
                <div class="breadcrumb-wrap2">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Videos</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>

    <section class="container py-5">
        @php
            $groupedVideos = $videos->groupBy('video_tipo');
        @endphp

        {{-- Sección para YouTube --}}
        @if ($groupedVideos->has('YOUTUBE'))
            <div class="video-section">
                <h3 class="video-section-title">Videos de YouTube</h3>
                <div class="row">
                    @foreach ($groupedVideos['YOUTUBE'] as $video)
                        <div class="col-lg-4 col-md-6 col-sm-12 video-container">
                            <div class="video-iframe embed-responsive embed-responsive-16by9">
                                {!! $video->video_iframe !!}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Sección para Facebook --}}
        @if ($groupedVideos->has('FACEBOOK'))
            <div class="video-section">
                <h3 class="video-section-title">Videos de Facebook</h3>
                <div class="row">
                    @foreach ($groupedVideos['FACEBOOK'] as $video)
                        <div class="col-lg-4 col-md-6 col-sm-12 video-container">
                            <div class="video-iframe embed-responsive embed-responsive-16by9">
                                {!! $video->video_iframe !!}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Sección para TikTok --}}
        @if ($groupedVideos->has('TIKTOK'))
            <div class="video-section">
                <h3 class="video-section-title">Videos de TikTok</h3>
                <div class="row">
                    @foreach ($groupedVideos['TIKTOK'] as $video)
                        <div class="col-lg-4 col-md-6 col-sm-12 video-container">
                            <div class="video-iframe embed-responsive embed-responsive-16by9">
                                {!! $video->video_iframe !!}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Si no hay videos disponibles --}}
        @if ($videos->isEmpty())
            <div class="text-center">
                <p>No hay videos disponibles en este momento.</p>
            </div>
        @endif
    </section>
@endsection
