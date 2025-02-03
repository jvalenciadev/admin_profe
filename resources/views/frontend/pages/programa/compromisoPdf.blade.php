<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Declaración Jurada y Compromiso Académico y Administrativo</title>
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
            margin-bottom: 0px;
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
            max-width: 500px;
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

        @if ($participante->pro_tip_id == 2)
            <div class="content">
                Yo, <strong>{{ strtoupper($participante->per_nombre1) }} {{ strtoupper($participante->per_nombre2) }}
                    {{ strtoupper($participante->per_apellido1) }} {{ strtoupper($participante->per_apellido2) }}</strong>,
                con documento de identidad N.º
                <strong>{{ strtoupper($participante->per_ci) }}{{ $participante->per_complemento ? '-' . strtoupper($participante->per_complemento) : '' }}</strong>
                , en mi condición de
                <strong>{{ mb_strtoupper($participante->pro_tip_nombre, 'UTF-8') }} EN
                    {{ mb_strtoupper($participante->pro_nombre, 'UTF-8') }}</strong>,
                del Programa de Formación Especializada – PROFE, para fines académicos, administrativos y legales, declaro
                lo siguiente:
            </div>
        @elseif($participante->pro_tip_id == 3)
            <div class="content">
                Yo, <strong>{{ strtoupper($participante->per_nombre1) }} {{ strtoupper($participante->per_nombre2) }}
                    {{ strtoupper($participante->per_apellido1) }} {{ strtoupper($participante->per_apellido2) }}</strong>,
                con documento de identidad N.º
                <strong>{{ strtoupper($participante->per_ci) }}{{ $participante->per_complemento ? '-' . strtoupper($participante->per_complemento) : '' }}</strong>
                con Licenciatura en <strong>{{ mb_strtoupper($participante->pi_licenciatura, 'UTF-8') }}</strong>
                que desempeña sus funciones como <strong>{{ mb_strtoupper($participante->pi_materia, 'UTF-8') }}</strong>  en la institución
                <strong>{{ mb_strtoupper($participante->pi_unidad_educativa, 'UTF-8') }}</strong> del nivel <strong>{{ mb_strtoupper($participante->pi_nivel, 'UTF-8') }}</strong> del subsistema de Educación
                <strong>{{ mb_strtoupper($participante->pi_subsistema, 'UTF-8') }}</strong>, en mi condición de participante del
                <strong>{{ mb_strtoupper($participante->pro_tip_nombre, 'UTF-8') }} EN
                    {{ mb_strtoupper($participante->pro_nombre, 'UTF-8') }}</strong>,
                del Programa de Formación Especializada – PROFE, para fines académicos, administrativos y legales, declaro, suscribo y me comprometo a
                lo siguiente:
            </div>
        @endif


        <ul style="list-style-type: none; margin-left: -40px;">
            <li>- Todos los documentos presentados para la inscripción al postgrado son auténticos.</li>
            <li>- Cumpló las normas académicas y administrativas para el desarrollo de los postgrados.</li>
        </ul>
        <div class="content"><strong>ME COMPROMETO:</strong></div>
        <ul>
            <li>A ser responsable con la asistencia al postgrado, teniendo en cuenta que el porcentaje de asistencia mínimo es de 75%
                a las sesiones virtuales y el 90% a las sesiones presenciales. Considerando que el sistema de calificaciones de cada curso o módulos
                se lo realiza sobre 100 puntos y para obtener la aprobación en los cursos o módulos
                regulares se requiere un mínimo de 70 puntos. El Programa de Formación Especializada - PROFE no se responsabiliza por la reprobación
                por la reprobación de cursos o módulos del programa en caso de incumplimiento al reglamento académico.
            </li>
            <li>A Respetar las normas nacionales e internacionales de protección de los derechos de autoría
                intelectual para trabajos académicos, comprometiéndome a no usar producciones, textos, libros o ideas
                sin la debida cita y/o referencia bibliográfica según normas APA. Para este efecto, el Programa de Formación Especializada –
                PROFE podrá recurrir a sistemas anti plagio y otros recursos para la verificación.</li>
            <li>A Tener las condiciones de conectividad a internet y equipamiento computacional necesarias para el proceso de
                enseñanza-aprendizaje.</li>
            <li>A realizar los pagos de colegiatura de manera puntual al inicio de cada módulo, evitando cualquier mora que
                pueda impedir mi habilitación para el desarrollo de los módulos. Los pagos se
                realizarán conforme al plan o cronograma proporcionado. El incumplimiento de este compromiso provocará
                mi inhabilitación académica, lo que resultará en la pérdida del derecho a participar en clases,
                exámenes y prácticas.</li>
        </ul>
        <div class="content" style="margin-bottom: 0;"><strong>NOTA:</strong></div>
        <ul style="list-style-type: none; margin-left: -40px; margin-top: 0;"><li>- Tengo conocimiento que los costos por trámites administrativos y titulación del Diplomado, incluyendo
            la obtención de certificaciones de calificaciones, timbres, carpetas y títulos, son adicionales.<br>
        - En caso de abandono, no se realizará la devolución de los montos pagados por mátricula y/o colegiatura.</li>
        </ul>
        <div class="divider"></div>
        <p class="content">Es cuanto declaro, comprometo y suscribo a los <strong
                class="highlight">{{ now()->format('d') }}</strong> de <strong
                class="highlight">{{ ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'][now()->format('n') - 1] }}</strong>
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

        {{-- <div class="footer">
            <p><em class="important-text">Nota: El participante debe imprimir la presente Declaración Jurada, Compromiso
                    Académico y Administrativo firmada y adjuntar al folder I conjuntamente los requisitos mencionado en la convocatoria.</em></p>
        </div> --}}
    </main>
</body>

</html>
