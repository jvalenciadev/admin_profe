@extends('backend.layouts.master')

@section('title')
    Campeonato Ajedrez - Admin Panel
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('backend/files/bower_components/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('backend/files/assets/pages/data-table/css/buttons.dataTables.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('backend/files/bower_components/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('backend/files/bower_components/switchery/dist/switchery.min.css') }}" />
@endsection

@section('admin-content')
    <div class="main-body">
        <div class="page-wrapper">
            <div class="page-header">
                <div class="row align-items-end">
                    <div class="col-lg-8">
                        <div class="page-header-title">
                            <div class="d-inline">
                                <h4>Calificaciones</h4>
                                <span>{{ $inscripciones->first()->dep_nombre ?? '' }} - {{ $inscripciones->first()->sede_nombre ?? 'Nombre de Sede' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="page-header-breadcrumb">
                            <ul class="breadcrumb-title">
                                <li class="breadcrumb-item">
                                    <a href="../index-2.html">
                                        <i class="feather icon-home"></i>
                                    </a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="#!">Calificaciones</a>
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
                                </ul>
                            </div>
                        </div>
                        <div class="card-block">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="sub-title">
                                        {{ $inscripciones->first()->dep_nombre ?? '' }} - {{ $inscripciones->first()->sede_nombre ?? 'Nombre de Sede' }}
                                    </div>
                                    @include('backend.layouts.partials.messages')

                                    <ul class="nav nav-tabs tabs" role="tablist" >
                                            <h6>
                                                <strong>{{ $inscripciones->first()->pro_nombre_abre }}</strong> 
                                            </h6>
                                    </ul>
                                    <br>
                                    <div class="tab-content">
                                        @foreach ($inscripciones->groupBy('pro_id') as $pro_id => $inscripcionesGrouped)
                                            <div class="tab-pane {{ $loop->first ? 'active' : '' }}" id="tab_{{ $pro_id }}">
                                                <div class="dt-responsive table-responsive">
                                                    <table id="dataTable{{ $loop->index }}" class="table table-striped table-bordered nowrap">
                                                        <thead>
                                                            <tr>
                                                                <th>Nro</th>
                                                                <th>CI</th>
                                                                <th>Nombre</th>
                                                                <th>Estado</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($inscripcionesGrouped as $inscripcion)
                                                                <tr>
                                                                    <td>{{ $loop->iteration }}</td>
                                                                    <td>{{ $inscripcion->per_ci }}</td>
                                                                    <td>{{ $inscripcion->per_nombre1 }} {{ $inscripcion->per_nombre2 }} {{ $inscripcion->per_apellido1 }} {{ $inscripcion->per_apellido2 }}</td>
                                                                    <td>
                                                                        @php
                                                                            $pa_estado = $inscripcion->pa_estado ?? 'inactivo';
                                                                        @endphp
                                                                        <form action="{{ route('admin.ajedrez.storeajedrez', ['pi_id' => $inscripcion->pi_id]) }}" method="POST" id="form-{{ $inscripcion->pi_id }}">
                                                                            @csrf
                                                                            <input type="hidden" name="pa_estado" id="pa_estado_{{ $inscripcion->pi_id }}" value="{{ $pa_estado }}">
                                                                            <input type="checkbox" id="checkbox_{{ $inscripcion->pi_id }}" name="pa_estado_checkbox" class="js-switch" {{ $pa_estado === 'activo' ? 'checked' : '' }} data-form="form-{{ $inscripcion->pi_id }}" />
                                                                        </form>
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
@endsection

@section('scripts')
    <script src="{{ asset('backend/files/assets/pages/advance-elements/swithces.js') }}"></script>
    <script src="{{ asset('backend/files/bower_components/switchery/dist/switchery.min.js') }}"></script>
    <script src="{{ asset('backend/files/bower_components/bootstrap-tagsinput/dist/bootstrap-tagsinput.js') }}"></script>
    <script src="{{ asset('backend/files/bower_components/modernizr/feature-detects/css-scrollbars.js') }}"></script>
    <script src="{{ asset('backend/files/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/files/bower_components/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('backend/files/assets/pages/data-table/js/jszip.min.js') }}"></script>
    <script src="{{ asset('backend/files/assets/pages/data-table/js/vfs_fonts.js') }}"></script>
    <script src="{{ asset('backend/files/assets/pages/data-table/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('backend/files/bower_components/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('backend/files/bower_components/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('backend/files/assets/pages/data-table/js/data-table-custom.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Inicializar Switchery en los checkboxes
            var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
            elems.forEach(function(html) {
                var switchery = new Switchery(html, {
                    color: '#1AB394',
                    secondaryColor: '#BABABA'
                });

                html.onchange = function() {
                    var formId = $(html).data('form');
                    var form = $('#' + formId);
                    var isChecked = html.checked;
                    $('#pa_estado_' + formId.replace('form-', '')).val(isChecked ? 'activo' : 'inactivo');
                    form.submit();
                };
            });

            // Inicializar las tablas
            var tables = [];
            @foreach ($inscripciones->groupBy('pro_id') as $pro_id => $inscripcionesGrouped)
                tables.push($('#dataTable{{ $loop->index }}').DataTable({
                    responsive: true,
                    searching: true
                }));
            @endforeach

            // Configurar el buscador general
            $('#searchInput').on('keyup', function() {
                var searchValue = $(this).val().toLowerCase();
                tables.forEach(function(table) {
                    table.search(searchValue).draw();
                });
            });
        });
    </script>
@endsection
