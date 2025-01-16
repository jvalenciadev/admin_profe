@extends('errors.errors_layout')

@section('title')
    403 - Acceso denegado
@endsection

@section('error-content')
    <style>
        .btn-custom-primary {
            background-color: #ff7700;
            color: white;
            border-radius: 8px;
            padding: 12px 24px;
        }

        .btn-custom-secondary {
            background-color: #12cc24;
            color: white;
            border-radius: 8px;
            padding: 12px 24px;
        }

        .btn-custom-primary:hover, .btn-custom-secondary:hover {
            opacity: 0.9;
        }
        .button-group {
            gap: 20px; /* Ajusta el valor del espacio entre los botones */
        }
        h2 {
            font-size: 48px;
        }

        p {
            font-size: 18px;
        }
    </style>

    <h2>403</h2>
    <p>Se deniega el acceso a este recurso en el servidor</p>
    <hr>
    <p class="mt-2">
        {{ $exception->getMessage() }}
    </p>
    <div class="mt-4 d-flex justify-content-center button-group">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-lg btn-custom-primary me-4 shadow-lg">
            Regresar al panel de control
        </a>
        <a href="{{ route('admin.login') }}" class="btn btn-lg btn-custom-secondary shadow-lg">
            ¡Iniciar sesión nuevamente!
        </a>
    </div>    
@endsection