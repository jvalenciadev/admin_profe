@extends('frontend.layouts.master')

@section('title')
    PROFE - OFERTAS ACADÉMICAS
@endsection

@section('description')
    ✅ {{ $programa->pro_nombre }}
@endsection

@section('styles')
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: 40px auto;
            border: 1px solid #1474a6;
        }

        .form-title {
            text-align: center;
            font-size: 1.5em;
            font-weight: bold;
            color: #1474a6;
            margin-bottom: 20px;
        }

        .afiche-image {
            border-radius: 10px;
            margin-bottom: 30px;
        }

        .logout-btn {
            align-items: center;
            background-color: transparent;
            color: #dc3545;
            border: none;
            cursor: pointer;
            font-size: 1em;
            transition: color 0.3s;
            margin-top: 20px;
        }

        .logout-btn:hover {
            color: #b02a2a;
        }

        .alert {
            margin-bottom: 20px;
        }

        .btn-lg {
            font-size: 1.2rem;
            padding: 10px 20px;
        }

        .welcome-message {
            font-size: 1.2em;
            font-weight: bold;
            color: #28a745;
            margin-bottom: 30px;
        }
        .error-message {
            font-size: 1.2em;
            font-weight: bold;
            color: #ba352c;
            margin-bottom: 30px;
        }
        .error-dos-message {
            font-weight: bold;
            color: #ba352c;
        }

        .document-info {
            font-size: 1.1em;
            line-height: 1.8;
            color: #333;
            margin-bottom: 20px;
            text-align: justify;
        }

        .document-info ul {
            list-style: none;
            padding-left: 0;
            margin-left: 0;
        }

        .document-info li {
            padding: 8px;
            position: relative;
        }

        .document-info li::before {
            content: '✔️';
            position: absolute;
            left: -30px;
            top: 0;
        }

        .download-btn {
            font-size: 1.2rem;
            padding: 12px 25px;
            background-color: #1474a6;
            color: #fff;
            border-radius: 5px;
            text-decoration: none;
            margin-right: 10px;
            transition: background-color 0.3s ease;
            display: inline-block;
            width: auto;
        }

        .download-btn:hover {
            background-color: #0c3c4c;
        }

        .logout-btn-container {
            margin-top: 30px;
            text-align: center;
        }

        .logout-btn-container a {
            font-size: 1.1rem;
            padding: 10px 20px;
            background-color: #dc3545;
            color: #fff;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .logout-btn-container a:hover {
            background-color: #b02a2a;
        }

        /* Media Queries para pantallas pequeñas */
        @media (max-width: 767px) {
            .form-container {
                padding: 20px;
            }

            .download-btn {
                width: 100%;
                margin: 10px 0;
            }

            .logout-btn-container a {
                width: 100%;
                margin: 10px 0;
            }

            .document-info ul {
                margin-left: 20px; /* Ajusta el margen de las viñetas */
            }

            .document-info li {
                padding-left: 10px; /* Ajusta el espaciado a la izquierda */
            }

            .document-info li::before {
                left: -20px; /* Ajusta la posición de la viñeta */
            }
        }
    </style>
@endsection

@section('frontend-content')
    <div id="content" class="site-content">
        <section>
            <div class="form-container">
                <img src="{{ asset('storage/programa_afiches/' . $programa->pro_afiche) }}" alt="Afiche del programa" class="afiche-image img-fluid">

                <!-- Mostrar mensaje de sesión -->
                @if (session('danger'))
                    <div class="alert alert-danger">
                        {{ session('danger') }}
                    </div>
                @endif

                <h4 class="font-weight-normal text-center">
                    {{ $programa->per_nombre1 . ' ' . $programa->per_nombre2 . ' ' . $programa->per_apellido1 . ' ' . $programa->per_apellido2 }}
                </h4>

                <!-- Mensaje de bienvenida -->
                <p class="welcome-message text-center">
                    ¡BIENVENIDO AL {{ mb_strtoupper($programa->pro_tip_nombre, 'UTF-8') }}!
                </p>
                @if($programa->pie_id == 4)
                    <p class="alert alert-danger text-justify">
                        {{-- <strong>Importante:</strong> Tiene un plazo de <b>48 horas</b> para realizar el depósito y presentar su documentación en la sede en la que se inscribió. --}}
                        {{-- <br> --}}
                        <span class="fw-bold">Nota:</span> Debe dejar sus documentos en la sede que se inscribió.
                    </p>
                @endif
                @if($programa->pie_id == 2 || $programa->pie_id == 7)
                <p class="error-message text-center">
                    USTED YA SE ENCUENTRA INSCRITO
                </p>
                @endif
                @if($programa->pie_id == 4 )
                    <p class="form-title">REQUISITOS PARA EL {{ mb_strtoupper($programa->pro_tip_nombre, 'UTF-8') }}</p>
                        @if($programa->pro_tip_id==2)
                        <div class="document-info">
                            <ul>
                                <li>Fotocopia simple de la Cédula de identidad vigente.</li>
                                <li>Formulario del Registro Docente Administrativo (RDA) actualizado.</li>
                                <li>Fotocopia simple de la Boleta de Pago que demuestre estar en función activa dentro del Sistema Educativo Plurinacional.</li>
                                <li>Presentación de la boleta original o el comprobante de transferencia bancaria, acompañado de cuatro fotocopias simples (por el costo total).</li>
                                <li>Ficha de inscripción al programa. <div class="error-dos-message"> (Recoger en la sede inscrita)</div></li>
                                <li>Carta de compromiso y cumplimiento de todas las actividades académicas descargue <a href="{{ route('programa.compromisoParticipantePdf', [
                                    'per_id' => encrypt($programa->per_id),
                                    'pro_id' => encrypt($programa->pro_id),
                                ]) }}" target="_blank">aquí</a>.</li>
                                {{-- <li>Certificado de trabajo (actualizado) emitido por la autoridad competente de su institución para otros actores educativos del SEP.</li> --}}
                                <li>Toda la documentación debe ser presentada en la subsede del Programa PROFE donde realizó su inscripción, en un folder debidamente <a href="{{ route('programa.rotuloParticipantePdf', [
                                    'per_id' => encrypt($programa->per_id),
                                    'pro_id' => encrypt($programa->pro_id),
                                ]) }}" target="_blank">rotulado</a> con el nombre del diplomado al que se postula.</li>
                            </ul>
                        </div>
                    @else
                        <div class="document-info">
                            <ul>
                                <li>Fotocopia simple de la Cédula de identidad vigente.</li>
                                <li>Formulario del Registro Docente Administrativo (RDA) actualizado, donde conste el registro de título Profesional en el MESCP o universitario a nivel de licenciatura.</li>
                                <li>Fotocopia simple de la Boleta de Pago que demuestre estar en función activa y de acuerdo con el diplomado al que postula dentro del Sistema Educativo Plurinacional.</li>
                                <li>Presentación de la boleta original o el comprobante de transferencia bancaria, acompañado de cuatro fotocopias simples, correspondiente al pago de la primera cuota.</li>
                                <li>Ficha de inscripción al programa. <div class="error-dos-message"> (Recoger en la sede inscrita)</div></li>
                                <li>Carta de compromiso y cumplimiento de todas las actividades académicas descargue <a href="{{ route('programa.compromisoParticipantePdf', [
                                    'per_id' => encrypt($programa->per_id),
                                    'pro_id' => encrypt($programa->pro_id),
                                ]) }}" target="_blank">aquí</a>.</li>
                                <li>Toda la documentación debe ser presentada en la subsede del Programa PROFE donde realizó su inscripción, en un folder debidamente <a href="{{ route('programa.rotuloParticipantePdf', [
                                    'per_id' => encrypt($programa->per_id),
                                    'pro_id' => encrypt($programa->pro_id),
                                ]) }}" target="_blank">rotulado</a> con el nombre del diplomado al que se postula.</li>
                            </ul>
                        </div>
                    @endif
                @endif
                <div class="text-center mt-4">
                    @if($programa->pie_id == 4 || $programa->pie_id == 7)
                        <a href="{{ route('programa.habilitacionParticipantePdf', [
                            'per_id' => encrypt($programa->per_id),
                            'pro_id' => encrypt($programa->pro_id),
                        ]) }}" class="download-btn" target="_blank">
                            Habilitación de Pagó PDF
                        </a>
                    @endif
                    @if($programa->pie_id == 2)
                        <a href="{{ route('programa.comprobanteParticipantePdf', [
                            'per_id' => encrypt($programa->per_id),
                            'pro_id' => encrypt($programa->pro_id),
                        ]) }}" class="download-btn" target="_blank">
                            Inscripción PDF
                        </a>
                    @endif
                    <a href="{{ route('programa.compromisoParticipantePdf', [
                        'per_id' => encrypt($programa->per_id),
                        'pro_id' => encrypt($programa->pro_id),
                    ]) }}" class="download-btn" target="_blank">
                        Compromiso PDF
                    </a>
                    <a href="{{ route('programa.rotuloParticipantePdf', [
                        'per_id' => encrypt($programa->per_id),
                        'pro_id' => encrypt($programa->pro_id),
                    ]) }}" class="download-btn" target="_blank">
                        Rotulo PDF
                    </a>
                </div>

                <div class="logout-btn-container">
                    <a href="{{ route('programa.logout') }}" class="logout-btn">
                        SALIR
                    </a>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('scripts')
@endsection
