<!DOCTYPE html>
<html>

<head>
    <title>MINEDU - PROFE</title>
    <style>
        body {
            font-size: 11px;
            /* Tamaño de fuente global para el cuerpo del documento */
            font-family: Arial, sans-serif;
            /* Cambia el tipo de fuente globalmente */
            margin: 0;
            padding: 0;
            background-image: url('data:image/jpeg;base64,{{ $fondo }}');
            background-position: left;
            background-repeat: no-repeat;
            background-position: 5px 630px;
        }

        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
            font-size: 10px;
            /* Tamaño de fuente reducido */
            table-layout: fixed;
            /* Fija el ancho de las columnas */
            background-color: #ffffff;
            /* Fondo blanco para la tabla */
        }

        .main-table th,
        .main-table td {
            padding: 1.2px, 1.2px;
            /* Espaciado reducido */
            padding-left: 4px;
            padding-right: 4px;
            /* Espaciado derecho */
            border: 0.25px solid #898989;
            text-align: left;
        }

        .main-table thead th {
            background-color: #cdcdcd;
            /* Fondo negro para el encabezado */
            color: #000000;
            /* Texto blanco para el encabezado */
            text-align: center;
            /* Centra el texto horizontalmente en el encabezado */
            vertical-align: middle;
            /* Centra el texto verticalmente en el encabezado */
            padding: 3px;
            /* Mayor espaciado en el encabezado */
        }

        .main-table th {
            background-color: #ffffff;
        }

        .main-table tr:nth-child(even) {
            background-color: #e0e0e0;
            /* Gris claro para filas pares */
        }

        .page-break {
            page-break-before: always;
            /* Fuerza un salto de página */
        }

        .page-number {
            position: fixed;
            bottom: 0;
            right: 0;
            padding: 10px;
            font-size: 10px;
            color: #000000;
        }

        @media print {
            @page {
                size: auto;
                margin: 10mm;
            }

            body {
                counter-reset: page;
            }

            .page-number:after {
                content: "Página " counter(page);
                counter-increment: page;
            }

            .page-break {
                page-break-before: always;
            }
        }
    </style>
</head>

<body>

    <header>
    </header>

    <main style="text-align: left; padding-top: 0px; padding-left: 12px; padding-right: 12px; padding-bottom: 25px;">
        <table width="100%">
            <tr>
                <td width="15%" style="text-align:left; position: relative; height: 100px;">
                    <img src="data:image/jpeg;base64,{{ $qr }}" alt=""
                        style="position: absolute; top: 0; left: 0;">
                    <img src="data:image/jpeg;base64,{{ $logo2 }}" alt="" width="50px"
                        style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                </td>
                <td width="70%" style="text-align:center;">
                    <strong>
                        <h2 style="margin: 0;"> REGISTRO ACADÉMICO</h2>
                    </strong>
                    <p style="margin: 0; font-size: 10px;">(Programa de Formación Especializada)</p>
                </td>
                <td width="15%" style="text-align:right;">
                    <img src="data:image/jpeg;base64,{{ $logo2 }}" alt="" width="150px">
                </td>
            </tr>
        </table>
        <table class="main-table">
            <tr>
                <th style="background-color: #cdcdcd;" width="20%">Gestión:</th>
                <th style="font-weight: normal;" colspan="3">{{ '  ' . $datos_programa->pv_gestion }}</th>
                <th colspan="4" style="font-weight: normal;">
                    {{ $datos_programa->pv_nombre . ' ' }}{{ $datos_programa->pv_numero }} -
                    {{ $datos_programa->pm_nombre }}
                </th>
            </tr>
            <tr>
                <th style="background-color: #cdcdcd;" width="20%">Sede:</th>
                <th colspan="7" style="font-weight: normal;">{{ $inscritos[0]->sede_nombre }}</th>
            </tr>
            <tr>
                <th style="background-color: #cdcdcd;" width="20%">Programa:</th>
                <th colspan="7" style="font-weight: normal;">{{ $datos_programa->programa_completo }}</th>
            </tr>
            <tr>
                <th style="background-color: #cdcdcd;" width="20%">Módulo:</th>
                <th colspan="7" style="font-weight: normal;">{{ $datos_programa->modulo_completo }}</th>
            </tr>
            <tr>
                <th style="background-color: #cdcdcd;" width="20%">Facilitador:</th>
                <th colspan="7" style="font-weight: normal;">
                    {{ $facilitador_nombre }} {{ $facilitador_apellidos }}
                </th>
            </tr>
            <tr>
                <th style="background-color: #cdcdcd;" width="20%">Periodo:</th>
                <th colspan="3" style="font-weight: normal;">
                    DE
                    <?php
                    // Formatear la fecha de inicio
                    $fechaInicio = \Carbon\Carbon::parse($datos_programa->pm_fecha_inicio)->format('d \d\e M Y');
                    // Formatear la fecha de fin
                    $fechaFin = \Carbon\Carbon::parse($datos_programa->pm_fecha_fin)->format('d \d\e M Y');
                    
                    // Array para traducir los meses de inglés a español
                    $mesesIngles = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                    $mesesEspanol = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
                    
                    // Reemplazar los meses en las fechas
                    $fechaInicio = str_replace($mesesIngles, $mesesEspanol, $fechaInicio);
                    $fechaFin = str_replace($mesesIngles, $mesesEspanol, $fechaFin);
                    
                    // Mostrar las fechas
                    echo $fechaInicio;
                    ?>
                    A
                    <?php echo $fechaFin; ?>
                </th>
                <th colspan="2" style="background-color: #cdcdcd;">#Grupo:</th>
                <th colspan="2" style="font-weight: normal; text-align:center;">
                    {{ $inscritos->first()->pro_tur_id }}</th>
            </tr>
        </table>
        <br>
        <table class="main-table" style="margin: 0;">
            <thead>
                <tr>
                    <th rowspan="2">N°</th>
                    <th colspan="2">PARTICIPANTES</th>
                    <th rowspan="2" style="font-weight: normal; font-size: 8px;">Evaluación del Producto del Módulo
                        (80)</th>
                    <th rowspan="2" style="font-weight: normal; font-size: 8px;">Producto del Módulo (20)</th>
                    <th rowspan="2" style="font-weight: normal; font-size: 8px;">Nota Final (100)</th>
                    <th rowspan="2" style="font-weight: normal; font-size: 8px;">Segunda Instancia (70)</th>
                    <th rowspan="2">ESTADO</th>
                    <th rowspan="2">VC</th>
                </tr>
                <tr>
                    <th>CARNET</th>
                    <th>NOMBRE COMPLETO</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalMatriculados = $inscritos->count();
                    $totalAprobados = 0;
                    $totalReprobados = 0;
                    $totalAbandono = 0;
                @endphp
                @foreach ($inscritos as $index => $inscripcion)
                    @if ($index == 30)
            </tbody>
        </table>

        <div class="page-break"></div>
        <table class="main-table" style="margin: 0;">
            <thead>
                <tr>
                    <th rowspan="2">N°</th>
                    <th colspan="2">PARTICIPANTES</th>
                    <th rowspan="2" style="font-weight: normal; font-size: 8px;">Evaluación del Producto del Módulo
                        (80)</th>
                    <th rowspan="2" style="font-weight: normal; font-size: 8px;">Producto del Módulo (20)</th>
                    <th rowspan="2" style="font-weight: normal; font-size: 8px;">Nota Final (100)</th>
                    <th rowspan="2" style="font-weight: normal; font-size: 8px;">Segunda Instancia (70)</th>
                    <th rowspan="2">ESTADO</th>
                    <th rowspan="2">VC</th>
                </tr>
                <tr>
                    <th>CARNET</th>
                    <th>NOMBRE COMPLETO</th>
                </tr>
            </thead>
            <tbody>
                @endif
                <tr>
                    <td width="3%" style="text-align: center;">{{ $index + 1 }}</td>
                    <td width="10%" style="text-align: center;">{{ $inscripcion->per_ci }}</td>
                    <td width="40%">{{ $inscripcion->nombre_completo }}</td>

                    @php
                        // Filtra las calificaciones del participante actual
                        $calificacion = $calificacion_participantes
                            ->where('pi_id', $inscripcion->pi_id)
                            ->where('ptc_id', 1)
                            ->first();
                    @endphp
                    <td width="12%" style="text-align: center;">{{ $calificacion->cp_puntaje ?? 0 }}</td>
                    @php
                        // Filtra las calificaciones del participante actual
                        $calificacion1 = $calificacion_participantes
                            ->where('pi_id', $inscripcion->pi_id)
                            ->where('ptc_id', 2)
                            ->first();
                    @endphp
                    <td style="text-align: center;">{{ $calificacion1->cp_puntaje ?? 0 }}</td>
                    @php
                        // Filtra las calificaciones del participante actual
                        $calificacion4 = $calificacion_participantes
                            ->where('pi_id', $inscripcion->pi_id)
                            ->where('ptc_id', 4)
                            ->first();
                    @endphp
                    <td style="text-align: center;">{{ $calificacion4->cp_puntaje ?? 0 }}</td>
                    @php
                        // Filtra las calificaciones del participante actual
                        $calificacion5 = $calificacion_participantes
                            ->where('pi_id', $inscripcion->pi_id)
                            ->where('ptc_id', 3)
                            ->first();
                    @endphp
                    <td style="text-align: center;">{{ $calificacion5->cp_puntaje ?? '' }}</td>
                    <td width="12%" style="text-align: center;">{{ strtoupper($calificacion4->cp_estado ?? '') }}
                    </td>


                    <td width="3%" style="text-align: center;">
                        @if (isset($calificacion4) &&
                                ($calificacion4->cp_puntaje !== null && $calificacion4->cp_puntaje !== '' && $calificacion4->cp_puntaje != 0))
                            @if ($calificacion4->cp_puntaje < 70)
                                @php $totalReprobados++; @endphp
                                DI
                            @elseif($calificacion4->cp_puntaje >= 70 && $calificacion4->cp_puntaje < 80)
                                @php $totalAprobados++; @endphp
                                DS
                            @elseif($calificacion4->cp_puntaje >= 80 && $calificacion4->cp_puntaje < 90)
                                @php $totalAprobados++; @endphp
                                DO
                            @elseif($calificacion4->cp_puntaje >= 90 && $calificacion4->cp_puntaje <= 100)
                                @php $totalAprobados++; @endphp
                                DP
                            @endif
                        @else
                            @php $totalAbandono++; @endphp
                            NN
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <p style="margin: 0; font-size: 7.2px;"><strong>DP</strong>=Desempeño Pleno; <strong>DO</strong>=Desempeño Óptimo;
            <strong>DP</strong>=Desempeño Suficiente; <strong>DI</strong>=Desempeño Insuficiente
        </p>
        <table class="main-table">
            <thead>
                <tr>
                    <th>MATRICULADOS</th>
                    <th>APROBADOS</th>
                    <th>REPROBADOS</th>
                    <th>ABANDONO</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="text-align: center;">{{ $totalMatriculados }}</td>
                    <td style="text-align: center;">{{ $totalAprobados }}</td>
                    <td style="text-align: center;">{{ $totalReprobados }}</td>
                    <td style="text-align: center;">{{ $totalAbandono }}</td>
                </tr>
            </tbody>
        </table>

        <br><br><br>
        <br><br><br>
        <br><br><br>
        <table width="100%">
            <tr>
                
                <td width="" style="text-align:center;">
                    <div>.................................................................</div>
                    <div><strong>{{ $facilitador_cargo }}</strong></div>
                </td>
                <td width="" style="text-align:center;">
                    <div>.................................................................</div>
                    <div><strong>{{ $responsable_cargo }}</strong></div>
                </td>
                <td width="" style="text-align:center;">
                    <div>.................................................................</div>
                    <div><strong>ASISTENTE ACADÉMICO/A</strong></div>
                </td>
            </tr>
        </table>
    </main>

    <footer>
        <footer>
            <div class="page-number"></div>
        </footer>
    </footer>

</body>

</html>
