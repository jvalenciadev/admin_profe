<section
    class="elementor-section elementor-top-section elementor-element elementor-element-531532d elementor-section-full_width elementor-section-height-default elementor-section-height-default"
    data-id="531532d" data-element_type="section">
    <div class="elementor-container elementor-column-gap-no">
        <div class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-83ab1d1"
            data-id="83ab1d1" data-element_type="column">
            <div class="elementor-widget-wrap elementor-element-populated">
                <div class="elementor-element elementor-element-e68a716 elementor-widget elementor-widget-Elementor-Widget-header-slider-new"
                    data-id="e68a716" data-element_type="widget"
                    data-widget_type="Elementor-Widget-header-slider-new.default">
                    <div class="elementor-widget-container"> <!-- slider-area -->
                        <section id="home" class="slider-area fix p-relative">
                            <div class="slider-active" style="background: #ffffff;">
                                <div class="single-slider slider-bg"
                                    style="background-image: url(storage/profe/{{ $profe->profe_banner }});">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-lg-7 col-md-7">
                                                <div class="slider-content s-slider-content mt-130">
                                                    <h5 data-animation="fadeInUp" data-delay=".4s">
                                                        Bienvenido</h5>
                                                    <h2 data-animation="fadeInUp" data-delay=".4s">
                                                        {{ $profe->profe_nombre }}</h2>
                                                    <p data-animation="fadeInUp" data-delay=".6s">
                                                    <div >
                                                        {!! $profe->profe_descripcion !!}
                                                    </div>
                                                    </p>
                                                    {{-- <div class="slider-btn mt-30"> <a
                                                                            href="about-us/index.html"
                                                                            class="btn ss-btn mr-15"
                                                                            data-animation="fadeInLeft"
                                                                            data-delay=".4s">Discover More <i
                                                                                class="fal fa-long-arrow-right"></i></a>
                                                                        <a href="contact/index.html"
                                                                            class="btn ss-btn active"
                                                                            data-animation="fadeInLeft"
                                                                            data-delay=".4s">Contact Us <i
                                                                                class="fal fa-long-arrow-right"></i></a>
                                                                    </div> --}}
                                                </div>
                                            </div>
                                            <div class="col-lg-5 col-md-5 p-relative"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="single-slider slider-bg"
                                    style="background-image: url(frontend/images/banner-minedu4.jpg); background-size: cover;">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-lg-7 col-md-7">
                                                <div class="slider-content s-slider-content mt-130">
                                                    <h5 data-animation="fadeInUp" data-delay=".4s">
                                                        Bienvenido</h5>
                                                    <h2 data-animation="fadeInUp" data-delay=".4s">
                                                        {{ $profe->profe_nombre }}
                                                    </h2>
                                                    <p data-animation="fadeInUp" data-delay=".6s">
                                                        <div >
                                                            {!! $profe->profe_mision !!}
                                                        </div>
                                                    </p>
                                                    {{-- <div class="slider-btn mt-30"> <a
                                                                            href="about-us/index.html"
                                                                            class="btn ss-btn mr-15"
                                                                            data-animation="fadeInLeft"
                                                                            data-delay=".4s">Discover More <i
                                                                                class="fal fa-long-arrow-right"></i></a>
                                                                        <a href="contact/index.html"
                                                                            class="btn ss-btn active"
                                                                            data-animation="fadeInLeft"
                                                                            data-delay=".4s">Contact Us <i
                                                                                class="fal fa-long-arrow-right"></i></a>
                                                                    </div> --}}
                                                </div>
                                            </div>
                                            <div class="col-lg-5 col-md-5 p-relative"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section> <!-- slider-area-end -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
