@extends('backend.layouts.master')

@section('title', 'Crear Blog - PROFE')

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
                                <h4>Crear Blog</h4>
                                <span>Agrega un nuevo blog</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="breadcrumb-title">
                            <a href="{{ route('home') }}" class="breadcrumb-item">
                                <i class="feather icon-home"></i>
                            </a>
                            <span class="breadcrumb-item"><a href="{{ route('admin.blog.index') }}">Blogs</a></span>
                            <span class="breadcrumb-item active">Crear Blog</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulario para crear blog -->
            <div class="col-12 mt-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-4">Detalles del Blog</h5>
                        @include('backend.layouts.partials.messages')

                        <form id="createBlogForm" enctype="multipart/form-data">
                            @csrf
                            <!-- Título del blog -->
                            <div class="form-section">
                                <label for="blog_titulo">Título del Blog</label>
                                <input type="text" class="form-control" id="blog_titulo" name="blog_titulo"
                                    placeholder="Ingrese el título del blog" required>
                            </div>

                            <!-- Descripción del blog -->
                            <div class="form-section">
                                <label for="blog_descripcion">Descripción</label>
                                <textarea class="form-control" id="blog_descripcion" name="blog_descripcion" placeholder="Ingrese la descripción"></textarea>
                            </div>

                            <!-- Imagen con vista previa -->
                            <div class="form-section">
                                <label for="blog_imagen">Imagen del Blog</label>
                                <input type="file" class="form-control" id="blog_imagen" name="blog_imagen">
                                <img id="preview" class="preview-img" alt="Vista previa de la imagen">
                            </div>

                            <!-- Botón de guardar -->
                            <button type="submit" class="btn btn-primary btn-block" id="submitBtn">Guardar Blog</button>
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

        // Initialize CKEditor
        ClassicEditor
            .create(document.querySelector('#blog_descripcion'))
            .then(editor => {
                blogEditor = editor;
                console.log('Editor initialized');
            })
            .catch(error => {
                console.error('Error initializing editor:', error);
            });

        // AJAX form submission
        document.getElementById('createBlogForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent default form submission

            var formData = new FormData(this); // Capture form data

            // Get the content from the CKEditor
            var editorContent = blogEditor.getData();

            // Validate that the content is not empty
            if (editorContent.trim() === '') {
                Swal.fire({
                    title: 'Error',
                    text: 'La descripción del blog no puede estar vacía.',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
                return;
            }

            formData.append('blog_descripcion', editorContent); // Add editor content to formData

            var submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true; // Disable submit button while processing

            // Perform the AJAX request
            fetch('{{ route('admin.blog.store') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    submitBtn.disabled = false; // Enable the button after the response
                    if (data.success) {
                        Swal.fire({
                            title: '¡Éxito!',
                            text: data.success,
                            icon: 'success',
                            confirmButtonText: 'Aceptar'
                        }).then(() => {
                            window.location.href =
                            '{{ route('admin.blog.index') }}'; // Redirect after success
                        });
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: data.error || 'Hubo un error al crear el blog.',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    submitBtn.disabled = false; // Enable the button in case of an error
                    Swal.fire({
                        title: 'Error',
                        text: 'Hubo un error en la solicitud.',
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                });
        });

        // Preview selected image
        document.getElementById('blog_imagen').addEventListener('change', function(event) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var preview = document.getElementById('preview');
                preview.src = e.target.result; // Set the preview image source
                preview.style.display = 'block'; // Show the preview image
            }
            reader.readAsDataURL(event.target.files[0]); // Read the selected file
        });
    </script>

@endsection
