@extends('frontend.layouts.master')

@section('title')
    {{ $evento->eve_nombre }}
@endsection

@section('og-meta-tags')
    <meta property="og:locale" content="es_ES" />
    <meta property="og:title" content="{{ $evento->eve_nombre }}" />
    <meta property="og:description" content="Formulario de inscripción." />
    <meta property="og:image" content="{{ asset('storage/evento_afiches/' . $evento->eve_afiche) }}" />
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
            border: 1px solid #1474a6;
        }

        .form-title {
            text-align: center;
            font-size: 2.5em;
            font-weight: bold;
            color: #1474a6;
            margin-bottom: 30px;
        }

        .form-control:focus {
            border-color: #1474a6;
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
    </style>
@endsection

@section('frontend-content')
    <div id="content" class="site-content">
        <section>
            <div class="lower-content2">
                <div class="form-container">
                    <img src="{{ asset('storage/evento_afiches/' . $evento->eve_afiche) }}" alt="Afiche del evento"
                        class="afiche-image img-fluid mb-3">
                    <h2 class="form-title">Inscripciones</h2>
                    <div class="text-right mt-4">
                        <button class="logout-btn" onclick="window.location.href='{{ route('evento.logout') }}'">
                            <i class="fas fa-sign-out-alt"></i> Salir
                        </button>
                    </div>
                    @if ($errors->has('eve_per_ci'))
                        <span class="text-danger">{{ $errors->first('eve_per_ci') }}</span>
                    @endif
                    <form action="{{ route('evento.postInscribirse') }}" method="POST" enctype="multipart/form-data"
                        id="inscripcionForm">
                        @csrf

                        <div class="form-row">
                            <div class="form-group col-md-9">
                                <label for="eve_per_ci" class="col-form-label">Carnet de identidad</label>
                                <input type="text" class="form-control" name="eve_per_ci" id="eve_per_ci"
                                    value="{{ isset($user->per_ci) ? $user->per_ci : session('per_ci') }}" autofocus
                                    placeholder="Ingrese el número de carnet de identidad" required pattern="[0-9]{4,10}"
                                    title="Debe tener entre 4 y 10 dígitos."
                                    {{ session('per_ci') || isset($user->per_ci) ? 'readonly' : '' }} />
                            </div>

                            <div class="form-group col-md-3">
                                <label for="eve_per_complemento" class="col-form-label">Complemento</label>
                                <input type="text" class="form-control" name="eve_per_complemento"
                                    id="eve_per_complemento"
                                    value="{{ isset($user->per_complemento) ? $user->per_complemento : session('per_complemento') }}"
                                    maxlength="5" {{ isset($user->per_complemento) ? 'readonly' : '' }} />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-7">
                                <label for="eve_per_fecha_nacimiento">Fecha de nacimiento</label>
                                <input type="date" class="form-control" name="eve_per_fecha_nacimiento"
                                    id="eve_per_fecha_nacimiento" required
                                    min="1900-01-01" max="2010-12-31" />
                                    <div class="alert alert-warning mt-2">
                                        Debe ingresar correctamente su fecha de nacimiento.
                                    </div>
                                    <div id="error-message" class="error-message text-danger font-weight-bold" style="display: none;">
                                        La fecha de nacimiento debe estar entre 1900 y 2010 para que pueda inscribirse.
                                    </div>
                            </div>

                            <div class="form-group col-md-5">
                                <label for="gen_id">Sexo</label>
                                <select class="form-control" name="gen_id" id="gen_id" required>
                                    <option value="">Seleccione el sexo</option>
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
                            <label for="eve_per_nombre_1">Nombres</label>
                            <input type="text" class="form-control" name="eve_per_nombre_1" id="eve_per_nombre_1" required
                                onkeyup="mayusculas(this);"
                                value="{{ isset($user) ? $user->per_nombre1 . ' ' . $user->per_nombre2 : session('per_nombre1') }}" maxlength="150" {{ isset($user) ? 'readonly' : '' }} />
                        </div>

                        <div class="form-group">
                            <label for="eve_per_apellido_1">Apellido paterno</label>
                            <input type="text" class="form-control" name="eve_per_apellido_1" id="eve_per_apellido_1"
                                value="{{ isset($user->per_apellido1) ? $user->per_apellido1 : session('per_apellido1', '') }}"
                                onkeyup="mayusculas(this);" maxlength="100" {{ isset($user) ? 'readonly' : '' }} />
                        </div>

                        <div class="form-group">
                            <label for="eve_per_apellido_2">Apellido materno</label>
                            <input type="text" class="form-control" name="eve_per_apellido_2" id="eve_per_apellido_2"
                                value="{{ isset($user->per_apellido2) ? $user->per_apellido2 : session('per_apellido2', '') }}"
                                onkeyup="mayusculas(this);" maxlength="100"  {{ isset($user) ? 'readonly' : '' }}/>
                        </div>
                        <div class="form-group">
                            <label for="eve_per_celular">Celular</label>
                            <input type="tel" class="form-control" name="eve_per_celular" id="eve_per_celular"
                                value="{{ session('per_celular', isset($user->per_celular) ? $user->per_celular : '') }}"
                                required pattern="^6[0-9]{7}|7[0-9]{7}$"
                                title="El número debe comenzar con 6 o 7 y tener 8 dígitos." />
                        </div>
                        <div class="form-group">
                            <label for="eve_correo">Correo electronico</label>
                            <input type="email" class="form-control" name="eve_per_correo" id="eve_per_correo"
                                value="{{ session('per_correo', isset($user->per_correo) ? $user->per_correo : '')}}"
                                required onkeyup="minusculas(this);" maxlength="100" />
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="dep_id">Departamento</label>
                                <select class="form-control" name="dep_id" id="dep_id" required>
                                    <option value="">Seleccione</option>
                                    @foreach ($departamentos as $dep)
                                        <option value="{{ encrypt($dep->dep_id) }}">
                                            {{ $dep->dep_nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="pm_id">Modalidad</label>
                                <select class="form-control" name="pm_id" id="pm_id" required>
                                    <option value="">Seleccione</option>
                                    @foreach ($modalidades as $mod)
                                        <option value="{{ encrypt($mod->pm_id) }}">
                                            {{ $mod->pm_nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <input type="hidden" name="eve_id" id="eve_id" value="{{ encrypt($evento->eve_id) }}">
                        <button type="submit" class="submit-btn">Enviar</button>
                    </form>

                </div>
            </div>
        </section>
    </div>
@endsection

@section('scripts')
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
        function mayusculas(e) {
            e.value = e.value.toUpperCase();
        }

        document.getElementById('eve_per_fecha_nacimiento').addEventListener('input', function () {
            const input = this;
            const minDate = new Date(input.min);
            const maxDate = new Date(input.max);
            const selectedDate = new Date(input.value);

            const errorMessage = document.getElementById('error-message');

            // Check if selected date is within the valid range
            if (selectedDate < minDate || selectedDate > maxDate || isNaN(selectedDate.getTime())) {
                errorMessage.style.display = 'block';
                input.classList.add('is-invalid');
            } else {
                errorMessage.style.display = 'none';
                input.classList.remove('is-invalid');
            }
        });
    </script>
@endsection
