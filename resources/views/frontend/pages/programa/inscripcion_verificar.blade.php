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
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
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
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .warning-message {
            color: #856404;
            background-color: #fff3cd;
            border-color: #ffeeba;
            padding: 10px;
            border-radius: 5px;
            margin-top: 5px;
        }

        .afiche-image {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        input:invalid {
            border-color: rgba(240, 5, 5, 0.338);
        }

        input:required:invalid {
            background-color: #ffcccc44;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
@endsection

@section('frontend-content')
    <div id="content" class="site-content">
        <section>
            <div class="lower-content2">
                <div class="form-container">
                    <img src="{{ asset('storage/programa_afiches/' . $programa->pro_afiche) }}" alt="Afiche del programa"
                        class="afiche-image img-fluid mb-3">
                    <h2 class="form-title">Inscripciones</h2>
                    <div class="text-right mt-4">
                        <button class="logout-btn" onclick="window.location.href='{{ route('programa.logout') }}'">
                            <i class="fas fa-sign-out-alt"></i> SALIR
                        </button>
                    </div>
                    @if ($errors->has('per_ci'))
                        <span class="text-danger">{{ $errors->first('per_ci') }}</span>
                    @endif
                    <form action="{{ route('programa.postInscribirse') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="form-row">
                            <div class="form-group col-md-9">
                                <label for="per_ci" class="col-form-label">Carnet de identidad</label>
                                <input type="text" class="form-control" name="per_ci" id="per_ci"
                                    value="{{ isset($user->per_ci) ? $user->per_ci : session('per_ci') }}"
                                    placeholder="Ingrese el número de carnet de identidad" readonly />
                            </div>

                            <div class="form-group col-md-3">
                                <label for="per_complemento" class="col-form-label">Complemento</label>
                                <input type="text" class="form-control" name="per_complemento" id="per_complemento"
                                    value="{{ isset($user->per_complemento) ? $user->per_complemento : session('per_complemento') }}"
                                    maxlength="5" disabled />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-7">
                                <label for="per_fecha_nacimiento">Fecha de nacimiento</label>
                                <input type="date" class="form-control" name="per_fecha_nacimiento"
                                    id="per_fecha_nacimiento" required
                                    value="{{ isset($user->per_fecha_nacimiento) ? $user->per_fecha_nacimiento : session('per_fecha_nacimiento') }}"
                                    min="1900-01-01" max="2010-12-31" readonly />
                            </div>

                            <div class="form-group col-md-5">
                                <label for="gen_id">Sexo</label>
                                <select class="form-control" disabled>
                                    @foreach ($generos as $gen)
                                        <option value="{{ encrypt($gen->gen_id) }}"
                                            {{ (isset($user->gen_id) && $user->gen_id == $gen->gen_id) || session('gen_id') == $gen->gen_id ? 'selected' : '' }}>
                                            {{ $gen->gen_nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="per_nombre_1">Nombres:</label>
                            <input type="text" class="form-control" name="per_nombre_1" id="per_nombre_1"
                                onkeyup="mayusculas(this);"
                                value="{{ isset($user) ? $user->per_nombre1 . ' ' . $user->per_nombre2 : session('per_nombre1') }}"
                                maxlength="150" disabled />
                        </div>

                        <div class="form-group">
                            <label for="per_apellido_1">Apellido paterno:</label>
                            <input type="text" class="form-control" name="per_apellido_1" id="per_apellido_1"
                                value="{{ isset($user->per_apellido1) ? $user->per_apellido1 : session('per_apellido1', '') }}"
                                onkeyup="mayusculas(this);" maxlength="100" disabled />
                        </div>

                        <div class="form-group">
                            <label for="per_apellido_2">Apellido materno:</label>
                            <input type="text" class="form-control" name="per_apellido_2" id="per_apellido_2"
                                value="{{ isset($user->per_apellido2) ? $user->per_apellido2 : session('per_apellido2', '') }}"
                                onkeyup="mayusculas(this);" maxlength="100" disabled />
                        </div>
                        <div class="form-group">
                            <label for="per_celular">Celular:</label>
                            <input type="tel" class="form-control" name="per_celular" id="per_celular"
                                value="{{ isset($user->per_celular) && $user->per_celular != '0' && $user->per_celular != null ? $user->per_celular : '' }}"
                                required pattern="^6[0-9]{7}|7[0-9]{7}$" />
                        </div>
                        <div id="error-message-cel" class="error-message text-danger font-weight-bold"
                            style="display: none;">
                            El número de celular debe comenzar con 6 o 7 y tener 8 dígitos.
                        </div>
                        <div class="form-group">
                            <label for="correo">Correo electrónico:</label>
                            <input type="email" class="form-control" name="per_correo" id="per_correo"
                                value="{{ isset($user->per_correo) && $user->per_correo != 'sincorreo' && $user->per_correo != null ? $user->per_correo : '' }}"
                                required onkeyup="minusculas(this);" maxlength="100" />
                        </div>
                        <div id="error-message-correo" class="error-message text-danger font-weight-bold"
                            style="display: none;">
                            El correo electrónico debe ser válido (ejemplo@dominio.com).
                        </div>
                        @if($programa->pro_tip_id != 2)
                        <div class="form-group">
                            <label for="pi_licenciatura">Licenciatura:</label>
                            <input type="text" class="form-control" name="pi_licenciatura" id="pi_licenciatura"
                                required onkeyup="mayusculas(this);" maxlength="255" title="Este campo es obligatorio" />
                        </div>
                        <!-- Título Separador -->
                        <h5 class="mt-4">Lugar de Trabajo</h5>
                        <hr>
                        <div class="form-group">
                            <label for="pi_unidad_educativa">Institución:</label>
                            <input type="text" class="form-control" name="pi_unidad_educativa"
                                id="pi_unidad_educativa" required onkeyup="mayusculas(this);" maxlength="255" />
                        </div>
                        <div class="form-group">
                            <label for="pi_materia">Cargo Actual:</label>
                            <input type="text" class="form-control" name="pi_materia" id="pi_materia" required
                                onkeyup="mayusculas(this);" maxlength="255" />
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="pi_nivel">Nivel:</label>
                                <select class="form-control" name="pi_nivel" id="pi_nivel" required>>
                                    <option value="">Seleccione una opción</option>
                                    @foreach ($nivel as $niv)
                                        <option value="{{ $niv->niv_nombre }}"
                                            @if ($niv->niv_id == 1) selected @endif>
                                            {{ $niv->niv_id == 1 ? 'Ninguno' : $niv->niv_nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="pi_subnivel">Subsistema:</label>
                                <select class="form-control" name="pi_subsistema" id="pi_subsistema" required>>
                                    @foreach ($subsistema as $sub)
                                        <option value="{{ $sub->sub_nombre }}"
                                            @if ($sub->sub_id == 1) selected @endif>
                                            {{ $sub->sub_id == 1 ? 'Ninguno' : $sub->sub_nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endif
                        <!-- Título para otra sección -->
                        <h5 class="mt-4">Sede y Turno Solicitado</h5>
                        <hr> <!-- Línea separadora opcional -->
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="dep_id">Departamento:</label>
                                <select class="form-control" name="dep_id" id="dep_id" required>
                                    <option value="">Seleccione</option>
                                    @foreach ($departamentos as $dep)
                                        <option value="{{ $dep->dep_id }}">{{ $dep->dep_nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="sede_id">Sede:</label>
                                <select class="form-control" name="sede_id" id="sede_id" required disabled>
                                    <option value="">Elija un departamento primero</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="pro_tur_id">Turno:</label>
                            <select class="form-control" name="pro_tur_id" id="pro_tur_id" required disabled>
                                <option value="">Elija una sede primero</option>
                            </select>
                        </div>
                        <h5 class="mt-4">Deposito Bancario</h5>
                        <hr>
                        <div class="form-group">
                            <label for="pro_bau_nro_deposito">Nro de Deposito:</label>
                            <input type="text" class="form-control" name="pro_bau_nro_deposito"
                                id="pro_bau_nro_deposito" required maxlength="100" />
                        </div>
                        @if($programa->pro_tip_id != 2)
                            <div class="form-group">
                                <label for="pro_bau_monto">Monto:</label>
                                <select class="form-control" name="pro_bau_monto" id="pro_bau_monto" required>
                                    <option value="300">300</option>
                                    <option value="1500">{{$programa->pro_costo}}</option>
                                </select>
                            </div>
                        @else
                            <div class="form-group">
                                <label for="pro_bau_monto">Monto:</label>
                                <input type="number" class="form-control" name="pro_bau_monto" id="pro_bau_monto"
                                    value="{{ $programa->pro_costo }}" readonly />
                            </div>
                        @endif
                        <div class="form-group">
                            <label for="pro_bau_fecha">Fecha de Depósito:</label>
                            <input type="date" class="form-control" name="pro_bau_fecha" id="pro_bau_fecha"
                                min="2025-01-01" max="2025-12-31" required />
                        </div>
                        <div class="alert alert-danger mt-1" role="alert">
                            <strong>¡Atención!</strong> {!! $proRestriccion->res_descripcion ?? '' !!}
                        </div>
                        <input type="hidden" name="pro_id" id="pro_id" value="{{ encrypt($programa->pro_id) }}">
                        <button type="submit" id="confirmar-envio" class="submit-btn">Enviar</button>
                    </form>

                </div>
            </div>
        </section>
    </div>
@endsection

@section('scripts')
    <script defer type="text/javascript" src="{{ asset('backend/assets/js/sweetalert2.js') }}"></script>
    <style>
        /* ... tus estilos existentes ... */

        .logout-btn {
            align-items: center;
            background-color: transparent;
            color: #dc3545;
            /* Color del texto del botón de salir */
            border: none;
            cursor: pointer;
            font-size: 0.9em;
            transition: color 0.3s;
        }

        .logout-btn:hover {
            color: #b02a2a;
            /* Color al pasar el ratón */
        }

        .logout-btn i {
            margin-right: 5px;
            /* Espacio entre el ícono y el texto */
        }
    </style>
    <script>
        document.getElementById('per_correo').addEventListener('input', function() {
            const correo = this;
            var errorMessageCorreo = document.getElementById('error-message-correo');

            // Verificamos si el correo electrónico cumple con el patrón
            const patternCorreo = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            if (!patternCorreo.test(correo.value)) {
                // Si no cumple con el patrón, mostramos el mensaje de error
                errorMessageCorreo.style.display = 'block';
            } else {
                // Si cumple con el patrón, ocultamos el mensaje de error
                errorMessageCorreo.style.display = 'none';
            }
        });
        document.getElementById('per_celular').addEventListener('input', function() {
            const celular = this;
            var errorMessage = document.getElementById('error-message-cel');
            // Verificamos si el valor cumple con el patrón establecido
            const pattern = /^6[0-9]{7}|7[0-9]{7}$/;
            if (!pattern.test(celular.value)) {
                // Si no cumple con el patrón, mostramos el mensaje de error
                errorMessage.style.display = 'block';
            } else {
                // Si cumple con el patrón, ocultamos el mensaje de error
                errorMessage.style.display = 'none';
            }
        });

        function mayusculas(e) {
            e.value = e.value.toUpperCase();
        }

        function minusculas(e) {
            e.value = e.value.toLowerCase();
        }
        document.getElementById("confirmar-envio").addEventListener("click", function (e) {
            e.preventDefault(); // Prevenir el envío inmediato del formulario
            // Mostrar mensaje de confirmación
            Swal.fire({
                title: '¿Estás seguro?',
                text: 'Por favor, verifica que hayas llenado correctamente todos los campos del formulario.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, enviar',
                cancelButtonText: 'No, cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Si el usuario confirma, se envía el formulario
                    document.querySelector('form').submit();
                } else {
                    // Si cancela, no hace nada
                    return false;
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            // Cargar sedes al seleccionar un departamento
            $('#dep_id').on('change', function() {
                let dep_id = $(this).val();
                let pro_id = $('#pro_id').val(); // Asegúrate de tener el valor de `pro_id` disponible.
                $('#sede_id').html('<option value="">Cargando...</option>').prop('disabled', true);

                if (dep_id && pro_id) {

                    $.get(`/ofertas-academicas/get-sedes/${dep_id}/${pro_id}`, function(data) {
                        let options = '<option value="">Seleccione</option>';
                        data.forEach(function(sede) {
                            options +=
                                `<option value="${sede.sede_id}">${sede.sede_nombre}</option>`;
                        });
                        $('#sede_id').html(options).prop('disabled', false);
                    });
                } else {
                    $('#sede_id').html(
                        '<option value="">Seleccione primero un departamento y programa</option>').prop(
                        'disabled', true);
                }
            });

            // Cargar turnos al seleccionar una sede
            $('#sede_id').on('change', function() {
                let sede_id = $(this).val(); // Obtener el ID de la sede seleccionada
                let pro_id = $('#pro_id').val(); // Obtener el ID del programa seleccionado
                $('#pro_tur_id').html('<option value="">Cargando...</option>').prop('disabled', true);

                if (sede_id && pro_id) {
                    // Llamada AJAX para obtener los turnos
                    $.get(`/ofertas-academicas/get-turnos/${sede_id}/${pro_id}`, function(data) {
                        // Limpiar el select antes de añadir las nuevas opciones
                        $('#pro_tur_id').html('<option value="">Seleccione un turno</option>');

                        // Iterar sobre los turnos devueltos y añadirlos al select
                        data.forEach(turno => {
                            $('#pro_tur_id').append(
                                `<option value="${turno.pro_tur_id}">${turno.pro_tur_nombre}</option>`
                                );
                        });

                        // Habilitar el select después de cargar las opciones
                        $('#pro_tur_id').prop('disabled', false);
                    }).fail(function() {
                        // Manejo de errores
                        $('#pro_tur_id').html(
                            '<option value="">Error al cargar los turnos</option>').prop(
                            'disabled', true);
                    });
                } else {
                    // Si no se selecciona una sede o programa, deshabilitar el select
                    $('#pro_tur_id').html(
                        '<option value="">Seleccione primero una sede y programa</option>').prop(
                        'disabled', true);
                }
            });


        });
    </script>
@endsection
