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
    <link rel="shortcut icon" type="image/png" href="{{ asset ('landing') }}/images/sylph/logo.svg" />


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
                                    <li class="menu-item"><a href="#our-art" class="nav-link">Cute gifts</a></li>
                                    <li class="menu-item"><a href="#how-to-order" class="nav-link">How to order</a></li>
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

                    <div class="main-slider pattern-overlay" data-aos="fade-up">
                        <div class="slider-item">
                            <div class="banner-content">
                                <h2 class="banner-title">Love Gifts that Come to Life</h2>
                                <p>Sylph AR brings your memories to life.
                                    From animated frames, QR-coded love notes, to magical keychains
                                    our AR-powered gifts are made to move hearts and make moments last forever.</p>
                                <div class="btn-wrap d-flex gap-2">
                                    <a href="#our-art"
                                       id="button-product" class="btn btn-outline-accent btn-accent-arrow">Product</a>
                                    <a href="{{ route('envelope.sender') }}"
                                        target="_blank" class="btn btn-outline-accent btn-accent-arrow">Try QR envelope</a>
                                </div>
                            </div>
                            <!--banner-content-->
                            <img src="{{ asset('landing') }}/images/sylph/sylph3.png" alt="banner"
                                class="banner-image">
                        </div>
                        <!--slider-item-->

                    </div>
                    <!--slider-->

                </div>
            </div>
        </div>

    </section>
    <section id="best-selling" class="leaf-pattern-overlay">
        <div class="corner-pattern-overlay"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="banner-slider pattern-overlay" data-aos="fade-up">
                        <div class="slider-item">
                            <img src="{{ asset('landing') }}/images/sylph/landscape1.png" alt="banner"
                                class="banner-image">
                        </div>
                        <div class="slider-item">
                            <img src="{{ asset('landing') }}/images/sylph/landscape2.png" alt="banner"
                                class="banner-image">
                        </div>
                        <div class="slider-item">
                            <img src="{{ asset('landing') }}/images/sylph/landscape3.png" alt="banner"
                                class="banner-image">
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <section id="quotation" class="align-center pb-5 mb-5">
		<div class="inner-content" data-aos="fade-up">
			<h2 class="section-title divider">Send a Love Letter in a Digital Envelope</h2>
                <br>
                <div class="container justify-content-center">

                    <p>Just write the sender’s name, the receiver’s name, and your heartfelt message
                        and we’ll turn it into a unique QR code you can attach to your gift.
                        When scanned, it reveals your special message, making your gift unforgettable.</p>
                </div>
				<div class="author-name">
                    <a href="{{ route('envelope.sender') }}"
                    target="_blank" class="btn btn-outline-accent btn-accent-arrow">Try QR Love Envelope</a>
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
                        <h2 class="section-title">Cute & Custom Gifts</h2>
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
    <section id="how-to-order" class="leaf-pattern-overlay" style="background:none!important;">
        <div class="corner-pattern-overlay"></div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="row">

                        <div class="col-md-5">
                            <figure>
                                <img src="{{ asset('landing') }}/images/shopee_image.png" alt="phone"
                                    class="single-image">
                            </figure>
                        </div>

                        <div class="col-md-7">
                            <div class="app-info">
                                <h2 class="section-title divider">How to Order</h2>
                                <ul style="list-style-type: none;">
                                    <strong>1.Choose a product</strong>
                                    <li>Click "ORDER" or visit our <a href="https://s.shopee.co.id/zxQvHmoZ?share_channel_code=1" target="_blank">Shopee Store</a></li>
                                    <strong>2.Send Your Photo to Sylph</strong>
                                    <li>After ordering, send the photo you want in the frame or gift</li>
                                    <strong>3.Add Your AR Video</strong>
                                    <li>Send your video, or let us create one for you!</li>
                                    <strong>4.Access the AR App</strong>
                                    <li>We’ll create an account for you to log in and view your gift in AR.</li>
                                </ul>
                            </div>
                        </div>

                    </div>
                </div>
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
                                    <a href="https://drive.google.com/file/d/1ClzyOR_9jfBxK7odb2qNdc7De3FRyj8v/view?usp=drive_link"
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
