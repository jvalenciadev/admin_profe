<!DOCTYPE html>
<html>

<head>
    <title>MINEDU - PROFE</title>
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            font-size: 10.5px;
            font-family: Arial, sans-serif;
        }

        body {
        }

        table {
            width: 100%;
            border-collapse: collapse; /* Elimina espacios entre celdas */
        }

        table td {
            border: none; /* Elimina bordes entre tablas */
            padding: 4px; /* Espaciado interno */
        }

        main {
            text-align: left;
            padding: 180px 60px 0px 60px;
        }

        .name-row td {
            text-align: center;
            border-bottom: 1px solid black; /* Línea de separación */
        }
        .bordered-row td {
            border: 1px solid black;
        }
    </style>
</head>

<body>
    <header>
    </header>

    <main>
        <table>
            <tr>
                <td style="width: 100%; text-align: right;">
                    <img src="data:image/jpeg;base64,{{ $qr }}" alt="" width="90px">
                </td>
            </tr>
        </table>
        <br>
        <table>
            <!-- Fila 1: Gestión -->
            <tr>
                <td style="width: 12%;"><b>Gestión:</b></td>
                <td style="width: 30%;">{{$inscripcion->pv_gestion}}</td>
                <td style="width: 58%; text-align: right;"><b>{{$inscripcion->pv_numero}}RA. {{$inscripcion->pv_nombre}} - {{$inscripcion->pm_nombre}}</b></td>
            </tr>

            <!-- Fila 2: CFP -->
            <tr>
                <td style="width: 12%;"><b>CFP:</b></td>
                <td style="width: 83%;" colspan="3">{{$inscripcion->dep_nombre}} - {{$inscripcion->sede_nombre}}</td>
            </tr>

            <!-- Fila 3: Programa -->
            <tr>
                <td style="width: 12%;"><b>PROGRAMA:</b></td>
                <td style="width: 83%;" colspan="3">{{$inscripcion->pro_tip_nombre}} EN {{$inscripcion->pro_nombre}}</td>
            </tr>
        </table>
        <table>
            <!-- Fila 4: Código RDA y CI -->
            <tr>
                <td style="width: 20%;"><b>Código RDA:</b></td>
                <td style="width: 30%;">{{$inscripcion->per_rda}}</td>
                <td style="width: 20%;"><b>Cédula de Identidad:</b></td>
                <td style="width: 30%;">
                    {{$inscripcion->per_ci}}{{$inscripcion->per_complemento ? '-' . $inscripcion->per_complemento : ''}}
                </td>
            </tr>
        </table>
        <br>
        <br>
        <table>
            <tr class="name-row" style="font-size: 14px;">
                <td style="width: 30%;">{{$inscripcion->per_apellido1}}</td>
                <td style="width: 30%;">{{$inscripcion->per_apellido2}}</td>
                <td style="width: 30%;">{{$inscripcion->per_nombre1}} {{$inscripcion->per_nombre2}}</td>
            </tr>
            <tr>
                <td style="width: 30%; text-align: center; padding: 2px;"><b>Paterno</b></td>
                <td style="width: 30%; text-align: center; padding: 2px;"><b>Materno</b></td>
                <td style="width: 30%; text-align: center; padding: 2px;"><b>Nombre(s)</b></td>
            </tr>
        </table>
        <br>
        <br>
        <table>
            <tr class="bordered-row" style="text-align: center; background-color: rgb(221, 220, 220);">
                <td style="width: 9%; padding: 1px;"><b>Semestre</b></td>
                <td style="width: 9%; padding: 1px"><b>Grupo</b></td>
                <td style="width: 9%;  padding: 1px"><b>Código</b></td>
                <td style="width: 50%;  padding: 1px"><b>Módulo</b></td>
                <td style="width: 5%; padding: 1px"><b>Nota</b></td>
                <td style="width: 9%;  padding: 1px"><b>Literal</b></td>
                <td style="width: 9%;  padding: 1px"><b>Estado</b></td>
            </tr>
            @foreach ($modulos as $key => $modulo)
                <tr style="font-size: 9px; {{ $key == count($modulos) - 1 ? 'border-bottom: 1px solid black;' : '' }}">
                    <td style="width: 9%;">PRIMERO</td>
                    <td style="width: 9%; text-align: center;">GRUPO {{$modulo->pro_tur_id}}</td>
                    <td style="width: 9%; text-align: center;">{{$modulo->pm_codigo}}-{{ str_pad($modulo->pm_id, 3, '0', STR_PAD_LEFT) }}</td>
                    <td style="width: 50%;">{{$modulo->pm_descripcion}}</td>
                    <td style="width: 5%; text-align: center;">{{$modulo->cp_puntaje}}</td>
                    <td style="width: 9%;">
                        @php
                            $formatter = new NumberFormatter("es", NumberFormatter::SPELLOUT);
                            echo mb_strtoupper($formatter->format($modulo->cp_puntaje));
                        @endphp
                    </td>
                    <td style="width: 9%; text-align: center;">{{$modulo->cp_estado}}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="7" style="text-align: center; border-top: 1px solid black; padding-top: 5px;">
                    <b>Total Módulo: {{ count($modulos) }}</b>
                </td>
            </tr>
        </table>

        <p>
            <b>Fecha de Impresión:</b> {{ \Carbon\Carbon::now()->format('d/m/Y') }}
        </p>
    </main>
    <footer style="position: fixed; padding-left: 60px; bottom: 20px; width: 100%; text-align: left; font-size: 10.5px;">
        <p>
            <b>Usuario:</b> {{ $user->nombre }} {{ $user->apellidos }}
        </p>
    </footer>
</body>

</html>
