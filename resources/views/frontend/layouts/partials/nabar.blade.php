<header class="header-area header-three">
    <!-- header -->
    <div class="header-top second-header d-none d-md-block">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-4 col-md-4 d-none d-lg-block">

                    <div class="header-social">

                        {{-- <span>
                            Síganos:-
                            <a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" title="LinkedIn"><i class="fab fa-instagram"></i></a>
                            <a href="#" title="Twitter"><i class="fab fa-twitter"></i></a>
                            <a href="#" title="Twitter"><i class="fab fa-youtube"></i></a>
                        </span> --}}

                    </div>
                </div>
                <div class="col-lg-8 col-md-8 d-none d-lg-block text-right">
                    <div class="header-cta">
                        <ul>
                            {{-- <li>
                                <div class="call-box">
                                    <div class="icon">
                                        <img src="{{ asset('frontend/wp-content/themes/qeducato/inc/assets/images/phone-call.png') }}"
                                            alt="https://wpdemo.zcubethemes.com/qeducato/wp-content/themes/qeducato/inc/assets/images/phone-call.png" />
                                    </div>
                                    <div class="text">
                                        <span>Call Now !</span>
                                        <strong><a href="tel:+917052101786">+91 7052 101 786</a></strong>
                                    </div>
                                </div>
                            </li> --}}
                            <li>
                                <div class="call-box">
                                    <div class="icon">
                                        <img src="{{ asset('/assets/image/mailing1.png') }}"
                                            alt="mailing" />
                                    </div>
                                    <div class="text">
                                        <span>Correo</span>
                                        <strong><a href="mailto:info@example.com">
                                                profe@iipp.edu.bo
                                            </a></strong>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="header-sticky" class="menu-area">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-xl-2 col-lg-2">
                    <div class="logo">
                        <!-- LOGO IMAGE -->
                        <!-- For Retina Ready displays take a image with double the amount of pixels that your image will be displayed (e.g 268 x 60 pixels) -->
                        <a href="{{ route('home') }}" class="navbar-brand logo-black">
                            <!-- Logo Standard -->
                            <img src="{{ asset('assets/image/logominedu.jpg') }}" alt="PROFE" title=""
                                width="120%" />
                        </a>
                    </div>
                </div>
                <div class="col-xl-8 col-lg-8">
                    <div class="main-menu text-right text-xl-right">
                        <nav id="mobile-menu">
                            <div id="cssmenu" class="menu-main-menu-container">
                                <ul id="menu-main-menu" class="menu">
                                    <li id="menu-item-113"
                                        class="menu-item menu-item-type-post_type menu-item-object-page menu-item-113">
                                        <a href="{{ route('home') }}" aria-current="page">Inicio</a>
                                    </li>
                                    <li id="menu-item-116"
                                        class="menu-item menu-item-type-post_type menu-item-object-page menu-item-116">
                                        <a href="{{ route('programa') }}">Ofertas Académicas</a>
                                    </li>
                                    <li id="menu-item-117"
                                        class="menu-item menu-item-type-post_type menu-item-object-page menu-item-117">
                                        <a href="{{ route('evento') }}">Eventos</a>
                                    </li>
                                    <li id="menu-item-118"
                                        class="menu-item menu-item-type-post_type menu-item-object-page menu-item-118">
                                        <a href="{{ route('blog') }}">Novedades</a>
                                    </li>
                                    <li id="menu-item-118"
                                        class="menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children menu-item-118">
                                        <a href="#">+ Nosotros</a>
                                        <ul class="sub-menu">
                                           
                                            <li id="menu-item-123"
                                                class="menu-item menu-item-type-post_type menu-item-object-page menu-item-123">
                                                <a href="{{ route('galeria') }}">Galerías</a>
                                            </li>
                                            <li id="menu-item-124"
                                                class="menu-item menu-item-type-post_type menu-item-object-page menu-item-124">
                                                <a href="{{ route('sede') }}">Nuestras Sedes</a>
                                            </li>
                                            <li id="menu-item-125"
                                                class="menu-item menu-item-type-post_type menu-item-object-page menu-item-125">
                                                <a href="{{ route('videos') }}">Videos</a>
                                            </li>
                                            <li id="menu-item-126"
                                                class="menu-item menu-item-type-post_type menu-item-object-page menu-item-126">
                                                <a href="{{ route('quienesSomos') }}">Quiénes Somos</a>
                                            </li>
                                        </ul>
                                    </li>

                                    {{-- <li id="menu-item-118"
                                        class="menu-item menu-item-type-post_type menu-item-object-page menu-item-118">
                                        <a href="{{ route('evento') }}">Eventos</a>
                                    </li> --}}
                                </ul>
                            </div>
                        </nav>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-2  text-right d-none d-lg-block text-right text-xl-right">
                    <div class="login">

                        <ul>

                            <li>
                                <a href="{{ route('home') }}" class="navbar-brand logo-black">
                                    <!-- Logo Standard -->
                                    <img src="{{ asset('assets/profe/logoprofe1.png') }}" alt="PROFE" title=""
                                        width="65%" />
                                </a>
                                {{-- <div class="second-header-btn">
                                    <a href="{{ route('admin.login') }}" class="btn">ACCEDER</a>
                                </div> --}}
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-12">
                    <div class="mobile-menu"></div>
                </div>
            </div>
        </div>
    </div>
</header>
