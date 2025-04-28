@extends('backend.layouts.master')

@section('title')
    Inscripciones - Admin Panel
@endsection

@section('styles')
    <link rel="stylesheet" type="text/css"
        href="{{ asset('backend/files/bower_components/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" />
    <link rel="stylesheet" type="text/css"
        href="{{ asset('backend/files/assets/pages/data-table/css/buttons.dataTables.min.css') }}" />
    <link rel="stylesheet" type="text/css"
        href="{{ asset('backend/files/bower_components/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" />
@endsection


@section('admin-content')
    <div class="main-body">
        <div class="page-wrapper">
            <div class="page-header">
                <div class="row align-items-end">
                    <div class="col-lg-8">
                        <div class="page-header-title">
                            <div class="d-inline">
                                <h4>Inscripciones</h4>
                                <span>{{ $inscripciones->first()->dep_nombre ?? '' }} -
                                    {{ $inscripciones->first()->sede_nombre ?? 'Nombre de Sede' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="page-header-breadcrumb">
                            <ul class="breadcrumb-title">
                                <li class="breadcrumb-item" style="float: left">
                                    <a href="../index-2.html">
                                        <i class="feather icon-home"></i>
                                    </a>
                                </li>
                                <li class="breadcrumb-item" style="float: left">
                                    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item" style="float: left">
                                    <a href="#!">Lista de Inscripciones</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-header-right">
                                <ul class="list-unstyled card-option">
                                    <li><i class="feather icon-maximize full-card"></i></li>
                                    {{-- <li><i class="feather icon-minus minimize-card"></i> --}}
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-block">

                            <div class="row">
                                <div class="col-lg-12 col-xl-12">
                                    <div class="sub-title">{{ $inscripciones->first()->dep_nombre ?? '' }} -
                                        {{ $inscripciones->first()->sede_nombre ?? 'Nombre de Sede' }}
                                    </div>
                                    @if (isset($totalBaucheresPorSede))
                                        <p><strong>Total Baucheres Registrados:</strong>
                                            {{ $totalBaucheresPorSede->total_baucheres }}</p>
                                    @else
                                        <p>No hay baucheres registrados para esta sede.</p>
                                    @endif
                                    <!-- El resto de tu contenido aquí -->
                                    @include('backend.layouts.partials.messages')
                                    @if (Auth::guard('admin')->user()->can('inscripcion.create'))
                                        <a class="btn btn-out btn-success btn-square"
                                            href="{{ route('admin.inscripcion.create', ['sede_id' => $sede_id]) }}">Preinscribir
                                        </a><br><br>
                                    @endif
                                    <ul class="nav nav-tabs tabs" role="tablist" id="inscripcionTabs">
                                        @if ($inscripciones->groupBy('pro_id')->count() > 1)
                                            @foreach ($inscripciones->groupBy('pro_id') as $pro_id => $inscripcionesGrouped)
                                                <li class="nav-item">
                                                    <a class="nav-link" data-toggle="tab" href="#tab_{{ $pro_id }}"
                                                        role="tab">
                                                        {{ $inscripcionesGrouped->first()->pro_nombre_abre }}
                                                    </a>
                                                    <div class="slide"></div>
                                                </li>
                                            @endforeach
                                        @else
                                            <h6>
                                                <strong>{{ $inscripciones->first()->pro_nombre_abre ?? '' }}</strong>
                                            </h6>
                                        @endif
                                    </ul>
                                    <br>
                                    <div class="tab-content tabs">
                                        @foreach ($inscripciones->groupBy('pro_id') as $pro_id => $inscripcionesGrouped)
                                            <div class="tab-pane {{ $inscripciones->groupBy('pro_id')->count() > 1 ? '' : 'active' }}"
                                                id="tab_{{ $pro_id }}" role="tabpanel">

                                                <div class="d-flex justify-content-between align-items-start mb-3">
                                                    {{-- Columna 1: Información del programa y cantidad por turno --}}
                                                    <div style="flex: 3;">
                                                        <h5 class="mb-0">{{ $inscripcionesGrouped->first()->pro_nombre }}
                                                        </h5>
                                                        {{-- Cantidad por Turno --}}
                                                        <div class="d-flex gap-2 flex-wrap" style="margin-top: 5px;">
                                                            @foreach ($inscripcionesGrouped->groupBy('pro_tur_id') as $pro_tur_id => $turnoGrouped)
                                                                <span class="badge bg-dark p-1 px-2"
                                                                    style="font-size: 0.75rem;">
                                                                    {{ $turnoGrouped->first()->pro_tur_nombre }}:
                                                                    {{ $turnoGrouped->count() }}
                                                                </span>
                                                            @endforeach
                                                        </div>
                                                    </div>

                                                    {{-- Columna 2: Información de estados y reporte --}}
                                                    <div style="flex: 1;">
                                                        <div class="d-flex gap-2 flex-wrap" style="justify-content: start;">
                                                            {{-- Estados --}}
                                                            <span class="badge bg-warning p-1 px-2"
                                                                style="font-size: 0.75rem;">Preinscrito:
                                                                {{ $inscripcionesGrouped->where('pie_id', 4)->count() }}</span>
                                                            <span class="badge bg-success p-1 px-2"
                                                                style="font-size: 0.75rem;">Inscrito:
                                                                {{ $inscripcionesGrouped->where('pie_id', 2)->count() }}</span>
                                                            <span class="badge bg-primary p-1 px-2"
                                                                style="font-size: 0.75rem;">Confirmado:
                                                                {{ $inscripcionesGrouped->where('pie_id', 7)->count() }}</span>
                                                            <span class="badge bg-danger p-1 px-2"
                                                                style="font-size: 0.75rem;">Baja:
                                                                {{ $inscripcionesGrouped->where('pie_id', 3)->count() }}</span>
                                                        </div>
                                                        {{-- Reporte --}}
                                                        <div class="mt-3">
                                                            <a target="_blank"
                                                                href="{{ route('admin.inscripcion.reporteinscritopdf', ['sede_id' => $sede_id, 'pro_id' => encrypt($inscripcionesGrouped->first()->pro_id)]) }}"
                                                                class="btn btn-outline-danger waves-effect waves-light">
                                                                <i class="icofont icofont-file-pdf"></i> Reporte Pagos
                                                            </a>
                                                            {{-- <a target="_blank" href="{{ route('admin.inscripcion.reporteinscritopdf', ['sede_id' => $sede_id, 'pro_id' => encrypt($inscripcionesGrouped->first()->pro_id)]) }}" class="btn btn-outline-primary waves-effect waves-light">
                                                                <i class="icofont icofont-file-pdf"></i> Lista
                                                            </a> --}}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="dt-responsive table-responsive">

                                                    <table id="dataTable{{ $loop->index }}"
                                                        class="table table-striped table-bordered nowrap">
                                                        <thead>
                                                            <tr>
                                                                <th>Nro</th>
                                                                <th>Nombre</th>
                                                                <th>Turno</th>
                                                                <th>Total Pagado</th>
                                                                <th>Estado</th>
                                                                <th>Fecha Actualizado</th>
                                                                <th>Acciones</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($inscripcionesGrouped as $inscripcion)
                                                                <tr>
                                                                    <td>{{ $loop->index + 1 }}</td>
                                                                    <td>
                                                                        {{ $inscripcion->per_nombre1 }}
                                                                        {{ $inscripcion->per_nombre2 }}
                                                                        {{ $inscripcion->per_apellido1 }}
                                                                        {{ $inscripcion->per_apellido2 }}
                                                                        {{-- <br><strong>|RDA:</strong> --}}
                                                                        {{-- {{ $inscripcion->per_rda }}<br> --}}
                                                                        <br><strong>CI:</strong>
                                                                        {{ $inscripcion->per_ci }}
                                                                        {{ $inscripcion->per_complemento ? '-' . $inscripcion->per_complemento : '' }}
                                                                        {{-- <br><strong>En funcion:
                                                                            {{ $inscripcion->per_en_funcion ? 'SI' : 'NO' }}
                                                                        </strong> --}}
                                                                        @if (!empty($inscripcion->per_celular))
                                                                            <br><strong>Celular:</strong> <a
                                                                                href="https://wa.me/{{ '+591' . $inscripcion->per_celular }}"
                                                                                target="_blank">{{ $inscripcion->per_celular }}</a><br>
                                                                        @endif
                                                                    </td>
                                                                    <td>{{ $inscripcion->pro_tur_nombre }} </td>
                                                                    <td>
                                                                        <div>
                                                                            <span style="font-weight: bold;">Total
                                                                                Pagado:</span>
                                                                            {{ $inscripcion->total_pagado }} Bs.
                                                                        </div>
                                                                        <div>
                                                                            <span
                                                                                style="font-weight: bold;">Restante:</span>
                                                                            {{ $inscripcion->restante }} Bs.
                                                                        </div>
                                                                        <div
                                                                            style="font-weight: bold; color: {{ $inscripcion->estado_pago == 'Completado' ? 'green' : ($inscripcion->estado_pago == 'Incompleto' ? 'red' : 'gray') }};">
                                                                            <span>
                                                                                @if ($inscripcion->estado_pago == 'Completado')
                                                                                    <i class="fa fa-check-circle"></i>
                                                                                @elseif($inscripcion->estado_pago == 'Incompleto')
                                                                                    <i class="fa fa-times-circle"></i>
                                                                                @else
                                                                                    <i class="fa fa-exclamation-circle"></i>
                                                                                @endif
                                                                            </span>
                                                                            {{ $inscripcion->estado_pago }}
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        @if ($inscripcion->pie_nombre == 'INSCRITO')
                                                                            <span
                                                                                class="label label-success">{{ $inscripcion->pie_nombre }}</span>
                                                                        @elseif ($inscripcion->pie_nombre == 'PREINSCRITO')
                                                                            <span
                                                                                class="label label-warning">{{ $inscripcion->pie_nombre }}</span>
                                                                        @elseif ($inscripcion->pie_nombre == 'CONFIRMADO')
                                                                            <span
                                                                                class="label label-warning">{{ $inscripcion->pie_nombre }}</span>
                                                                        @else
                                                                            <span
                                                                                class="label label-danger">{{ $inscripcion->pie_nombre }}</span>
                                                                        @endif
                                                                    </td>
                                                                    <td>{{ $inscripcion->updated_at }}</td>
                                                                    <td>
                                                                        @if (Auth::guard('admin')->user()->can('inscripcion.edit'))
                                                                            <a href="{{ route('admin.inscripcion.edit', encrypt($inscripcion->pi_id)) }}"
                                                                                class="btn btn-outline-info waves-effect waves-light m-r-20">
                                                                                <i class="icofont icofont-pencil-alt-5"></i>
                                                                            </a>
                                                                        @endif
                                                                        @if (Auth::guard('admin')->user()->can('inscripcion.delete'))
                                                                            <a href="{{ route('admin.inscripcion.edit', encrypt($inscripcion->pi_id)) }}"
                                                                                class="btn btn-outline-danger waves-effect waves-light m-r-20">
                                                                                <i class="icofont icofont-ui-delete"></i>
                                                                            </a>
                                                                        @endif
                                                                       
                                                                        {{-- @if (Auth::guard('admin')->user()->can('inscripcion.pdflista'))
                                                                            @if ($inscripcion->pie_nombre == 'INSCRITO')
                                                                                    <a href="{{ route('admin.inscripcion.formulariopdf', encrypt($inscripcion->pi_id)) }}"
                                                                                        class="btn btn-warning btn-outline-warning waves-effect waves-light m-r-20">
                                                                                        <i class="icofont icofont-files"></i>
                                                                                    </a>
                                                                            @endif
                                                                            @if ($inscripcion->pie_nombre == 'INSCRITO')
                                                                                <a href="{{ route('programa.comprobanteParticipantePdf', [
                                                                                    'per_id' => encrypt($inscripcion->per_id),
                                                                                    'pro_id' => encrypt($inscripcion->pro_id),
                                                                                ]) }}"
                                                                                    class="btn btn-warning btn-outline-warning waves-effect waves-light m-r-20" target="_blank">
                                                                                    <i class="icofont icofont-files"></i>
                                                                                </a>
                                                                            @endif
                                                                        @endif --}}
                                                                        <div class="btn-group">
                                                                            <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                                                <i class="icofont icofont-files"></i> <!-- Icono de PDF con IcoFont -->
                                                                            </button>
                                                                            <ul class="dropdown-menu">
                                                                                @if (Auth::guard('admin')->user()->can('inscripcion.pdflista'))
                                                                                    @if ($inscripcion->pie_nombre == 'INSCRITO')
                                                                                        <li>
                                                                                            <a class="dropdown-item" href="{{ route('programa.comprobanteParticipantePdf', [
                                                                                                'per_id' => encrypt($inscripcion->per_id),
                                                                                                'pro_id' => encrypt($inscripcion->pro_id),
                                                                                            ]) }}" target="_blank">
                                                                                                <i class="icofont icofont-files"></i> FICHA DE INSCRIPCIÓN
                                                                                            </a>
                                                                                        </li>
                                                                                    @endif
                                                                                @endif
                                                                                <li>
                                                                                    <a class="dropdown-item" href="{{ route('programa.compromisoParticipantePdf', [
                                                                                        'per_id' => encrypt($inscripcion->per_id),
                                                                                        'pro_id' => encrypt($inscripcion->pro_id),
                                                                                    ]) }}" target="_blank">
                                                                                        <i class="icofont icofont-files"></i> COMPROMISO
                                                                                    </a>
                                                                                </li>
                                                                                <li>
                                                                                    <a class="dropdown-item" href="{{ route('programa.rotuloParticipantePdf', [
                                                                                        'per_id' => encrypt($inscripcion->per_id),
                                                                                        'pro_id' => encrypt($inscripcion->pro_id),
                                                                                    ]) }}" target="_blank">
                                                                                        <i class="icofont icofont-files"></i> RÓTULO
                                                                                    </a>
                                                                                </li>
                                                                                @if (Auth::guard('admin')->user()->can('inscripcion.pdfpago'))
                                                                                    @if ($inscripcion->pie_nombre == 'INSCRITO')
                                                                                        @if ($inscripcion->estado_pago == 'Completado')
                                                                                            <li>
                                                                                                <a class="dropdown-item" href="{{ route('admin.inscripcion.participantepagopdf', encrypt($inscripcion->pi_id)) }}"
                                                                                                    target="_blank" >
                                                                                                    <i class="icofont icofont-files"></i> CONCLUSIÓN
                                                                                                    DE PAGOS
                                                                                                </a>
                                                                                            </li>
                                                                                        @endif
                                                                                    @endif
                                                                                @endif
                                                                            </ul>
                                                                        </div>
                                                                        <a
                                                                            class="btn btn-outline-primary waves-effect waves-light btn-view"
                                                                            data-toggle="modal" data-target="#viewModal"
                                                                            data-nombre="{{ $inscripcion->per_nombre1 }} {{ $inscripcion->per_nombre2 }} {{ $inscripcion->per_apellido1 }} {{ $inscripcion->per_apellido2 }}"
                                                                            data-ci="{{ $inscripcion->per_ci }}{{ $inscripcion->per_complemento ? '-' . $inscripcion->per_complemento : '' }}"
                                                                            data-fecha_nacimiento="{{ $inscripcion->per_fecha_nacimiento }}"
                                                                            data-celular="{{ $inscripcion->per_celular }}"
                                                                            data-correo="{{ $inscripcion->per_correo }}"
                                                                            data-en_funcion="{{ $inscripcion->per_en_funcion ? 'SI' : 'NO' }}"
                                                                            data-esp="{{ $inscripcion->pi_licenciatura }}"
                                                                            data-car="{{ $inscripcion->pi_materia }}"
                                                                            data-subsistema="{{ $inscripcion->pi_subsistema }}"
                                                                            data-unidad="{{ $inscripcion->pi_unidad_educativa }}"
                                                                            data-nivel="{{ $inscripcion->pi_nivel }}"
                                                                            data-genero="{{ $inscripcion->gen_nombre }}">
                                                                            <i class="icofont icofont-eye"></i>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>

                                                    </table>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                </div>
                            </div>


                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewModalLabel">Detalles del Participante</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><strong>Nombre:</strong> <span id="modal-nombre"></span></p>
                    <p><strong>CI:</strong> <span id="modal-ci"></span></p>
                    <p><strong>Fecha de Nacimiento:</strong> <span id="modal-fecha_nacimiento"></span></p>
                    <p><strong>Celular:</strong> <span id="modal-celular"></span></p>
                    <p><strong>Correo:</strong> <span id="modal-correo"></span></p>
                    <p><strong>En Función:</strong> <span id="modal-en_funcion"></span></p>
                    <p><strong>Licenciatura en:</strong> <span id="modal-esp"></span></p>
                    <p><strong>Cargo:</strong> <span id="modal-car"></span></p>
                    <p><strong>Institución:</strong> <span id="modal-unidad"></span></p>
                    <p><strong>Subsistema:</strong> <span id="modal-subsistema"></span></p>
                    <p><strong>Nivel:</strong> <span id="modal-nivel"></span></p>
                    <p><strong>Género:</strong> <span id="modal-genero"></span></p>
                </div>
            </div>
        </div>
    </div>
    <div id="styleSelector"></div>
@endsection


@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $('.btn-view').click(function() {
            $('#modal-nombre').text($(this).data('nombre'));
            $('#modal-ci').text($(this).data('ci'));
            $('#modal-fecha_nacimiento').text($(this).data('fecha_nacimiento'));
            $('#modal-celular').text($(this).data('celular'));
            $('#modal-correo').text($(this).data('correo'));
            $('#modal-en_funcion').text($(this).data('en_funcion'));
            $('#modal-esp').text($(this).data('esp'));
            $('#modal-car').text($(this).data('car'));
            $('#modal-subsistema').text($(this).data('subsistema'));
            $('#modal-nivel').text($(this).data('nivel'));
            $('#modal-unidad').text($(this).data('unidad'));
            $('#modal-genero').text($(this).data('genero'));
        });
    </script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var tabLinks = document.querySelectorAll('#inscripcionTabs .nav-link');
            var activeTabIndex = localStorage.getItem('activeTabIndex');

            if (activeTabIndex !== null && activeTabIndex < tabLinks.length) {
                // Desactiva todas las pestañas y contenidos primero
                tabLinks.forEach(link => {
                    link.classList.remove('active');
                    document.querySelector(link.getAttribute('href')).classList.remove('active', 'show');
                });

                // Activa la pestaña almacenada en localStorage
                tabLinks[activeTabIndex].classList.add('active');
                document.querySelector(tabLinks[activeTabIndex].getAttribute('href')).classList.add('active',
                    'show');
            } else {
                // Si no hay una pestaña almacenada o si el índice es inválido, activa la primera por defecto
                tabLinks[0].classList.add('active');
                document.querySelector(tabLinks[0].getAttribute('href')).classList.add('active', 'show');
            }

            tabLinks.forEach(function(link, index) {
                link.addEventListener('click', function() {
                    // Almacena el índice de la pestaña clickeada
                    localStorage.setItem('activeTabIndex', index);

                    // Desactiva todas las pestañas y contenidos
                    tabLinks.forEach(l => {
                        l.classList.remove('active');
                        document.querySelector(l.getAttribute('href')).classList.remove(
                            'active', 'show');
                    });

                    // Activa la pestaña clickeada y su contenido
                    link.classList.add('active');
                    document.querySelector(link.getAttribute('href')).classList.add('active',
                        'show');
                });
            });
        });
    </script>
    <script src="{{ asset('backend/files/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/files/bower_components/datatables.net-buttons/js/dataTables.buttons.min.js') }}">
    </script>
    <script src="{{ asset('backend/files/assets/pages/data-table/js/jszip.min.js') }}"></script>
    <script src="{{ asset('backend/files/assets/pages/data-table/js/vfs_fonts.js') }}"></script>
    <script src="{{ asset('backend/files/bower_components/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('backend/files/bower_components/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('backend/files/assets/pages/data-table/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('backend/files/bower_components/datatables.net-responsive/js/dataTables.responsive.min.js') }}">
    </script>
    <script
        src="{{ asset('backend/files/bower_components/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}">
    </script>
    <script src="{{ asset('backend/files/assets/pages/data-table/js/data-table-custom.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Inicializar las tablas
            var tables = [];
            @foreach ($inscripciones->groupBy('pro_id') as $pro_id => $inscripcionesGrouped)
                var table{{ $loop->index }} = $('#dataTable{{ $loop->index }}').DataTable({
                    responsive: false,
                    searching: true, // Activa la búsqueda interna de DataTables
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.11.3/i18n/es_es.json'
                    }

                });
                tables.push(table{{ $loop->index }});
            @endforeach

            // Configurar el buscador general
            $('#searchInput').on('keyup', function() {
                var searchValue = $(this).val().toLowerCase();
                tables.forEach(function(table) {
                    table.columns().every(function() {
                        var column = this;
                        column.search(searchValue, false, true).draw();
                    });
                });
            });
        });
    </script>
@endsection
