@extends('frontend.layouts.master')
@section('title')
    ProfeBotics
@endsection
@section('frontend-content')
    <style id="qeducato_data-dynamic-css" title="dynamic-css" class="redux-options-output">
        .breadcrumb-area {
            background-color: rgba(32, 40, 46, 0);
            /* Fondo con un poco de transparencia */
            background-image: url('{{ asset('frontend/images/banner-minedu5.jpg') }}');
            background-blend-mode: overlay;
            /* Mezcla el color de fondo y la imagen */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            /* Añade sombra */
        }
    </style>
    <section class="breadcrumb-area d-flex  p-relative align-items-center">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-xl-12 col-lg-12">
                    <div class="breadcrumb-wrap text-left">
                        <div class="breadcrumb-title">
                            <h2>Profe Robotics</h2>
                        </div>
                    </div>
                </div>
                <div class="breadcrumb-wrap2">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Profebotics</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>

    <article id="post-78" class="post-78 page type-page status-publish hentry">
        <div class="pages-content">
            <div data-elementor-type="wp-page" data-elementor-id="78" class="elementor elementor-78">
                <section
                    class="elementor-section elementor-top-section elementor-element elementor-element-ce034c9 elementor-section-full_width elementor-section-height-default elementor-section-height-default"
                    data-id="ce034c9" data-element_type="section">
                    <div class="elementor-container elementor-column-gap-no">
                        <div class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-c20593e"
                            data-id="c20593e" data-element_type="column">
                            <div class="elementor-widget-wrap elementor-element-populated">
                                <div class="elementor-element elementor-element-1e2c1f4 elementor-widget elementor-widget-Elementor-courses-widget"
                                    data-id="1e2c1f4" data-element_type="widget"
                                    data-widget_type="Elementor-courses-widget.default">
                                    <div class="elementor-widget-container"> <!-- courses-area -->
                                        <section class="shop-area pt-50 pb-90 p-relative "
                                            data-animation="fadeInUp animated" data-delay=".2s">
                                            <div class="container">
                                                {{-- <div class="alert alert-info text-center" role="alert"
                                                    style="background-color: #e0f7fa; border-color: #8de0eb; padding: 20px;">
                                                    <h3 class="category-header text-secondary mb-0"
                                                        style="font-weight: 600;">
                                                        Podrás descargar la reglamentación detallada a partir<br> del 6 de
                                                        septiembre de 2024.</h3>
                                                </div> --}}

                                                <!-- Tabla de categorías -->
                                                <div class="row text-center">
                                                    <div class="col-12">
                                                        <h2 class="mb-4">Categoría A</h2>
                                                        <p>Basada en el torneo de robótica con hardware libre, la categoría
                                                            “A” tiene 5 subcategorías</p>
                                                    </div>
                                                </div>
                                                <table class="table table-bordered table-hover mb-40">
                                                    <thead class="thead-light">
                                                        <tr>
                                                            <th>N°</th>
                                                            <th>Categoría</th>
                                                            <th>Grados</th>
                                                            <th>Participación Nacional</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td><strong>1.</strong></td>
                                                            <td><strong>Constructores Insectos</strong></td>
                                                            <td>1ro y/o 2do de primaria</td>
                                                            <td>No participa en la etapa nacional</td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>2.</strong></td>
                                                            <td><strong>Robot Soccer</strong></td>
                                                            <td>5to y/o 6to de primaria</td>
                                                            <td>Participa en la etapa nacional</td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>3.</strong></td>
                                                            <td><strong>Zumo Con Control Remoto (Rc)</strong></td>
                                                            <td>1ro, 2do y/o 3ro de secundaria</td>
                                                            <td>No participa en la etapa nacional</td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>4.</strong></td>
                                                            <td><strong>Zumo Autónomo</strong></td>
                                                            <td>1ro, 2do y/o 3ro de secundaria</td>
                                                            <td>Participa en la etapa nacional</td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>5.</strong></td>
                                                            <td><strong>Seguidor de Línea</strong></td>
                                                            <td>4to y/o 5to de secundaria</td>
                                                            <td>Participa en la etapa nacional</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <div class="row align-items-center">
                                                    <div class="col-lg-4 col-md-6 ">
                                                        <div class="courses-item mb-30 hover-zoomin">
                                                            <div class="thumb fix"> <a href="#"> <img
                                                                        fetchpriority="high" decoding="async"
                                                                        style="height: 300px; object-fit: cover; border-top-left-radius: 10px; border-top-right-radius: 10px;"
                                                                        src="{{ asset('frontend/robots/robot-arana.jpg') }}"
                                                                        class="attachment-post-thumbnail size-post-thumbnail wp-post-image"
                                                                        alt="" /> </a></div>
                                                            <div class="courses-content">
                                                                <div class="cat"><i class="fal fa-graduation-cap"></i>
                                                                    Constructores Insectos</div>
                                                                <h3><a
                                                                        href="{{ asset('frontend/robots/constructores_insectos.pdf') }}">Constructores
                                                                        Insectos</a>
                                                                </h3>
                                                                <p>Equipos conformados por dos estudiantes de 1ro y/o 2do
                                                                    primaria de la misma unidad educativa y un
                                                                    tutor. Esta categoría no participa en la etapa nacional.
                                                                </p>
                                                                {{-- <a href="{{ asset('frontend/robots/constructores_insectos.pdf') }}"
                                                                    class="btn btn-primary text-light">DESCARGAR</a> --}}
                                                                {{-- <a href="../courses/biochemistry/index.html"
                                                                    class="readmore">Read More <i
                                                                        class="fal fa-long-arrow-right"></i></a> --}}
                                                            </div>

                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-md-6 ">
                                                        <div class="courses-item mb-30 hover-zoomin">
                                                            <div class="thumb fix"> <a href="#"> <img
                                                                        fetchpriority="high" decoding="async"
                                                                        style="height: 300px; object-fit: cover; border-top-left-radius: 10px; border-top-right-radius: 10px;"
                                                                        src="{{ asset('frontend/robots/soccer.png') }}"
                                                                        class="attachment-post-thumbnail size-post-thumbnail wp-post-image"
                                                                        alt="" /> </a></div>
                                                            <div class="courses-content">
                                                                <div class="cat"><i class="fal fa-graduation-cap"></i>
                                                                    Robot Soccer</div>
                                                                <h3><a
                                                                        href="{{ asset('frontend/robots/robot_soccer.pdf') }}">Robot
                                                                        Soccer</a>
                                                                </h3>
                                                                <p>Equipos conformados por dos estudiantes de 5to y/o 6to de
                                                                    primaria de la misma unidad educativa y un tutor. El
                                                                    equipo ganador en la etapa departamental participará en
                                                                    la etapa nacional.
                                                                </p>
                                                                {{-- <a href="{{ asset('frontend/robots/robot_soccer.pdf') }}"
                                                                    class="btn btn-primary text-light">DESCARGAR</a> --}}
                                                                {{-- <a href="../courses/biochemistry/index.html"
                                                                    class="readmore">Read More <i
                                                                        class="fal fa-long-arrow-right"></i></a> --}}
                                                            </div>

                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-md-6 ">
                                                        <div class="courses-item mb-30 hover-zoomin">
                                                            <div class="thumb fix"> <a href="#"> <img
                                                                        fetchpriority="high" decoding="async"
                                                                        style="height: 300px; object-fit: cover; border-top-left-radius: 10px; border-top-right-radius: 10px;"
                                                                        src="{{ asset('frontend/robots/zumo-robot.jpg') }}"
                                                                        class="attachment-post-thumbnail size-post-thumbnail wp-post-image"
                                                                        alt="" /> </a></div>
                                                            <div class="courses-content">
                                                                <div class="cat"><i class="fal fa-graduation-cap"></i>
                                                                    Zumo con control Remoto (Rc)</div>
                                                                <h3><a
                                                                        href="{{ asset('frontend/robots/minisumo_rc.pdf') }}">Zumo
                                                                        con control Remoto (Rc)</a>
                                                                </h3>
                                                                <p>Equipos conformados por dos estudiantes de 1ro, 2do y/o
                                                                    3ro de secundaria de la misma unidad educativa y un
                                                                    tutor. Esta categoría no participa en la etapa nacional.
                                                                </p>
                                                                {{-- <a href="{{ asset('frontend/robots/minisumo_rc.pdf') }}"
                                                                    class="btn btn-primary text-light">DESCARGAR</a> --}}
                                                                {{-- <a href="../courses/biochemistry/index.html"
                                                                    class="readmore">Read More <i
                                                                        class="fal fa-long-arrow-right"></i></a> --}}
                                                            </div>

                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-md-6 ">
                                                        <div class="courses-item mb-30 hover-zoomin">
                                                            <div class="thumb fix"> <a href="#"> <img
                                                                        fetchpriority="high" decoding="async"
                                                                        style="height: 300px; object-fit: cover; border-top-left-radius: 10px; border-top-right-radius: 10px;"
                                                                        src="{{ asset('frontend/robots/sumo-bot.jpg') }}"
                                                                        class="attachment-post-thumbnail size-post-thumbnail wp-post-image"
                                                                        alt="" /> </a></div>
                                                            <div class="courses-content">
                                                                <div class="cat"><i class="fal fa-graduation-cap"></i>
                                                                    Zumo Autónomo</div>
                                                                <h3><a
                                                                        href="{{ asset('frontend/robots/minisumo_automatico.pdf') }}">Zumo
                                                                        Autónomo</a>
                                                                </h3>
                                                                <p>Equipos conformados por dos estudiantes de 1ro, 2do y/o
                                                                    3ro de secundaria de la misma unidad educativa y un
                                                                    tutor. El equipo ganador en la etapa departamental
                                                                    participará en la etapa nacional.
                                                                </p>
                                                                {{-- <a href="{{ asset('frontend/robots/minisumo_automatico.pdf') }}"
                                                                    class="btn btn-primary text-light">DESCARGAR</a> --}}
                                                                {{-- <a href="../courses/biochemistry/index.html"
                                                                    class="readmore">Read More <i
                                                                        class="fal fa-long-arrow-right"></i></a> --}}
                                                            </div>

                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-md-6 ">
                                                        <div class="courses-item mb-30 hover-zoomin">
                                                            <div class="thumb fix"> <a href="#"> <img
                                                                        fetchpriority="high" decoding="async"
                                                                        style="height: 300px; object-fit: cover; border-top-left-radius: 10px; border-top-right-radius: 10px;"
                                                                        src="{{ asset('frontend/robots/linea-Robot.png') }}"
                                                                        class="attachment-post-thumbnail size-post-thumbnail wp-post-image"
                                                                        alt="" /> </a></div>
                                                            <div class="courses-content">
                                                                <div class="cat"><i class="fal fa-graduation-cap"></i>
                                                                    Seguidor de Línea</div>
                                                                <h3><a
                                                                        href="{{ asset('frontend/robots/seguidor_linea.pdf') }}">Seguidor
                                                                        de Línea</a>
                                                                </h3>
                                                                <p>Equipos conformados por dos estudiantes de 4to y/o 5to de
                                                                    secundaria de la misma unidad educativa y un tutor. El
                                                                    equipo ganador en la etapa departamental participará en
                                                                    la etapa nacional.
                                                                </p>
                                                                {{-- <a href="{{ asset('frontend/robots/seguidor_linea.pdf') }}"
                                                                    class="btn btn-primary text-light">DESCARGAR</a> --}}
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row text-center">
                                                    <div class="col-12">
                                                        <h2 class="mb-4">Categoría B</h2>
                                                        <p>La categoría “B” tiene 2 subcategorías, la temática de esta categoría es (alimentación y animales endémicos)</p>
                                                    </div>
                                                </div>
                                                <table class="table table-bordered table-hover mb-40">
                                                    <thead class="thead-light">
                                                        <tr>
                                                            <th>N°</th>
                                                            <th>SUBCATEGORIAS</th>
                                                            <th>AÑO DE ESCOLARIDAD QUE PARTICIPA </th>
                                                            <th>PARTICIPACIÓN EN LA ETAPA NACIONAL </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td><strong>1.</strong></td>
                                                            <td><strong>Contructores Robo Mecanics</strong></td>
                                                            <td>3ro y 4to de primaria.</td>
                                                            <td>No participa en la etapa nacional</td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>2.</strong></td>
                                                            <td><strong>Hardware Libre Proyectos</strong></td>
                                                            <td>6to de secundaria.</td>
                                                            <td>Participa en la etapa nacional pero no califica para
                                                                eventos internacionales</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <div class="row align-items-center">
                                                    <div class="col-lg-6 col-md-6 ">
                                                        <div class="courses-item mb-30 hover-zoomin">
                                                            <div class="thumb fix"> <a href="#"> <img
                                                                        fetchpriority="high" decoding="async"
                                                                        style="height: 300px; object-fit: cover; border-top-left-radius: 10px; border-top-right-radius: 10px;"
                                                                        src="{{ asset('frontend/robots/robot-mecanics.jpg') }}"
                                                                        class="attachment-post-thumbnail size-post-thumbnail wp-post-image"
                                                                        alt="" /> </a></div>
                                                            <div class="courses-content">
                                                                <div class="cat"><i class="fal fa-graduation-cap"></i>
                                                                    PRIMARIA</div>
                                                                <h3><a
                                                                        href="{{ asset('frontend/robots/proyectos_primaria.pdf') }}">Constructores
                                                                        Robo Mecanics 3ro y 4to de primaria</a>
                                                                </h3>
                                                                <p>Utilizando materiales reciclados, los participantes deben
                                                                    construir proyectos que involucren mecánica, mecanismos
                                                                    bioinspirados, que no solo reflejen al animal en peligro
                                                                    de extinción, sino que también concientice sobre la
                                                                    importancia de proteger la biodiversidad del país, el
                                                                    robot no debe utilizar microcontroladores.
                                                                </p>
                                                                {{-- <a href="{{ asset('frontend/robots/proyectos_primaria.pdf') }}"
                                                                    class="btn btn-primary text-light">DESCARGAR</a> --}}
                                                            </div>

                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 ">
                                                        <div class="courses-item mb-30 hover-zoomin">
                                                            <div class="thumb fix"> <a href="#"> <img
                                                                        fetchpriority="high" decoding="async"
                                                                        style="height: 300px; object-fit: cover; border-top-left-radius: 10px; border-top-right-radius: 10px;"
                                                                        src="{{ asset('frontend/robots/hadwarelibre.jpg') }}"
                                                                        class="attachment-post-thumbnail size-post-thumbnail wp-post-image"
                                                                        alt="" /> </a></div>
                                                            <div class="courses-content">
                                                                <div class="cat"><i class="fal fa-graduation-cap"></i>
                                                                    SECUNDARIA</div>
                                                                <h3><a
                                                                        href="{{ asset('frontend/robots/proyectos_secundaria.pdf') }}">Hardware
                                                                        Libre Proyectos 6to de secundaria</a>
                                                                </h3>
                                                                <p>Los participantes deben diseñar mecanismos que optimicen
                                                                    la producción y procesamiento de estos alimentos,
                                                                    respetando sus características nutritivas y culturales.
                                                                    promoviendo la innovación en la industria alimentaria,
                                                                    alentando a los jóvenes a explorar soluciones
                                                                    tecnológicas que potencien la economía local y fomenten
                                                                    el uso sostenible de los recursos endémicos.
                                                                </p>
                                                                {{-- <a href="{{ asset('frontend/robots/proyectos_secundaria.pdf') }}"
                                                                    class="btn btn-primary text-light">DESCARGAR</a> --}}
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </section> <!-- courses-area-end -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div><!-- .entry-content -->
    </article><!-- #post-127 -->
@endsection
