<style>
        /* Contenedor del slider */
        .slider-container {
            position: relative;
            width: 100%;
            height: 65vh;
            overflow: hidden;
        }

        /* Estilos de las imágenes */
        .slider-item {
            position: absolute;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        /* Contenido del slider */
        .slider-content {
            position: absolute;
            top: 50%;
            left: 10%;
            transform: translateY(-50%);
            background: rgba(0, 0, 0, 0.6);
            padding: 20px;
            border-radius: 10px;
            color: #fff;
            max-width: 50%;
        }

        .slider-content h2 {
            font-size: 2.5rem;
            font-weight: bold;
        }

        .slider-content p {
            font-size: 1.2rem;
        }

        /* Estilos de los botones */
        .slider-btn a {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 20px;
            font-size: 1rem;
            border-radius: 5px;
            text-decoration: none;
            transition: 0.3s;
        }

        .btn-primary {
            background-color: #007bff;
            color: #fff;
            border: none;
        }

        .btn-outline-light {
            border: 2px solid #fff;
            color: #fff;
        }

        .btn-primary:hover,
        .btn-outline-light:hover {
            opacity: 0.8;
        }
    </style>
<section class="slider-container">
    <!-- Imagen 1 -->
    <div class="slider-item" style="background-image: url('frontend/images/banner_oferta_formativa.jpg');"></div>
    <div class="slider-content">
        <h2>Bienvenido</h2>
        <p>Explora nuestra oferta formativa y descubre nuevas oportunidades.</p>
        <div class="slider-btn">
            <a href="about-us/index.html" class="btn btn-primary">Más Información</a>
            <a href="contact/index.html" class="btn btn-outline-light">Contacto</a>
        </div>
    </div>
</section>

<section class="slider-container">
    <!-- Imagen 2 -->
    <div class="slider-item" style="background-image: url('storage/profe/{{ $profe->profe_banner }}');"></div>
    <div class="slider-content">
        <h2>{{ $profe->profe_nombre }}</h2>
        <p>{!! $profe->profe_descripcion !!}</p>
    </div>
</section>

<section class="slider-container">
    <!-- Imagen 3 -->
    <div class="slider-item" style="background-image: url('frontend/images/banner-minedu4.jpg');"></div>
    <div class="slider-content">
        <h2>Sobre Nosotros</h2>
        <p>{!! $profe->profe_mision !!}</p>
    </div>
</section>
