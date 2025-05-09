@extends('backend.layouts.master')

@php
    $usr = Auth::guard('admin')->user();
@endphp
@section('title')
    Crear Inscripcion - PROFE
@endsection

@section('styles')
@endsection


@section('admin-content')
    <div class="main-body">
        <div class="page-wrapper">

            <div class="page-header">
                <div class="row align-items-end">
                    <div class="col-lg-8">
                        <div class="page-header-title">
                            <div class="d-inline">
                                <h4>Inscripción</h4>
                                <span>Editar Inscripción</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="page-header-breadcrumb">
                            <ul class="breadcrumb-title">
                                <li class="breadcrumb-item" style="float: left;">
                                    <a href="#"> <i class="feather icon-home"></i>
                                    </a>
                                </li>
                                <li class="breadcrumb-item" style="float: left;"><a href="#!">Lista de Inscripción</a>
                                </li>
                                <li class="breadcrumb-item" style="float: left;"><a href="#!">Editar Inscripción</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- data table start -->
                <div class="col-12 mt-1">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">Editar Inscripción</h4>

                            @include('backend.layouts.partials.messages')

                            <form action="{{ route('admin.inscripcion.update', encrypt($inscripcion->pi_id)) }}"
                                method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row form-group">
                                    <div class="col-sm-1 col-form-label">
                                        <label for="per_rda">RDA</label>
                                    </div>
                                    <div class="col-sm-2">
                                        <input type="number" class="form-control" id="per_rda" name="per_rda"
                                            placeholder="Ingrese el RDA" value="{{ $inscripcion->per_rda }}" readonly>
                                    </div>
                                    <div class="col-sm-1 col-form-label">
                                        <label for="nombre">Nombres</label>
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" id="nombre" name="nombre"
                                            placeholder="Ingrese el nombre"
                                            value="{{ $inscripcion->per_nombre1 . ' ' . $inscripcion->per_nombre2 }}"
                                            readonly>
                                    </div>
                                    <div class="col-sm-1 col-form-label">
                                        <label for="apellidos">Apellidos</label>
                                    </div>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" id="apellidos" name="apellidos"
                                            placeholder="Ingrese el apellido"
                                            value="{{ $inscripcion->per_apellido1 . ' ' . $inscripcion->per_apellido2 }}"
                                            readonly>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-3 col-sm-12">
                                        <label for="correo_nombre">Nombre del Correo</label>
                                        <input type="text" class="form-control" id="correo_nombre" name="correo_nombre"
                                            placeholder="Ingrese el nombre del correo">
                                    </div>
                                    <div class="form-group col-md-3 col-sm-12">
                                        <label for="correo_dominio">Dominio del Correo</label>
                                        <select class="form-control" id="correo_dominio" name="correo_dominio">
                                            <option value="@gmail.com">@gmail.com</option>
                                            <option value="@iipp.edu.bo">@iipp.edu.bo</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3 col-sm-12">
                                        <label for="per_correo">Correo Completo</label>
                                        <input type="text" class="form-control" id="per_correo" name="per_correo"
                                            placeholder="Correo Completo" value="{{ $inscripcion->per_correo }}" readonly required>
                                    </div>
                                    <div class="form-group col-md-3 col-sm-12">
                                        <label for="per_celular">Celular</label>
                                        <input type="number" class="form-control" id="per_celular" name="per_celular"
                                            value="{{ $inscripcion->per_celular }}" placeholder="Ingrese su número de celular" required>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="pi_licenciatura">Licenciatura en:</label>
                                        <input type="text" class="form-control" id="pi_licenciatura"
                                            name="pi_licenciatura" value="{{ $inscripcion->pi_licenciatura ?? '' }}"
                                            placeholder="Ingrese su Licenciatura del participante">
                                    </div>
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="pi_unidad_educativa">Institución: </label>
                                        <input type="text" class="form-control" id="pi_unidad_educativa"
                                            name="pi_unidad_educativa"
                                            value="{{ $inscripcion->pi_unidad_educativa ?? '' }}"
                                            placeholder="Ingrese su lugar de trabajo">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="pi_materia">Cargo Actual:</label>
                                        <input type="text" class="form-control" id="pi_materia" name="pi_materia"
                                            value="{{ $inscripcion->pi_materia ?? '' }}"
                                            placeholder="Ingrese sus datos correctos">
                                    </div>
                                    <div class="form-group col-md-3 col-sm-12">
                                        <label for="pi_nivel">Nivel:</label>
                                        <select class="form-control" id="pi_nivel" name="pi_nivel">
                                            <option value="{{ $inscripcion->niv_nombre }}">{{ $inscripcion->niv_nombre }}
                                            </option>
                                            @foreach ($niveles as $nivel)
                                                <option value="{{ $nivel->niv_nombre }}">{{ $nivel->niv_nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3 col-sm-12">
                                        <label for="pi_subsistema">Subsistema:</label>
                                        <select class="form-control" id="pi_subsistema" name="pi_subsistema">
                                            <option value="{{ $inscripcion->sub_nombre }}">{{ $inscripcion->sub_nombre }}
                                            </option>
                                            @foreach ($subsistemas as $subsistema)
                                                <option value="{{ $subsistema->sub_nombre }}">
                                                    {{ $subsistema->sub_nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="col-sm-1 col-form-label">Sede</label>
                                    <div class="col-sm-3">
                                        <select name="sede_id" id="sede" class="form-control" readonly>
                                            <option value="{{ $sede->sede_id }}"
                                                {{ $sede->sede_id == $sede->sede_id ? 'selected' : '' }}>
                                                {{ $sede->dep_nombre }} - {{ $sede->sede_nombre }}</option>
                                        </select>
                                    </div>
                                    <label class="col-sm-1 col-form-label">Programa</label>
                                    <div class="col-sm-3">
                                        <select name="pro_id" id="programa" class="form-control" readonly>

                                            <option value="{{ $programa->pro_id }}"
                                                {{ $programa->pro_id == $programa->pro_id ? 'selected' : '' }}>
                                                {{ $programa->pro_nombre }}</option>
                                            {{-- 
                                                <option value="">Seleccione un programa</option>
                                                @foreach ($programa as $prog)
                                                <option value="{{ $prog->pro_id }}"
                                                    {{ $inscripcion->pro_id == $prog->pro_id ? 'selected' : '' }}>
                                                    {{ $prog->pro_nombre }}</option>
                                            @endforeach --}}
                                        </select>
                                    </div>

                                    <div class="col-sm-3">
                                        <select name="pro_tur_id" id="turno" class="form-control" required>
                                            <option value="">Seleccione un turno</option>
                                        </select>
                                    </div>

                                </div>
                                <div class="row form-group">
                                    @if ($usr->can('inscripcion.estado'))
                                        <label class="col-sm-1 col-form-label">Estado</label>
                                        <div class="col-sm-2">
                                            <select name="pie_id" id="estado" class="form-control">
                                                <option value="">Seleccione estado</option>
                                                @foreach ($inscripcionestado as $estado)
                                                    @if (!(count($bauchers) == 0 && $estado->pie_nombre == 'INSCRITO'))
                                                        <option value="{{ $estado->pie_id }}"
                                                            {{ $inscripcion->pie_id == $estado->pie_id ? 'selected' : '' }}>
                                                            {{ $estado->pie_nombre }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif



                                    {{-- <label for="documentos" class="col-sm-2 col-form-label">Adjuntar Documentos</label>
                                    <div class="col-sm-4">
                                        <input type="file" class="form-control" id="pi_doc_digital" name="pi_doc_digital">
                                        <span class="j-hint">Agregar Documentos: pdf 5Mb</span>
                                    </div> --}}
                                </div>


                                <!-- Campo oculto para per_id -->
                                <input type="hidden" id="per_id" name="per_id">
                                @if (count($bauchers) == 0)
                                    <div class="alert alert-warning mt-3" role="alert">
                                        <strong>Atención:</strong> Debe agregar baucher.
                                    </div>
                                @endif

                                <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4" id="submitBtn">Guardar
                                    Inscripcion
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-12 ">

                    <div class="card shadow-lg">
                        <div class="alert alert-warning text-center shadow-sm mb-3" role="alert">
                            <strong>¡Atención!</strong> Este formulario es <u>obligatorio</u>. Por favor, asegúrese de
                            completar todos los campos correctamente antes de guardar.
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.datosnacres.update', ['per_id' => $inscripcion->per_id]) }}"
                                method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <!-- Columna Izquierda: Datos de Nacimiento -->
                                    <div class="col-md-6 mb-1">
                                        <h4 class="header-title text-primary">Datos de Nacimiento</h4>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="dep_nac_id" class="form-label">Departamento</label>
                                                    <select name="dep_nac_id" id="dep_nac_id"
                                                        class="form-control shadow-sm" required>
                                                        <option value="">Seleccione un departamento</option>
                                                        @foreach ($departamentos as $departamento)
                                                            <option value="{{ $departamento->dep_id }}"
                                                                {{ old('dep_nac_id', $mapPersonaNr->dep_nac_id ?? '') == $departamento->dep_id ? 'selected' : '' }}>
                                                                {{ $departamento->dep_nombre }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="per_nac_provincia" class="form-label">Provincia</label>
                                                    <input type="text" class="form-control shadow-sm"
                                                        id="per_nac_provincia" name="per_nac_provincia"
                                                        placeholder="Ingrese la provincia"
                                                        value="{{ $mapPersonaNr->per_nac_provincia ?? '' }}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="per_nac_municipio" class="form-label">Municipio</label>
                                                    <input type="text" class="form-control shadow-sm"
                                                        id="per_nac_municipio" name="per_nac_municipio"
                                                        placeholder="Ingrese el municipio"
                                                        value="{{ $mapPersonaNr->per_nac_municipio ?? '' }}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="localidad" class="form-label">Localidad</label>
                                                    <input type="text" class="form-control shadow-sm"
                                                        id="per_nac_localidad" name="per_nac_localidad"
                                                        placeholder="Ingrese la localidad"
                                                        value="{{ $mapPersonaNr->per_nac_localidad ?? '' }}" required>
                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                    <!-- Línea de separación -->

                                    <div class="col-md-6 mb-1" style="border-left: 2px solid #007bff;">
                                        <h4 class="header-title text-primary">Datos de Residencia</h4>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="dep_res_id" class="form-label">Departamento</label>
                                                    <select name="dep_res_id" id="dep_res_id"
                                                        class="form-control shadow-sm" required>
                                                        <option value="">Seleccione un departamento</option>
                                                        @foreach ($departamentos as $departamento)
                                                            <option value="{{ $departamento->dep_id }}"
                                                                {{ old('dep_res_id', $mapPersonaNr->dep_res_id ?? '') == $departamento->dep_id ? 'selected' : '' }}>
                                                                {{ $departamento->dep_nombre }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="per_res_provincia" class="form-label">Provincia</label>
                                                    <input type="text" class="form-control shadow-sm"
                                                        id="per_res_provincia" name="per_res_provincia"
                                                        placeholder="Ingrese la provincia"
                                                        value="{{ $mapPersonaNr->per_res_provincia ?? '' }}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="per_res_municipio" class="form-label">Municipio</label>
                                                    <input type="text" class="form-control shadow-sm"
                                                        id="per_res_municipio" name="per_res_municipio"
                                                        placeholder="Ingrese el municipio"
                                                        value="{{ $mapPersonaNr->per_res_municipio ?? '' }}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="per_res_localidad" class="form-label">Localidad</label>
                                                    <input type="text" class="form-control shadow-sm"
                                                        id="per_res_localidad" name="per_res_localidad"
                                                        placeholder="Ingrese la localidad"
                                                        value="{{ $mapPersonaNr->per_res_localidad ?? '' }}" required>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="form-group">
                                            <label for="per_res_direccion" class="form-label">Dirección</label>
                                            <input type="text" class="form-control shadow-sm" id="per_res_direccion"
                                                name="per_res_direccion" placeholder="Ingrese la dirección"
                                                value="{{ $mapPersonaNr->per_res_direccion ?? '' }}" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-center mt-1">
                                    <button type="submit" class="btn btn-primary px-5">Guardar Información</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>



                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">Lista de Baucheres</h4>
                            {{-- Mostrar el total pagado y el costo del programa --}}
                            <div class="alert alert-success p-4 border border-success rounded shadow-sm">
                                <h5 class="text-success font-weight-bold">
                                    <i class="icofont icofont-check-circled"></i> Total pagado:
                                    <span class="text-dark">Bs. {{ $totalMonto }}</span>
                                </h5>
                                <h6 class="text-success font-weight-bold">
                                    <i class="icofont icofont-money-bag"></i> Costo del programa:
                                    <span class="text-dark">Bs. {{ $programa->pro_costo }}</span>
                                </h6>
                                <h6 class="text-warning font-weight-bold">
                                    <i class="icofont icofont-alert-warning"></i> Falta por pagar:
                                    <span class="text-dark">Bs. {{ $programa->pro_costo - $totalMonto }}</span>
                                </h6>
                            </div>

                            <div class="row">
                                @foreach ($bauchers as $baucher)
                                    <div class="col-md-6 mb-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-5 mb-3">
                                                        <img src="{{ Storage::url($baucher->pro_bau_imagen) }}?{{ \Illuminate\Support\Str::random(8) }}"
                                                            alt="Baucher Imagen" class="img-fluid">
                                                    </div>
                                                    <div class="col-md-7">
                                                        <form
                                                            action="{{ route('admin.inscripcion.baucherupdate', [$inscripcion->pi_id, $baucher->pro_bau_id]) }}"
                                                            method="POST" enctype="multipart/form-data">
                                                            @csrf
                                                            @method('PUT')

                                                            @if ($usr->can('inscripcion.bauchereditar'))
                                                                <div class="form-group">
                                                                    <label for="pro_bau_imagen">Imagen</label>
                                                                    <input type="file" name="pro_bau_imagen"
                                                                        class="form-control">
                                                                </div>
                                                            @endif

                                                            <div class="form-group">
                                                                <label for="pro_bau_nro_deposito">Número de
                                                                    Depósito</label>
                                                                <input type="text" name="pro_bau_nro_deposito"
                                                                    class="form-control"
                                                                    value="{{ $baucher->pro_bau_nro_deposito }}">
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="pro_bau_monto">Monto (Bs)</label>
                                                                <input type="text" name="pro_bau_monto"
                                                                    class="form-control"
                                                                    value="{{ $baucher->pro_bau_monto }}" readonly>
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="pro_bau_fecha">Fecha</label>
                                                                <input type="date" name="pro_bau_fecha"
                                                                    class="form-control"
                                                                    value="{{ $baucher->pro_bau_fecha }}">
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="pro_bau_tipo_pago">Tipo de Pago</label>
                                                                <select name="pro_bau_tipo_pago" class="form-control">
                                                                    <option value="Baucher"
                                                                        {{ $baucher->pro_bau_tipo_pago == 'Baucher' ? 'selected' : '' }}>
                                                                        Baucher
                                                                    </option>
                                                                    <option value="Banca Móvil"
                                                                        {{ $baucher->pro_bau_tipo_pago == 'Banca Móvil' ? 'selected' : '' }}>
                                                                        Banca Móvil
                                                                    </option>
                                                                </select>
                                                            </div>

                                                            @if ($usr->can('inscripcion.bauchereditar'))
                                                                <button type="submit" class="btn btn-primary">Actualizar
                                                                    Baucher</button>
                                                            @endif
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>


                        </div>
                    </div>
                </div>
                @if ($usr->can('inscripcion.baucheragregar'))
                    <div class="col-6 mt-1">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="header-title">Agregar Baucher</h4>
                                @if ($totalMonto >= $programa->pro_costo)
                                    <div class="alert alert-success">
                                        El monto total ya ha sido cubierto. No es necesario agregar más depósitos.
                                    </div>
                                @else
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="alert alert-warning text-center shadow-sm mb-3" role="alert">
                                                <strong>¡Atención!</strong> Si el participante realizó el pago por
                                                transferencia, solo se aceptan transferencias desde Banco Unión. Además,
                                                debe adjuntar la imagen de transferencia y en el campo "Número
                                                de Depósito" ingresar el número de cuenta del depositante o Nro de comprobante.
                                            </div>
                                            <form
                                                action="{{ route('admin.inscripcion.baucherpost', $inscripcion->pi_id) }}"
                                                method="POST" enctype="multipart/form-data">
                                                @csrf

                                                <div class="form-group">
                                                    <label for="pro_bau_imagen">Imagen</label>
                                                    <input type="file" name="pro_bau_imagen" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label for="pro_bau_nro_deposito">Número de Depósito</label>
                                                    <input type="text" name="pro_bau_nro_deposito"
                                                        class="form-control" placeholder="Ingrese el nro de baucher">
                                                </div>
                                                {{-- <div class="form-group">
                                                    <label for="pro_bau_monto">Monto (Bs)</label>
                                                    <input type="text" name="pro_bau_monto" class="form-control"
                                                        value="{{ $inscripcion->pro_tip_id == 3 ? 300 : ($inscripcion->pro_tip_id == 2 ? 150 : '') }}"
                                                        readonly>
                                                </div> --}}
                                                <div class="form-group">
                                                    <label for="pro_bau_monto">Monto (Bs)</label>
                                                
                                                    @if ($inscripcion->pro_tip_id == 2)
                                                        {{-- Monto fijo para tipo 2 --}}
                                                        <input type="text" name="pro_bau_monto" class="form-control" value="150" readonly>
                                                    
                                                    @elseif ($inscripcion->pro_tip_id == 3)
                                                        {{-- Select con opciones para tipo 3 --}}
                                                        <select name="pro_bau_monto" class="form-control">
                                                            <option value="300" >300 Bs</option>
                                                            <option value="1500" >1500 Bs</option>
                                                        </select>
                                                    @endif
                                                </div>
                                                <div class="form-group">
                                                    <label for="pro_bau_fecha">Fecha</label>
                                                    <input type="date" name="pro_bau_fecha" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label for="pro_bau_tipo_pago">Tipo de Pago</label>
                                                    <select name="pro_bau_tipo_pago" class="form-control">
                                                        <option value="Baucher">Baucher</option>
                                                        <option value="Banca Móvil">Banca Móvil
                                                        </option>
                                                    </select>
                                                </div>

                                                <button type="submit" class="btn btn-success">Guardar
                                                    Baucher</button>
                                            </form>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                <!-- data table end -->
            </div>
        </div>
    </div>

    <div id="styleSelector">
    @endsection

    @section('scripts')
        <script type="text/javascript" src="{{ asset('backend/files/assets/pages/j-pro/js/jquery-cloneya.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('backend/files/assets/pages/j-pro/js/custom/cloned-form.js') }}"></script>
        <script src="{{ asset('backend/files/assets/js/pcoded.min.js') }}"></script>
        <script>
            $(document).ready(function() {
                $('.clone-container').cloneya();
            });
        </script>
        <script>
            $(document).ready(function() {
                $('.clone-container').cloneya({
                    cloneButton: '.add-more',
                    deleteButton: '.remove'
                });
            });
        </script>
        <script>
            $(document).ready(function() {
                function updateCorreoCompleto() {
                    var nombre = $('#correo_nombre').val();
                    var dominio = $('#correo_dominio').val();
                    if (nombre) {
                        $('#per_correo').val(nombre + dominio);
                    } else {
                        $('#per_correo').val('');
                    }
                }

                $('#correo_nombre').on('input', function() {
                    updateCorreoCompleto();
                });

                $('#correo_dominio').on('change', function() {
                    updateCorreoCompleto();
                });

                $('#per_rda').on('input', function() {
                    var rda = $(this).val();
                    console.log(rda);
                    if (rda.length > 0) {
                        $.ajax({
                            url: '{{ route('admin.searchInscripcion.rda') }}',
                            method: 'GET',
                            data: {
                                rda: rda
                            },
                            success: function(response) {
                                if (response.success) {
                                    $('#per_id').val(response.person
                                        .per_id); // Asigna el per_id al campo oculto
                                    var nombre = '';
                                    if (response.person.per_nombre1) {
                                        nombre += response.person.per_nombre1;
                                    }
                                    if (response.person.per_nombre2) {
                                        nombre += ' ' + response.person.per_nombre2;
                                    }
                                    $('#nombre').val(nombre.trim());

                                    var apellidos = '';
                                    if (response.person.per_apellido1) {
                                        apellidos += response.person.per_apellido1;
                                    }
                                    if (response.person.per_apellido2) {
                                        apellidos += ' ' + response.person.per_apellido2;
                                    }
                                    $('#apellidos').val(apellidos.trim());
                                    // Actualiza el campo de celular
                                    $('#per_celular').val(response.person.per_celular);

                                    // Actualiza el campo de correo
                                    $('#per_correo').val(response.person.per_correo);
                                } else {
                                    $('#nombre').val('');
                                    $('#apellidos').val('');
                                }
                            },
                            error: function() {
                                $('#nombre').val('');
                                $('#apellidos').val('');
                            }
                        });
                    } else {
                        $('#nombre').val('');
                        $('#apellidos').val('');
                    }
                });

                $('#sede, #programa').on('change', function() {
                    var sedeId = $('#sede').val();
                    var proId = $('#programa').val();
                    var selectedTurnoId =
                        '{{ $inscripcion->pro_tur_id }}'; // Asumiendo que tienes la variable $inscripcion en tu Blade

                    if (sedeId && proId) {
                        $.ajax({
                            url: '{{ route('admin.search.turnos') }}',
                            method: 'GET',
                            data: {
                                sede_id: sedeId,
                                pro_id: proId
                            },
                            success: function(response) {
                                $('#turno').html('<option value="">Seleccione un turno</option>');
                                if (response.length > 0) {
                                    response.forEach(function(turno) {
                                        var selected = turno.pro_tur_id == selectedTurnoId ?
                                            'selected' : '';
                                        $('#turno').append(
                                            `<option value="${turno.pro_tur_id}" ${selected}>${turno.pro_tur_nombre}</option>`
                                        );
                                    });
                                }
                            },
                            error: function() {
                                $('#turno').html('<option value="">Seleccione un turno</option>');
                            }
                        });
                    } else {
                        $('#turno').html('<option value="">Seleccione un turno</option>');
                    }
                });

                // Trigger the change event to populate the "turno" options when the page loads
                $('#sede, #programa').trigger('change');
            });
        </script>
    @endsection
