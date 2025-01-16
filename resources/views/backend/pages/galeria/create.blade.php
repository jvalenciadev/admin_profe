@extends('backend.layouts.master')

@section('title', 'Crear Galería - PROFE')

@section('styles')
    <link rel="stylesheet" href="{{ asset('backend/files/assets/icon/ion-icon/css/ionicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/files/bower_components/select2/dist/css/select2.min.css') }}">
    <style>
        .preview-img {
            width: 100%;
            max-width: 250px;
            height: auto;
            margin-top: 20px;
            display: none;
            border: 2px solid #ddd;
            padding: 5px;
            border-radius: 8px;
        }

        .form-section {
            margin-bottom: 25px;
        }
    </style>
@endsection

@section('admin-content')
    <div class="main-body">
        <div class="page-wrapper">
            <div class="page-header">
                <div class="row align-items-end">
                    <div class="col-lg-8">
                        <div class="page-header-title">
                            <div class="d-inline">
                                <h4>Crear Galería</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="breadcrumb-title">
                            <a href="{{ route('home') }}" class="breadcrumb-item">
                                <i class="feather icon-home"></i>
                            </a>
                            <span class="breadcrumb-item"><a href="{{ route('admin.galeria.index') }}">Galerías</a></span>
                            <span class="breadcrumb-item active">Crear Galería</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulario para agregar galería -->
            <div class="col-12 mt-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-4">Detalles de la Galería</h5>
                        @include('backend.layouts.partials.messages')

                        <form action="{{ route('admin.galeria.store') }}" method="POST" enctype="multipart/form-data"
                            id="myForm">
                            @csrf
                            <!-- Imagen con vista previa -->
                            <div class="form-section">
                                <label for="galeria_imagen">Imagen de la Galería</label>
                                <input type="file" class="form-control" id="galeria_imagen" name="galeria_imagen"
                                    required>
                                <img id="preview" class="preview-img" src="#" alt="Vista previa de la imagen">
                            </div>

                            <!-- Asignar Programa -->
                            <div class="form-section" style="{{ count($programas) === 1 ? 'display: none;' : '' }}">
                                <label for="pro_id">Asignar Programa</label>
                                <select class="form-control select2" name="pro_id" id="pro_id"
                                    {{ count($programas) === 1 ? 'disabled' : 'required' }}>
                                    <option value="" disabled {{ !isset($selectedPrograma) ? 'selected' : '' }}>
                                        Seleccione un programa</option>
                                    @foreach ($programas as $programa)
                                        <option value="{{ $programa->pro_id }}"
                                            {{ $selectedPrograma == $programa->pro_id ? 'selected' : '' }}>
                                            {{ $programa->pro_nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @if (count($programas) === 1)
                                    <input type="hidden" name="pro_id" value="{{ $selectedPrograma }}">
                                @endif
                            </div>

                            <!-- Asignar Sede -->
                            <div class="form-section" style="{{ count($sedes) === 1 ? 'display: none;' : '' }}">
                                <label for="sede_id">Asignar Sede</label>
                                <select class="form-control select2" name="sede_id" id="sede_id"
                                    {{ count($sedes) === 1 ? 'disabled' : 'required' }}>
                                    <option value="" disabled {{ !isset($selectedSede) ? 'selected' : '' }}>
                                        Seleccione una sede</option>
                                    @foreach ($sedes as $sede)
                                        <option value="{{ $sede->sede_id }}"
                                            {{ $selectedSede == $sede->sede_id ? 'selected' : '' }}>
                                            {{ $sede->sede_nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @if (count($sedes) === 1)
                                    <input type="hidden" name="sede_id" value="{{ $selectedSede }}">
                                @endif
                            </div>

                            <!-- Botón de guardar -->
                            <button type="submit" class="btn btn-primary btn-block" id="submitBtn">Guardar Galería</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('backend/assets/js/sweetalert2.js') }}"></script>
    <script>
        // Mostrar vista previa de la imagen
        document.getElementById('galeria_imagen').addEventListener('change', function(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var previewImg = document.getElementById('preview');
                previewImg.src = reader.result;
                previewImg.style.display = 'block';
            };
            reader.readAsDataURL(event.target.files[0]);
        });

        // Enviar formulario con AJAX
        document.getElementById('myForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevenir el comportamiento por defecto del formulario
            var formData = new FormData(this); // Obtener los datos del formulario

            // Deshabilitar el botón mientras se envía el formulario
            var submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.innerText = 'Guardando...';

            // Enviar la solicitud AJAX
            fetch('{{ route("admin.galeria.store") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' // Incluir el token CSRF
                }
            })
            .then(response => response.json())
            .then(data => {
                // Habilitar el botón y mostrar un mensaje
                submitBtn.disabled = false;
                submitBtn.innerText = 'Guardar Galería';

                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: data.message,
                        confirmButtonText: 'Aceptar'
                    }).then(() => {
                        // Opcional: redirigir o actualizar la vista
                        window.location.href = '{{ route("admin.galeria.index") }}'; // Redireccionar
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Hubo un problema al guardar la imagen.', // Mostrar un mensaje de error
                        confirmButtonText: 'Aceptar'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Hubo un error al enviar la solicitud.', // Mostrar un mensaje de error
                    confirmButtonText: 'Aceptar'
                });
                submitBtn.disabled = false; // Habilitar el botón en caso de error
                submitBtn.innerText = 'Guardar Galería';
            });
        });

        // Inicializar select2
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Seleccione una opción",
                allowClear: true
            });
        });
    </script>
@endsection
