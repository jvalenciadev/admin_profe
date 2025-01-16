<!DOCTYPE html>
<html>

<head>
    <title>MINEDU - PROFE</title>
    <style>
        html{
            height: 100%; /* Asegura que el html y el body ocupen toda la altura de la ventana */
            margin: 0px 0px 0px 0px; /* Eliminar márgenes */
           
            overflow: hidden; 
        }
        body {
            font-size: 11px;
            /* Tamaño de fuente global para el cuerpo del documento */
            font-family: Arial, sans-serif;
            background-image: url('data:image/jpeg;base64,{{ $fondo }}');
            background-position: center bottom;
            background-repeat: no-repeat;
            background-size: 100% auto;
            padding: 100px 0 0 0;
            width: 21.59cm; /* Ancho de carta */
            height: 27.94cm; /* Alto de carta */
        }

        .main-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
            /* Tamaño de fuente reducido */
            table-layout: fixed;
            /* Fija el ancho de las columnas */
            background-color: #ffffff;
            /* Fondo blanco para la tabla */
        }

        .main-table th,
        .main-table td {
            padding: 1px, 1px;
            /* Espaciado reducido */
            padding-left: 4px;
            padding-right: 4px;
            /* Espaciado derecho */
            border: 0.15px solid #898989;
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
        }
        
        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100px; /* Ajusta según la altura real del encabezado */
            text-align: left;
            padding: 20px 0px 0px 50px;
        }

        footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            padding: 0px 0px 75px 55px;
            text-align: left;
            font-size: 9px;
        }
    </style>
</head>

<body>
    <header>
        <table width="100%" style="vertical-align: middle;">
            <tr style="">
                <td style="text-align:left; vertical-align: middle;">
                    <img src="data:image/jpeg;base64,{{ $logo5 }}" alt="" width="500">
                </td>
               
            </tr>
        </table>
        
    </header>
    <div class="content">
    <main style="text-align: left; padding: 0px 55px 120px 55px; ">
        <table width="100%">
            <tr>
                <td width="70%" style="text-align:center;">
                    <strong>
                        <h2 style="margin: 0;"> REGISTRO ACADÉMICO</h2>
                    </strong>
                    {{-- <p style="margin: 0; font-size: 10px;">(Programa de Formación Especializada - PROFE)</p> --}}
                </td>
            </tr>
        </table>
        <br>
        <table class="main-table">
            <tr>
                <th style="background-color: #cdcdcd;" width="20%">PROGRAMA:</th>
                <th colspan="7" style="font-weight: normal;">PROGRAMA DE FORMACIÓN ESPECIALIZADA - PROFE</th>
            </tr>
            <tr>
                <th style="background-color: #cdcdcd;" width="20%">GESTIÓN:</th>
                <th style="font-weight: normal;" colspan="3">{{ '  ' . $datos_programa->pv_gestion }}</th>
                <th colspan="4" style="font-weight: normal;">
                    {{ $datos_programa->pv_nombre . ' ' }}{{ $datos_programa->pv_numero }} -
                    {{ $datos_programa->pm_nombre }}
                </th>
            </tr>
            
            <tr>
                <th style="background-color: #cdcdcd;" width="20%">SEDE:</th>
                <th colspan="7" style="font-weight: normal;">{{ $inscritos[0]->sede_nombre }}</th>
            </tr>
            <tr>
                <th style="background-color: #cdcdcd;" width="20%">{{ $datos_programa->programa_tipo }}:</th>
                <th colspan="7" style="font-weight: normal;">{{ $datos_programa->programa }}</th>
            </tr>
            <tr>
                <th style="background-color: #cdcdcd;" width="20%">
                    {{ $datos_programa->modulo }}:
                    </th>
                <th colspan="7" style="font-weight: normal;">{{ $datos_programa->modulo_completo }}</th>
            </tr>
            <tr>
                <th style="background-color: #cdcdcd;" width="20%">FACILITADOR:</th>
                <th colspan="7" style="font-weight: normal;">
                    {{ $facilitador_nombre }} {{ $facilitador_apellidos }}
                </th>
            </tr>
            <tr>
                <th style="background-color: #cdcdcd;" width="20%">PERIODO:</th>
                <th colspan="3" style="font-weight: normal;">
                    DE
                    <?php
                    // Formatear la fecha de inicio
                    $fechaInicio = \Carbon\Carbon::parse($datos_programa->pm_fecha_inicio)->format('d/M/Y');
                    // Formatear la fecha de fin
                    $fechaFin = \Carbon\Carbon::parse($datos_programa->pm_fecha_fin)->format('d/M/Y');
                    
                    // Array para traducir los meses de inglés a español
                    $mesesIngles = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                    $mesesEspanol = ['ENE', 'FEB', 'MAR', 'ABR', 'MAY', 'JUN', 'JUL', 'AGO', 'SEP', 'OCT', 'NOV', 'DIC'];
                    
                    // Reemplazar los meses en las fechas
                    $fechaInicio = str_replace($mesesIngles, $mesesEspanol, $fechaInicio);
                    $fechaFin = str_replace($mesesIngles, $mesesEspanol, $fechaFin);
                    
                    // Mostrar las fechas
                    echo $fechaInicio;
                    ?>
                    A
                    <?php echo $fechaFin; ?>
                </th>
                <th colspan="2" style="background-color: #cdcdcd;">GRUPO:</th>
                <th colspan="2" style="font-weight: normal; text-align:center;">
                    {{ $inscritos->first()->pro_tur_id }}</th>
            </tr>
        </table>
        <br>
        <table class="main-table" >
            <thead style="">
                <tr>
                    <th rowspan="2">N°</th>
                    <th colspan="2">PARTICIPANTES</th>
                    <th rowspan="2" style="font-weight: normal; font-size: 8px;">Evaluación del Producto del Módulo
                        (80)</th>
                    <th rowspan="2" style="font-weight: normal; font-size: 8px;">Producto del Módulo (20)</th>
                    <th rowspan="2" style="font-weight: normal; font-size: 8px;">Nota Final (100)</th>
                    @if ($datos_programa->pro_tip_id == 3)
                        <th rowspan="2" style="font-weight: normal; font-size: 8px;">Segunda Instancia (70)</th>
                    @endif
                    <th rowspan="2">OBSERVACIÓN</th>
                    <th rowspan="2">VC</th>
                </tr>
                <tr>
                    <th>C.I.</th>
                    <th>APELLIDOS Y NOMBRES</th>
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
                    @if ($index == 30 && $totalMatriculados < 70)
                        <div class="page-break"></div>
                    @endif
                    <tr>
                        <td width="3%" style="text-align: center;">{{ $index + 1 }}</td>
                        <td width="10%" style="text-align: center;">
                            {{ $inscripcion->per_ci }}{{ $inscripcion->per_complemento ? '-' . $inscripcion->per_complemento : '' }}
                        </td>
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

                        @if ($datos_programa->pro_tip_id == 3)
                            @php
                                // Filtra las calificaciones del participante actual
                                $calificacion5 = $calificacion_participantes
                                    ->where('pi_id', $inscripcion->pi_id)
                                    ->where('ptc_id', 3)
                                    ->first();
                            @endphp

                            <td style="text-align: center;">{{ $calificacion5->cp_puntaje ?? '' }}</td>
                        @endif
                        <td width="12%" style="text-align: center;">
                            {{ strtoupper($calificacion4->cp_estado ?? 'ABANDONO') }}
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
        <p style="margin: 0; font-size: 7.2px;"><strong>VC</strong>=Valoración Cualitativa; <strong>DP</strong>=Desempeño Pleno; <strong>DO</strong>=Desempeño
            Óptimo;
            <strong>DS</strong>=Desempeño Suficiente; <strong>DI</strong>=Desempeño Insuficiente
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
        <table width="100%">
            <tr>

                <td width="" style="text-align: center;  width: 50%;">
                    <p style="margin: 0;">.................................................</p>
                    <p style="margin: 0;">Sello y firma</p>
                    <p style="margin: 0;">FACILITADOR/A</p>
                </td>
                <td style="text-align: center;  white; width: 50%;">
                    <p style="margin: 0;">.................................................</p>
                    <p style="margin: 0;">Sello y firma</p>
                    <p style="margin: 0;">RESPONSABLE DEPARTAMENTAL</p>
                </td>
                <td width="" style="text-align: center;  width: 50%;">
                    <p style="margin: 0;">.................................................</p>
                    <p style="margin: 0;">Sello y firma</p>
                    {{-- @if ($datos_programa->pro_id == 9 or $datos_programa->pro_id == 8) --}}
                    COORDINADOR DE PROGRAMAS <br>EDUCATIVOS
                    {{-- @else --}}
                    {{-- DIRECTOR ACADÉMICO/A --}}
                    {{-- @endif --}}
                    </p>
                </td>
            </tr>
        </table>
    </main>
    </div>
    <footer>
        <div style="display: flex; flex-direction: column; text-align: left;">
            <img src="data:image/png;base64,{{ $barcodeImage }}" alt="Código de Barras" width="155" height="25" style="vertical-align: middle;" />
            <p style="margin: 0;">{{ $codigoBarra }}</p>
        </div>
        <div style="text-align: left;">
            <p style="margin: 0;">Fecha de Impresión: {{ date('d/m/Y') }}</p>
        </div>
    </footer>

</body>

</html>
