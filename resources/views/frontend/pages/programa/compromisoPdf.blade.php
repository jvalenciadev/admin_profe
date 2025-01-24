<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Declaración y Compromiso Académico y Administrativo</title>
    <style>
        /* Ajustes generales */
        @page {
            margin: 0px 50px 0px 70px;
        }

        body {
            font-family: 'Arial', sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
            text-align: justify;
        }

        main {
            width: 100%;
            background-color: white;
            margin: 20px auto;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            line-height: 1.6;
        }

        /* Estilo para el encabezado y subtítulos */
        .header {
            text-align: center;
            font-size: 15px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 20px;
        }

        /* Estilo de contenido principal */
        .content {
            font-size: 12px;
            line-height: 1.8;
            color: #34495e;
            margin-bottom: 20px;
        }

        .content strong {
            font-weight: bold;
            color: #2c3e50;
        }

        /* Estilo de las listas */
        ul {
            margin-left: 10px;
            list-style-type: disc;
            color: #34495e;
        }

        ul li {
            font-size: 12px;
        }



        /* Estilo para las firmas */
        .signature-section {
            margin-top: 30px;
            text-align: center;
        }

        .signature-section p {
            font-size: 12px;
            color: #34495e;
            margin: 0px 0;
        }

        .divider {
            margin: 10px 0;
            border-top: 1px solid #bdc3c7;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            color: #7f8c8d;
            margin-top: 10px;
        }

        /* Estilo para logo */
        .logo {
            display: block;
            margin: 0 auto 10px;
            max-width: 400px;
        }

        .highlight {
            color: #2980b9;
        }

        .important-text {
            font-weight: bold;
            color: #e74c3c;
        }
    </style>
</head>

<body>
    <main>
        <img src="data:image/jpeg;base64,{{ $logo1 }}" alt="Logo 1" class="logo">
        <div class="header">
            DECLARACIÓN JURADA, COMPROMISO ACADÉMICO Y ADMINISTRATIVO –
            {{ mb_strtoupper($participante->pro_tip_nombre, 'UTF-8') }}
        </div>


        <div class="content">
            Yo, <strong>{{ strtoupper($participante->per_nombre1) }} {{ strtoupper($participante->per_nombre2) }}
                {{ strtoupper($participante->per_apellido1) }} {{ strtoupper($participante->per_apellido2) }}</strong>,
            con documento de identidad N.º
            <strong>{{ strtoupper($participante->per_ci) }}{{ $participante->per_complemento ? '-' . strtoupper($participante->per_complemento) : '' }}</strong>
            con Licenciatura en <strong>{{ mb_strtoupper($participante->pi_licenciatura, 'UTF-8') }}</strong>
            que desempeña su funcion en <strong>{{ mb_strtoupper($participante->pi_materia, 'UTF-8') }}</strong>  en la institución
             <strong>{{ mb_strtoupper($participante->pi_unidad_educativa, 'UTF-8') }}</strong> con el nivel de <strong>{{ mb_strtoupper($participante->pi_nivel, 'UTF-8') }}</strong> y subsistema
             <strong>{{ mb_strtoupper($participante->pi_subsistema, 'UTF-8') }}</strong>, en mi condición de
            <strong>{{ mb_strtoupper($participante->pro_tip_nombre, 'UTF-8') }} EN
                {{ mb_strtoupper($participante->pro_nombre, 'UTF-8') }}</strong>,
            del Programa de Formación Especializada – PROFE, para fines académicos, administrativos y legales, declaro
            lo siguiente:
        </div>

        <ul>
            <li>Que todos los documentos presentados para la postulación antes del inicio del programa son auténticos.
            </li>
            <li>Que conozco las normas académicas y administrativas para el desarrollo de los postgrados del Programa de
                Formación Especializada – PROFE.</li>
            <li>Que conozco el sistema de calificaciones de cada curso o módulo, el cual se realiza sobre 100
                puntos, y que para obtener la aprobación en los cursos o módulos regulares se requiere un mínimo de 70
                puntos.
            </li>
            <li>Que me comprometo a cumplir con el 75% de asistencia a las sesiones virtuales y con el 90% de asistencia
                a las sesiones presenciales.</li>
            <li>Que conozco y respeto las normas nacionales e internacionales de protección de los derechos de autoría
                intelectual para trabajos académicos, comprometiéndome a no usar producciones, textos, libros o ideas
                sin la debida cita y referencia bibliográfica. A este efecto, el Programa de Formación Especializada –
                PROFE podrá recurrir a sistemas anti plagio y otros recursos para la verificación.</li>
            <li>Que tengo las condiciones de conectividad y equipamiento computacional necesarias para el proceso de
                enseñanza-aprendizaje.</li>
            <li>Que me comprometo a realizar los pagos de colegiatura de manera puntual, para evitar cualquier mora que
                pueda impedir mi habilitación para cursar cursos o módulos. Los pagos se
                realizarán conforme al plan o cronograma proporcionado. El incumplimiento de este compromiso provocará
                mi inhabilitación académica, lo que resultará en la pérdida del derecho a participar en clases,
                exámenes y prácticas.</li>
            <li>El Programa de Formación Especializada – PROFE no se responsabiliza por la reprobación de cursos o
                módulos del programa si el/la participante está inhabilitado por los motivos antes mencionados.</li>
            <li>Que conozco que los costos por trámites administrativos y titulación en Ciclos Formativos y Diplomados,
                incluyendo la obtención de certificaciones de calificaciones, timbres, carpetas y el título, son
                adicionales. En el caso de ciclos formativos es gratuito los tramites administrativos de certificación
            </li>
            <li>Que en caso de abandono o retiro del programa, no se realizará la devolución de los montos pagados por
                matrícula y/o colegiatura. Estoy obligado a cancelar por los cursos o módulos cursados,
                independientemente de la
                calificación obtenida.</li>
            <li>Que la condición de participante regular se adquiere y mantiene cuando se cumplen las obligaciones
                académicas y administrativas del Programa de Formación Especializada – PROFE.</li>
        </ul>

        <div class="divider"></div>
        <p class="content">Es cuanto declaro, comprometo y suscribo a los <strong
                class="highlight">{{ now()->format('d') }}</strong> de <strong
                class="highlight">{{ ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'][now()->format('n') - 1] }}</strong>
            de <strong class="highlight">{{ now()->format('Y') }}</strong>.</p>

        <div class="signature-section">
            <p style="margin: 40px 0px 0px 0px;">....................................................................</p>

            <p>{{ strtoupper($participante->per_nombre1) }}
                {{ strtoupper($participante->per_nombre2) }} {{ strtoupper($participante->per_apellido1) }}
                {{ strtoupper($participante->per_apellido2) }}</p>

            <p>CI:
                {{ strtoupper($participante->per_ci) }}{{ $participante->per_complemento ? '-' . strtoupper($participante->per_complemento) : '' }}
            </p>
        </div>
{{--
        <div class="footer">
            <p><em class="important-text">Nota: El participante debe imprimir la presente Declaración y Compromiso
                    Académico y Administrativo firmada y adjuntar al folder I conjuntamente los requisitos mencionado en la convocatoria.</em></p>
        </div> --}}
    </main>
</body>

</html>
