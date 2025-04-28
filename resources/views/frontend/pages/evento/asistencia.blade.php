@extends('frontend.layouts.master')

@section('title')
    {{ $evento->eve_nombre }}
@endsection
@php
    $usr = Auth::guard('map_persona')->check();
    $is_inscrito = session('per_ci');
@endphp
@section('og-meta-tags')
    <meta property="og:locale" content="es_ES" />
    <meta property="og:title" content="REGISTRA SU ASISTENCIA" />
    <meta property="og:image" content="{{ asset('storage/evento_afiches/' . $evento->eve_afiche) }}" />
    <meta property="og:image:width" content="545" />
    <meta property="og:image:height" content="493" />
    <meta property="og:image:type" content="image/jpeg" />
@endsection

@section('styles')
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-container {
            background-color: #f8f9fa;
            /* Fondo más claro */
            padding: 30px;
            /* Mayor padding */
            border-radius: 10px;
            /* Bordes redondeados */
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            /* Ancho máximo */
            margin: 20px auto;
            /* Centrar el contenedor */
            border: 1px solid #1474a6;
            /* Borde azul */
        }

        .form-title {
            text-align: center;
            font-size: 2.5em;
            font-weight: bold;
            color: #1474a6;
            /* Color del título */
            margin-bottom: 20px;
        }

        .form-control {
            border: 1px solid #ced4da;
            /* Borde gris */
            border-radius: 5px;
            /* Bordes redondeados */
            transition: border-color 0.3s;
            /* Transición suave */
        }

        .form-control:focus {
            border-color: #1474a6;
            /* Borde azul en enfoque */
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
            /* Sombra azul */
        }

        .submit-btn {
            width: 100%;
            padding: 12px;
            background-color: #0056b3;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 1.2em;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: 20px;
            /* Espaciado superior */
        }

        .submit-btn:hover {
            background-color: #0056b3;
            /* Color en hover */
        }

        .error-message {
            color: #dc3545;
            /* Color rojo para mensajes de error */
            margin-top: 5px;
            /* Espaciado superior */
        }

        .captcha-container {
            text-align: center;
            /* Centrar el captcha */
            margin: 20px 0;
            /* Espaciado vertical */
        }

        .captcha img {
            margin-bottom: 10px;
            /* Espaciado inferior para la imagen del captcha */
        }

        .captcha button {
            margin-left: 5px;
            /* Espaciado entre el botón y la imagen */
        }

        .red {
            background-color: #dc3545;
            /* Color rojo para el botón */
        }
        .alert {
            margin-top: 10px; /* Espacio superior para separar de otros elementos */
            padding: 15px; /* Espaciado interno */
            border-radius: 5px; /* Bordes redondeados */
        }
        .alert-danger {
            color: #721c24; /* Color de texto para error */
            background-color: #f8d7da; /* Fondo para error */
            border-color: #f5c6cb; /* Borde para error */
            font-size: 18px;
        }
    </style>
@endsection

@section('frontend-content')
    <div id="content" class="site-content">
        <section>
            <div class="lower-content2">
                <div class="form-container">
                    <img src="{{ asset('storage/evento_afiches/' . $evento->eve_afiche) }}" alt="Afiche del evento"
                        class="afiche-image img-fluid mb-3">
                    <h2 class="form-title">Asistencia</h2>
                    <div class="alert alert-info text-center py-3 my-3" role="alert">
                        <i class="bi bi-clock-history me-2"></i>
                        La asistencia estará habilitada <strong>24 horas</strong>.
                    </div>
                    @error('asistencia')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    <form action="{{ route('evento.storeAsistencia') }}" method="POST" enctype="multipart/form-data"
                        id="inscripcionForm">
                        @csrf
                        <div class="form-group">
                            <label for="eve_per_ci">Carnet de identidad</label>
                            <input type="text" class="form-control" name="eve_per_ci" id="eve_per_ci" autofocus
                                placeholder="Ingrese el número de carnet de identidad" pattern="[0-9]{4,10}"
                                title="Debe tener entre 4 y 10 dígitos." />
                            @error('eve_per_ci')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="eve_codigo">Código de asistencia</label>
                            <input type="text" class="form-control" name="eve_codigo" id="eve_codigo" autofocus
                                placeholder="Ingrese el código de asistencia" />
                            @error('eve_codigo')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <input type="hidden" name="eve_id" id="eve_id" value="{{ encrypt($evento->eve_id) }}">
                        <div class="captcha-container">
                            <label for="captcha" class="fw-bold d-block mb-2">Código de verificación (CAPTCHA):</label>
                            <div class="captcha">
                                <span>{!! captcha_img('mini') !!}</span>
                                <button type="button" class="btn btn-danger reload" id="reload">&#x21bb;</button>
                            </div>
                            <div class="form-group mb-2">
                                <input type="text" class="form-control" name="captcha" id="captcha"                                     placeholder="Ingrese el código que aparece en la imagen">
                                @error('captcha')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <button type="submit" class="btn submit-btn @if ($is_inscrito || $usr) red @endif">
                            @if ($is_inscrito || $usr)
                                Cerrar sesión
                            @else
                                Enviar
                            @endif
                        </button>
                    </form>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $('#reload').click(function() {
            $.ajax({
                type: 'GET',
                url: '{{ route('reload-captcha') }}',
                success: function(data) {
                    $('.captcha span').html(data.captcha);
                },
            });
        });
        // Función para cerrar sesión
        $('.submit-btn.red').click(function(e) {
            e.preventDefault(); // Prevenir el envío del formulario
            $.ajax({
                type: 'GET',
                url: '{{ route('evento.logout') }}',
                data: {
                    _token: '{{ csrf_token() }}', // Incluir el token CSRF
                },
                success: function() {
                    // Recargar la página actual sin redirigir
                    location.reload();
                },
                error: function() {
                    alert('Error al cerrar sesión. Intente nuevamente.');
                }
            });
        });
    </script>
@endsection
