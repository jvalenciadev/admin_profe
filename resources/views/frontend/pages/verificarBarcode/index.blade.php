<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Código de Verificación</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f9;
        }

        .breadcrumb-area {
            background-color: #002855; /* Azul oscuro */
            /* background-image: url('frontend/images/bannerminedu1.jpg'); */
            background-blend-mode: overlay;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            padding: 50px 0;
            text-align: center;
            color: white;
        }

        .breadcrumb-area img {
            max-width: 100%;
            height: auto;
            margin-bottom: 10px;
        }

        .breadcrumb-area h4 {
            font-size: 28px;
            font-weight: 700;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.4);
        }

        .card {
            background-color: #ffffff;
            border: 1px solid #e3e6f0;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .card-title {
            font-size: 24px;
            font-weight: bold;
            color: #002855;
        }

        .badge {
            font-size: 16px;
            padding: 8px 12px;
            border-radius: 15px;
        }

        .badge.bg-success {
            background-color: #007bff;
            color: white;
        }

        .badge.bg-danger {
            background-color: #dc3545;
            color: white;
        }

        footer {
            margin-top: 40px;
            padding: 15px 0;
            background-color: #002855;
            color: white;
            text-align: center;
            font-size: 14px;
            letter-spacing: 0.5px;
        }

        .important-note {
            font-size: 16px;
            color: #dc3545;
            font-weight: bold;
            margin-top: 30px;
        }

        /* Media Queries para dispositivos móviles */
        @media (max-width: 768px) {
            .breadcrumb-area h4 {
                font-size: 24px;
            }

            .card-title {
                font-size: 22px;
            }

            .card {
                padding: 15px;
            }

            .breadcrumb-area img {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <!-- Encabezado -->
    <section class="breadcrumb-area">
        <div class="container">
            <img src="{{ asset('assets/image/logoprofeiippminedu2.png') }}" alt="Logo Minedu">
            <h4>SISTEMA DE VALIDACIÓN DE DOCUMENTOS MEDIANTE CÓDIGO QR</h4>
        </div>
    </section>

    <!-- Contenido principal -->
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10 col-sm-12">
                <div class="card shadow-lg">
                    <div class="card-body text-center">
                        @if(isset($error))
                            <div class="alert alert-danger text-center">
                                <h4 class="alert-heading">Error</h4>
                                <p>{{ $error }}</p>
                            </div>
                        @else
                            
                                <h1 class="card-title">{{ $tipo_barcode }}</h1>
                                <hr>
                                <p class="card-text mb-3">Código: <strong> {{ $barcode->bar_md5 }}</strong></p>
                                <hr>
                                <p class="card-text mb-3"><strong> PROGRAMA:</strong> <br>{{ mb_strtoupper($participante->pro_tip_nombre, 'UTF-8') }} EN {{ mb_strtoupper($participante->pro_nombre, 'UTF-8') }}</p>
                                <p class="card-text mb-3"><strong> OFERTA:</strong> {{$participante->pv_romano }}/{{$participante->pv_gestion }}</p>
                                <p class="card-text mb-3"><strong> VERSIÓN:</strong> {{$participante->pv_numero }}RA. {{mb_strtoupper($participante->pv_nombre) }}  </p>
                                <hr>
                                <p class="card-text mb-3"><strong>PARTICIPANTE: </strong><br>{{ mb_strtoupper($participante->per_nombre1, 'UTF-8') }} {{ mb_strtoupper($participante->per_nombre2, 'UTF-8') }} {{ mb_strtoupper($participante->per_apellido1, 'UTF-8') }} {{ mb_strtoupper($participante->per_apellido2, 'UTF-8') }}</p>
                                <hr>
                                <p class="card-text mb-3"><strong>CÉDULA DE IDENTIDAD:</strong> <br>{{ $participante->per_ci }} </p>
                                <hr>
                                @if($tipo_barcode == "CERTIFICADO DE NOTA")
                                    <p class="card-text"><strong> MÓDULOS:</strong> </p>
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Módulo</th>
                                                <th>Descripción</th>
                                                <th>Puntaje</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($modulos as $mod)
                                            <tr>
                                                <td>{{ $mod->pm_nombre}}</td>
                                                <td>{{ $mod->pm_descripcion}}</td>
                                                <td>{{ $mod->cp_puntaje}}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                @endif
                          
                        @endif
                        <!-- Importante nota -->
                        <div class="important-note">
                            <p>Importante: Si la información expuesta coincide con la información impresa del documento que desea validar, el certificado puede considerarse válido. Caso contrario, es inválido.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2025 Ministerio de Educación - Todos los derechos reservados</p>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
