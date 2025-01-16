@extends('backend.layouts.master')

@section('title')
    Participantes Diplomados
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
                                <h4>Buscar Participantes del Programa - PROFE</h4>
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
                                <li class="breadcrumb-item" style="float: left;"><a href="#!">Buscar Participantes del Programa - PROFE</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- data table start -->
                <div class="col-12 mt-5">
                    <div class="card">
                        <div class="card-body">

                            <div class="input-group mb-3 col-6">
                                <input type="text" id="ci" class="form-control" placeholder="Ingrese CI del participante">
                            </div>
                
                            <!-- Div donde se mostrarán los resultados -->
                            <div id="resultadoParticipante" class="mt-3"></div>
                            
                        </div>
                    </div>
                </div>
                
                <!-- data table end -->
            </div>
        </div>
    </div>
@endsection

@section('scripts')

    <script>
        $(document).ready(function () {
            $('#ci').on('input', function () {
                const ci = $(this).val(); // Obtiene el CI ingresado

                if (ci) {
                    // Muestra un spinner o mensaje de carga
                    $('#resultadoParticipante').html('<p class="text-primary">Buscando participante... <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></p>');

                    $.ajax({
                        url: '{{ route("admin.buscar.participante") }}', // Ruta hacia el controlador que manejará la búsqueda
                        type: 'GET',
                        data: { ci: ci },
                        success: function (response) {
                            $('#resultadoParticipante').html(response);
                        },
                        error: function () {
                            $('#resultadoParticipante').html('<p class="text-danger">Error al buscar el participante.</p>');
                        }
                    });
                } else {
                    $('#resultadoParticipante').html('<p class="text-warning">Por favor, ingrese un CI.</p>');
                }
            });
        });

    </script>
@endsection
