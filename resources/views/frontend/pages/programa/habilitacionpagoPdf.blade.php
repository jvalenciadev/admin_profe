<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>MINEDU - PROFE</title>
    <style>
        @page {
            margin: 20px 20px 20px 70px;
        }

        body {
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            font-size: 10px;
            color: #333;
            line-height: 1.3;
            margin: 0;
            padding: 0;
        }

        header img {
            display: block;
            margin: 0 auto;
            max-width: 100%;
            border-bottom: 3px solid #2980b9;
        }

        main {
            margin: 5px;
            background-color: #fff;
            padding: 5px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        h2,
        h3 {
            margin: 5px 0;
            font-weight: bold;
            color: #2c3e50;
        }

        h2 {
            font-size: 17px;
            text-decoration: underline;
            letter-spacing: 1px;
        }

        h3 {
            font-size: 15px;
            color: #2980b9;
        }

        table {
            width: 100%;
            margin-top: 10px;
            border-collapse: collapse;
            table-layout: fixed;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        table td {
            padding: 5px 7px;
            word-wrap: break-word;
            border: 1px solid #ddd;
            font-size: 10px;
            text-align: left;
        }

        table td:first-child {
            font-weight: bold;
            background-color: #f4f6f9;
            color: #2c3e50;
        }

        table td:last-child {
            background-color: #ffffff;
            color: #333;
        }

        table tr:nth-child(even) td {
            background-color: #fafafa;
        }

        table tr:hover td {
            background-color: #ecf0f1;
        }

        .section-header {
            margin: 5px 0 15px 0;
            font-size: 11px;
            font-weight: bold;
            text-align: center;
            background-color: #2980b9;
            color: white;
            padding: 4px;
            border-radius: 4px;
        }

        .highlight {
            font-size: 11px;
            background-color: #f9f9f9;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ddd;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>

    <main>
        <img src="data:image/jpeg;base64,{{ $logo1 }}" alt="Logo 1" width="400">
        <h2 style="text-align: center; vertical-align: middle;">HABILITACIÓN DE PAGO</h2>
        <h3>{{ mb_strtoupper($participante->pro_tip_nombre, 'UTF-8') ?? '' }} EN
            {{ mb_strtoupper($participante->pro_nombre, 'UTF-8') }}</h3>

        <div class="section-header">DATOS DE INSCRIPCIÓN</div>
        <table>
            <tr>
                <td>MODALIDAD</td>
                <td>{{ strtoupper($participante->pm_nombre) }}</td>
            </tr>
            <tr>
                <td>TURNO</td>
                <td>{{ mb_strtoupper($participante->pro_tur_nombre, 'UTF-8') }}</td>
            </tr>
            <tr>
                <td>VERSIÓN</td>
                <td>{{ mb_strtoupper($participante->pv_nombre, 'UTF-8') }} {{ $participante->pv_romano }} -
                    {{ strtoupper($participante->pv_gestion) }}</td>
            </tr>
            <tr>
                <td>DEPARTAMENTO</td>
                <td>{{ strtoupper($participante->dep_nombre) }}</td>
            </tr>
            <tr>
                <td>SEDE</td>
                <td>{{ strtoupper($participante->sede_nombre) }}</td>
            </tr>
            <tr>
                <td>FECHA DE REGISTRO</td>
                <td>
                    <strong>
                        {{ mb_strtoupper(\Carbon\Carbon::parse($participante->created_at)->translatedFormat('j \\d\\e M Y, h:i A'), 'UTF-8') }}
                    </strong>
                </td>
            </tr>
        </table>
        <div class="section-header">DATOS PERSONALES</div>
        <table>
            <tr>
                <td>CEDULA DE IDENTIDAD</td>
                <td>{{ strtoupper($participante->per_ci) }}{{ $participante->per_complemento ? '-' . strtoupper($participante->per_complemento) : '' }}
                </td>
            </tr>
            <tr>
                <td>NOMBRE(S)</td>
                <td>{{ mb_strtoupper($participante->per_nombre1, 'UTF-8') }}
                    {{ mb_strtoupper($participante->per_nombre2, 'UTF-8') }}</td>
            </tr>
            <tr>
                <td>APELLIDO(S)</td>
                <td>{{ mb_strtoupper($participante->per_apellido1, 'UTF-8') }}
                    {{ mb_strtoupper($participante->per_apellido2, 'UTF-8') }}</td>
            </tr>
            <tr>
                <td>CELULAR</td>
                <td>{{ strtoupper($participante->per_celular) }}</td>
            </tr>
            <tr>
                <td>CORREO ELECTRÓNICO</td>
                <td>{{ $participante->per_correo }}</td>
            </tr>
            {{-- <tr>
                <td>LICENCIATURA</td>
                <td>{{ strtoupper($participante->pi_licenciatura) }}</td>
            </tr> --}}
        </table>

        {{-- <div class="section-header">INFORMACIÓN INSTITUCIONAL</div>
        <table>
            <tr>
                <td>INSTITUCIÓN</td>
                <td>{{ strtoupper($participante->pi_unidad_educativa) }}</td>
            </tr>
            <tr>
                <td>CARGO ACTUAL</td>
                <td>{{ strtoupper($participante->pi_materia) }}</td>
            </tr>
            <tr>
                <td>NIVEL</td>
                <td>{{ strtoupper($participante->pi_nivel) }}</td>
            </tr>
            <tr>
                <td>SUBSISTEMA</td>
                <td>{{ strtoupper($participante->pi_subsistema) }}</td>
            </tr>
        </table> --}}
        <table width="100%">
            <tr>
                <td width="100%"
                    style=" font-weight: normal; padding: 10px; text-align: justify; background-color: #ffebcc; font-size: 12px; border-left: 4px solid #ff9800;">
                    <b style="color: #d35400;">NOTA IMPORTANTE:</b>
                    <ul style="margin-top: 5px; padding-left: 20px;">
                        <li>
                            Tranferencia bancaria donde se visibilice: nombre del participante, monto, cuenta de
                            destino, cuenta origen del participante y Nro. de documento (exclusivamente Banco Unión).
                        </li>
                        <li>
                            Depósito bancario a cuenta del Ministerio de Educación, a nombre del participante.
                        </li>
                        @if ($participante->pro_tip_id == 2)
                            <li>
                                Para ciclos formativos, el deposito debe de ser el costo total <strong>Bs. 150</strong>.
                            </li>
                        @else
                            <li>
                                Para diplomados, el depósito debe ser por módulo de <strong>Bs. 300</strong>.
                            </li>
                        @endif
                    </ul>
                    El monto de los depósitos o transferencias bancarias debe ser sin fraccionamiento al inicio de cada
                    módulo o ciclo formativos a la cuenta: <strong>Nro. 10000004669343</strong> del Ministerio de
                    Educación (Banco Unión). Los errores en el depósito o transferencia bancaria son de exclusiva
                    responsabilidad de la o el participante.

                </td>
            </tr>
        </table>
        <table width="100%">
            <tr>
                <td width="40%" style="text-align: center; vertical-align: middle;">
                    <div style="text-align: center;">
                        <img src="data:image/jpeg;base64,{{ $qr }}" alt="" width="140px">
                        <p style="font-size: 10px; text-align: center;">VERIFICAR Y/O VOLVER A DESCARGAR <br> FORMULARIO
                        </p>
                        <div style="text-align: center; display: inline-block;">
                            {!! DNS1D::getBarcodeHTML($participante->per_ci, 'C128', 2, 35) !!}
                        </div>
                    </div>
                </td>
                <td width="60%" class="text-center" style="text-align: center;">
                    <!-- Alerta arriba -->
                    <div style=" background-color: #d7d7d7; padding: 15px; font-size: 12px;">
                        <img src="data:image/jpeg;base64,{{ $logo3 }}" alt="" width="50px"> <br>
                        <div style="text-align: justify;">


                            <span style="font-size: 15px; color: #333;">
                                    {!! $participante->res_descripcion ?? '' !!}
                            </span>
                            {{-- <strong>Importante:</strong> Tiene un plazo de <b>48 horas</b> para realizar el depósito y
                            presentar su documentación en la sede en la que se inscribió.
                            <br> --}}
                            <strong class="fw-bold">Nota:</strong> Debe dejar sus documentos en la sede que se inscribió.
                        </div>


                    </div>

                    <!-- Espacio entre la alerta y la firma -->
                    <br><br><br><br><br>

                    <!-- Firma abajo -->
                    <h5 style="display: ;"> FIRMA DEL PARTICIPANTE</h5>
                </td>

            </tr>
        </table>


    </main>
</body>

</html>
