@extends('backend.layouts.master')

@section('title')
    Dashboard Page - Admin Panel
@endsection


@section('admin-content')
    <!-- page title area start -->

    <div class="page-wrapper">
        <div class="page-body">
            <div class="row">
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-c-yellow update-card">
                        <a href="{{ route('admin.roles.index') }}">
                            <div class="card-block">
                                <div class="row align-items-end">
                                    <div class="col-8">
                                        <h4 class="text-white">{{ $total_roles }}</h4>
                                        <h3 class="text-white m-b-0">Roles</h3>
                                    </div>
                                    <div class="col-4 text-right">
                                        <canvas id="update-chart-1" height="50"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <p class="text-white m-b-0">
                                    <i class="feather icon-clock text-white f-14 m-r-10"></i>Actualizado:
                                    <?php echo NOW(); ?>
                                </p>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-c-green update-card">
                        <a href="{{ route('admin.admins.index') }}">
                            <div class="card-block">
                                <div class="row align-items-end">
                                    <div class="col-8">
                                        <h4 class="text-white">{{ $total_admins }}</h4>
                                        <h3 class="text-white m-b-0">Admins</h3>
                                    </div>
                                    <div class="col-4 text-right">
                                        <canvas id="update-chart-2" height="50"></canvas>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <div class="card-footer">
                            <p class="text-white m-b-0">
                                <i class="feather icon-clock text-white f-14 m-r-10"></i>Actualizado: <?php echo NOW(); ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-c-pink update-card">
                        <div class="card-block">
                            <div class="row align-items-end">
                                <div class="col-8">
                                    <h4 class="text-white">{{ $total_preinscritos }}</h4>
                                    <h3 class="text-white m-b-0">
                                        Preinscritos
                                    </h3>
                                </div>
                                <div class="col-4 text-right">
                                    <canvas id="update-chart-3" height="50"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <p class="text-white m-b-0">
                                <i class="feather icon-clock text-white f-14 m-r-10"></i>Actualizado: <?php echo NOW(); ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-c-lite-green update-card">
                        <div class="card-block">
                            <div class="row align-items-end">
                                <div class="col-8">
                                    <h4 class="text-white">{{ $total_inscritos }}</h4>
                                    <h3 class="text-white m-b-0">Inscritos</h3>
                                </div>
                                <div class="col-4 text-right">
                                    <canvas id="update-chart-4" height="50"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <p class="text-white m-b-0">
                                <i class="feather icon-clock text-white f-14 m-r-10"></i>Actualizado: <?php echo NOW(); ?>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-xl-12 col-md-12">
                    <div class="card-header py-1 bg-white text-black">
                        <h2 class="text-center mb-1">Reporte de Inscritos Gestión 2025</h2>
                    </div>
                    <div class="card">
                        <div class="card-block bg-c-lite-green">
                            <div id="proj-sede" style="height: 350px"></div>
                        </div>
                        {{-- <div class="card-footer">
                            <h4 class="m-b-0 f-w-600">3500</h4>
                            <h6 class="text-muted m-b-30 m-t-15">
                                Total completed project and earning
                            </h6>
                            <div class="row text-center">
                                <div class="col-6 b-r-default">
                                    <h6 class="text-muted m-b-10">
                                        Completed Projects
                                    </h6>
                                    <h4 class="m-b-0 f-w-600">175</h4>
                                </div>
                                <div class="col-6">
                                    <h6 class="text-muted m-b-10">
                                        Total Inscritos
                                    </h6>
                                    
                                </div>
                            </div>
                        </div> --}}
                    </div>
                </div>
                <div class="col-xl-12 col-md-12">
                    
                    <div class="card">
                        <div class="card-block bg-c-green">
                            <div id="proj-departamento" style="height: 350px"></div>
                        </div>
                        {{-- <div class="card-footer">
                            <h4 class="m-b-0 f-w-600">3500</h4>
                            <h6 class="text-muted m-b-30 m-t-15">
                                Total completed project and earning
                            </h6>
                            <div class="row text-center">
                                <div class="col-6 b-r-default">
                                    <h6 class="text-muted m-b-10">
                                        Completed Projects
                                    </h6>
                                    <h4 class="m-b-0 f-w-600">175</h4>
                                </div>
                                <div class="col-6">
                                    <h6 class="text-muted m-b-10">
                                        Total Inscritos
                                    </h6>
                                    
                                </div>
                            </div>
                        </div> --}}
                    </div>
                </div>
                <div class="col-xl-12 col-md-12">
                    <div class="card-header py-1  bg-white text-black">
                        <h2 class="text-center mb-1">Reporte de Preinscritos Gestión 2025</h2>
                    </div>
                    <div class="card">
                        <div class="card-block bg-c-lite-green">
                            <div id="proj-sede-pre" style="height: 350px"></div>
                        </div>
                    </div>
                </div>
            
               
                <div class="col-xl-12 col-md-12">
                    <div class="card">
                        <div class="card-block bg-c-green">
                            <div id="proj-departamento-pre" style="height: 350px"></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-12 col-md-12 ">
                    <div class="card o-hidden shadow-lg mb-1">
                        <div class="card-header py-1 bg-primary text-white">
                            <h2 class="text-center mb-1">Reporte de Preinscritos por Departamento Gestión 2025</h2>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Programa</th>
                                            <th>Chuquisaca</th>
                                            <th>La Paz</th>
                                            <th>Cochabamba</th>
                                            <th>Oruro</th>
                                            <th>Potosí</th>
                                            <th>Tarija</th>
                                            <th>Santa Cruz</th>
                                            <th>Beni</th>
                                            <th>Pando</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($programas as $programa)
                                            <tr>
                                                <td>{{ $programa->pro_nombre_abre }}</td>
                                                <td>{{ $programa->CHUQUISACA }}</td>
                                                <td>{{ $programa->LA_PAZ }}</td>
                                                <td>{{ $programa->COCHABAMBA }}</td>
                                                <td>{{ $programa->ORURO }}</td>
                                                <td>{{ $programa->POTOSI }}</td>
                                                <td>{{ $programa->TARIJA }}</td>
                                                <td>{{ $programa->SANTA_CRUZ }}</td>
                                                <td>{{ $programa->BENI }}</td>
                                                <td>{{ $programa->PANDO }}</td>
                                                <td><strong>{{ $programa->TOTAL }}</strong></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-12 col-md-12 ">
                    <div class="card o-hidden shadow-lg mb-1">
                        <div class="card-header py-1 bg-primary text-white">
                            <h2 class="text-center mb-1">Reporte de Inscritos por Departamento Gestión 2025 </h2>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Programa</th>
                                            <th>Chuquisaca</th>
                                            <th>La Paz</th>
                                            <th>Cochabamba</th>
                                            <th>Oruro</th>
                                            <th>Potosí</th>
                                            <th>Tarija</th>
                                            <th>Santa Cruz</th>
                                            <th>Beni</th>
                                            <th>Pando</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($programasInscrito as $programa)
                                            <tr>
                                                <td>{{ $programa->pro_nombre_abre }}</td>
                                                <td>{{ $programa->CHUQUISACA }}</td>
                                                <td>{{ $programa->LA_PAZ }}</td>
                                                <td>{{ $programa->COCHABAMBA }}</td>
                                                <td>{{ $programa->ORURO }}</td>
                                                <td>{{ $programa->POTOSI }}</td>
                                                <td>{{ $programa->TARIJA }}</td>
                                                <td>{{ $programa->SANTA_CRUZ }}</td>
                                                <td>{{ $programa->BENI }}</td>
                                                <td>{{ $programa->PANDO }}</td>
                                                <td><strong>{{ $programa->TOTAL }}</strong></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-12 col-md-12 ">
                    <div class="card o-hidden shadow-lg mb-1">
                        <div class="card-header py-1 bg-primary text-white">
                            <h2 class="text-center mb-1">Reporte de Inscritos por Departamento Gestión 2024 </h2>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Programa</th>
                                            <th>Chuquisaca</th>
                                            <th>La Paz</th>
                                            <th>Cochabamba</th>
                                            <th>Oruro</th>
                                            <th>Potosí</th>
                                            <th>Tarija</th>
                                            <th>Santa Cruz</th>
                                            <th>Beni</th>
                                            <th>Pando</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($programasInscrito2024 as $programa)
                                            <tr>
                                                <td>{{ $programa->pro_nombre_abre }}</td>
                                                <td>{{ $programa->CHUQUISACA }}</td>
                                                <td>{{ $programa->LA_PAZ }}</td>
                                                <td>{{ $programa->COCHABAMBA }}</td>
                                                <td>{{ $programa->ORURO }}</td>
                                                <td>{{ $programa->POTOSI }}</td>
                                                <td>{{ $programa->TARIJA }}</td>
                                                <td>{{ $programa->SANTA_CRUZ }}</td>
                                                <td>{{ $programa->BENI }}</td>
                                                <td>{{ $programa->PANDO }}</td>
                                                <td><strong>{{ $programa->TOTAL }}</strong></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="col-xl-6 col-md-12">
                    <div class="card table-card">
                        <div class="card-header">
                            <h5>Campeonato Ajedrez</h5>
                        </div>
                        <div class="card-block">
                            <div class="table-responsive">
                                <table class="table table-hover table-borderless">
                                    <thead>
                                        <tr>
                                            <th>Status</th>
                                            <th>Nombre</th>
                                            <th>Departamento</th>
                                            <th>Fecha</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($participantes as $participante)
                                            <tr>
                                                <td><label class="label label-primary">MEJORES</label>
                                                </td>
                                                <td>{{ $participante->nombre_completo }}</td>
                                                <td>{{ $participante->dep_nombre }}</td>
                                                <td>{{ \Carbon\Carbon::parse($participante->updated_at)->translatedFormat('d \d\e M H:i') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div> --}}
                {{-- <div class="col-xl-4 col-md-6">
                    <div class="card o-hidden">
                        <div class="card-block bg-c-green text-white">
                            <h6>Visits<span class="f-right"><i class="feather icon-activity m-r-15"></i>9%</span>
                            </h6>
                            <canvas id="sale-chart2" height="150"></canvas>
                        </div>
                        <div class="card-footer text-center">
                            <div class="row">
                                <div class="col-6 b-r-default">
                                    <h4>3562</h4>
                                    <p class="text-muted m-b-0">Monthly Visits</p>
                                </div>
                                <div class="col-6">
                                    <h4>96</h4>
                                    <p class="text-muted m-b-0">Today Visits</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-12">
                    <div class="card o-hidden">
                        <div class="card-block bg-c-blue text-white">
                            <h6>Orders<span class="f-right"><i class="feather icon-activity m-r-15"></i>12%</span>
                            </h6>
                            <canvas id="sale-chart3" height="150"></canvas>
                        </div>
                        <div class="card-footer text-center">
                            <div class="row">
                                <div class="col-6 b-r-default">
                                    <h4>1695</h4>
                                    <p class="text-muted m-b-0">Monthly Orders</p>
                                </div>
                                <div class="col-6">
                                    <h4>52</h4>
                                    <p class="text-muted m-b-0">Today Orders</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>

        </div>

    </div>
    <div id="styleSelector"></div>
@endsection
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        // Mostrar la alerta con un diseño mejorado y mensaje claro
        Swal.fire({
            title: "Información Importante",
            text: "Los responsables deben revisar la lista de solicitudes, llegar a un acuerdo y confirmar o eliminar las solicitudes que no estén aprobadas, de acuerdo a su departamento.",
            icon: "info",
            background: '#ffffff', // Fondo blanco para más elegancia
            color: '#4e4e4e', // Color de texto elegante
            showCancelButton: true, // Habilita el botón de cancelar
            confirmButtonText: "Entendido",
            cancelButtonText: "Revisar solicitudes",
            confirmButtonColor: '#00b5e2', // Color suave para el botón "Entendido"
            cancelButtonColor: '#e74c3c', // Color suave para el botón "Revisar solicitudes"
            buttonsStyling: false, // Deshabilitar el estilo predeterminado de los botones
            customClass: {
                confirmButton: 'btn btn-primary', // Estilo elegante para el botón "Entendido"
                cancelButton: 'btn btn-outline-danger' // Estilo elegante para el botón "Revisar solicitudes"
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Si el usuario hace clic en "Entendido", se cierra la alerta
                console.log('Usuario entendió la alerta');
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                // Si el usuario hace clic en "Revisar solicitudes", redirige a la ruta
                window.location.href = "{{ route('admin.solicitudes.index') }}";
            }
        });
    });
</script>

<!-- Estilos personalizados -->
<style>
    /* Personalización de los botones en la alerta */
    .btn {
        padding: 12px 24px;
        font-size: 14px;
        border-radius: 25px;
        font-weight: bold;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    /* Botón "Entendido" con color elegante */
    .btn-primary {
        background-color: #00b5e2;
        border: none;
        color: white;
    }

    .btn-primary:hover {
        background-color: #008ba3;
    }

    /* Botón "Revisar solicitudes" con borde elegante */
    .btn-outline-danger {
        border: 2px solid #e74c3c;
        color: #e74c3c;
        background-color: transparent;
    }

    .btn-outline-danger:hover {
        background-color: #e74c3c;
        color: white;
    }

    /* Personalización de la ventana de la alerta */
    .swal2-popup {
        border-radius: 15px;
        font-family: 'Arial', sans-serif;
        padding: 20px 30px;
    }

    .swal2-title {
        font-size: 22px;
        font-weight: 600;
        color: #2c3e50;
    }

    .swal2-text {
        font-size: 16px;
        color: #7f8c8d;
    }

    /* Sombra elegante para la alerta */
    .swal2-popup {
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
    }

    /* Estilo del contenedor de la alerta */
    .swal2-container {
        z-index: 9999;
    }
</style>

<!-- Agregar estilos personalizados si es necesario -->
<style>
    /* Personalización de los botones en la alerta */
    .btn {
        padding: 10px 20px;
        font-size: 16px;
        border-radius: 5px;
        font-weight: bold;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .btn-success {
        background-color: #28a745;
        color: white;
    }

    .btn-danger {
        background-color: #dc3545;
        color: white;
    }

    .swal2-popup {
        border-radius: 10px;
        font-family: 'Arial', sans-serif;
    }

    .swal2-title {
        font-size: 20px;
        color: #333;
    }

    .swal2-text {
        font-size: 16px;
        color: #555;
    }
</style>
    <script>
        $(document).ready(function() {
            // Gráfico de Inscritos por Departamento
            createChart("proj-departamento", @json(
                $inscritosDep->map(function ($item) {
                    return [
                        'type' => $item->dep_abreviacion,
                        'visits' => (int) $item->total_inscripciones,
                    ];
                })));
            createChart("proj-departamento-pre", @json(
                $preinscritosDep->map(function ($item) {
                    return [
                        'type' => $item->dep_abreviacion,
                        'visits' => (int) $item->total_inscripciones,
                    ];
                })));

            // Gráfico de Inscritos por Sede
            createChart("proj-sede", @json(
                $inscritosSede->map(function ($item) {
                    return [
                        'type' => $item->sede_nombre_abre,
                        'visits' => (int) $item->total_inscripciones,
                    ];
                })));
            createChart("proj-sede-pre", @json(
                $preinscritoSede->map(function ($item) {
                    return [
                        'type' => $item->sede_nombre_abre,
                        'visits' => (int) $item->total_inscripciones,
                    ];
                })));

            // Función para crear gráficos de AmCharts
            function createChart(chartId, data) {
                AmCharts.makeChart(chartId, {
                    type: "serial",
                    hideCredits: !0,
                    theme: "light",
                    dataProvider: data,
                    valueAxes: [{
                        gridAlpha: 0.3,
                        gridColor: "#fff",
                        axisColor: "transparent",
                        color: "#fff",
                        dashLength: 0,
                    }],
                    gridAboveGraphs: !0,
                    startDuration: 1,
                    graphs: [{
                        balloonText: "Cantidad: <b>[[value]]</b>",
                        fillAlphas: 1,
                        lineAlpha: 1,
                        lineColor: "#fff",
                        type: "column",
                        valueField: "visits",
                        columnWidth: 0.5,
                    }],
                    chartCursor: {
                        categoryBalloonEnabled: !1,
                        cursorAlpha: 0,
                        zoomable: !1
                    },
                    categoryField: "type",
                    categoryAxis: {
                        gridPosition: "start",
                        gridAlpha: 0,
                        axesAlpha: 0,
                        lineAlpha: 0,
                        fontSize: 10,
                        color: "#fff",
                        tickLength: 0,
                    },
                    export: {
                        enabled: !1
                    },
                });
            }

            
        });
    </script>
    <script src="{{ asset('backend/files/assets/pages/widget/amchart/amcharts.js') }}"></script>
    <script src="{{ asset('backend/files/assets/pages/widget/amchart/serial.js') }}"></script>
    <script src="{{ asset('backend/files/assets/pages/widget/amchart/light.js') }}"></script>
    <script type="text/javascript" src="{{ asset('backend/files/assets/js/SmoothScroll.js') }}"></script>
    </script>
@endsection
