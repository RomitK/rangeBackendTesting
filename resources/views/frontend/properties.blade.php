@extends('frontend.layout.master')

@if ($pagemeta)
    @section('title', $pagemeta->meta_title)
    @section('pageDescription', $pagemeta->meta_description)
    @section('pageKeyword', $pagemeta->meta_keywords)
@else
    @section('title', 'Properties | ' . $name)
    @section('pageDescription', $website_description)
    @section('pageKeyword', $website_keyword)
@endif
@section('content')
    {{-- main banner --}}
    <section class="homeMainBg" id="home"
        style="background-image: url('{{ asset('frontend/assets/images/banner/homeBg.webp') }}')">

        <div class="container py-3">
            <div class="row">
                <div class="col-12 col-lg-12 col-md-12">
                    <div class="row justify-content-center">

                        <div class="col-12 col-lg-11 col-md-11 my-auto">
                            <div class="searchDiv">
                                <form action="" class="searchForm">
                                    @csrf
                                    <div class="row justify-content-center">
                                        <div class="col-6 col-lg my-auto">
                                            {!! Form::label('location', 'LOCATION', null) !!}
                                            {!! Form::select('location', ['' => 'Any', 'Jumeirah' => 'Jumeirah', 'Dubai Marina' => 'Dubai Marina'], null, [
                                                'class' => 'form-select form-select-sm',
                                            ]) !!}
                                        </div>
                                        <div class="col-6 col-lg my-auto">
                                            {!! Form::label('developer', 'DEVELOPER', null) !!}
                                            {!! Form::select('developer', ['' => 'Any', 'Damac' => 'Damac', 'Aldar' => 'Aldar'], null, [
                                                'class' => 'form-select form-select-sm',
                                            ]) !!}
                                        </div>
                                        <div class="col-6 col-lg my-auto">
                                            {!! Form::label('project', 'PROJECT', null) !!}
                                            {!! Form::select(
                                                'project',
                                                ['' => 'Any', 'Damac Lagoons' => 'Damac Lagoons', 'Saadiyat Island' => 'Saadiyat Island'],
                                                null,
                                                [
                                                    'class' => 'form-select form-select-sm',
                                                ],
                                            ) !!}
                                        </div>
                                        <div class="col-6 col-lg-auto">
                                            <button type="button" class="btn btn-primary rounded-1 btn-lg h-100">SEARCH
                                                PROPERTIES</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6 col-md-6 my-auto">
                            <div class="scroll-btn float-end cursor-pointer text-center">
                                <div class="vertLine"></div>
                                <div id=mouse_body>
                                    <div id="mouse_wheel" class=" bg-white border-light"></div>
                                </div>
                                <small class="fs-12 text-white text-uppercase">Scroll Down</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Services  --}}
    <section class="my-5">
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-12 col-md-12">
                    <div class="row justify-content-center">

                        <div class="col-12 col-lg-3 col-md-3">
                            <div class="communityImgCont">
                                <img src="{{ asset('frontend/assets/images/services/service1.png') }}" alt="community1"
                                    class="img-fluid" style="height: 400px;">
                                <div class="communityImgOverlay">
                                    <div class="text-white">
                                        <p class="fw-semibold mb-1">BUY | RENT | SALE</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-3 col-md-3">
                            <div class="communityImgCont">
                                <img src="{{ asset('frontend/assets/images/services/service3.png') }}" alt="community1"
                                    class="img-fluid" style="height: 400px;">
                                <div class="communityImgOverlay">
                                    <div class="text-white">
                                        <p class="fw-semibold mb-1">PROPERTY MANAGEMENT & HOLIDAY HOMES</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-3 col-md-3">
                            <div class="communityImgCont">
                                <img src="{{ asset('frontend/assets/images/services/service2.png') }}" alt="community1"
                                    class="img-fluid" style="height: 400px;">
                                <div class="communityImgOverlay">
                                    <div class="text-white">
                                        <p class="fw-semibold mb-1">MORTGAGE</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>


    {{-- Projects  --}}

    <section class="my-5">
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-12 col-md-12">
                    <div class="row">
                        <div class="col-12 col-lg-12 col-md-12">
                            <div>
                                <div class="mainHead mb-5 text-center text-primary">
                                    <h4>LATEST PROJECTS</h4>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-lg-12 col-md-12">
                            <div class="swiper pb-5 projectSlider">
                                <div class="swiper-button-prev swiperUniquePrev text-primary">
                                    <span class=''>
                                        <i class='bi bi-chevron-left fs-1'></i>
                                    </span>
                                </div>
                                <div class="swiper-wrapper">
                                    <?php
                        for($i=1;$i<=8;$i++){
                            ?>
                                    <div class="swiper-slide">
                                        <div>
                                            <div class="card propCard rounded-0">
                                                <div>
                                                    <div class="">
                                                        <a href="" class="text-decoration-none">
                                                            <div class="projectImgCont">
                                                                <img src="{{ asset('frontend/assets/images/properties/p' . $i . '.png') }}"
                                                                    alt="project1" class="img-fluid propImg">
                                                                <div class="projectImgOverlay">
                                                                    <div></div>
                                                                    <div><span
                                                                            class="badge float-start fs-10 projectType">VILLAS</span>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                    <div class="card-body rounded-3 rounded-top-0">
                                                        <a href="#" class="text-decoration-none">
                                                            <h6 class="text-black fs-16 fw-semibold mb-0">
                                                                Nice, Damac Lagoons
                                                            </h6>
                                                        </a>
                                                        <div class="mb-1">
                                                            <small class="text-secondary">Palm Jumeirah</small>
                                                        </div>
                                                        <p class="fs-18 mb-2 text-primary fw-semibold">AED 2,200,000 </p>
                                                        <ul class="list-unstyled mb-0 d-flex justify-content-between">
                                                            <li class="d-inline">
                                                                <small><img
                                                                        src="{{ asset('frontend/assets/images/icons/bed.png') }}"
                                                                        alt="Range" class="img-fluid" width="25px">
                                                                    <span class="align-text-top ms-1">4 & 5</span>
                                                                </small>
                                                            </li>
                                                            <li class="d-inline">
                                                                <small><img
                                                                        src="{{ asset('frontend/assets/images/icons/bath.png') }}"
                                                                        alt="Range" class="img-fluid" width="20px">
                                                                    <span class="align-text-top ms-1">2</span></small>
                                                            </li>
                                                            <li class="d-inline">
                                                                <small><img
                                                                        src="{{ asset('frontend/assets/images/icons/area.png') }}"
                                                                        alt="Range" class="img-fluid" width="20px">
                                                                    <span
                                                                        class="align-text-top ms-1">726sqft</span></small>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php } ?>

                                </div>
                                <div class="swiper-button-next swiperUniqueNext text-primary">
                                    <span class=''>
                                        <i class='bi bi-chevron-right fs-1'></i>
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

    {{-- About dubai  --}}
    <section class="my-5 py-5 p-relative">
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-12 col-md-12">
                    <div class="row">
                        <div class="col-12 col-lg-6 col-md-6 my-auto">
                            <div class="">

                                <div class="mainHead pb-3 text-primary">
                                    <h4 class="mb-0">DUBAI GUIDE</h4>
                                </div>
                                <div class="pb-4">
                                    <p class="text-secondary">Lorem ipsum dolor sit amet, consectetur adipiscing elit,
                                        sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim
                                        veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                                        consequat.
                                    </p>
                                    <p class="text-secondary mb-0">Lorem ipsum dolor sit amet, consectetur adipiscing elit,
                                        sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim
                                        veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                                        consequat. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod
                                        tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis
                                        nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                                    </p>

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

    {{-- Blogs  --}}
    <section class="mt-5 bg-light py-5">
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-12 col-md-12">
                    <div class="row">
                        <div class="col-12 col-lg-12 col-md-12">
                            <div class="mainHead mb-3 text-center text-primary">
                                <h4>LATEST BLOGS</h4>
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
                <div class="col-12 col-lg-12 col-md-12">
                    <div class="swiper blogSwiper">
                        <div class="swiper-wrapper py-5">
                            <?php for($i=1;$i<=8;$i++){  ?>
                            <div class="swiper-slide">

                                <div class="card border-0 shadow-sm rounded-0">
                                    <div>
                                        <div>
                                            <div class="projectImgCont">
                                                <img src="{{ asset('frontend/assets/images/properties/p1.png') }}"
                                                    alt="project1" class="img-fluid">
                                                <div class="projectImgOverlay">
                                                    <div><span class="badge blogDate fs-10">Aug 08</span></div>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body text-center rounded-top-0">
                                            <a href="#" class="text-decoration-none">
                                                <h6 class="text-primary fs-18 fw-semibold mb-0">
                                                    Do millennials care about saving?
                                                </h6>
                                            </a>
                                            <div class="mb-3">
                                                <small class="text-secondary">Curabitur tincidunt sed neque id pretium.
                                                    Aenean volutpat tristique tincidunt. Pellentesque ac urna.</small>
                                            </div>
                                            <div class="text-center">
                                                <button class="btn btn-blue text-uppercase btn-lg w-100">READ MORE</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                        <div class="swiper-pagination"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
