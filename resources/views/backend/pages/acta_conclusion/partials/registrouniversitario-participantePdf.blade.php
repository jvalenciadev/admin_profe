<!DOCTYPE html>
<html>

<head>
    <title>MINEDU - PROFE</title>
    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
            font-size: 10.5px;
            font-family: Arial, sans-serif;
        }

        @page {
            size: landscape;
            /* Orientación horizontal */
        }

        body {}

        table {
            width: 100%;
            border-collapse: collapse;
            /* Elimina espacios entre celdas */
        }

        table td {
            border: none;
            /* Elimina bordes entre tablas */
            padding: 4px;
            /* Espaciado interno */
        }

        main {
            text-align: left;
            padding: 100px 150px 0px 120px;
        }

        .name-row td {
            text-align: center;
            border-bottom: 1px dashed black;
            /* Línea de separación */
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
                <td style="width: 80%;"></td> <!-- Espacio vacío para alinear a la derecha -->
                <td style="width: 20%; text-align: right; vertical-align: top;">
                    <!-- Imagen del QR -->
                    <div style="display: inline-block; text-align: center;">
                        <img src="data:image/jpeg;base64,{{ $qr }}" alt="QR Code" width="120px" style="display: block; margin: 0 auto;">
                        <!-- Texto del RDA centrado debajo del QR -->
                        <b style="display: block; margin-top: 15px; font-size: 14px;">RDA {{ $inscripcion->per_rda }}</b>
                    </div>
                </td>
            </tr>
        </table>
        
        <br>
        <table>
            <tr>
                <td style="width: 100%; text-align: right;" colspan="2">
                    <b>{{ $inscripcion->pv_numero }}RA.
                        {{ $inscripcion->pv_nombre }} - {{ $inscripcion->pm_nombre }}</b>
                </td>
            </tr>
            <!-- Fila 1: Gestión -->
            <tr>
                <td style="width: 12%;"><b>Gestión:</b></td>
                <td style="width: 30%;">{{ $inscripcion->pv_gestion }}</td>
            </tr>

            <!-- Fila 2: CFP -->
            <tr>
                <td style="width: 12%;"><b>CFP:</b></td>
                <td style="width: 83%;" >{{ $inscripcion->dep_nombre }} - {{ $inscripcion->sede_nombre }}
                </td>
            </tr>

            <!-- Fila 3: Programa -->
            <tr>
                <td style="width: 12%;"><b>PROGRAMA:</b></td>
                <td style="width: 83%;" >{{ $inscripcion->pro_tip_nombre }} EN
                    {{ $inscripcion->pro_nombre }}</td>
            </tr>
            <tr>
                <td style="width: 12%;"><b>PARTICIPANTE:</b></td>
            </tr>
        </table>
        <table>
            <tr class="name-row" style="">
                <td style="width: 30%;">{{ $inscripcion->per_apellido1 }}</td>
                <td style="width: 30%;">{{ $inscripcion->per_apellido2 }}</td>
                <td style="width: 30%;">{{ $inscripcion->per_nombre1 }} {{ $inscripcion->per_nombre2 }}</td>
            </tr>
            <tr>
                <td style="width: 30%; text-align: center; padding: 2px;"><b>Paterno</b></td>
                <td style="width: 30%; text-align: center; padding: 2px;"><b>Materno</b></td>
                <td style="width: 30%; text-align: center; padding: 2px;"><b>Nombre(s)</b></td>
            </tr>
        </table>
        <table>
            <tr>
                <td colspan="7" style="padding: 10px 0px;"><b>DATOS DE NACIMIENTO:</b></td>
            </tr>

            <tr class="name-row" style="">
                <td style="width: 10%;">
                    {{ $inscripcion->per_ci }}{{ $inscripcion->per_complemento ? '-' . $inscripcion->per_complemento : '' }}
                </td>
                <td style="width: 10%;">{{ $inscripcion->per_fecha_nacimiento }}</td>
                <td style="width: 10%;">{{ $inscripcion->gen_nombre }}</td>
                <td style="width: 10%;">{{ $inscripcion->per_nac_departamento }}</td>
                <td style="width: 20%;">{{ $inscripcion->per_nac_provincia }}</td>
                <td style="width: 20%;">{{ $inscripcion->per_nac_municipio }}</td>
                <td style="width: 25%;">{{ $inscripcion->per_nac_localidad }}</td>
            </tr>
            <tr>
                <td style="width: 10%; text-align: center; padding: 2px;"><b>Carnet</b></td>
                <td style="width: 10%; text-align: center; padding: 2px;"><b>Nacimiento</b></td>
                <td style="width: 10%; text-align: center; padding: 2px;"><b>Género</b></td>
                <td style="width: 10%; text-align: center; padding: 2px;"><b>Departamento</b></td>
                <td style="width: 20%; text-align: center; padding: 2px;"><b>Provincia</b></td>
                <td style="width: 20%; text-align: center; padding: 2px;"><b>Municipio</b></td>
                <td style="width: 25%; text-align: center; padding: 2px;"><b>Localidad</b></td>
            </tr>
        </table>
        <table>
            <tr>
                <td colspan="5" style="padding: 10px 0px;"><b>DATOS DE RESIDENCIA:</b></td>
            </tr>
            <tr class="name-row" style="">
                <td style="width: 10%;">{{ $inscripcion->per_res_departamento }}</td>
                <td style="width: 15%;">{{ $inscripcion->per_res_provincia }}</td>
                <td style="width: 15%;">{{ $inscripcion->per_res_municipio }}</td>
                <td style="width: 25%;">{{ $inscripcion->per_res_localidad }}</td>
                <td style="width: 35%;">{{ $inscripcion->per_res_direccion }}</td>
            </tr>
            <tr>
                <td style="width: 8%; text-align: center; padding: 2px;"><b>Departamento</b></td>
                <td style="width: 15%; text-align: center; padding: 2px;"><b>Provincia</b></td>
                <td style="width: 17%; text-align: center; padding: 2px;"><b>Municipio</b></td>
                <td style="width: 25%; text-align: center; padding: 2px;"><b>Localidad</b></td>
                <td style="width: 35%; text-align: center; padding: 2px;"><b>Dirección</b></td>
            </tr>
        </table>
        <table style="padding: 0px 250px;">
            <tr class="name-row" style="">
                <td style="">{{ $inscripcion->per_celular }}</td>
                <td style="">{{ $inscripcion->per_correo }}</td>
            </tr>
            <tr>
                <td style="text-align: center; padding: 2px;"><b>Celular</b></td>
                <td style="text-align: center; padding: 2px;"><b>Correo Electrónico</b></td>
            </tr>
        </table>
    </main>
    <footer style="position: fixed; padding-left: 60px; bottom: 20px; width: 100%; text-align: left; font-size: 10.5px;">
        <p>
            <b>Usuario:</b> {{ $user->nombre }} {{ $user->apellidos }}
        </p>
    </footer>
</body>

</html>
