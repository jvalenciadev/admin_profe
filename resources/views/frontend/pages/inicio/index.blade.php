@extends('frontend.layouts.master')

@section('styles')
    <style>
        /* Fondo oscuro del modal */
        .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.5);
        }

        /* Centrar el modal verticalmente */
        .modal-dialog {
            display: flex;
            align-items: center;
            min-height: 100vh; /* Ocupa toda la altura de la pantalla */
        }

        /* Ajuste del iframe dentro del modal */
        .modal-body iframe {
            width: 100%;
            height: 500px; /* Ajusta la altura según sea necesario */
            border: none;
        }
        .pdf-container {
            width: 100%;
            height: 500px;
        }
    </style>
@endsection

@section('og-meta-tags')
    <meta property="og:locale" content="es_ES" />
    <meta property="og:title" content="Lanzamiento de la oferta académica" />
    <meta name="og:description" content="Participa en los diplomados, ciclos formativos y especialidades del Programa PROFE y descubre nuevas herramientas y estrategias para enriquecer tu enseñanza. ¡Inscríbete ahora!" />
    <meta property="og:image" content="{{ asset('storage/profe/8S2NFI3igKxpAufKni2xwJ8Z12leCdM9C1p5CRgU.jpg') }}" />
    <meta property="og:image:width" content="545" />
    <meta property="og:image:height" content="493" />
    <meta property="og:image:type" content="image/jpeg" />
@endsection

@section('frontend-content')
    <article id="post-9" class="post-9 page type-page status-publish hentry">
        <div class="pages-content">
            <div data-elementor-type="wp-page" data-elementor-id="9" class="elementor elementor-9">
                @include('frontend.pages.inicio.components.portada')
                @include('frontend.pages.inicio.components.programas')
                @include('frontend.pages.inicio.components.eventos')
                @include('frontend.pages.inicio.components.acerca')
                @include('frontend.pages.inicio.components.blogs')
            </div>
        </div>
    </article>

   <!-- MODAL DE NOTIFICACIÓN -->
   <div class="modal fade" id="convocatoriaModal" tabindex="-1" aria-labelledby="convocatoriaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered"> <!-- Se agregó 'modal-lg' para mayor tamaño -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="convocatoriaLabel">📢 Convocatoria Pública N° 001/2025</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <p></p>

                <!-- Iframe para mostrar el PDF -->
                <div class="pdf-container">
                    <iframe src="https://docs.google.com/gview?embedded=true&url={{ asset('storage/profe/convocatoria.pdf') }}" 
                            style="width:100%; height:500px;" frameborder="0">
                    </iframe>
                </div>
                <p class="mt-3">
                    <a href="{{ asset('storage/profe/convocatoria.pdf') }}" class="btn btn-secundary" target="_blank">
                        📥 Descargar Convocatoria
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var convocatoriaModal = new bootstrap.Modal(document.getElementById('convocatoriaModal'));
            convocatoriaModal.show();
        });
    </script>
@endsection
