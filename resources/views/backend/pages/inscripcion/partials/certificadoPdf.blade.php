<!DOCTYPE html>
<html>

<head>
    <title>MINEDU - PROFE</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Flex:opsz,wght@8..144,100..1000&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <style>
        @font-face {
            font-family: 'Helvetica Neue';
            src: url('{{ storage_path('storage/fonts/HelveticaNeue.ttc') }}') format('truetype');
        }
        
        @page {
            size: landscape;
            margin: 0;
        }
        
        body {
            font-family: 'Helvetica Neue', sans-serif;
            margin: 0;
            padding: 0;
            font-size: 16px;
        }
        .roboto-light {
            font-family: "Roboto", sans-serif;
            font-weight: 300;
            font-style: normal;
        }
        .roboto-bold {
            font-family: "Roboto", sans-serif;
            font-weight: 400;
            font-style: normal;
        }
        .page {
            position: relative;
            width: 100%;
            height: 100%;
            page-break-after: always;
        }

        .name {
            position: absolute;
            font-size: 24px;
            font-weight: 700;
            top: 315px;
            left: 50%;
            transform: translateX(-50%);
            white-space: nowrap; /* Evita el salto de línea */
            overflow: hidden; /* Oculta el texto que desborda */
            text-overflow: ellipsis; /* Muestra puntos suspensivos si el texto es demasiado largo */
        }

        .certificate-content {
            position: absolute;
            top: 350px;
            left: 9.1%;
            right: 9%;
            text-align: center;
            line-height: 1.3;
            padding-left: 20px;
            padding-right: 20px;
            box-sizing: border-box;
        }

        .certificate-content .justify {
            text-align: justify;
        }

        .certificate-content .centreado {
            position: absolute;
            left: 200px;
            width: calc(100% - 400px);
            text-align: left;
        }

        .certificate-content .space-half {
            margin-top: 0.4em;
        }

        .certificate-content .space-half1 {
            margin-top: 0.4em;
        }

        .certificate-date {
            position: absolute;
            bottom: 180px;
            right: 110px;
            text-align: right;
        }
    </style>
</head>

<body>
    <main>
        @foreach ($results as $result)
            <div class="page">
                <div class="certificate-background"></div>

                <div class="name">
                    {{ $result->nombre }} {{ $result->paterno }} {{ $result->materno }}
                </div>

                <div class="certificate-content roboto-light">
                    <div style="text-align: left;">
                        Aprobó el ciclo formativo:
                    </div>
                    <div class="space-half1"></div>
                    <span class="roboto-bold">“Resolución de Conflictos en el Ámbito Educativo para la Convivencia Armónica”</span>
                    <div class="space-half1"></div>
                    <div class="justify">
                        Desarrollado del <span class="roboto-bold">03 de junio</span> al <span class="roboto-bold">18 de agosto</span> de 2024, con una
                        carga horaria de 150 horas académicas acumuladas en la modalidad semipresencial, el cual comprende los siguientes cursos:
                    </div>
                    <div class="space-half"></div>
                    <div class="centreado roboto-light">
                        <span class="roboto-bold">- Gestión del Conflicto en el Ámbito Educativo</span> (50 horas)<br>
                        <span class="roboto-bold">- Técnicas para la Mediación en el Ámbito Educativo desde el Enfoque en Soluciones</span> (50 horas)<br>
                        <span class="roboto-bold">- Resolución de Conflictos en el Ámbito Educativo</span> (50 horas)
                    </div>
                </div>
                

                <div class="certificate-date roboto-light">
                    La Paz, noviembre de 2024.
                </div>

                <div style="position: relative; width: 100px; height: 100px; top: 45px; left: 50px;">
                    <img src="data:image/jpeg;base64,{{ $result->qr_code }}" alt="Código QR"
                        style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover;">
                    <img src="data:image/jpeg;base64,{{ $logo2 }}" alt="Logo 2" width="30px"
                        style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                </div>
            </div>
        @endforeach
        
    </main>
</body>

</html>
