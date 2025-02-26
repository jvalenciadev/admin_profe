<!DOCTYPE html>
<html>

<head>
    <title>MINEDU - PROFE</title>
</head>
<style>
    @page {
        margin: 20px 60px 20px 60px;
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
        font-size: 17px;
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
        font-size: 11px;
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

<body
    style=" ">
    <header>

    </header>

    <main>
        <img src="data:image/jpeg;base64,{{ $logo1 }}" alt="Logo 1" width="500">
        <h2 style="text-align: center; vertical-align: middle;">FORMULARIO DE INSCRIPCIÓN</h2>
        <h3 style="margin-top:-15px; text-align:center;" style="text-transform: uppercase;">
            {{ strtoupper($participante[0]->eve_nombre) }}
        </h3>
            <!-- Aquí se agrega la imagen del código de barras -->

        <h2><b>DATOS PERSONALES</b></h2>

        <table>
            <tr>
                <td>NOMBRE(S)</td>
                <td>{{ $participante[0]->eve_per_nombre_1 }}</td>
            </tr>
            <tr>
                <td>APELLIDO(S)</td>
                <td>
                    {{ $participante[0]->eve_per_apellido_1 . ' ' . $participante[0]->eve_per_apellido_2 }}
                </td>
            </tr>
            <tr>
                <td>CÉDULA DE IDENTIDAD</td>
                <td>
                    {{ $participante[0]->eve_per_ci . '  ' . $participante[0]->eve_per_complemento }}</td>
            </tr>
            <tr>
                <td>CELULAR</td>
                <td>{{ $participante[0]->eve_per_celular }}</td>
            </tr>
            <tr>
                <td>CORREO ELECTRÓNICO</td>
                <td>{{ $participante[0]->eve_per_correo }}</td>
            </tr>
            <tr>
                <td>DEP. DE RESIDENCIA</td>
                <td>{{ strtoupper($participante[0]->dep_nombre) }}</td>
            </tr>
            {{-- <tr>
                <td>NIVEL</td>
                <td>{{ strtoupper($participante[0]->niv_nombre) }}</td>
            </tr> --}}
            <tr>
                <td>MODALIDAD DE ASISTENCIA</td>
                <td>
                        {{ $participante[0]->pm_nombre }}
                </td>
            </tr>

        </table>

        <table width="100%">
            <tr>
                <td width="40%" style="text-align: center; vertical-align: middle;">
                    <img src="data:image/jpeg;base64,{{ $qr }}" alt="" width="180px">
                    <p style="font-size: 10px; text-align:center">VERIFICAR Y/O VOLVER A DESCARGAR <br> FORMULARIO DE
                        INSCRIPCIÓN</p>
                    @if ($participante[0]->pm_id == 1)
                        <div style="text-align: center;">
                            <div style="display: inline-block;">
                                {!! DNS1D::getBarcodeHTML($participante[0]->eve_per_ci, 'C128', 2.5, 50) !!}
                            </div>
                        </div>
                    @endif
                </td>
                <td width="60%" class="text-center" style="text-align: center;">
                    @if ($participante[0]->pm_id == 1)
                        <div style="border-color: #aaa; background-color: #bbb; padding: 12px; font-size: 17px; ">
                            <img src="data:image/jpeg;base64,{{ $logo3 }}" alt="" width="100px"> <br>
                            El registro de la <b>asistencia presencial</b> se realizará desde horas {{ \Carbon\Carbon::parse($participante[0]->eve_ins_hora_asis_habilitado)->format('H:i') }} hasta las {{ \Carbon\Carbon::parse($participante[0]->eve_ins_hora_asis_deshabilitado)->format('H:i') }}.
                            Después de este horario, no se
                            aceptarán reclamos.
                        </div>
                    @endif
                </td>

            </tr>
        </table>

        <table width="100%">
            <tr>
                <td width="100%"
                    style=" padding: 10px; text-align: justify; background-color: #eeeeee; font-size:11px; ">
                    <b>NOTA</b>
                    <ul>
                        <li>
                            En la modalidad presencial: los datos personales son exclusiva responsabilidad del inscrito
                            por lo que no se aceptaran
                            cambios y/o modificaciones posterior a la entrega del certificado.
                        </li>
                        <li>
                            En la modalidad presencial: Para confirmar su asistencia al evento, deberá presentar este
                            comprobante de manera impresa.
                        </li>
                        <li>En la modalidad virtual: para acceder a la certificación digital debe responder los
                            formularios que se irán
                            publicando durante la transmisión.</li>
                        <li>Si usted respondió a los formularios podrá descargar su certificado del
                            siguiente enlace <b><u>https://certificados.minedu.gob.bo/</u></b> 10 días posterior a
                            la conclusión del evento.</li>
                    </ul>
                </td>

            </tr>
        </table>

    </main>

    <script>
        // Funcion JavaScript para la conversion a mayusculas
        function mayusculas(e) {
            e.value = e.value.toUpperCase();
        }

        function minusculas(e) {
            e.value = e.value.toLowerCase();
        }
    </script>


</body>
</html>
