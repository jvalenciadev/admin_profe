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
                                    <h4 class="text-white">{{ $total_permissions }}</h4>
                                    <h3 class="text-white m-b-0">
                                        Permisos
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
                <div class="col-xl-12 col-md-12 ">
                    <div class="card o-hidden shadow-lg mb-4">
                        <div class="card-header py-3 bg-primary text-white">
                            <h2 class="text-center mb-0">Reporte de Inscripciones por Departamento</h2>
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

            // Gráfico de Inscritos por Sede
            createChart("proj-sede", @json(
                $inscritosSede->map(function ($item) {
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
                        balloonText: "Cantidad Inscrito: <b>[[value]]</b>",
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
