@extends('backend.layouts.master')

@section('title', 'Editar Galería - PROFE')

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
                                <h4>Editar Galería</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="breadcrumb-title">
                            <a href="{{ route('home') }}" class="breadcrumb-item">
                                <i class="feather icon-home"></i>
                            </a>
                            <span class="breadcrumb-item"><a href="{{ route('admin.galeria.index') }}">Galerías</a></span>
                            <span class="breadcrumb-item active">Editar Galería</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulario para editar galería -->
            <div class="col-12 mt-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-4">Detalles de la Galería</h5>
                        @include('backend.layouts.partials.messages')

                        <form id="editGalleryForm" enctype="multipart/form-data">
                            @csrf
                            @method('PUT') <!-- Método PUT para actualizar -->
                            <input type="hidden" name="galeria_id" value="{{ $galeria->galeria_id }}"> <!-- ID de la galería -->

                            <!-- Imagen con vista previa -->
                            <div class="form-section">
                                <label for="galeria_imagen">Imagen de la Galería</label>
                                <input type="file" class="form-control" id="galeria_imagen" name="galeria_imagen">
                                <img id="preview" class="preview-img"
                                    src="{{ asset('storage/galeria/' . $galeria->galeria_imagen) }}"
                                    alt="Vista previa de la imagen" style="display: {{ $galeria->galeria_imagen ? 'block' : 'none' }}">
                            </div>

                            <!-- Asignar Programa -->
                            <div class="form-section">
                                <label for="pro_id">Asignar Programa</label>
                                <select class="form-control select2" name="pro_id" id="pro_id" required>
                                    <option value="" disabled>Seleccione un programa</option>
                                    @foreach ($programas as $programa)
                                        <option value="{{ $programa->pro_id }}"
                                            {{ $galeria->pro_id == $programa->pro_id ? 'selected' : '' }}>
                                            {{ $programa->pro_nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Asignar Sede -->
                            <div class="form-section">
                                <label for="sede_id">Asignar Sede</label>
                                <select class="form-control select2" name="sede_id" id="sede_id" required>
                                    <option value="" disabled>Seleccione una sede</option>
                                    @foreach ($sedes as $sede)
                                        <option value="{{ $sede->sede_id }}"
                                            {{ $galeria->sede_id == $sede->sede_id ? 'selected' : '' }}>
                                            {{ $sede->sede_nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Botón de guardar -->
                            <button type="submit" class="btn btn-primary btn-block" id="submitBtn">Actualizar
                                Galería</button>
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
        // Enviar el formulario mediante AJAX
        document.getElementById('editGalleryForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Evita el envío normal del formulario
            var formData = new FormData(this); // Captura todos los datos del formulario
            var submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true; // Deshabilitar el botón mientras se envía
    
            // Realiza la solicitud AJAX
            fetch('{{ route('admin.galeria.update', $galeria->galeria_id) }}', {
                    method: 'POST', // Cambiar a POST
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    submitBtn.disabled = false; // Habilitar el botón después de la respuesta
                    if (data.success) {
                        // Mostrar mensaje de éxito con SweetAlert2
                        Swal.fire({
                            title: '¡Éxito!',
                            text: data.success,
                            icon: 'success',
                            confirmButtonText: 'Aceptar'
                        }).then(() => {
                            var preview = document.getElementById('preview');
                            preview.src = data.galeria_imagen; // Actualizar la fuente de la imagen
                            window.location.href = '{{ route('admin.galeria.index') }}'; // Redirigir a la lista de galerías
                        });
                    } else {
                        // Mostrar mensaje de error
                        Swal.fire({
                            title: 'Error',
                            text: data.error || 'Hubo un error al actualizar la galería.',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    submitBtn.disabled = false; // Habilitar el botón en caso de error
                    // Mostrar mensaje de error
                    Swal.fire({
                        title: 'Error',
                        text: 'Hubo un error en la solicitud.',
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                });
        });
    
        // Muestra la vista previa de la imagen seleccionada
        document.getElementById('galeria_imagen').addEventListener('change', function(event) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var preview = document.getElementById('preview');
                preview.src = e.target.result; // Cargar la imagen seleccionada
                preview.style.display = 'block'; // Mostrar la imagen
            }
            reader.readAsDataURL(event.target.files[0]); // Leer el archivo seleccionado
        });
    </script>
    
@endsection
