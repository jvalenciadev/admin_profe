<!DOCTYPE html>
<html>

<head>
    <title>MINEDU - PROFE</title>
</head>
<style>
    html {
        height: 100%;
        /* Asegura que el html y el body ocupen toda la altura de la ventana */
        margin: 0;
        /* Eliminar márgenes */
        padding: 0;
        /* Eliminar padding */
        overflow: hidden;
    }

    body {
        font-size: 10px;
        /* Tamaño de fuente global para el cuerpo del documento */
        font-family: Arial, sans-serif;
        /* Cambia el tipo de fuente globalmente */

        background-image: url('data:image/jpeg;base64,{{ $fondo }}');
        background-position: center bottom;
        background-repeat: no-repeat;
        background-size: 100% auto;
        width: 21.59cm;
        /* Ancho de carta */
        height: 27.94cm;
        /* Alto de carta */
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


    .completed-payment {
        display: none;
        text-align: center;
        font-size: 24px;
        font-weight: bold;
        color: green;
        margin-top: 30px;
    }

    .show-completed-payment {
        display: block;
        /* Muestra el mensaje cuando la condición se cumple */
        color: green;
        /* Ajusta el color según sea necesario */
        font-weight: bold;
    }
</style>

<body>

    <header>
    </header>

    <main style="text-align: left; padding: 20px 55px; ">
        <table width="100%" style="vertical-align: middle;">
            <tr style="">
                <td style="text-align:left; vertical-align: middle;">
                    <img src="data:image/jpeg;base64,{{ $logo5 }}" alt="" width="500">
                </td>
                {{-- <td style="text-align:right; vertical-align: middle;">
                    <div style="display: inline-block; text-align: center;">
                        <img src="data:image/png;base64,{{ $barcodeImage }}" alt="Código de Barras" width="190"
                            height="35" style="vertical-align: middle;" />
                        <p style="margin: 0;">{{ $codigoBarra }}</p>
                    </div>
                </td> --}}
            </tr>
        </table>
        <table width="100%; padding: 10px">
            <tr>
                <td width="70%" style="text-align:center;">
                    <strong>
                        <h2 style="margin: 0;">CERTIFICADO DE CONCLUSIÓN <br>DE PAGOS</h2>
                    </strong>
                    {{-- <p style="margin: 0; font-size: 10px;">(Programa de Formación Especializada - PROFE)</p> --}}
                </td>
            </tr>
        </table>

        <table width="100%">
            <tr>
                <td width="20%" style="background-color: #ddd; padding: 5px; ">
                    <b>PROGRAMA</b>
                </td>
                <td width="80%" style="background-color: #ddd; padding: 5px; ">
                    PROGRAMA DE FORMACIÓN ESPECIALIZADA - PROFE
                </td>
            </tr>
        </table>
        <table width="100%">
            <tr>
                <td width="100%" style="background-color: #ddd; padding: 5px; ">
                    <b>DATOS PERSONALES</b>
                </td>
            </tr>
        </table>

        <table width="100%">
            <tr>
                <td width="20%" style="background-color: #ddd; padding: 5px; ">NOMBRE(S)</td>
                <td width="30%" style="padding: 5px; ">{{ $programaInscripcion->per_nombre1 }}
                    {{ $programaInscripcion->per_nombre2 }}</td>
                <td width="20%" style="background-color: #ddd; padding: 5px; ">APELLIDO(S)</td>
                <td width="30%" style="padding: 5px; ">
                    {{ $programaInscripcion->per_apellido1 . ' ' . $programaInscripcion->per_apellido2 }}
                </td>
            </tr>
            <tr>
                <td width="20%" style="background-color: #ddd; padding: 5px; ">CÉDULA DE IDENTIDAD</td>
                <td width="30%" style="padding: 5px; ">
                    {{ $programaInscripcion->per_ci . '  ' . $programaInscripcion->per_complemento }}
                </td>
                <td width="20%" style="background-color: #ddd; padding: 5px; ">RDA</td>
                <td width="30%" style="padding: 5px; ">{{ $programaInscripcion->per_rda }}</td>
            </tr>
            <tr>
                <td width="20%" style="background-color: #ddd; padding: 5px; ">CORREO ELECTRÓNICO</td>
                <td width="30%" style="padding: 5px; ">{{ $programaInscripcion->per_correo }}</td>
                <td width="20%" style="background-color: #ddd; padding: 5px; ">CELULAR</td>
                <td width="30%" style="padding: 5px; ">{{ $programaInscripcion->per_celular }}</td>
            </tr>
        </table>

        <table width="100%">
            <tr>
                <td width="100%" style="background-color: #ddd; padding: 5px; ">
                    <b>DATOS DE INSCRIPCIÓN</b>
                </td>
            </tr>
        </table>

        <table width="100%" style="border-collapse: collapse;">
            <tr>
                <td width="20%" style="background-color: #ddd; padding: 5px; ">GESTIÓN</td>
                <td width="30%" style="padding: 5px; ">{{ $programaInscripcion->pv_gestion }}</td>
                <td width="20%" style="background-color: #ddd; padding: 5px; ">{{ $programaInscripcion->pv_nombre }}</td>
                <td width="30%" style="padding: 5px; ">
                    {{ $programaInscripcion->pv_numero }}</td>
            </tr>
            <tr>
                <td width="20%" style="background-color: #ddd; padding: 5px; ">DEPARTAMENTO</td>
                <td width="30%" style="padding: 5px; ">{{ $programaInscripcion->dep_nombre }}</td>
                <td width="20%" style="background-color: #ddd; padding: 5px; ">SEDE</td>
                <td width="30%" style="padding: 5px; ">{{ $programaInscripcion->sede_nombre }}</td>
            </tr>
            <tr>
                <td width="20%" style="background-color: #ddd; padding: 5px; ">
                    {{ $programaInscripcion->pro_tip_nombre }}</td>
                <td colspan="3" width="80%" style="padding: 5px; ">{{ $programaInscripcion->pro_nombre }}</td>
            </tr>
            <tr>
                <td width="20%" style="background-color: #ddd; padding: 5px; ">COSTO</td>
                <td width="30%" style="padding: 5px; ">Bs{{ number_format($programaInscripcion->pro_costo, 0, ',', '.') }}</td>
                <td width="20%" style="background-color: #ddd; padding: 5px; ">CARGA HORARIA</td>
                <td width="30%" style="padding: 5px; ">{{ $programaInscripcion->pro_carga_horaria }}hrs</td>
            </tr>
            <tr>
                <td width="20%" style="background-color: #ddd; padding: 5px; ">MODALIDAD</td>
                <td width="30%" style="padding: 5px; ">{{ $programaInscripcion->pm_nombre }}</td>
                @if($programaInscripcion->pm_id != 3)
                    <td width="20%" style="background-color: #ddd; padding: 5px; ">TURNO</td>
                    <td width="30%" style="padding: 5px; ">{{ $programaInscripcion->pro_tur_nombre }}</td>
                @endif
            </tr>
            <tr>
                <td width="20%" style="background-color: #ddd; padding: 5px; ">DURACIÓN</td>
                <td width="30%" style="padding: 5px; ">{{ $programaInscripcion->pd_nombre }}</td>
                <td width="20%" style="background-color: #ddd; padding: 5px; ">FECHA DE INICIO</td>
                <td width="30%" style="padding: 5px; ">
                    {{ \Carbon\Carbon::parse($programaInscripcion->pro_fecha_inicio_clase)->format('d-m-Y') }}</td>
            </tr>
        </table>

        @php
            $totalPagado = 0;
        @endphp

        @foreach ($programaBauchers as $baucher)
            @php
                $totalPagado += $baucher->pro_bau_monto;
            @endphp
        @endforeach

        <table width="100%" style="margin-top: 20px">
            <tr>
                <td width="100%" style="background-color: #ddd; padding: 5px; ">
                    <b>DATOS DE BAUCHERS</b>
                </td>
            </tr>
        </table>

        <table width="100%">
            <thead>
                <tr>
                    <th width="20%" style="background-color: #ddd; padding: 5px; ">NÚMERO DE BAUCHER</th>
                    <th width="20%" style="background-color: #ddd; padding: 5px; ">MONTO (Bs)</th>
                    <th width="20%" style="background-color: #ddd; padding: 5px; ">FECHA DE PAGO</th>
                    <th width="40%" style="background-color: #ddd; padding: 5px; ">OBSERVACIONES</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($programaBauchers as $baucher)
                    <tr>
                        @if ($baucher->pro_bau_tipo_pago == 'Baucher')
                            <td style="padding: 2px; ">{{ $baucher->pro_bau_nro_deposito }}</td>
                        @else
                            <td style="padding: 2px; ">------</td>
                        @endif
                        <td style="padding: 2px; ">Bs{{ number_format($baucher->pro_bau_monto, 0, ',', '.') }}</td>
                        <td style="padding: 2px; ">
                            {{ \Carbon\Carbon::parse($baucher->pro_bau_fecha)->format('d-m-Y') }}</td>
                        <td style="padding: 2px; ">{{ $baucher->pro_bau_tipo_pago }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="2" style="background-color: #ddd; padding: 5px; "><b>TOTAL PAGADO</b></td>
                    <td colspan="2" style="padding: 5px; ">Bs{{ number_format($totalPagado, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td colspan="2" style="background-color: #ddd; padding: 5px; "><b>SALDO RESTANTE</b></td>
                    <td colspan="2" style="padding: 5px; ">Bs{{ number_format($programaInscripcion->pro_costo - $totalPagado, 0, ',', '.') }}
                    </td>
                </tr>
            </tbody>
        </table>
        <!-- Mensaje de pago completado -->
        <div
            class="{{ $totalPagado >= $programaInscripcion->pro_costo ? 'completed-payment show-completed-payment' : 'completed-payment' }}">
            <p>¡PAGO COMPLETO!</p>
        </div>

        <br>
        <table width="100%" style="margin-top: 40px; font-size: 10px;">
            <tr>
                <td style="text-align: center; border: 1px solid white; width: 50%;">
                    <p style="margin: 0;">.................................................</p>
                    <p style="margin: 0;">Sello y firma</p>
                    <p style="margin: 0;">{{ $responsable->cargo }}</p>
                </td>
                <td width="" style="text-align: center; border: 1px solid white; width: 50%;">
                    <p style="margin: 0;">.................................................</p>
                    <p style="margin: 0;">Sello y firma</p>
                    ASISTENTE ADMINISTRATIVO
                </td>
                <td width="" style="text-align: center; border: 1px solid white; width: 50%;">
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
    <footer style="width: 100%; padding: 50; position: fixed; bottom: 10; left: 0;">
        <div style="display: flex; flex-direction: column; text-align: left;">
            <img src="data:image/png;base64,{{ $barcodeImage }}" alt="Código de Barras" width="185" height="30" style="vertical-align: middle;" />
            <p style="margin: 0;">{{ $codigoBarra }}</p>
        </div>
        <div style="text-align: left;">
            <p style="margin: 0;">Fecha de Impresión: {{ date('d/m/Y') }}</p>
        </div>
    </footer>
</body>

</html>
