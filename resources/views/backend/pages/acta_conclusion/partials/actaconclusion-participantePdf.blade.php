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
        font-family: Arial, Helvetica, sans-serif;
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
        padding: 0px 10px;
        font-size: 13.8px;
        /* Tamaño de fuente reducido */
        table-layout: fixed;
        /* Fija el ancho de las columnas */
        background-color: #ffffff;
        line-height: 1.4;
        /* Fondo blanco para la tabla */
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
                <td width="70%" style="text-align:center; font-size: 11px;">
                    <strong>
                        <h2 style="margin: 0;">ACTA DE CONCLUSIÓN DE DIPLOMADO
                    </strong>
                    {{-- <p style="margin: 0; font-size: 10px;">(Programa de Formación Especializada - PROFE)</p> --}}
                </td>
            </tr>
        </table>

        <br>
        <table class="main-table" style="text-align:justify;">
            <p> El Programa de Formación Especialidad - PROFE, del departamento de <strong>{{$inscripcion->dep_nombre}},</strong> de la sede
                <strong> {{$inscripcion->sede_nombre}},</strong>
                en la fecha 
                <strong>{{ \Carbon\Carbon::parse($inscripcion->updated_at_ac)->isoFormat('D [de] MMMM [de] YYYY') }},</strong> 
                emite la presente acta de conclusión por haber
                completado satisfactoriamente los módulos
                establecidos en el diplomado titulado: <strong>{{$inscripcion->pro_nombre}}, primera versión.</strong>
            </p>
            <p>
                <strong>
                    DATOS DEL PARTICIPANTE:
                </strong>
            </p>
        </table>
        <table class="main-table" style="font-size: 12.8px;">
            <tr>
                <td style="width: 100%; padding: 5px 30px; font-weight: bold; text-align: right;">
                    NOMBRES Y APELLIDOS:
                </td>
                <td style="width: 100%; padding: 5px 25px; text-align: left;">
                    {{ $inscripcion->per_nombre1}} {{$inscripcion->per_nombre2}} {{$inscripcion->per_apellido1}} {{$inscripcion->per_apellido2}}
                </td>
            </tr>
            <tr>
                <td style="width: 100%; padding: 5px 30px; font-weight: bold; text-align: right;">
                    CÉDULA DE IDENTIDAD:
                </td>
                <td style="width: 100%; padding: 5px 25px; text-align: left;">
                    {{$inscripcion->per_ci}}{{$inscripcion->per_complemento ? '-' . $inscripcion->per_complemento : ''}}
                </td>
            </tr>
            <tr>
                <td style="width: 100%; padding: 5px 30px; font-weight: bold; text-align: right;">
                    FECHA DE NACIMIENTO:
                </td>
                <td style="width: 100%; padding: 5px 25px; text-align: left;">
                    {{ \Carbon\Carbon::createFromFormat('Y-m-d', $inscripcion->per_fecha_nacimiento)->isoFormat('D [de] MMMM [de] YYYY') }}
                </td>
            </tr>
        </table>
        <table class="main-table" style="text-align:justify;">
            <p>
                <strong>
                    TÍTULO DEL PRODUCTO ACADÉMICO FINAL:
                </strong>
            </p>
        </table>
        <table class="main-table" style="">
            <tr>
                <td style="width: 100%; padding: 8px 130px; text-align: center;">
                    {{ $inscripcion->ac_titulo}}
                </td>
            </tr>
        </table>
        <table class="main-table" style="text-align:justify;">
            <p>
                <strong>
                    DESEMPEÑO ACADÉMICO:
                </strong>
            </p>
            <p>
                El/la participante ha completado satisfactoriamente el diplomado, obteniendo las siguientes calificaciones:
            </p>
        </table>
        <br>
        @php
            // Calcular el promedio final
            $promedio_final = round(($promedio + $inscripcion->ac_nota) / 2);

            // Función para convertir número a texto
            function numeroALetras($numero) {
                $formatter = new NumberFormatter('es', NumberFormatter::SPELLOUT);
                return strtolower($formatter->format($numero));
            }

            // Convertir el promedio final a texto
            $promedio_literal = numeroALetras($promedio_final);
        @endphp
        <table class="main-table" style="font-size: 12.8px;">
            <tr>
                <td style="width: 100%; padding: 5px 30px; font-weight: bold; text-align: right;">
                    PROMEDIO DE MÓDULOS:
                </td>
                <td style="width: 100%; padding: 5px 25px; text-align: left;">
                    {{$promedio}}
                </td>
            </tr>
            <tr>
                <td style="width: 100%; padding: 5px 30px; font-weight: bold; text-align: right;">
                    PRODUCTO ACADÉMICO FINAL:
                </td>
                <td style="width: 100%; padding: 5px 25px; text-align: left;">
                    {{$inscripcion->ac_nota}}
                </td>
            </tr>
            <tr>
                <td style="width: 100%; padding: 5px 30px; font-weight: bold; text-align: right;">
                    PROMEDIO FINAL:
                </td>
                <td style="width: 100%; padding: 5px 25px; text-align: left;">
                    {{ $promedio_final }} ({{ $promedio_literal }})
                </td>
            </tr>
        </table>
        <br>
        <table class="main-table" style="text-align:justify;">
            <p>
                Concluido el proceso formativo, el/la participante obtuvo un promedio final de <strong>{{ $promedio_final }} ({{ $promedio_literal }}),</strong> aprobando el correspondiente diplomado.
            </p>
        </table>
        <table width="100%" style="margin-top: 120px; font-size: 11px;">
            <tr>
                <td style="text-align: center;">
                    <p style="margin: 0;">.................................................</p>
                    <p style="margin: 0;">Sello y firma</p>
                    <p style="margin: 0;">RESPONSABLE DEPARTAMENTAL</p>
                </td>
            </tr>
        </table>
        
    </main>
    <footer style="width: 100%; padding: 50; position: fixed; bottom: 10; left: 0;">
        <div style="display: flex; flex-direction: column; text-align: left;">
            <img src="data:image/png;base64,{{ $barcodeImage }}" alt="Código de Barras" width="185" height="30"
                style="vertical-align: middle;" />
            <p style="margin: 0;">{{ $codigoBarra }}</p>
        </div>
        <div style="text-align: left;">
            <p style="margin: 0;">Fecha de Impresión: {{ date('d/m/Y') }}</p>
        </div>
    </footer>
</body>

</html>
