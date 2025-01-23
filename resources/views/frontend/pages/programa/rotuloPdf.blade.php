<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rótulo del Participante PROFE</title>
    <style>
        /* Contenedor principal */
        .rotulo-container {
            width: 50%;
            margin: 0 auto;
            padding: 20px;
            border-radius: 25px;
            border: 4px solid #125875;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            font-family: Arial, sans-serif;
        }

        /* Encabezado con el logo */
        .rotulo-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .rotulo-header img {
            max-width: 100%;
            height: auto;
            display: block;
            margin: 0 auto;
        }

        .rotulo-header h2 {
            font-size: 1.5em;
            font-weight: bold;
            color: #125875;
            margin-top: 10px;
        }

        /* Cuerpo del rótulo */
        .rotulo-body {
            font-size: 1.1em;
            color: #333;
        }

        .rotulo-body p {
            margin-bottom: 15px;
            line-height: 1.6;
        }

        .rotulo-body .data-label {
            font-weight: bold;
            color: #125875;
        }

        /* Pie de página */
        .rotulo-footer {
            margin-top: 20px;
            font-size: 1.2em;
            text-align: center;
            color: #125875;
        }

        /* Ajuste para el contenedor del nombre del participante */
        .name-container {
            margin-top: 40px;
            padding: 10px;
            border-top: 8px solid #125875;
            /* Borde superior más grueso */
            border-bottom: 3px solid #125875;
            /* Borde inferior más delgado */
            font-weight: bold;
            background-color: #e6f7ff;
            /* Fondo suave */
            text-align: center;
            font-size: 1.3em;
            color: #125875;
            border-radius: 40px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            /* Sombra suave para el contenedor */
        }

        /* Ajuste responsivo */
        @media (max-width: 767px) {
            .rotulo-container {
                width: 90%;
                padding: 15px;
            }

            .rotulo-header h2 {
                font-size: 1.3em;
            }

            .rotulo-body {
                font-size: 1em;
            }

            .name-container {
                max-width: 100%;
                font-size: 1.2em;
                /* Ajustar el tamaño de la fuente en pantallas pequeñas */
                padding: 0px;
            }
        }
    </style>
</head>

<body>

    <div class="rotulo-container">
        <!-- Encabezado con el logo -->
        <div class="rotulo-header">
            <img src="data:image/jpeg;base64,{{ $logo1 }}" alt="Logo 1" width="500">
            <h2>RÓTULO - {{ mb_strtoupper($participante->pro_tip_nombre, 'UTF-8') }}</h2>
        </div>

        <!-- Información del participante -->
        <div class="rotulo-body">
            <p><span class="data-label">PARTICIPANTE:</span> {{ $participante->per_nombre1 }}
                {{ $participante->per_nombre2 }} {{ $participante->per_apellido1 }} {{ $participante->per_apellido2 }}
            </p>
            <p><span class="data-label">CELULAR Y CORREO:</span> {{ $participante->per_celular }} |
                {{ $participante->per_correo }}</p>
            <p><span class="data-label">SEDE:</span> {{ $participante->sede_nombre }}</p>
            <p><span class="data-label">OFERTA FORMATIVA:</span>
                {{ mb_strtoupper($participante->pro_tip_nombre, 'UTF-8') }} EN
                {{ mb_strtoupper($participante->pro_nombre, 'UTF-8') }}</p>
        </div>

        <!-- Pie de página -->
        <div class="rotulo-footer">
            <p>PROGRAMA DE FORMACIÓN ESPECIALIZADA - PROFE</p>
        </div>
    </div>

    <!-- Contenedor con el nombre del participante -->
    <div class="name-container">
        <p>{{ $participante->per_nombre1 }} {{ $participante->per_nombre2 }} {{ $participante->per_apellido1 }}
            {{ $participante->per_apellido2 }}</p>
    </div>

</body>

</html>
