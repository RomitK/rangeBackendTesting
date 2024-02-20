@extends('frontend.layout.master')

@if ($pagemeta)
    @section('title', $pagemeta->meta_title)
    @section('pageDescription', $pagemeta->meta_description)
    @section('pageKeyword', $pagemeta->meta_keywords)
@else
    @section('title', 'Home | ' . $name)
    @section('pageDescription', $website_description)
    @section('pageKeyword', $website_keyword)
@endif
@section('content')
    {{-- main banner --}}
    <section class="homeMainBg justify-content-start" id="home">

        <div class="container-fluid px-0 py-3">
            <div class="d-flex">
                <video class="d-block  videoMainHome" autoplay="" loop="" muted="" playsinline=""
                    preload="metadata" poster="{{ asset('frontend/assets/images/banner/homeBg.webp') }}">
                    <source src="https://range.ae/frontend/assets/images/prime/BannerVideo.mp4" type="video/mp4">
                    <source src="https://range.ae/frontend/assets/images/prime/BannerVideo.mp4" type="video/mov">
                    Sorry, your browser doesn't support videos.
                </video>
                <video class="d-block videoMainHome videoMainHome2" autoplay="" loop="" muted="" playsinline=""
                    preload="metadata" poster="{{ asset('frontend/assets/images/banner/homeBg.webp') }}">
                    <source src="https://range.ae/frontend/assets/images/prime/BannerVideo.mp4" type="video/mp4">
                    <source src="https://range.ae/frontend/assets/images/prime/BannerVideo.mp4" type="video/mov">
                    Sorry, your browser doesn't support videos.
                </video>
                <video class="d-block videoMainHome videoMainHome3" autoplay="" loop="" muted="" playsinline=""
                    preload="metadata" poster="{{ asset('frontend/assets/images/banner/homeBg.webp') }}">
                    <source src="https://range.ae/frontend/assets/images/prime/BannerVideo.mp4" type="video/mp4">
                    <source src="https://range.ae/frontend/assets/images/prime/BannerVideo.mp4" type="video/mov">
                    Sorry, your browser doesn't support videos.
                </video>
                <div class="videoOverlay"></div>
            </div>
            <div class="row g-0">
                <div class="col-12 col-lg-12 col-md-12">
                    <div class="row g-0">
                        <div class="col-12 col-lg-6 col-md-6 my-auto">
                            <div class="scroll-btn float-end cursor-pointer text-center">
                                <div class="vertLine"></div>
                                <div id=mouse_body>
                                    <div id=mouse_wheel></div>
                                </div>
                                <small class="fs-12 text-white text-uppercase">Scroll Down</small>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6 col-md-6 my-auto">
                            <div class="float-end">
                                <form action="" method="post">
                                    <div class="input-group">
                                        <div class="form-outline">
                                            <input type="search" id="form1"
                                                class="form-control form-control-lg py-3 rounded-0 border-0"
                                                placeholder="Type for search..." />

                                        </div>
                                        <button type="button" class="btn bg-white  rounded-0 text-primary">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- looking for  --}}
    <section class="my-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-10 col-md-11">
                    <div class="row g-3">
                        <div class="col-12 col-lg-12 col-md-12">
                            <div>
                                <div class="mainHead mb-5 text-primary text-center">
                                    <h4>I AM INTERESTED TO</h4>
                                </div>
                                <center><img src="{{ asset('frontend/assets/images/buy2.png') }}"
                                            class="img-fluid" alt="range" width="1500px"></center>
                            </div>
                        </div>
                        <!--<div class="col-6 col-lg-3 col-md-3">-->
                        <!--    <div class="card cardInterest">-->
                        <!--        <div class="my-3">-->
                        <!--            <center><img src="{{ asset('frontend/assets/images/icons/interest1.png') }}"-->
                        <!--                    class="img-fluid" alt="range" width="80px"></center>-->
                        <!--        </div>-->
                        <!--        <div class="card-body text-center">-->
                        <!--            <small class="card-title">Buy/Rent</small>-->
                        <!--        </div>-->
                        <!--    </div>-->
                        <!--</div>-->

                        <!--<div class="col-6 col-lg-3 col-md-3">-->
                        <!--    <div class="card cardInterest">-->
                        <!--        <div class="my-3">-->
                        <!--            <center><img src="{{ asset('frontend/assets/images/icons/interest2.png') }}"-->
                        <!--                    class="img-fluid" alt="range" width="80px"></center>-->
                        <!--        </div>-->
                        <!--        <div class="card-body text-center">-->
                        <!--            <small class="card-title">Sell with us</small>-->
                        <!--        </div>-->
                        <!--    </div>-->
                        <!--</div>-->

                        <!--<div class="col-6 col-lg-3 col-md-3">-->
                        <!--    <div class="card cardInterest">-->
                        <!--        <div class="my-3">-->
                        <!--            <center><img src="{{ asset('frontend/assets/images/icons/interest3.png') }}"-->
                        <!--                    class="img-fluid" alt="range" width="80px"></center>-->
                        <!--        </div>-->
                        <!--        <div class="card-body text-center">-->
                        <!--            <small class="card-title">Property Management / Holiday Homes</small>-->
                        <!--        </div>-->
                        <!--    </div>-->
                        <!--</div>-->

                        <!--<div class="col-6 col-lg-3 col-md-3">-->
                        <!--    <div class="card cardInterest">-->
                        <!--        <div class="my-3">-->
                        <!--            <center><img src="{{ asset('frontend/assets/images/icons/interest4.png') }}"-->
                        <!--                    class="img-fluid" alt="range" width="80px"></center>-->
                        <!--        </div>-->
                        <!--        <div class="card-body text-center">-->
                        <!--            <small class="card-title">Mortgage</small>-->
                        <!--        </div>-->
                        <!--    </div>-->
                        <!--</div>-->
                    </div>
                </div>
                <div class="col-12 col-lg-12 col-md-12">
                    <div class="borderBottom"></div>
                </div>
            </div>
        </div>
    </section>

    {{-- why range  --}}
    <section class="my-5">
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-12 col-md-12">
                    <div class="row">
                        <div class="col-12 col-lg-5 col-md-5 my-auto">
                            <div class="">

                                <div class="mainHead pb-3 text-primary">
                                    <h4 class="mb-0">WHY RANGE?</h4>
                                </div>
                                <div class="pb-4">
                                    <p class="text-secondary mb-0">Range provide an upgraded, automated, and futuristic
                                        approach to the Dubai property market. Our state-of-the-art marketing ensures a
                                        competitive edge with modern and advanced strategies. Experience bespoke services
                                        tailored to deliver accurate results and ultimate customer satisfaction.
                                    </p>
                                </div>
                                <div class="">
                                    <button class="btn btn-blue text-uppercase btn-lg">Book A Call</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-7 col-md-7">
                            <div class="row">
                                <div class="col-12 col-lg-6 col-md-6 my-auto">
                                    <div class="d-flex justify-content-start py-3 py-lg-5 py-md-3">
                                        <div class="my-auto me-3">
                                            <center><img src="{{ asset('frontend/assets/images/icons/why1.png') }}"
                                                    class="img-fluid" alt="range" width="60" /></center>
                                        </div>
                                        <div class="my-auto">
                                            <div class="mainHead text-primary text-uppercase">
                                                <h4 class="fw-800 mb-0">AED 20B+</h4>
                                                <p class="text-dark mb-0 fs-20">PROPERTY SALES</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6 col-md-6 my-auto">
                                    <div class="d-flex justify-content-start  py-3 py-lg-5 py-md-3">
                                        <div class="my-auto me-3">
                                            <center><img src="{{ asset('frontend/assets/images/icons/why2.png') }}"
                                                    class="img-fluid" alt="range" width="60" /></center>
                                        </div>
                                        <div class="my-auto">
                                            <div class="mainHead text-primary text-uppercase">
                                                <h4 class="fw-800 mb-0">5-STAR</h4>
                                                <p class="text-dark mb-0 fs-20">CUSTOMER REVIEWS</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6 col-md-6 my-auto">
                                    <div class="d-flex justify-content-start  py-3 py-lg-5 py-md-3">
                                        <div class="my-auto me-3">
                                            <center><img src="{{ asset('frontend/assets/images/icons/why3.png') }}"
                                                    class="img-fluid" alt="range" width="60" /></center>
                                        </div>
                                        <div class="my-auto">
                                            <div class="mainHead text-primary text-uppercase">
                                                <h4 class="fw-800 mb-0">MULTI</h4>
                                                <p class="text-dark mb-0 fs-20">LANGUAGE AGENTS</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6 col-md-6 my-auto">
                                    <div class="d-flex justify-content-start  py-3 py-lg-5 py-md-3">
                                        <div class="my-auto me-3">
                                            <center><img src="{{ asset('frontend/assets/images/icons/why4.png') }}"
                                                    class="img-fluid" alt="range" width="60" /></center>
                                        </div>
                                        <div class="my-auto">
                                            <div class="mainHead text-primary text-uppercase">
                                                <h4 class="fw-800 mb-0">5,000</h4>
                                                <p class="text-dark mb-0 fs-20">PROPERTY SOLD</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- About dubai  --}}
    <section class="bg-light my-5 py-5 p-relative">
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-12 col-md-12">
                    <div class="row">
                        <div class="col-12 col-lg-6 col-md-6 my-auto">
                            <div class="">

                                <div class="mainHead pb-3 text-primary">
                                    <h4 class="mb-0">ABOUT DUBAI</h4>
                                </div>
                                <div class="pb-4">
                                    <p class="text-secondary mb-0">Lorem ipsum dolor sit amet, consectetur adipiscing elit,
                                        sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim
                                        veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                                        consequat.
                                    </p>
                                </div>
                                <div class="pb-4">
                                    <div class="accordion benefitAccord" id="accordionExample">
                                        <div class="accordion-item mb-3">
                                            <h2 class="accordion-header" id="headingOne">
                                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                                    data-bs-target="#collapseOne" aria-expanded="true"
                                                    aria-controls="collapseOne">
                                                    BENEFITS
                                                </button>
                                            </h2>
                                            <div id="collapseOne" class="accordion-collapse collapse show"
                                                aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <table class="table table-borderless table-md mb-0">
                                                        </tbody>
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex">
                                                                    <div class="my-auto me-2">
                                                                        <img src="{{ asset('frontend/assets/images/icons/benefit1.png') }}"
                                                                            alt="range" class="img-fluid"
                                                                            width="25px">
                                                                    </div>
                                                                    <div class="my-auto">
                                                                        <small class="text-dark">100% Property
                                                                            ownership</small>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="d-flex">
                                                                    <div class="my-auto me-2">
                                                                        <img src="{{ asset('frontend/assets/images/icons/benefit2.png') }}"
                                                                            alt="range" class="img-fluid"
                                                                            width="25px">
                                                                    </div>
                                                                    <div class="my-auto">
                                                                        <small class="text-dark">Attractive Loan
                                                                            Options</small>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex">
                                                                    <div class="my-auto me-2">
                                                                        <img src="{{ asset('frontend/assets/images/icons/benefit3.png') }}"
                                                                            alt="range" class="img-fluid"
                                                                            width="25px">
                                                                    </div>
                                                                    <div class="my-auto">
                                                                        <small class="text-dark">10-year Golden
                                                                            Visa</small>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="d-flex">
                                                                    <div class="my-auto me-2">
                                                                        <img src="{{ asset('frontend/assets/images/icons/benefit3.png') }}"
                                                                            alt="range" class="img-fluid"
                                                                            width="25px">
                                                                    </div>
                                                                    <div class="my-auto">
                                                                        <small class="text-dark">Permanent
                                                                            Residency</small>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex">
                                                                    <div class="my-auto me-2">
                                                                        <img src="{{ asset('frontend/assets/images/icons/benefit5.png') }}"
                                                                            alt="range" class="img-fluid"
                                                                            width="25px">
                                                                    </div>
                                                                    <div class="my-auto">
                                                                        <small class="text-dark">8-10% Return on
                                                                            Investment</small>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="d-flex">
                                                                    <div class="my-auto me-2">
                                                                        <img src="{{ asset('frontend/assets/images/icons/benefit6.png') }}"
                                                                            alt="range" class="img-fluid"
                                                                            width="25px">
                                                                    </div>
                                                                    <div class="my-auto">
                                                                        <small class="text-dark">Safest City in the
                                                                            World</small>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingTwo">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapseTwo"
                                                    aria-expanded="false" aria-controls="collapseTwo">
                                                    TRANSACTIONS
                                                </button>
                                            </h2>
                                            <div id="collapseTwo" class="accordion-collapse collapse"
                                                aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <table class="table table-borderless  table-md mb-0">
                                                        </tbody>
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex">
                                                                    <div class="my-auto me-2">
                                                                        <img src="{{ asset('frontend/assets/images/icons/benefit1.png') }}"
                                                                            alt="range" class="img-fluid"
                                                                            width="25px">
                                                                    </div>
                                                                    <div class="my-auto">
                                                                        <small class="text-dark">100% Property
                                                                            ownership</small>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="d-flex">
                                                                    <div class="my-auto me-2">
                                                                        <img src="{{ asset('frontend/assets/images/icons/benefit2.png') }}"
                                                                            alt="range" class="img-fluid"
                                                                            width="25px">
                                                                    </div>
                                                                    <div class="my-auto">
                                                                        <small class="text-dark">Attractive Loan
                                                                            Options</small>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex">
                                                                    <div class="my-auto me-2">
                                                                        <img src="{{ asset('frontend/assets/images/icons/benefit3.png') }}"
                                                                            alt="range" class="img-fluid"
                                                                            width="25px">
                                                                    </div>
                                                                    <div class="my-auto">
                                                                        <small class="text-dark">10-year Golden
                                                                            Visa</small>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="d-flex">
                                                                    <div class="my-auto me-2">
                                                                        <img src="{{ asset('frontend/assets/images/icons/benefit3.png') }}"
                                                                            alt="range" class="img-fluid"
                                                                            width="25px">
                                                                    </div>
                                                                    <div class="my-auto">
                                                                        <small class="text-dark">Permanent
                                                                            Residency</small>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex">
                                                                    <div class="my-auto me-2">
                                                                        <img src="{{ asset('frontend/assets/images/icons/benefit5.png') }}"
                                                                            alt="range" class="img-fluid"
                                                                            width="25px">
                                                                    </div>
                                                                    <div class="my-auto">
                                                                        <small class="text-dark">8-10% Return on
                                                                            Investment</small>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="d-flex">
                                                                    <div class="my-auto me-2">
                                                                        <img src="{{ asset('frontend/assets/images/icons/benefit6.png') }}"
                                                                            alt="range" class="img-fluid"
                                                                            width="25px">
                                                                    </div>
                                                                    <div class="my-auto">
                                                                        <small class="text-dark">Safest City in the
                                                                            World</small>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="">
                                    <button class="btn btn-blue text-uppercase btn-lg">DOWNLOAD BROCHURE</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6 col-md-6">
                            <div class="bgAboutDubai"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Projects  --}}
    <section class="my-5">
        <div class="container-fluid px-0">
            <div class="row g-0">
                <div class="col-12 col-lg-12 col-md-12">
                    <div class="row g-0">
                        <div class="col-12 col-lg-12 col-md-12">
                            <div>
                                <div class="mainHead mb-5 text-center text-primary">
                                    <h4>LATEST PROJECTS</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-12 col-md-12">
                            <div class="row g-0 justify-content-center mb-4">
                                <div class="col-10 col-lg-2 col-md-3  mx-3 my-auto">
                                    <div class="bg-white shadow px-3 py-2">
                                        <p class="text-primary mb-1 fw-semibold">NEW PROJECTS</p>
                                        <div>
                                            <select name="" id=""
                                                class="form-select form-select-sm border-0">
                                                <option value="">All</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-10 col-lg-2 col-md-3 mx-3 my-auto">
                                    <div class="mapShowBg shadow">
                                        <p class="text-primary mb-1 fw-semibold">SHOW MAP</p>
                                    </div>
                                </div>
                                <div class="col-10 col-lg-2 col-md-3 mx-3 my-auto">
                                    <div class="bg-white shadow  px-3 py-2">
                                        <p class="text-primary mb-1 fw-semibold">PRICE RANGE</p>
                                        <div>
                                            <select name="" id=""
                                                class="form-select form-select-sm border-0">
                                                <option value="">All</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                            for($i=1;$i<=8;$i++){
                                ?>
                        <div class="col-12 col-lg-3 col-md-3">
                            <div class="projectImgCont">
                                <img src="{{ asset('frontend/assets/images/properties/p' . $i . '.png') }}"
                                    alt="project1" class="img-fluid">
                                <div class="projectImgOverlay">
                                    <div><span class="badge projectType">VILLAS</span></div>
                                    <div class="text-white">
                                        <p class="fw-bold mb-1">Palmiera - The Oasis</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Community  --}}
    <section class="my-5">
        <div class="container-fluid px-0">
            <div class="row g-0">
                <div class="col-12 col-lg-12 col-md-12">
                    <div class="row g-0">
                        <div class="col-12 col-lg-12 col-md-12">
                            <div>
                                <div class="mainHead mb-5 text-center text-primary">
                                    <h4>TOP LOCATIONS</h4>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-lg-12 col-md-12">
                            <div class="swiper pb-5 communitySwiper">
                                <div class="swiper-wrapper">
                                    <?php
                            for($i=1;$i<=4;$i++){
                                ?>
                                    <div class="swiper-slide">
                                        <div class="communityImgCont">
                                            <img src="{{ asset('frontend/assets/images/community/community' . $i . '.png') }}"
                                                alt="community1" class="img-fluid">
                                            <div class="communityImgOverlay">
                                                <div class="text-white">
                                                    <p class="fw-bold mb-1">Palmiera - The Oasis</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php } ?>
                                    <?php
                            for($i=1;$i<=4;$i++){
                                ?>
                                    <div class="swiper-slide">
                                        <div class="communityImgCont">
                                            <img src="{{ asset('frontend/assets/images/community/community' . $i . '.png') }}"
                                                alt="community1" class="img-fluid">
                                            <div class="communityImgOverlay">
                                                <div class="text-white">
                                                    <p class="fw-bold mb-1">Palmiera - The Oasis</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php } ?>
                                </div>
                                <div class="swiper-button-next text-white">
                                    <span class=''>
                                        <i class='bi bi-chevron-right fs-1'></i>
                                    </span>
                                </div>
                                <div class="swiper-button-prev text-white">
                                    <span class=''>
                                        <i class='bi bi-chevron-left fs-1'></i>
                                    </span>
                                </div>
                                <div class="swiper-pagination"></div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Team 
    <section class="my-5 bg-light py-5">
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-12 col-md-12">
                    <div class="row">
                        <div class="col-12 col-lg-12 col-md-12">
                            <div class="mainHead mb-3 text-center text-primary">
                                <h4>OUR TEAM</h4>
                            </div>
                            <div>
                                <p class="text-center text-secondary mb-0">
                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor
                                    incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud
                                    exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid px-0">
            <div class="row g-0">
                <div class="col-12 col-lg-12 col-md-12">
                    <div class="row g-0">
                        <div class="col-12 col-lg-12 col-md-12">
                            <div class="swiper teamSwiper">
                                <div class="swiper-wrapper py-5">
                                    <div class="swiper-slide">
                                        <div class="teamCont">
                                            <img src="https://range.ae/frontend/assets/images/developer/file_1656493718.jpg"
                                                alt="" class="img-fluid">
                                            <div class="teamDetail">
                                                <div class="text-white text-center p-3">
                                                    <h5 class="mb-0 fw-semibold text-uppercase fs-16">Agent Name</h5>
                                                    <p class="fs-14 mb-2">Agent Designation</p>
                                                    <div class="text-white fs-12"><span class="fa fa-star"></span>
                                                        <span class="fa fa-star checked"></span>
                                                        <span class="fa fa-star checked"></span>
                                                        <span class="fa fa-star"></span>
                                                        <span class="fa fa-star"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="teamCont">
                                            <img src="https://range.ae/frontend/assets/images/developer/file_1656486978.jpg"
                                                alt="" class="img-fluid">
                                            <div class="teamDetail">
                                                <div class="text-white text-center p-3">
                                                    <h5 class="mb-0 fw-semibold text-uppercase fs-16">Agent Name</h5>
                                                    <p class="fs-14 mb-2">Agent Designation</p>
                                                    <div class="text-white fs-12"><span class="fa fa-star"></span>
                                                        <span class="fa fa-star checked"></span>
                                                        <span class="fa fa-star checked"></span>
                                                        <span class="fa fa-star"></span>
                                                        <span class="fa fa-star"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="teamCont">
                                            <img src="https://range.ae/frontend/assets/images/developer/file_1671701145.jpeg"
                                                alt="" class="img-fluid">
                                            <div class="teamDetail">
                                                <div class="text-white text-center p-3">
                                                    <h5 class="mb-0 fw-semibold text-uppercase fs-16">Agent Name</h5>
                                                    <p class="fs-14 mb-2">Agent Designation</p>
                                                    <div class="text-white fs-12"><span class="fa fa-star"></span>
                                                        <span class="fa fa-star checked"></span>
                                                        <span class="fa fa-star checked"></span>
                                                        <span class="fa fa-star"></span>
                                                        <span class="fa fa-star"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="teamCont">
                                            <img src="https://range.ae/frontend/assets/images/developer/file_1678433562.jpg"
                                                alt="" class="img-fluid">
                                            <div class="teamDetail">
                                                <div class="text-white text-center p-3">
                                                    <h5 class="mb-0 fw-semibold text-uppercase fs-16">Agent Name</h5>
                                                    <p class="fs-14 mb-2">Agent Designation</p>
                                                    <div class="text-white fs-12"><span class="fa fa-star"></span>
                                                        <span class="fa fa-star checked"></span>
                                                        <span class="fa fa-star checked"></span>
                                                        <span class="fa fa-star"></span>
                                                        <span class="fa fa-star"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="teamCont">
                                            <img src="https://range.ae/frontend/assets/images/developer/file_1678433516.jpg"
                                                alt="" class="img-fluid">
                                            <div class="teamDetail">
                                                <div class="text-white text-center p-3">
                                                    <h5 class="mb-0 fw-semibold text-uppercase fs-16">Agent Name</h5>
                                                    <p class="fs-14 mb-2">Agent Designation</p>
                                                    <div class="text-white fs-12"><span class="fa fa-star"></span>
                                                        <span class="fa fa-star checked"></span>
                                                        <span class="fa fa-star checked"></span>
                                                        <span class="fa fa-star"></span>
                                                        <span class="fa fa-star"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="teamCont">
                                            <img src="https://range.ae/frontend/assets/images/developer/file_1656486978.jpg"
                                                alt="" class="img-fluid">
                                            <div class="teamDetail">
                                                <div class="text-white text-center p-3">
                                                    <h5 class="mb-0 fw-semibold text-uppercase fs-16">Agent Name</h5>
                                                    <p class="fs-14 mb-2">Agent Designation</p>
                                                    <div class="text-white fs-12"><span class="fa fa-star"></span>
                                                        <span class="fa fa-star checked"></span>
                                                        <span class="fa fa-star checked"></span>
                                                        <span class="fa fa-star"></span>
                                                        <span class="fa fa-star"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="teamCont">
                                            <img src="https://range.ae/frontend/assets/images/developer/file_1671701145.jpeg"
                                                alt="" class="img-fluid">
                                            <div class="teamDetail">
                                                <div class="text-white text-center p-3">
                                                    <h5 class="mb-0 fw-semibold text-uppercase fs-16">Agent Name</h5>
                                                    <p class="fs-14 mb-2">Agent Designation</p>
                                                    <div class="text-white fs-12"><span class="fa fa-star"></span>
                                                        <span class="fa fa-star checked"></span>
                                                        <span class="fa fa-star checked"></span>
                                                        <span class="fa fa-star"></span>
                                                        <span class="fa fa-star"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="teamCont">
                                            <img src="https://range.ae/frontend/assets/images/developer/file_1678433562.jpg"
                                                alt="" class="img-fluid">
                                            <div class="teamDetail">
                                                <div class="text-white text-center p-3">
                                                    <h5 class="mb-0 fw-semibold text-uppercase fs-16">Agent Name</h5>
                                                    <p class="fs-14 mb-2">Agent Designation</p>
                                                    <div class="text-white fs-12"><span class="fa fa-star"></span>
                                                        <span class="fa fa-star checked"></span>
                                                        <span class="fa fa-star checked"></span>
                                                        <span class="fa fa-star"></span>
                                                        <span class="fa fa-star"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="swiper-button-next text-primary">
                                    <span class=''>
                                        <i class='bi bi-chevron-right fs-1'></i>
                                    </span>
                                </div>
                                <div class="swiper-button-prev text-primary">
                                    <span class=''>
                                        <i class='bi bi-chevron-left fs-1'></i>
                                    </span>
                                </div>
                                <div class="swiper-pagination"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section> --}}


    <section class="my-5">
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-12 col-md-12">
                    <div class="row g-3">
                        <div class="col-12 col-lg-12 col-md-12">
                            <div class="mainHead mb-3 text-center text-primary">
                                <h4>Client Testimonials</h4>
                            </div>
                            <div class="text-center mb-0">
                                <p class="text-secondary">
                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor
                                    incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud
                                    exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                                </p>
                            </div>
                        </div>
                        <div class="col-12 col-lg-12 col-md-12">
                            <div class="swiper px-5 testiSlider">
                                <div class="swiper-wrapper">
                                    <?php
                                    for($i=1;$i<=4;$i++){
                                    ?>
                                    <div class="swiper-slide">
                                        <div class="bg-light p-4">
                                            <div>
                                                <i class="fa fa-quote-left fs-6 text-blue"></i>
                                            </div>
                                            <div class="text-primary mt-1 fs-12"><span class="fa fa-star"></span>
                                                <span class="fa fa-star"></span>
                                                <span class="fa fa-star"></span>
                                                <span class="fa fa-star"></span>
                                                <span class="fa fa-star"></span>
                                            </div>
                                            <div>
                                                <p class="fs-14 my-1">Lorem ipsum dolor sit amet, consectetur adipiscing
                                                    elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                                                    Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi.
                                                </p>
                                            </div>
                                            <div class="text-end text-blue">
                                                <i class="fa fa-quote-right fs-6"></i>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <div class="d-flex justify-content-start mt-2">
                                                    <div class="my-auto me-3">
                                                        <center><img
                                                                src="{{ asset('frontend/assets/images/testimonials/client1.png') }}"
                                                                class="img-fluid" alt="range" width="40" /></center>
                                                    </div>
                                                    <div class="my-auto">
                                                        <div class="">
                                                            <h4 class="fw-800 mb-0 fs-14 text-blue">Daren Axell</h4>
                                                            <p class="text-primary fs-12 mb-0">Daren Axell</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="my-auto">
                                                    <a href="tel:800 72 888" class="btn btn-primary rounded-0 fs-12  py-1 px-2 text-decoration-none">Contact Agent</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php } ?>
                                </div>
                                <div class="swiper-button-next text-primary">
                                    <span class=''>
                                        <i class='bi bi-chevron-right fs-1'></i>
                                    </span>
                                </div>
                                <div class="swiper-button-prev text-primary">
                                    <span class=''>
                                        <i class='bi bi-chevron-left fs-1'></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-12 col-md-12">
                            <div class="text-center py-3">
                                <button class="btn btn-blue text-uppercase btn-lg">VIEW MORE</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
