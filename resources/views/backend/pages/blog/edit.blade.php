@extends('backend.layouts.master')

@section('title')
    Editar Blog - PROFE
@endsection

@section('styles')
    <script src="{{ asset('backend/files/assets/ckeditor/ckeditor.js') }}"></script>
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
                                <h4>Editar Blog</h4>
                                <span>Actualizar los detalles del blog</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="breadcrumb-title">
                            <a href="{{ route('home') }}" class="breadcrumb-item">
                                <i class="feather icon-home"></i>
                            </a>
                            <span class="breadcrumb-item"><a href="{{ route('admin.blog.index') }}">Blogs</a></span>
                            <span class="breadcrumb-item active">Editar Blog</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulario para editar blog -->
            <div class="col-12 mt-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-4">Detalles del Blog</h5>
                        @include('backend.layouts.partials.messages')

                        <form id="editBlogForm" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- Título del blog -->
                            <div class="form-section">
                                <label for="blog_titulo">Título del Blog</label>
                                <input type="text" class="form-control" id="blog_titulo" name="blog_titulo"
                                    value="{{ $blog->blog_titulo }}" required>
                            </div>

                            <!-- Descripción del blog -->
                            <div class="form-section">
                                <label for="blog_descripcion">Descripción</label>
                                <textarea class="form-control" id="blog_descripcion" name="blog_descripcion">{!! $blog->blog_descripcion !!}</textarea>
                            </div>

                            <!-- Imagen del blog -->
                            <div class="form-section">
                                <label for="blog_imagen">Imagen del Blog</label>
                                <input type="file" class="form-control" id="blog_imagen" name="blog_imagen">
                                    <img id="preview" class="preview-img" src="{{ asset('storage/blog/'.$blog->blog_imagen) }}"
                                     alt="Vista previa de la imagen" style="display: {{ $blog->blog_imagen ? 'block' : 'none' }}">
                                   
                            </div>

                            <!-- Botón de guardar -->
                            <button type="submit" class="btn btn-primary btn-block" id="submitBtn">Actualizar Blog</button>
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
        let blogEditor;

        // Inicializa CKEditor
        ClassicEditor
            .create(document.querySelector('#blog_descripcion'))
            .then(editor => {
                blogEditor = editor;
                console.log('Editor inicializado');
            })
            .catch(error => {
                console.error('Error al inicializar el editor:', error);
            });

        // Maneja el envío del formulario mediante AJAX
        document.getElementById('editBlogForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Evita el envío normal del formulario

            var formData = new FormData(this); // Captura los datos del formulario

            // Obtiene el contenido del editor CKEditor y lo añade al formData
            var editorContent = blogEditor.getData();

            // Valida que el contenido no esté vacío
            if (editorContent.trim() === '') {
                Swal.fire({
                    title: 'Error',
                    text: 'La descripción del blog no puede estar vacía.',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
                return;
            }

            formData.append('blog_descripcion', editorContent); // Agrega el contenido del editor

            var submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true; // Desactiva el botón de envío mientras se procesa

            // Realiza la solicitud AJAX
            fetch('{{ route('admin.blog.update', encrypt($blog->blog_id)) }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    submitBtn.disabled = false; // Habilita el botón después de recibir la respuesta
                    if (data.success) {
                        Swal.fire({
                            title: '¡Éxito!',
                            text: data.success,
                            icon: 'success',
                            confirmButtonText: 'Aceptar'
                        }).then(() => {
                            window.location.href = '{{ route('admin.blog.index') }}'; // Redirige a la lista de blogs
                        });
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: data.error || 'Hubo un error al actualizar el blog.',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    submitBtn.disabled = false; // Habilita el botón en caso de error
                    Swal.fire({
                        title: 'Error',
                        text: 'Hubo un error en la solicitud.',
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                });
        });

        // Muestra la vista previa de la imagen seleccionada
        document.getElementById('blog_imagen').addEventListener('change', function(event) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var preview = document.getElementById('preview');
                preview.src = e.target.result; // Actualiza la vista previa de la imagen
                preview.style.display = 'block'; // Muestra la imagen
            }
            reader.readAsDataURL(event.target.files[0]); // Lee el archivo seleccionado
        });
    </script>
@endsection
