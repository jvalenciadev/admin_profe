@extends('frontend.layouts.master')
@section('title')
    Quiénes Somos - Profe
@endsection
@section('frontend-content')
    <style>
        /* General Styles */
        body {
            font-family: 'Roboto', sans-serif;
            color: #444;
        }

        .section {
            padding: 40px 20px;
            margin-bottom: 50px;
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
        }

        .section-title {
            font-size: 30px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 25px;
            text-align: center;
        }

        .text-center p {
            font-size: 16px;
            line-height: 1.8;
            margin-bottom: 15px;
        }

        /* Banner Section */
        .breadcrumb-area {
            background-color: rgba(32, 40, 46, 0);
            background-image: url('{{ asset('frontend/images/nosotros.jpg') }}');
            background-size: cover;
            background-position: center;
            background-blend-mode: overlay;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            padding: 80px 0;
            color: #fff;
        }


        .breadcrumb-title h2 {
            font-size: 40px;
            font-weight: bold;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.5);
        }

        .breadcrumb ol {
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .breadcrumb-item a {
            color: #f39c12;
            text-decoration: none;
            font-weight: 500;
        }

        /* Cards for Mission and Vision */
        .info-card {
            background: linear-gradient(135deg, #f9f9f9, #eef1f5);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: all 0.3s ease;
        }

        .info-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .info-card h4 {
            font-size: 22px;
            font-weight: bold;
            color: #34495e;
            margin-bottom: 15px;
        }

        /* Convocatoria iframe */
        .iframe-container {
            position: relative;
            padding-top: 56.25%;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
        }

        .iframe-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: 0;
        }

        /* Sedes Section */
        .sede-card {
            background: #fdfdfd;
            border: 1px solid #eaeaea;
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }

        .sede-card:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .sede-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .sede-content {
            padding: 20px;
            text-align: center;
        }

        .sede-title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #2c3e50;
        }

        .sede-description {
            font-size: 16px;
            color: #7f8c8d;
        }
        .btn-view-more {
            background-color: #25578d;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            text-transform: uppercase;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }
        .btn-view-more:hover {
            background-color: #003d82;
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
                        <li class="breadcrumb-item active" aria-current="page">Quiénes Somos</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section> <!-- breadcrumb-area-end -->

    <section class="container py-5">
        <!-- Sobre Nosotros -->
        <div class="section">
            <h3 class="section-title">Sobre Nosotros</h3>
            <p class="text-center">{!! $profe->profe_sobre_nosotros !!}</p>
        </div>

        <!-- Misión y Visión -->
        <div class="row">
            <div class="col-md-6">
                <div class="info-card">
                    <h4>Misión</h4>
                    <p>{!! $profe->profe_mision !!}</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="info-card">
                    <h4>Visión</h4>
                    <p>{!! $profe->profe_vision !!}</p>
                </div>
            </div>
        </div>

        <!-- Convocatoria -->
        <div class="section">
            <h3 class="section-title">Convocatoria</h3>
            <div class="iframe-container">
                <iframe src="{{ asset('storage/profe/' . $profe->profe_convocatoria) }}" allowfullscreen></iframe>
            </div>
        </div>

        <!-- Información de Contacto -->
        <div class="section">
            <h3 class="section-title">Información de Contacto</h3>
            <ul class="list-unstyled text-center">
                <li><strong>Correo Electrónico:</strong> {{ $profe->profe_correo }}</li>
                {{-- <li><strong>Teléfono:</strong> {{ $profe->profe_celular }}</li> --}}
                <li><strong>Página Web:</strong> <a href="{{ $profe->profe_pagina }}" target="_blank">{{ $profe->profe_pagina }}</a></li>
            </ul>
        </div>

        <!-- Ubicación -->
        <div class="section">
            <h3 class="section-title">Ubicación</h3>
            <div class="iframe-container">
                {!! $profe->profe_ubicacion !!}
            </div>
        </div>

        <!-- Sedes -->
        <div class="section">
            <h3 class="section-title">Sedes</h3>
            <div class="row">
                @foreach ($sedes as $sede)
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="sede-card">
                            <img src="{{ asset('storage/sede_imagen/' . $sede->sede_imagen) }}" class="sede-image" alt="{{ $sede->sede_nombre }}">
                            <div class="sede-content">
                                <h4 class="sede-title">{{ $sede->dep_nombre }} - {{ $sede->sede_nombre }}</h4>
                                <p class="sede-description">{!! $sede->sede_descripcion !!}</p>
                                <button class="btn-view-more" onclick="window.location.href='{{ route('sede.show', $sede->sede_id) }}'">Ver más</button>

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
