@extends('frontend.layouts.master')
@section('title')
    Sedes - Profe
@endsection
@section('frontend-content')
    @php
        use Carbon\Carbon;
    @endphp
    <style id="qeducato_data-dynamic-css" title="dynamic-css" class="redux-options-output">
        .breadcrumb-area {
            background-color: rgba(32, 40, 46, 0);
            background-image: url('{{ asset('frontend/images/bannerminedu1.jpg') }}');
            background-blend-mode: overlay;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }
        .card {
            border: none;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }
        .card img {
            max-height: 200px;
            object-fit: cover;
            border-radius: 10px 10px 0 0;
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
                <div class="col-xl-12 col-lg-12">
                    <div class="breadcrumb-wrap text-left">
                        <div class="breadcrumb-title">
                            <h2>Nuestras Sedes</h2>
                        </div>
                    </div>
                </div>
                <div class="breadcrumb-wrap2">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Sedes</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section> <!-- breadcrumb-area-end -->

    <div class="inner-blog pt-120 pb-80">
        <div class="container">
            <div class="row">
                @foreach ($sedes as $sede)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card shadow-sm">
                            @if ($sede->sede_imagen)
                                <img src="{{ asset('storage/sede_imagen/' . $sede->sede_imagen) }}" class="card-img-top" alt="{{ $sede->sede_nombre }}">
                            @else
                                <img src="{{ asset('frontend/images/default-sede.jpg') }}" class="card-img-top" alt="Imagen no disponible">
                            @endif
                            <div class="card-body">
                                <h5 class="card-title">{{ $sede->dep_nombre }} - {{ $sede->sede_nombre }}</h5>
                                <p class="card-text text-muted">{{ Str::limit(strip_tags($sede->sede_descripcion), 100) }}</p>
                                <button class="btn-view-more" onclick="window.location.href='{{ route('sede.show', $sede->sede_id) }}'">Ver más</button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
