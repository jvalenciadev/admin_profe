@extends('frontend.layouts.master')
@section('title')
    Términos y Condiciones - PROFE
@endsection
@section('frontend-content')
    <style>
        .breadcrumb-area {
            background-color: rgba(32, 40, 46, 0.8);
            background-image: url('{{ asset('frontend/images/nosotros.jpg') }}');
            background-size: cover;
            background-position: center;
            color: #fff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            padding: 100px 0;
        }

        .breadcrumb-title h2 {
            font-size: 48px;
            font-weight: 700;
            text-shadow: 3px 3px 8px rgba(0, 0, 0, 0.7);
        }

        .breadcrumb-item a {
            color: #f39c12;
            text-decoration: none;
            font-weight: 500;
        }

        .section-title {
            font-size: 36px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 30px;
            text-align: center;
            letter-spacing: 1px;
            text-transform: uppercase;
            border-bottom: 3px solid #f39c12;
            padding-bottom: 10px;
        }

        .section-content {
            font-size: 18px;
            line-height: 1.8;
            color: #555;
            margin-bottom: 35px;
            text-align: justify;
            font-family: 'Arial', sans-serif;
        }

        .list-group-item {
            border: none;
            padding: 15px 30px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }

        .list-group-item .icon {
            font-size: 22px;
            color: #f39c12;
            margin-right: 15px;
        }

        .list-group-item .text {
            font-size: 18px;
            color: #333;
        }

        .section {
            padding: 50px 30px;
            margin-bottom: 50px;
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease-in-out;
        }

        .section:hover {
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            transform: translateY(-5px);
        }

        .section-header {
            margin-bottom: 25px;
            font-weight: 600;
            color: #2c3e50;
        }

        .section-content ul {
            padding-left: 25px;
            list-style-type: disc;
            color: #555;
        }

        .section-content li {
            margin-bottom: 10px;
            font-size: 16px;
        }

        /* Estilos de la sección para mejorar la legibilidad */
        .section-content ul li strong {
            color: #f39c12;
            font-weight: 700;
        }

        /* Fondo sutil para la página */
        body {
            background-color: #f4f6f9;
        }
    </style>

    <section class="breadcrumb-area d-flex align-items-center justify-content-center">
        <div class="container">
            <div class="breadcrumb-wrap2 text-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Términos y Condiciones</li>
                    </ol>
                </nav>
            </div>
        </div>
    </section> <!-- breadcrumb-area-end -->

    <section class="container py-5">
        <div class="section">
            <h2 class="section-title">Introducción</h2>
            <p class="section-content">Bienvenido a PROFE, el Programa de Formación de Maestros, administrado por el Ministerio de Educación de Bolivia. Al acceder y utilizar nuestros servicios, usted acepta cumplir con los siguientes términos y condiciones. Si no está de acuerdo con estos términos, le solicitamos que no utilice nuestros servicios.</p>
        </div>

        <div class="section">
            <h2 class="section-title">Servicios Ofrecidos</h2>
            <p class="section-content">PROFE ofrece una variedad de opciones académicas, tales como:</p>
            <ul class="list-group">
                <li class="list-group-item">
                    <i class="fas fa-graduation-cap icon"></i><span class="text">Diplomados</span>
                </li>
                <li class="list-group-item">
                    <i class="fas fa-book-reader icon"></i><span class="text">Ciclos Formativos</span>
                </li>
                <li class="list-group-item">
                    <i class="fas fa-cogs icon"></i><span class="text">Especialidades</span>
                </li>
            </ul>
            <p class="section-content">Estos programas están diseñados para capacitar y actualizar a los maestros del país, con el objetivo de mejorar su práctica profesional y contribuir al desarrollo de la educación en Bolivia.</p>
        </div>

        <div class="section">
            <h2 class="section-title">Requisitos de Inscripción</h2>
            <p class="section-content">Para poder acceder a los programas ofrecidos por PROFE, es necesario cumplir con los siguientes requisitos:</p>
            <ul class="list-group">
                <li class="list-group-item">Validación de información personal</li>
                <li class="list-group-item">Aprobación de los exámenes de admisión</li>
                <li class="list-group-item">Cumplimiento de las normativas internas del programa</li>
            </ul>
        </div>

        <div class="section">
            <h2 class="section-title">Responsabilidades del Usuario</h2>
            <p class="section-content">Al registrarse en el programa, usted asume las siguientes responsabilidades:</p>
            <ul class="section-content">
                <li><strong>Exactitud de la Información:</strong> Usted se compromete a proporcionar información precisa y actualizada durante el proceso de inscripción y a mantenerla actualizada durante su participación en los cursos.</li>
                <li><strong>Uso Adecuado de la Plataforma:</strong> Se compromete a utilizar la plataforma de manera ética, respetuosa y conforme a las normativas legales del país.</li>
                <li><strong>No Divulgación:</strong> Se prohíbe la divulgación no autorizada de materiales o contenido de los programas ofrecidos.</li>
                <li><strong>Cumplimiento de Normativas:</strong> El usuario se compromete a seguir todas las políticas y regulaciones establecidas por PROFE, incluyendo el código de conducta, el cumplimiento de las fechas límite, y la correcta utilización de los recursos.</li>
            </ul>
        </div>

        <div class="section">
            <h2 class="section-title">Modificación de los Términos</h2>
            <p class="section-content">PROFE se reserva el derecho de modificar estos términos en cualquier momento. Las modificaciones serán efectivas una vez publicadas en el sitio web de PROFE. Se recomienda revisar periódicamente estos términos para mantenerse informado sobre cualquier cambio. Los usuarios deberán aceptar los nuevos términos para continuar utilizando los servicios.</p>
        </div>

        <div class="section">
            <h2 class="section-title">Limitación de Responsabilidad</h2>
            <p class="section-content">PROFE no se hace responsable por los daños, pérdidas o perjuicios que puedan surgir del uso inapropiado de nuestros servicios. Asimismo, no somos responsables por interrupciones temporales en el acceso a nuestros servicios debido a mantenimiento o eventos fuera de nuestro control, como problemas técnicos, fuerza mayor o desastres naturales.</p>
        </div>

        <div class="section">
            <h2 class="section-title">Ley Aplicable y Jurisdicción</h2>
            <p class="section-content">Estos términos y condiciones están regidos por las leyes de Bolivia. Cualquier disputa relacionada con estos términos será resuelta ante los tribunales competentes de Bolivia. Los usuarios aceptan que cualquier litigio será resuelto exclusivamente en las cortes de Bolivia, renunciando a cualquier otro foro jurisdiccional.</p>
        </div>

        <div class="section">
            <h2 class="section-title">Datos Personales y Privacidad</h2>
            <p class="section-content">PROFE garantiza la protección de los datos personales de sus usuarios conforme a la Ley General de Protección de Datos Personales de Bolivia. Los datos recopilados serán utilizados exclusivamente para fines relacionados con la inscripción y gestión de los programas educativos, así como para la mejora de nuestros servicios. No compartiremos sus datos con terceros sin su consentimiento, salvo en casos legales o contractuales requeridos por las autoridades competentes.</p>
        </div>

        <div class="section">
            <h2 class="section-title">Política de Devoluciones y Cancelaciones</h2>
            <p class="section-content">En caso de que un usuario desee cancelar su inscripción, deberá comunicarse con el soporte de PROFE dentro de los primeros 15 días de la inscripción para evaluar la posibilidad de una devolución. Después de este período, no se realizarán devoluciones. Las devoluciones son válidas únicamente si la cancelación es solicitada por razones excepcionales y justificadas.</p>
        </div>

        <div class="section">
            <h2 class="section-title">Actualización de Datos Personales</h2>
            <p class="section-content">Es responsabilidad del usuario mantener actualizada su información personal. Cualquier cambio en datos importantes, como número de teléfono, dirección o correo electrónico, debe ser reportado inmediatamente a través del portal de usuario o contacto directo con el equipo de soporte. Esto es fundamental para recibir notificaciones e información sobre los programas de forma correcta.</p>
        </div>
    </section>
@endsection
