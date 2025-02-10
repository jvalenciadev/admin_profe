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
                                            @if(isset($totalBaucheresPorSede))
                                                <p><strong>Total Baucheres Registrados:</strong> {{ $totalBaucheresPorSede->total_baucheres }}</p>
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
                                                    <a class="nav-link" data-toggle="tab" href="#tab_{{ $pro_id }}" role="tab">
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
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <h5 class="mb-0">{{ $inscripcionesGrouped->first()->pro_nombre }}</h5>
                                                    <div>
                                                        <a target="_blank" href="{{ route('admin.inscripcion.reporteinscritopdf', ['sede_id' => $sede_id, 'pro_id' => encrypt($inscripcionesGrouped->first()->pro_id)]) }}" class="btn btn-outline-danger waves-effect waves-light">
                                                            <i class="icofont icofont-file-pdf"></i> Reporte Pagos
                                                        </a>
                                                        {{-- <a target="_blank" href="{{ route('admin.inscripcion.reporteinscritopdf', ['sede_id' => $sede_id, 'pro_id' => encrypt($inscripcionesGrouped->first()->pro_id)]) }}" class="btn btn-outline-primary waves-effect waves-light">
                                                            <i class="icofont icofont-file-pdf"></i> Lista
                                                        </a> --}}
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
                                                                        <br><strong>|RDA:</strong>
                                                                        {{ $inscripcion->per_rda }}<br>
                                                                        <strong>|CI:</strong>
                                                                        {{ $inscripcion->per_ci }} {{ $inscripcion->per_complemento ? '-' . $inscripcion->per_complemento : '' }}<br>
                                                                        <strong>En funcion:
                                                                            {{ $inscripcion->per_en_funcion ? 'SI' : 'NO' }}
                                                                        </strong>
                                                                        @if (!empty($inscripcion->per_celular))
                                                                            <br><strong>Celular:</strong> <a
                                                                                href="https://wa.me/{{ '+591' . $inscripcion->per_celular }}"
                                                                                target="_blank">{{ $inscripcion->per_celular }}</a><br>
                                                                        @endif
                                                                    </td>
                                                                    <td>{{ $inscripcion->pro_tur_nombre }}  </td>
                                                                    <td>
                                                                        <div>
                                                                            <span style="font-weight: bold;">Total Pagado:</span> {{ $inscripcion->total_pagado }} Bs.
                                                                        </div>
                                                                        <div>
                                                                            <span style="font-weight: bold;">Restante:</span> {{ $inscripcion->restante }} Bs.
                                                                        </div>
                                                                        <div style="font-weight: bold; color: {{ $inscripcion->estado_pago == 'Completado' ? 'green' : ($inscripcion->estado_pago == 'Incompleto' ? 'red' : 'gray') }};">
                                                                            <span>
                                                                                @if($inscripcion->estado_pago == 'Completado')
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
                                                                        @if (Auth::guard('admin')->user()->can('inscripcion.pdfpago'))
                                                                            @if ($inscripcion->pie_nombre == 'INSCRITO')
                                                                                @if($inscripcion->estado_pago == 'Completado')
                                                                                    <a href="{{ route('admin.inscripcion.participantepagopdf', encrypt($inscripcion->pi_id)) }}"
                                                                                        class="btn btn-outline-danger waves-effect waves-light m-r-20">
                                                                                        <i class="icofont icofont-file-pdf"></i>
                                                                                    </a>
                                                                                @endif
                                                                            @endif
                                                                        @endif
                                                                        @if (Auth::guard('admin')->user()->can('inscripcion.pdflista'))
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
                                                                                        class="btn btn-warning btn-outline-warning waves-effect waves-light m-r-20">
                                                                                        <i class="icofont icofont-files"></i>
                                                                                    </a>
                                                                            @endif
                                                                        @endif
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

    <div id="styleSelector"></div>
@endsection


@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
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
                document.querySelector(tabLinks[activeTabIndex].getAttribute('href')).classList.add('active', 'show');
            } else {
                // Si no hay una pestaña almacenada o si el índice es inválido, activa la primera por defecto
                tabLinks[0].classList.add('active');
                document.querySelector(tabLinks[0].getAttribute('href')).classList.add('active', 'show');
            }

            tabLinks.forEach(function (link, index) {
                link.addEventListener('click', function () {
                    // Almacena el índice de la pestaña clickeada
                    localStorage.setItem('activeTabIndex', index);

                    // Desactiva todas las pestañas y contenidos
                    tabLinks.forEach(l => {
                        l.classList.remove('active');
                        document.querySelector(l.getAttribute('href')).classList.remove('active', 'show');
                    });

                    // Activa la pestaña clickeada y su contenido
                    link.classList.add('active');
                    document.querySelector(link.getAttribute('href')).classList.add('active', 'show');
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
