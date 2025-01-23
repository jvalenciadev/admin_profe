@extends('frontend.layouts.master')

@section('title')
    {{ $programa->pro_nombre }}
@endsection
@php
    $usr = Auth::guard('map_persona')->check();
    $is_inscrito = session('per_ci');
@endphp
@section('og-meta-tags')
    <meta property="og:locale" content="es_ES" />
    <meta property="og:title" content="{{ $programa->pro_nombre }}" />
    <meta property="og:description" content="Formulario de inscripción." />
    <meta property="og:image" content="{{ asset('storage/programa_afiches/' . $programa->pro_afiche) }}" />
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
            border: 1px solid #125875;
            /* Borde azul */
        }

        .form-title {
            text-align: center;
            font-size: 2.5em;
            font-weight: bold;
            color: #125875;
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
            border-color: #125875;
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
    </style>
@endsection

@section('frontend-content')
    <div id="content" class="site-content">
        <section>
            <div class="lower-content2">
                <div class="form-container">
                    <img src="{{ asset('storage/programa_afiches/' . $programa->pro_afiche) }}" alt="Afiche del programa"
                        class="afiche-image img-fluid mb-3">
                    <h2 class="form-title">Inscripciones</h2>

                    <form action="{{ route('programa.storeParticipante') }}" method="POST" enctype="multipart/form-data"
                        id="inscripcionForm">
                        @csrf
                        @error('error')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        <div class="form-group">
                            <label for="per_ci">CARNET DE IDENTIDAD:</label>
                            <input type="text" class="form-control" name="per_ci" id="per_ci" autofocus
                                placeholder="Ingrese su número de carnet de identidad" required pattern="[0-9]{4,15}"
                                title="Debe tener entre 4 y 15 dígitos." />
                            @error('per_ci')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="per_fecha_nacimiento">FECHA DE NACIMIENTO:</label>
                            <input type="date" class="form-control" name="per_fecha_nacimiento" id="per_fecha_nacimiento"
                                required min="1900-01-01" max="2010-12-31" />

                            <!-- Mensaje de error debajo del campo -->
                            <div id="error-message" class="error-message text-danger font-weight-bold"
                                style="display: none;">
                                La fecha de nacimiento debe estar entre 1900 y 2010 para que pueda inscribirse.
                            </div>
                        </div>

                        <input type="hidden" name="pro_id" id="pro_id" value="{{ encrypt($programa->pro_id) }}">

                        <div class="captcha-container">
                            <div class="captcha">
                                <span>{!! captcha_img('mini') !!}</span>
                                <button type="button" class="btn btn-danger reload" id="reload">&#x21bb;</button>
                            </div>
                            <div class="form-group mb-2">
                                <input type="text" class="form-control" name="captcha" id="captcha" required
                                    placeholder="Ingrese el código de verificación">
                                @error('captcha')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <button type="submit"
                            class="btn submit-btn @if ($is_inscrito || $usr) red @endif"
                            @if ($is_inscrito || $usr)
                                id="logout-btn"
                            @else
                                id="inscribir-btn"
                            @endif
                            data-action="@if ($is_inscrito || $usr) logout @else register @endif">
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
        document.getElementById('per_fecha_nacimiento').addEventListener('input', function() {
            const input = this;
            const minDate = new Date(input.min);
            const maxDate = new Date(input.max);
            const selectedDate = new Date(input.value);

            const errorMessage = document.getElementById('error-message');

            // Verificar si la fecha seleccionada está dentro del rango válido
            if (selectedDate < minDate || selectedDate > maxDate || isNaN(selectedDate.getTime())) {
                errorMessage.style.display = 'block';
            } else {
                errorMessage.style.display = 'none';
            }
        });
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
                url: '{{ route('programa.logout') }}',
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
    <script defer type="text/javascript" src="{{ asset('backend/assets/js/sweetalert2.js') }}"></script>
    <script>
        document.getElementById("inscribir-btn").addEventListener("click", function(e) {
            e.preventDefault(); // Prevenir el envío inmediato del formulario

            // Mostrar el primer mensaje de alerta usando SweetAlert2
            Swal.fire({
                title: '¿Está seguro de cumplir con los requisitos?',
                html: `{!! $proRestriccion->res_descripcion ?? '' !!}`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, inscribirme',
                cancelButtonText: 'No, cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Si el usuario confirma, se envía el formulario
                    document.querySelector('form').submit();
                } else {
                    // Si el usuario cancela, no se hace nada
                    return false;
                }
            });
        });
    </script>
@endsection
