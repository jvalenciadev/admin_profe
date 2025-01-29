@extends('frontend.layouts.master')

@section('title')
    PROFE - EVENTOS
@endsection

@section('description')
    ✅ {{ $evento[0]->eve_nombre }}
@endsection

@section('styles')
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-container {
            background-color: #ffffff; /* Fondo blanco para el contenedor */
            padding: 40px; /* Mayor padding */
            border-radius: 10px; /* Bordes redondeados */
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            max-width: 700px; /* Ancho máximo */
            margin: 40px auto; /* Centrar el contenedor */
            border: 1px solid #1474a6; /* Borde azul */
        }

        .form-title {
            text-align: center;
            font-size: 2.5em;
            font-weight: bold;
            color: #1474a6; /* Color del título */
            margin-bottom: 20px;
        }

        .afiche-image {
            border-radius: 10px; /* Bordes redondeados para la imagen */
            margin-bottom: 20px; /* Espacio debajo de la imagen */
        }

        .logout-btn {
            align-items: center;
            background-color: transparent;
            color: #dc3545; /* Color del texto del botón de salir */
            border: none;
            cursor: pointer;
            font-size: 1em;
            transition: color 0.3s;
            margin-top: 20px; /* Espaciado superior */
        }

        .logout-btn:hover {
            color: #b02a2a; /* Color al pasar el ratón */
        }

        .logout-btn i {
            margin-right: 5px; /* Espacio entre el ícono y el texto */
        }

        .alert {
            margin-bottom: 20px; /* Espacio debajo de la alerta */
        }

        .btn-lg {
            font-size: 1.2rem; /* Tamaño de fuente más grande para los botones */
            padding: 10px 20px; /* Relleno para botones */
        }

    </style>
@endsection

@section('frontend-content')
    <div id="content" class="site-content">
        <section>
            <div class="form-container">
                <img src="{{ asset('storage/evento_afiches/' . $evento[0]->eve_afiche) }}" alt="Afiche del evento" class="afiche-image img-fluid">

                <!-- Mostrar mensaje de sesión -->
                @if (session('danger'))
                    <div class="alert alert-danger">
                        {{ session('danger') }}
                    </div>
                @endif

                <h2 class="form-title">¡Descargar formulario de inscripción!</h2>
                <h6 class="font-weight-normal text-center">
                    {{ $evento[0]->eve_per_nombre_1 . ' ' . $evento[0]->eve_per_apellido_1 . ' ' . $evento[0]->eve_per_apellido_2 }}
                </h6>

                <div class="text-center mt-4">
                    <button class="logout-btn" onclick="window.location.href='{{ route('evento.logout') }}'">
                        <i class="fas fa-sign-out-alt"></i> Salir
                    </button>
                </div>

                <div class="text-center mt-4">
                    <a href="{{ route('evento.comprobanteParticipantePdf', [
                        'eve_per_id' => encrypt($evento[0]->eve_per_id),
                        'eve_id' => encrypt($evento[0]->eve_id),
                    ]) }}" class="btn btn-info btn-lg">
                        Descargar
                    </a>
                    <a href="{{ route('eventoDetalle', $evento[0]->eve_id) }}" class="btn btn-dark btn-lg ml-2">
                        Volver
                    </a>
                </div>
            </div>
        </section>
    </div>
@endsection
