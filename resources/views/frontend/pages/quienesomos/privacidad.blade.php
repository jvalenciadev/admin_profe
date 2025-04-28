@extends('frontend.layouts.master')
@section('title')
    Política de Privacidad - PROFE
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

    <section class="breadcrumb-area d-flex p-relative align-items-center">
        <div class="container">
            <div class="row align-items-center">
                <div class="breadcrumb-wrap2">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Política de Privacidad</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section> <!-- breadcrumb-area-end -->

    <section class="container py-5">
        <div class="section">
            <h2 class="section-title">Introducción</h2>
            <p>La privacidad de los usuarios de PROFE es muy importante para nosotros. En esta política de privacidad, explicamos cómo recolectamos, utilizamos y protegemos su información personal cuando utiliza nuestros servicios. Al registrarse o usar nuestros servicios, usted acepta las prácticas descritas en esta política.</p>
        </div>

        <div class="section">
            <h2 class="section-title">Información que Recopilamos</h2>
            <p>PROFE recopila y almacena información personal cuando los usuarios se registran en nuestros programas, como:</p>
            <ul>
                <li>Nombre completo</li>
                <li>Dirección de correo electrónico</li>
                <li>Número de identificación o cédula de identidad</li>
                <li>Información académica (títulos, grados, etc.)</li>
                <li>Información de contacto</li>
            </ul>
        </div>

        <div class="section">
            <h2 class="section-title">Uso de la Información</h2>
            <p>La información que recopilamos se utiliza para:</p>
            <ul>
                <li>Gestionar la inscripción y participación en los programas ofrecidos.</li>
                <li>Enviar notificaciones y actualizaciones relacionadas con los programas (como fechas de inicio, resultados, etc.).</li>
                <li>Mejorar la experiencia de usuario y el rendimiento de nuestra plataforma.</li>
                <li>Cumplir con las normativas legales y fiscales vigentes en Bolivia.</li>
            </ul>
        </div>

        <div class="section">
            <h2 class="section-title">Protección de Datos</h2>
            <p>PROFE toma medidas de seguridad adecuadas para proteger su información personal contra accesos no autorizados, alteración, divulgación o destrucción. No obstante, ningún sistema de transmisión de datos por Internet es completamente seguro, por lo que no podemos garantizar la seguridad absoluta.</p>
        </div>

        <div class="section">
            <h2 class="section-title">Compartir Información con Terceros</h2>
            <p>PROFE no compartirá, venderá ni alquilará su información personal a terceros sin su consentimiento, salvo que sea necesario para cumplir con la ley o para la gestión de los programas educativos.</p>
        </div>

        <div class="section">
            <h2 class="section-title">Derechos de los Usuarios</h2>
            <ul>
                <li>Acceder a la información personal que PROFE tiene sobre usted.</li>
                <li>Solicitar la corrección o eliminación de datos incorrectos o desactualizados.</li>
                <li>Solicitar la cancelación de su cuenta en cualquier momento, siguiendo el procedimiento indicado en nuestra plataforma.</li>
            </ul>
        </div>

        <div class="section">
            <h2 class="section-title">Cookies y Seguimiento</h2>
            <p>Nuestra plataforma utiliza cookies para mejorar la experiencia del usuario. Las cookies son pequeños archivos almacenados en su dispositivo que nos permiten reconocerle en futuras visitas. Puede configurar su navegador para bloquear las cookies si lo desea, aunque esto puede afectar el funcionamiento de algunos servicios.</p>
        </div>

        <div class="section">
            <h2 class="section-title">Modificaciones a la Política de Privacidad</h2>
            <p>PROFE puede actualizar esta política de privacidad en cualquier momento. Cualquier cambio será publicado en nuestra página web y entrará en vigor inmediatamente después de su publicación.</p>
        </div>
    </section>
@endsection
