<style>
    /* Slider container styles */
    .slider-container {
        position: relative;
        overflow: hidden;
    }

    /* Background image styling */
    .slider-item {
        height: 65vh;
        background-size: cover;
        background-position: center;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        color: #fff;
        text-shadow: 0 2px 5px rgba(0, 0, 0, 0.7);
    }

    /* Slider text content */
    .slider-content {
        max-width: 700px;
        padding: 50px;
        background: rgba(0, 0, 0, 0.5);
        border-radius: 20px;
    }

    .slider-content h5 {
        font-size: 1.5rem;
        font-weight: 400;
        margin-bottom: 10px;
    }

    .slider-content h2 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 20px;
    }

    .slider-content p {
        font-size: 1rem;
        line-height: 1.5;
        margin-bottom: 20px;
    }

    .slider-btn a {
        margin-right: 15px;
        padding: 12px 20px;
        font-size: 1rem;
        border-radius: 5px;
        text-decoration: none;
    }

    .btn-primary {
        background-color: #007bff;
        border: none;
    }

    .btn-outline-light {
        border: 2px solid #fff;
        color: #fff;
    }
</style>
<section
class="elementor-section elementor-top-section elementor-element elementor-element-531532d elementor-section-full_width elementor-section-height-default elementor-section-height-default"
data-id="531532d" data-element_type="section">
<div class="elementor-container elementor-column-gap-no">
    <div class="elementor-column elementor-col-50 elementor-top-column elementor-element elementor-element-83ab1d1"
        data-id="83ab1d1" data-element_type="column">
        <div class="elementor-widget-wrap elementor-element-populated">
            <div class="elementor-element elementor-element-e68a716 elementor-widget elementor-widget-Elementor-Widget-header-slider-new"
                data-id="e68a716" data-element_type="widget"
                data-widget_type="Elementor-Widget-header-slider-new.default">
                <div class="elementor-widget-container"> <!-- slider-area -->
                    <section id="home" class="slider-area fix p-relative">
                        <div class="slider-active" style="background: #ffffff;">

                            {{-- <div class="single-slider slider-item"
                                style="background-image: url(frontend/images/banner_oferta_formativa.jpg);">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-lg-7 col-md-7">
                                            <div class="slider-content s-slider-content mt-130">

                                            </div>
                                        </div>
                                        <div class="col-lg-5 col-md-5 p-relative"></div>
                                    </div>
                                </div>
                            </div> --}}
                            <div class="single-slider slider-bg"
                                style="background-image: url(frontend/images/bannerprofe.jpg);">
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
                                                <div class="slider-btn mt-30"> <a
                                                                        href="{{ route('programa') }}"
                                                                        class="btn ss-btn mr-15"
                                                                        data-animation="fadeInLeft"
                                                                        data-delay=".4s">Ofertas Académicas<i
                                                                            class="fal fa-long-arrow-right"></i></a>
                                                                            <a href="{{ asset('storage/profe/' . $profe->profe_convocatoria) }}" 
                                                                                class="btn ss-btn active" 
                                                                                data-animation="fadeInLeft" target="_blank" 
                                                                                data-delay=".4s">Convocatoria
                                                                                <i class="fal fa-long-arrow-right" ></i>
                                                                             </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-5 col-md-5 p-relative"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="single-slider slider-bg"
                                style="background-image: url(frontend/images/bannerprofe.jpg); background-size: cover;">
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
                                                <div class="slider-btn mt-30"> <a
                                                                        href="{{ route('programa') }}"
                                                                        class="btn ss-btn mr-15"
                                                                        data-animation="fadeInLeft" target="_blank" 
                                                                        data-delay=".4s">Ofertas Académicas<i
                                                                            class="fal fa-long-arrow-right"></i></a>
                                                                            <a href="{{ asset('storage/profe/' . $profe->profe_convocatoria) }}" 
                                                                                class="btn ss-btn active" 
                                                                                data-animation="fadeInLeft" target="_blank" 
                                                                                data-delay=".4s">Convocatoria
                                                                                <i class="fal fa-long-arrow-right" ></i>
                                                                             </a>
                                                </div>
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
