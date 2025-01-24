@extends('frontend.layouts.master')

@section('title')
    {{ $programa->pro_nombre }}
@endsection

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
    <style>
        .form-container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
            max-width: 600px;
            margin: 40px auto;
            border: 1px solid #125875;
        }

        .form-title {
            text-align: center;
            font-size: 2.5em;
            font-weight: bold;
            color: #125875;
            margin-bottom: 30px;
        }

        .form-control:focus {
            border-color: #125875;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        .submit-btn {
            padding: 12px;
            background-color: #1c3f8a;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 1.2em;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: 20px;
        }

        .submit-btn:hover {
            background-color: #0056b3;
        }

        .error-message {
            color: #dc3545;
            margin-top: 5px;
            display: none;
        }

        .afiche-image {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
            font-size: 14px;
        }
        .alert-warning {
            color: #856404;
            background-color: #fff3cd;
            border-color: #ffeeba;
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
                    <h2 class="form-title">Solicitud de incripción</h2>
                    <div class="alert alert-warning" role="alert">
                        <strong>Importante:</strong> {!! $restriccion->res_descripcion ?? '' !!}
                    </div>
                    <form action="{{ route('programa.solicitarSedePost') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="sis_ci">Carnet de identidad:</label>
                            <input type="text" class="form-control" name="sis_ci" id="sis_ci" required />
                        </div>
                        <div class="form-group">
                            <label for="sis_nombre">Nombre(s):</label>
                            <input type="text" class="form-control" name="sis_nombre" id="sis_nombre" required />
                        </div>
                        <div class="form-group">
                            <label for="sis_apellido">Apellido(s):</label>
                            <input type="text" class="form-control" name="sis_apellido" id="sis_apellido" required />
                        </div>
                        <div class="form-group">
                            <label for="sis_celular">Celular:</label>
                            <input type="tel" class="form-control" name="sis_celular" id="sis_celular" required pattern="^[67]\d{7}$" />
                            <span id="error-message-cel" class="error-message">El número debe comenzar con 6 o 7 y tener 8 dígitos.</span>
                        </div>
                        <div class="form-group">
                            <label for="sis_correo">Correo electrónico:</label>
                            <input type="email" class="form-control" name="sis_correo" id="sis_correo" required />
                            <span id="error-message-correo" class="error-message">Debe ser un correo válido.</span>
                        </div>
                        <div class="form-group">
                            <label for="sis_departamento">Departamento:</label>
                            <select class="form-control" name="sis_departamento" id="sis_departamento" required>
                                <option value="">Seleccione una sede</option>
                                @foreach ($departamentos as $dep)
                                    <option value="{{ $dep->dep_nombre }}">{{ $dep->dep_nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="sis_sede">¿Dónde se llevarán a cabo las sesiones presenciales?</label>
                            <input type="text" class="form-control" name="sis_sede" id="sis_sede" required />
                        </div>
                        <div class="form-group">
                            <label for="sis_turno">¿Cuál sería el horario y día propuesto para las 6 sesiones de 5 horas presenciales?</label>
                            <textarea class="form-control" name="sis_turno" id="sis_turno" required rows="4"></textarea>
                        </div>
                        <input type="hidden" name="pro_id" value="{{ encrypt($programa->pro_id) }}">
                        <button type="submit" class="submit-btn solicitar-envio">Enviar</button>
                    </form>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('scripts')
    <script defer type="text/javascript" src="{{ asset('backend/assets/js/sweetalert2.js') }}"></script>
        <script>
            // Seleccionar todos los botones con la clase "solicitar-envio"
            document.querySelectorAll(".solicitar-envio").forEach(button => {
                button.addEventListener("click", function (e) {
                    e.preventDefault(); // Evita que el enlace se ejecute automáticamente

                    const url = this.getAttribute("href"); // Obtiene la URL del botón

                    // Mostrar mensaje de confirmación
                    Swal.fire({
                        title: 'Confirma tu solicitud',
                        html: `
                            <p>Estamos encantados de que desees habilitar este curso en tu sede.</p>
                            <p>Para hacerlo posible, necesitamos contar con al menos <b>30 participantes</b> inscritos.</p>
                            <p>¿Deseas enviar la solicitud?</p>
                        `,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, enviar solicitud',
                        cancelButtonText: 'No, cancelar',
                        reverseButtons: true,
                        customClass: {
                            confirmButton: 'btn-confirmar', // Clase personalizada para el botón confirmar
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.querySelector('form').submit(); // Esto enviará el formulario
                        }
                    });
                });
            });
        </script>
    <script>
        document.getElementById('per_correo').addEventListener('input', function() {
            const patternCorreo = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            const errorMessageCorreo = document.getElementById('error-message-correo');
            errorMessageCorreo.style.display = patternCorreo.test(this.value) ? 'none' : 'block';
        });

        document.getElementById('per_celular').addEventListener('input', function() {
            const patternCel = /^[67]\d{7}$/;
            const errorMessageCel = document.getElementById('error-message-cel');
            errorMessageCel.style.display = patternCel.test(this.value) ? 'none' : 'block';
        });
    </script>
@endsection
