@extends('backend.layouts.master')

@section('title')
    Eventos - Admin Panel
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
                                <h4>Eventos</h4>
                                <span>Cuestionario</span>
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
                                    <a href="#!">Lista de Preguntas</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="page-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card shadow-sm">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                <h4 class="text-primary font-weight-bold">{{ $evento->eve_nombre }}</h4>
                                <div>
                                    @include('backend.layouts.partials.messages')
                                    @if (Auth::guard('admin')->user()->can('eventocuestionario.create') && $numeroDeCuestionario == 0)
                                        <a href="{{ route('admin.eventocuestionario.create', encrypt($evento->eve_id)) }}" class="btn btn-success btn-sm">
                                            <i class="icofont icofont-plus-circle"></i> Agregar Cuestionario
                                        </a>
                                    @elseif (Auth::guard('admin')->user()->can('eventocuestionario.edit') && $numeroDeCuestionario != 0)
                                        <a href="{{ route('admin.eventocuestionario.edit', encrypt($evento->eve_id)) }}" class="btn btn-warning btn-sm">
                                            <i class="icofont icofont-edit"></i> Editar Cuestionario
                                        </a>
                                    @endif
                                </div>
                            </div>
                            
                            @if ($numeroDeCuestionario != 0)
                            <div class="card-body">
                                <div class="mb-4">
                                    <h5 class="text-secondary">{{ $eveCuestionario[0]->eve_cue_titulo }}</h5>
                                    <p class="text-muted">{!! $eveCuestionario[0]->eve_cue_descripcion !!}</p>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <p><strong>Fecha de Inicio:</strong> {{ \Carbon\Carbon::parse($eveCuestionario[0]->eve_cue_fecha_ini)->format('d/m/Y H:i') }}</p>
                                    </div>
                                    <div class="col-sm-6">
                                        <p><strong>Fecha de Fin:</strong> {{ \Carbon\Carbon::parse($eveCuestionario[0]->eve_cue_fecha_fin)->format('d/m/Y H:i') }}</p>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <a href="{{ route('admin.eventocuestionario.create', encrypt($eveCuestionario[0]->eve_cue_id)) }}" class="btn btn-primary btn-sm">
                                        <i class="icofont icofont-question-circle"></i> Agregar Pregunta
                                    </a>
                                </div>
                            </div>
                            @endif
                        </div>
            
                        @if (Auth::guard('admin')->user()->can('eventocuestionario.create') && $numeroDeCuestionario != 0)
                        <div class="card mt-3">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="simpletable" class="table table-striped table-bordered">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>Nro</th>
                                                <th>Nombre</th>
                                                <th>Inscripciones</th>
                                                <th>Estado</th>
                                                <th>Fecha Actualizado</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Aquí irían los registros -->
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>Nro</th>
                                                <th>Nombre</th>
                                                <th>Inscripciones</th>
                                                <th>Estado</th>
                                                <th>Fecha Actualizado</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            
        </div>
    </div>

    <div id="styleSelector"></div>
@endsection


@section('scripts')
    <!-- Start datatable js -->
    <style>
        .permissions-column {
            width: 60%;
            word-wrap: break-word;
            white-space: normal;
        }
    </style>
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
        /*================================
                                                                    datatable active
                                                                    ==================================*/
    </script>
@endsection
