<!DOCTYPE html>
<html lang="en">

<head>
    <title>Sylph Art</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="author" content="">
    <meta name="keywords" content="">
    <meta name="description" content="">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">

    <link rel="stylesheet" type="text/css" href="{{ asset('landing') }}/css/normalize.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('landing') }}/icomoon/icomoon.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('landing') }}/css/vendor.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('landing') }}/style.css">

</head>

<body data-bs-spy="scroll" data-bs-target="#header" tabindex="0">

    <div id="header-wrap">
        <header id="header">
            <div class="container-fluid">
                <div class="row">

                    <div class="col-md-2">
                        <div class="main-logo">
                            <a href="index.html"><img src="{{ asset('landing') }}/images/logo-app.png"
                                    alt="logo"></a>
                        </div>

                    </div>

                    <div class="col-md-10">

                        <nav id="navbar">
                            <div class="main-menu stellarnav">
                                <ul class="menu-list">
                                    <li class="menu-item"><a href="#billboard">Home</a></li>
                                    <li class="menu-item"><a href="#our-art" class="nav-link">Our Arts</a></li>
                                    <li class="menu-item"><a href="#AR" class="nav-link">Sylph AR</a></li>
                                    <li class="menu-item"><a href="#download-app" class="nav-link">Download App</a></li>
                                </ul>

                                <div class="hamburger">
                                    <span class="bar"></span>
                                    <span class="bar"></span>
                                    <span class="bar"></span>
                                </div>

                            </div>
                        </nav>

                    </div>

                </div>
            </div>
        </header>

    </div>
    <!--header-wrap-->

    <section id="billboard">

        <div class="container">
            <div class="row">
                <div class="col-md-12">

                    <button class="prev slick-arrow">
                        <i class="icon icon-arrow-left"></i>
                    </button>

                    <div class="main-slider pattern-overlay">
                        <div class="slider-item">
                            <div class="banner-content">
                                <h2 class="banner-title">Memories are Art</h2>
                                <p>Every memory holds a piece of who we are laugh, a glance, a moment frozen in time.
                                    At Sylph AR, we believe memories aren’t just meant to be stored — they’re meant to
                                    be experienced.
                                    Using the magic of augmented reality, we transform your most treasured moments into
                                    living works of art.
                                    Let your memories breathe, move, and shine not just in your heart, but in the world
                                    around you.</p>
                                <div class="btn-wrap">
                                    <a href="https://shopee.co.id/sylph.art?entryPoint=ShopBySearch&searchKeyword=sylph%20art"
                                        target="_blank" class="btn btn-outline-accent btn-accent-arrow">Read More<i
                                            class="icon icon-ns-arrow-right"></i></a>
                                </div>
                            </div>
                            <!--banner-content-->
                            <img src="{{ asset('landing') }}/images/sylph/sylph3.png" alt="banner"
                                class="banner-image">
                        </div>
                        <!--slider-item-->

                        <div class="slider-item">
                            <div class="banner-content">
                                <h2 class="banner-title">Your Moments, Reimagined</h2>
                                <p>Sylph AR lets you relive your most meaningful memories through stunning AR visuals —
                                    turning real emotions into interactive art pieces that stay with you forever.</p>
                                <div class="btn-wrap">
                                    <a href="https://shopee.co.id/sylph.art?entryPoint=ShopBySearch&searchKeyword=sylph%20art"
                                        target="_blank" class="btn btn-outline-accent btn-accent-arrow">Read More<i
                                            class="icon icon-ns-arrow-right"></i></a>
                                </div>
                            </div>
                            <!--banner-content-->
                            <img src="{{ asset('landing') }}/images/sylph/sylph1.png" alt="banner"
                                class="banner-image">
                        </div>
                        <!--slider-item-->

                    </div>
                    <!--slider-->

                    <button class="next slick-arrow">
                        <i class="icon icon-arrow-right"></i>
                    </button>

                </div>
            </div>
        </div>

    </section>
    <section id="best-selling" class="leaf-pattern-overlay">
        <div class="corner-pattern-overlay"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="banner-slider pattern-overlay">
                        <div class="slider-item">
                            <img src="{{ asset('landing') }}/images/sylph/landscape1.jpg" alt="banner"
                                class="banner-image">
                        </div>
                        <div class="slider-item">
                            <img src="{{ asset('landing') }}/images/sylph/landscape2.jpg" alt="banner"
                                class="banner-image">
                        </div>
                        <div class="slider-item">
                            <img src="{{ asset('landing') }}/images/sylph/landscape3.jpg" alt="banner"
                                class="banner-image">
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section id="our-art" class="bookshelf py-5 my-5">
        <div class="container">
            <div class="row">
                <div class="col-md-12">

                    <div class="section-header align-center">
                        <div class="title">
                            <span>Make It Yours</span>
                        </div>
                        <h2 class="section-title">Art Collection</h2>
                    </div>
                    <div class="row">
                        @foreach($bannerData as $item)
                            <div class="col-md-3">
                                <div class="product-item">
                                    <figure class="product-style">
                                        <div class="product-image-wrapper">
                                            <img src="{{ $item['image_url'] }}" alt="Books">
                                        </div>
                                        <a href="{{ $item['link'] }}" target="_blank">
                                            <button type="button" class="add-to-cart"
                                                data-product-tile="add-to-cart">Order</button>
                                        </a>
                                    </figure>
                                    <figcaption>
                                        <h3>{{ $item['nama'] }}</h3>
                                        <div class="item-price">
                                            Rp.{{ number_format($item['harga'], 0, ',', '.') }}
                                        </div>
                                    </figcaption>
                                </div>
                            </div>
                        @endforeach
                    </div>



                </div>
                <!--inner-tabs-->

            </div>
        </div>
    </section>

    <section id="AR" class="bookshelf pb-5 mb-5">

        <div class="section-header align-center">
            <div class="title">
                <span>Step Into the Magic of Sylph AR!</span>
            </div>
            <h2 class="section-title">Sylph AR Experience</h2>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="ar-slider pattern-overlay">
                        <div class="slider-item">
                            <div class="banner-content">
                                <h2 class="banner-title">Sylph AR Memories Come to Life</h2>
                                <p>
                                    Sylph AR is an augmented reality app that brings your memories to life through
                                    scannable photo frames.
                                    Just scan a specially designed frame, and watch your treasured moments play as
                                    living videos right before your eyes.
                                    Every laugh, every glance, every story is no longer just a memory — it's an
                                    experience.
                                    Let your memories live again with the power of Sylph’s AR technology.
                                </p>

                            </div>
                            <!--banner-content-->
                            <img src="{{ asset('landing') }}/images/sylph/ar1.png" alt="banner"
                                class="ar-image">
                        </div>
                        <!--slider-item-->

                        <div class="slider-item">
                            <div class="banner-content">
                                <h2 class="banner-title">Tutorial: How to Use the Sylph AR App</h2>
                                <p>
                                    Learn how to transform your memories using Sylph AR. This tutorial guides you
                                    step-by-step on scanning your frame and linking it with a video — so your moments
                                    come alive through augmented reality.
                                </p>

                            </div>
                            <div class="video-thumbnail-wrapper" style="position: relative; cursor: pointer;"
                                data-bs-toggle="modal" data-bs-target="#sylphArModal">
                                <img src="{{ asset('landing') }}/images/sylph/thumbnail.jpg"
                                    alt="Sylph AR Tutorial" class="ar-image"
                                    style="width: 385px;height: 572px; border-radius: 50px;">
                                <img src="{{ asset('landing') }}/images/sylph/play.png" alt="Play"
                                    style="position: absolute; top: 50%; left: 50%; width: 60px; transform: translate(-50%, -50%); opacity: 0.85;">
                            </div>
                        </div>

                        <!--slider-item-->

                    </div>
                    <!--slider-->

                </div>
            </div>
            <!-- Modal -->
            <div class="modal fade" id="sylphArModal" tabindex="-1" aria-labelledby="sylphArModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content" style="background-color: #000;">
                        <div class="modal-header border-0">
                            <h5 class="modal-title text-white" id="sylphArModalLabel">How to Use Sylph AR App</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Close" onclick="stopVideo()"></button>
                        </div>
                        <div class="modal-body p-0">
                            <div class="ratio ratio-16x9">
                                <video id="sylphArVideo" controls autoplay style="width: 100%; height: 100%;">
                                    <source src="{{ asset('landing') }}/videos/tutorial.mp4"
                                        type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
    <section id="download-app" class="leaf-pattern-overlay">
        <div class="corner-pattern-overlay"></div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="row">

                        <div class="col-md-5">
                            <figure>
                                <img src="{{ asset('landing') }}/images/device.png" alt="phone"
                                    class="single-image">
                            </figure>
                        </div>

                        <div class="col-md-7">
                            <div class="app-info">
                                <h2 class="section-title divider">Download our app now !</h2>
                                <p>With Sylph AR, your memories become more than just photos or videos — they become
                                    immersive experiences.
                                    Download now and see how every smile, every hug, and every place you’ve been can
                                    come alive right before your eyes.</p>
                                <div class="google-app">
                                    <a href="https://play.google.com/store/apps/details?id=com.sylph.ar"
                                        target="_blank">
                                        <img src="{{ asset('landing') }}/images/google-play.jpg"
                                            alt="google play">
                                    </a>
                                    <a href="https://apps.apple.com/us/app/sylph-ar/id1515151515" target="_blank">
                                        <img src="{{ asset('landing') }}/images/app-store.jpg"
                                            alt="app store">
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer id="footer">
        <div class="container">
            <div class="row">

                <div class="col-md-6">

                    <div class="footer-item">
                        <div class="company-brand">
                            <img src="{{ asset('landing') }}/images/logo-app.png" alt="logo"
                                class="footer-logo">
                            <p>Sylph Art brings your memories to life with AR-powered gifts! From custom frame art to
                                cute keychains and creative keepsakes — everything comes alive with a touch of magic.
                            </p>
                        </div>
                    </div>

                </div>

                <div class="col-md-2">

                    <div class="footer-menu">
                        <h5>About Us</h5>
                        <ul class="menu-list">
                            <li class="menu-item">
                                <a href="https://www.instagram.com/sylph.art.id/">Instagram</a>
                            </li>
                            <li class="menu-item">
                                <a href="https://www.tiktok.com/@sylph.art">TikTok</a>
                            </li>
                            <li class="menu-item">
                                <a href="mailto:sylphart14@gmail.com">Email</a>
                            </li>
                            <li class="menu-item">
                                <a href="{{ route('landing_page.privacy') }}">Privacy Policy</a>
                            </li>
                        </ul>
                    </div>

                </div>
                <div class="col-md-2">

                    <div class="footer-menu">
                        <h5>Discover</h5>
                        <ul class="menu-list">
                            <li class="menu-item">
                                <a href="#billboard">Home</a>
                            </li>
                            <li class="menu-item">
                                <a href="#our-arts">Our Arts</a>
                            </li>
                            <li class="menu-item">
                                <a href="#AR">Sylph AR</a>
                            </li>
                            <li class="menu-item">
                                <a href="#download-app">Download App</a>
                            </li>
                        </ul>
                    </div>

                </div>
                <div class="col-md-2">

                    <div class="footer-menu">
                        <h5>Help</h5>
                        <ul class="menu-list">
                            <li class="menu-item">
                                <a href="wa.me/6287826492190">Help center</a>
                            </li>
                            <li class="menu-item">
                                <a href="wa.me/6287826492190">Report a problem</a>
                            </li>
                            <li class="menu-item">
                                <a href="wa.me/6287826492190">Suggesting edits</a>
                            </li>
                            <li class="menu-item">
                                <a href="wa.me/6287826492190">Contact us</a>
                            </li>
                        </ul>
                    </div>

                </div>

            </div>
            <!-- / row -->

        </div>
    </footer>

    <div id="footer-bottom">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <p>&copy; 2024 Sylph Art. All rights reserved.</p>
                </div>
                <!--footer-bottom-content-->
            </div>
        </div>
    </div>

    <script src="{{ asset('landing') }}/js/jquery-1.11.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous">
    </script>
    <script src="{{ asset('landing') }}/js/plugins.js"></script>
    <script src="{{ asset('landing') }}/js/script.js"></script>
    <script>
        function stopVideo() {
            const video = document.getElementById("sylphArVideo");
            if (video) {
                video.pause();
                video.currentTime = 0;
            }
        }

        const videoModal = document.getElementById('sylphArModal');
        if (videoModal) {
            videoModal.addEventListener('hidden.bs.modal', stopVideo);
        }

    </script>

</body>

</html>
