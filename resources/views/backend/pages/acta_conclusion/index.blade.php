@extends('backend.layouts.master')

@section('title')
    Acta de Conclusión - Panel de Administración
@endsection

@section('styles')
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
                                <h4>Académico</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="page-header-breadcrumb">
                            <ul class="breadcrumb-title">
                                <li class="breadcrumb-item" style="float: left">
                                    <a href="#">
                                        <i class="feather icon-home"></i>
                                    </a>
                                </li>
                                <li class="breadcrumb-item" style="float: left">
                                    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item" style="float: left">
                                    <a href="#!">Académico</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-block">
                            <div class="row form-group">
                                <label class="col-sm-1 col-form-label">Sede</label>
                                <div class="col-sm-3">
                                    <select name="sede_id" id="sede" class="form-control" {{ $sedeCount === 1 ? 'disabled' : '' }}>
                                        <option value="">Seleccione una sede</option>
                                        @foreach ($sede as $se)
                                            <option value="{{ $se->sede_id }}" {{ $sedeCount === 1 ? 'selected' : '' }}>
                                                {{ $se->sede_nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <label class="col-sm-1 col-form-label">Programa</label>
                                <div class="col-sm-4">
                                    <select name="pro_id" id="programa" class="form-control"  >
                                        <option value="">Seleccione un programa</option>
                                        @foreach ($programa as $prog)
                                            <option value="{{ $prog->pro_id }}" >
                                               {{$prog->pro_tip_nombre}} en {{ $prog->pro_nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-block">
                            <div class="row form-group">
                                <div id="lista-resultados" class="col-sm-12"></div>
                                <div id="loading" class="text-center col-sm-12" style="display: none;">
                                    <img src="{{ asset('assets/image/loading.gif') }}" alt="Cargando..." style="width: 100px; height: 100px;">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Mejorado para Detalles de Inscripción -->
    <div class="modal fade" id="detalleInscripcionModal" tabindex="-1" aria-labelledby="detalleInscripcionLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content shadow-lg border-0">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="detalleInscripcionLabel">Detalles de la Inscripción</h5>
                </div>
                <div class="modal-body" id="modalDetallesContenido">
                    <!-- Aquí se insertará el contenido desde AJAX -->
                    <div class="spinner-border text-primary" role="status" id="loadingSpinner" style="display: none;">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        $(document).ready(function() {
             // Eventos de botones
             $(document).on('click', '.ver-detalles', function() {
                var inscripcionId = $(this).data('id');

                // Realiza una petición AJAX para obtener los datos de la inscripción
                $.ajax({
                    url: '{{ route("admin.obtener.detalles", "") }}/' + inscripcionId, // Ruta corregida
                    method: 'GET',
                    success: function(data) {
                        // Construir el contenido HTML para mostrar en el modal
                        var contenido = `
                            <div class="container-fluid">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <p><strong>C.I.:</strong> ${data.per_ci || ''} ${data.per_complemento ? ' - ' + data.per_complemento : ''}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Nombre:</strong> ${data.per_apellido1 || ''} ${data.per_apellido2 || ''}, ${data.per_nombre1 || ''} ${data.per_nombre2 || ''}</p>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <p><strong>Fecha de Nacimiento:</strong> ${data.per_fecha_nacimiento || ''}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Celular:</strong> ${data.per_celular || ''}</p>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <p><strong>Correo:</strong> ${data.per_correo || ''}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>En Función:</strong> ${data.per_en_funcion || ''}</p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <p><strong>Especialidad:</strong> ${data.esp_nombre || ''}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Categoría:</strong> ${data.cat_nombre || ''}</p>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <p><strong>Cargo:</strong> ${data.car_nombre || ''}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Subsistema:</strong> ${data.sub_nombre || ''}</p>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <p><strong>Nivel:</strong> ${data.niv_nombre || ''}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Género:</strong> ${data.gen_nombre || ''}</p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <p><strong>Programa:</strong> ${data.pro_nombre || ''} (${data.pro_nombre_abre || ''})</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Sede:</strong> ${data.sede_nombre || ''} (${data.sede_nombre_abre || ''})</p>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <p><strong>Turno:</strong> ${data.pro_tur_nombre || ''}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Fecha de Inscripción:</strong> ${data.created_at || ''}</p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <p><strong>Segunda Instancia:</strong> ${data.pi_modulo == 1 ? 'Sí' : 'No'}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Documento Digital:</strong> ${data.pi_doc_digital || ''}</p>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <p><strong>Total Depósito:</strong> Bs ${data.total_deposito ? Number(data.total_deposito).toLocaleString('es-BO') : '0'}</p>
                                    </div>
                                </div>
                            </div>
                        `;

                        // Llenar el contenido del modal con los datos obtenidos
                        $('#modalDetallesContenido').html(contenido);
                        $('#detalleInscripcionModal').modal('show'); // Muestra el modal
                    },
                    error: function(xhr, status, error) {
                            console.error("Error al obtener los detalles de la inscripción:", xhr.responseText); // Muestra el error en la consola
                            alert("Error al obtener los detalles de la inscripción: " + xhr.status + " " + error); // Muestra el código de error y el mensaje
                        }
                });
            });

            $('#sede, #programa').change(cargarListaFiltrada);

            var tables = [];

            function initDataTable() {
                $('.table').each(function() {
                    var tableId = $(this).attr('id');
                    if (!$.fn.DataTable.isDataTable('#' + tableId)) {
                        // Inicializa DataTable
                        var dataTableInstance = $('#' + tableId).DataTable({
                            responsive: false,
                            searching: true,
                            paging: true,
                            info: true,
                            language: {
                                url: '//cdn.datatables.net/plug-ins/1.11.3/i18n/es_es.json'
                            }
                        });

                        tables.push(dataTableInstance); // Guarda la instancia de DataTable
                    }
                });
            }

            function cargarListaFiltrada() {
                let sede_id = $('#sede').val();
                let programa_id = $('#programa').val();

                if (sede_id && programa_id) {
                    $('#loading').show();
                    $('#lista-resultados').html('');

                    $.ajax({
                        url: '{{ route("admin.filtrar.inscripcion") }}',
                        method: 'GET',
                        data: {
                            sede_id: sede_id,
                            programa_id: programa_id
                        },
                        success: function(response) {
                            $('#loading').hide();
                            $('#lista-resultados').html(response);
                            initDataTable(); // Inicializa la tabla después de cargar los datos

                            $('#myTab a').on('click', function (e) {
                                e.preventDefault();
                                $(this).tab('show');
                            });
                        },
                        error: function(xhr) {
                            console.error(xhr.responseText);
                            $('#loading').hide();
                            $('#lista-resultados').html('<p>Error al cargar los datos. Intente nuevamente más tarde.</p>');
                        }
                    });
                } else {
                    $('#lista-resultados').html('<p>Seleccione ambos campos para filtrar los resultados.</p>');
                }
            }

            // Filtros de búsqueda
            $('#searchInput').on('keyup', function() {
                var searchValue = $(this).val().toLowerCase();
                tables.forEach(function(table) {
                    table.search(searchValue).draw();
                });
            });
        });

    </script>

    <script src="{{ asset('backend/files/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/files/bower_components/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('backend/files/assets/pages/data-table/js/jszip.min.js') }}"></script>
    <script src="{{ asset('backend/files/assets/pages/data-table/js/vfs_fonts.js') }}"></script>
    <script src="{{ asset('backend/files/bower_components/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('backend/files/bower_components/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('backend/files/assets/pages/data-table/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('backend/files/bower_components/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('backend/files/bower_components/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('backend/files/assets/pages/data-table/js/data-table-custom.js') }}"></script>
@endsection
