@extends('backend.layouts.master')

@section('title')
    Crear Evento - PROFE
@endsection

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/files/assets/icon/ion-icon/css/ionicons.min.css') }}">
    <script src="{{ asset('backend/files/assets/ckeditor/ckeditor.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('backend/files/bower_components/select2/dist/css/select2.min.css') }}" />
    <link rel="stylesheet" type="text/css"
        href="{{ asset('backend/files/bower_components/multiselect/css/multi-select.css') }}" />
@endsection


@section('admin-content')
    <div class="main-body">
        <div class="page-wrapper">

            <div class="page-header">
                <div class="row align-items-end">
                    <div class="col-lg-8">
                        <div class="page-header-title">
                            <div class="d-inline">
                                <h4>Pregunta</h4>
                                <span>Crea nuevo pregunta</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="page-header-breadcrumb">
                            <ul class="breadcrumb-title">
                                <li class="breadcrumb-item" style="float: left;">
                                    <a href="../index-2.html"> <i class="feather icon-home"></i>
                                    </a>
                                </li>
                                <li class="breadcrumb-item" style="float: left;"><a href="#!">Lista de
                                        Cuestionarios</a>
                                </li>
                                <li class="breadcrumb-item" style="float: left;"><a href="#!">Crear Cuestionario</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- data table start -->
                <div class="col-12 mt-5">
                    <div class="card shadow-sm border-light">
                        <div class="card-body">
                            <h4 class="header-title">Agregar Pregunta</h4>
                            @include('backend.layouts.partials.messages')
                
                            <form action="{{ route('admin.eventopregunta.store') }}" method="POST" enctype="multipart/form-data" id="myForm">
                                @csrf
                                <input type="hidden" name="eve_cue_id" value="{{ $eventoCuestionario->eve_cue_id }}">
                                <!-- Título de la pregunta -->
                                <div class="form-group row">
                                    <label for="eve_pre_texto" class="col-sm-2 col-form-label">Texto</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="eve_pre_texto" name="eve_pre_texto" placeholder="Ingrese el título de la pregunta" required>
                                        <small class="form-text text-muted">Proporcione un título claro y conciso.</small>
                                    </div>
                                </div>
                
                                <!-- Tipo de pregunta -->
                                <div class="form-group row">
                                    <label for="eve_pre_tipo" class="col-sm-2 col-form-label">Tipo</label>
                                    <div class="col-sm-10">
                                        <select class="form-control" id="eve_pre_tipo" name="eve_pre_tipo" required>
                                            <option value="" disabled selected>Seleccione un tipo</option>
                                            <option value="multiple_choice">Multiple Choice</option>
                                            <option value="respuesta_abierta">Respuesta Abierta</option>
                                            <option value="booleano">Booleano</option>
                                        </select>
                                        <small class="form-text text-muted">Seleccione el tipo de pregunta adecuada.</small>
                                    </div>
                                </div>
                
                                <!-- Respuesta correcta para respuesta abierta -->
                                <div id="respuestaAbiertaDiv" class="form-group row" style="display: none;">
                                    <label for="eve_pre_respuesta_correcta" class="col-sm-2 col-form-label">Respuesta Correcta</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="eve_pre_respuesta_correcta" name="eve_pre_respuesta_correcta" placeholder="Ingrese la respuesta correcta">
                                        <small class="form-text text-muted">Escriba la respuesta correcta aquí.</small>
                                    </div>
                                </div>
                
                                <!-- Obligación -->
                                <div class="form-group row">
                                    <label for="eve_pre_obligatorio" class="col-sm-2 col-form-label">Obligación</label>
                                    <div class="col-sm-10">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="eve_pre_obligatorio" name="eve_pre_obligatorio" value="1" {{ old('eve_pre_obligatorio', $eve_pre_obligatorio ?? false) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="eve_pre_obligatorio">¿Es obligatorio?</label>
                                        </div>
                                        <small class="form-text text-muted">Marque si la pregunta es obligatoria.</small>
                                    </div>
                                </div>
                
                                <!-- Botón de guardar -->
                                <div class="form-group row">
                                    <div class="col-sm-10 offset-sm-2">
                                        <button type="submit" class="btn btn-primary mt-4">Guardar Pregunta</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- data table end -->
            </div>
        </div>
    </div>

    <div id="styleSelector">
    @endsection

    @section('scripts')
        <script>
            document.getElementById('eve_pre_tipo').addEventListener('change', function() {
                const respuestaAbiertaDiv = document.getElementById('respuestaAbiertaDiv');
                respuestaAbiertaDiv.style.display = this.value === 'respuesta_abierta' ? 'block' : 'none';
            });
        </script>
        <script>
            document.getElementById('myForm').addEventListener('submit', function() {
                var submitBtn = document.getElementById('submitBtn');
                submitBtn.disabled = true;
                submitBtn.innerText =
                    'Guardando ...';
            });
        </script>
        <script src="{{ asset('backend/files/assets/js/jquery.mCustomScrollbar.concat.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('backend/files/bower_components/select2/dist/js/select2.full.min.js') }}">
        </script>
        <script type="text/javascript" src="{{ asset('backend/files/assets/pages/advance-elements/select2-custom.js') }}">
        </script>
        <script>
            // Initialize CKEditor
            ClassicEditor
                .create(document.querySelector('textarea'))
                .then(editor => {
                    console.log('Editor was initialized', editor);
                })
                .catch(error => {
                    console.error('Error during initialization of the editor', error);
                });
        </script>
    @endsection
