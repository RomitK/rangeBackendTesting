@extends('frontend.layout.master')

@if ($developer->meta_title != '')
    @section('title', $developer->meta_title)
@else
    @section('title', $developer->name)
@endif
@if ($developer->meta_description != '')
    @section('pageDescription', $developer->meta_description)
@else
    @section('pageDescription', $website_description)
@endif
@if ($developer->meta_keyword != '')
    @section('pageKeyword', $developer->meta_keyword)
@else
    @section('pageKeyword', $website_keyword)
@endif
@section('content')
    {{-- main banner --}}

    <section class="my-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-12 col-md-12">
                    <div class="row g-3 justify-content-center">
                        <div class="col-12 col-lg-12 col-md-12">
                            <div>
                                <div class="mainHead mb-3 text-center">
                                    <h4 class=" text-primary">{{$developer->name}}</h4>
                                    {!! $developer->short_description !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-12 col-md-12">
                            <div>
                                <div class="swiper communityMainSwiper">
                                    <div class="swiper-wrapper">
                                        @if(count($developer->gallery) > 0)
                                            @foreach($developer->gallery as $key => $imgs)
                                            <div class="swiper-slide">
                                                <div class="communityImgCont">
                                                    <img src="{{ $imgs['path'] }}"
                                                        alt="{{ $developer->name }}" class="img-fluid" style="height:600px;">
                                                </div>
                                            </div>
                                            @endforeach
                                        @endif
                                        
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
        </div>
    </section>
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
                                <img src="{{ asset('frontend/assets/images/properties/p' . $i . '.png') }}" alt="project1"
                                    class="img-fluid">
                                <div class="projectImgOverlay">
                                    <div><span class="badge projectType">VILLAS</span></div>
                                    <div class="text-white">
                                        <p class="fw-bold mb-1">Palmiera - The Oasis</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                        <div class="text-center py-3 text-primary">
                            <a href="" class="text-primary">VIEW ALL</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="mt-5 bg-light py-5">
        <div class="container">
            <div class="row g-3 justify-content-center">
                <div class="col-12 col-lg-12 col-md-12">
                    <div class="row">
                        <div class="col-12 col-lg-12 col-md-12">
                            <div>
                                <div class="mainHead mb-5 text-primary text-center">
                                    <h4>DEVELOPMENT BY EMAAR</h4>
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

                                                            </div>
                                                        </a>
                                                    </div>
                                                    <div class="card-body rounded-3 rounded-top-0">
                                                        <a href="#" class="text-decoration-none">
                                                            <div class="mb-1 text-center">
                                                                <h5 class="text-black">Palm Jumeirah</h5>
                                                            </div>
                                                        </a>


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
    </section>
    @if(count($developer->properties)> 0)
    <section class="my-5" id="properties">
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-12 col-md-12">
                    <div class="row">
                        <div class="col-12 col-lg-12 col-md-12">
                            <div>
                                <div class="mainHead mb-5 text-center text-primary">
                                    <h4>AVAILABLE PROPERTIES</h4>
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
    @endif
@endsection
