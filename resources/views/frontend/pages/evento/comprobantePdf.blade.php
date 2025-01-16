<!DOCTYPE html>
<html>

<head>
    <title>MINEDU - PROFE</title>
</head>
<style>
    /** Define the margins of your page **/
    @page {
        /* margin: 170px 70px 70px 80px; */
        margin: 40px 10px 10px 30px;
    }


   
</style>

<body
    style=" text-align: center; background-image: url('data:image/jpeg;base64,{{ $fondo }}'); background-position: left; background-repeat: no-repeat; background-position: 5px 630px;  ">
    <header>

    </header>

    <main
        style="text-align: left; padding-top: 0px; padding-left: 50px; padding-right: 50px; font-size:14px; font-family: Helvetica;">
        <table width="100%">
            <tr>
                <td  style="text-align:left; "><img src="data:image/jpeg;base64,{{ $logo1 }}"
                        alt="" width="450px"></td>
            </tr>
        </table>
        <h2 style="margin-top:10px; text-align:center; ">FORMULARIO DE INSCRIPCIÓN</h2>
        <h3 style="margin-top:-15px; text-align:center;" style="text-transform: uppercase;">
            {{ strtoupper($participante[0]->eve_nombre) }}
        </h3>
            <!-- Aquí se agrega la imagen del código de barras -->
            
        <p style="background-color: #ddd; padding: 10px; margin-top:-10px; width: 650px; "><b>DATOS PERSONALES</b></p>

        <table width="100%" style="margin-top: -10px">
            <tr>
                <td width="35%" style="background-color: #ddd; padding: 5px; ">NOMBRE(S)</td>
                <td width="65%" style="padding: 5px; ">{{ $participante[0]->eve_per_nombre_1 }}</td>
            </tr>
            <tr>
                <td width="35%" style="background-color: #ddd; padding: 5px; ">APELLIDO(S)</td>
                <td width="65%" style="padding: 5px; ">
                    {{ $participante[0]->eve_per_apellido_1 . ' ' . $participante[0]->eve_per_apellido_2 }}
                </td>
            </tr>
            <tr>
                <td width="35%" style="background-color: #ddd; padding: 5px; ">CÉDULA DE IDENTIDAD</td>
                <td width="65%" style="padding: 5px; ">
                    {{ $participante[0]->eve_per_ci . '  ' . $participante[0]->eve_per_complemento }}</td>
            </tr>
            <tr>
                <td width="35%" style="background-color: #ddd; padding: 5px; ">CELULAR</td>
                <td width="65%" style="padding: 5px; ">{{ $participante[0]->eve_per_celular }}</td>
            </tr>
            <tr>
                <td width="35%" style="background-color: #ddd; padding: 5px; ">CORREO ELECTRÓNICO</td>
                <td width="65%" style="padding: 5px; ">{{ $participante[0]->eve_per_correo }}</td>
            </tr>
            <tr>
                <td width="35%" style="background-color: #ddd; padding: 5px; ">DEP. DE RESIDENCIA</td>
                <td width="65%" style="padding: 5px; ">{{ strtoupper($participante[0]->dep_nombre) }}</td>
            </tr>
            {{-- <tr>
                <td width="35%" style="background-color: #ddd; padding: 5px; ">NIVEL</td>
                <td width="65%" style="padding: 5px; ">{{ strtoupper($participante[0]->niv_nombre) }}</td>
            </tr> --}}
            <tr>
                <td width="35%" style="background-color: #ddd; padding: 5px; ">MODALIDAD DE ASISTENCIA</td>
                <td width="65%" style="padding: 5px; ">
                        {{ $participante[0]->pm_nombre }}
                </td>
            </tr>
            {{-- <tr>
                <td width="35%" style="background-color: #ddd; padding: 5px; ">MODALIDAD</td>
                <td width="65%" style="padding: 5px; ">
                    {{ $participante[0]->em_nombre }}
                </td>
            </tr> --}}

        </table>

        <table width="100%">
            <tr>
                <td width="40%" style="text-align: left;">
                    <img src="data:image/jpeg;base64,{{ $qr }}" alt="" width="180px">
                    <p style="font-size: 10px; text-align:left">VERIFICAR Y/O VOLVER A DESCARGAR <br> FORMULARIO DE
                        INSCRIPCIÓN</p>
                    @if ($participante[0]->pm_id == 1)
                        {!! DNS1D::getBarcodeHTML($participante[0]->eve_per_ci, 'C128', 2.5, 50) !!}
                    @endif
                </td>
                <td width="60%" class="text-center" style="text-align: center;">
                    @if ($participante[0]->pm_id == 1)
                        <div style="border-color: #aaa; background-color: #bbb; padding: 12px; font-size: 20px; ">
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
                        @if (1 == 1)
                            <li>
                                Usted, autorizó el envío de información sobre próximos eventos académicos organizados
                                por el Instituto de Investigaciones Pedagógicas Plurinacional del Ministerio de
                                Educación, a través del correo electrónico y/o número de WhatsApp proporcionado en este
                                formulario.
                            </li>
                        @endif
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
