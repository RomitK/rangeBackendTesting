@extends('frontend.layout.master')

{{-- @if ($service->meta_title != '')
    @section('title', $service->meta_title)
@else
    @section('title', $service->name)
@endif
@if ($service->meta_description != '')
    @section('pageDescription', $service->meta_description)
@else
    @section('pageDescription', $website_description)
@endif
@if ($service->meta_keyword != '')
    @section('pageKeyword', $service->meta_keyword)
@else
    @section('pageKeyword', $website_keyword)
@endif --}}
@section('content')
    {{-- main banner --}}

    <section class="my-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-12 col-md-12">
                    <div class="row">
                        <div class="col-12 col-lg-8 col-md-8">
                            <div class="mb-3">
                                <div style="--swiper-navigation-color: #fff; --swiper-pagination-color: #fff"
                                    class="swiper swiperThumb2">
                                    <div class="swiper-wrapper">
                                        <?php
                                        for($i=1;$i<=8;$i++){
                                            ?>
                                        <div class="swiper-slide">
                                            <img src="{{ asset('frontend/assets/images/properties/p' . $i . '.png') }}"
                                                class="img-fluid" alt="project name" />
                                        </div>
                                        <?php } ?>
                                        <div class="swiper-slide">
                                            <video width="100%" height="100%" controls>
                                                <source src="https://range.ae/frontend/assets/images/prime/BannerVideo.mp4"
                                                    type="video/mp4">
                                                Your browser does not support the video tag.
                                            </video>
                                        </div>
                                        <div class="swiper-slide">
                                            <iframe width="100%" height="100%"
                                                src="https://my.matterport.com/show/?m=oFjuJrGzTTB">
                                            </iframe>
                                        </div>
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
                                </div>
                                <div thumbsSlider="" class="swiper swiperThumb1 pt-3">
                                    <div class="swiper-wrapper">
                                        <?php
                                        for($i=1;$i<=8;$i++){
                                            ?>
                                        <div class="swiper-slide">
                                            <img src="{{ asset('frontend/assets/images/properties/p' . $i . '.png') }}"
                                                class="img-fluid" alt="project name" />
                                        </div>
                                        <?php } ?>
                                        <div class="swiper-slide">
                                            <video width="100%" height="100%" class="object-fit-cover" controls>
                                                <source src="https://range.ae/frontend/assets/images/prime/BannerVideo.mp4"
                                                    type="video/mp4">
                                                Your browser does not support the video tag.
                                            </video>
                                        </div>
                                        <div class="swiper-slide">
                                            <iframe width="100%" height="100%" style="pointer-events: none;"
                                                src="https://my.matterport.com/show/?m=oFjuJrGzTTB">
                                            </iframe>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="py-3">
                                    <div class="row">
                                        <div class="col-12 col-lg-6 my-auto">
                                            <a href="#" class="text-decoration-none">
                                                <div class="mainHead text-primary">
                                                    <h4 class="mb-0">EMAAR PALMIERA
                                                        THE OASIS VILLAS</h4>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="col-12 col-lg-6 my-auto">
                                            <div class="videoPlaywrapper cursor-pointer" data-bs-toggle="modal"
                                                data-bs-target="#videoModal">
                                                <div class="circle pulse"></div>
                                                <div class="circle">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="80" height="50"
                                                        fill="currentColor" class="bi bi-play-fill" viewBox="0 0 16 16">
                                                        <path
                                                            d="m11.596 8.697-6.363 3.692c-.54.313-1.233-.066-1.233-.697V4.308c0-.63.692-1.01 1.233-.696l6.363 3.692a.802.802 0 0 1 0 1.393z" />
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="py-2">
                                    <div>
                                        <div class="fs-14">
                                            <p>
                                                The Oasis is a unique residential development by Emaar Properties, who is a
                                                leading Emirati developer, and has already
                                                delivered over 85K residential units in Dubai and other locations. The value
                                                of the waterfront development, which will
                                                attract both local and foreign buyers, as well as investors, is USD 20B. The
                                                project will occupy an area of over 100M sq. ft
                                                and become one of the most prestigious locations in Dubai.</p>
                                            <p>The Oasis will become a real masterpiece, created by some of the world’s most
                                                well-known designers. Future residents of
                                                The Oasis are offered mansions and villas in various configurations
                                                overlooking lakes, water canals and parks. The total
                                                number of units will be 7K, and the first phase of the development called
                                                Palmiera includes 4-5 bedroom villas. The total
                                                living areas of residences vary from 5,843 sq. ft to 8,689 sq. ft. There are
                                                three interior styles to choose from: Classical,
                                                Contemporary and Chamfered. A private pool can also be installed at the
                                                owner's request.</p>
                                            <p>The Oasis will be appreciated by those who value their comfort and wish to
                                                live closer to nature, as it will feature sports
                                                facilities, a swimmable lagoon, an urban canal, lush green areas, lakes and
                                                jogging tracks, occupying 25% of the
                                                community. Adults will be able to follow a healthy and active lifestyle,
                                                while children will be amazed by the water spaces
                                                and various children’s activities. Moreover, the exclusive development will
                                                feature a school, a mosque, a retail area with
                                                lifestyle brands, spreading over 16M sq. ft, as well as a range of dining
                                                options to suit every taste.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="py-3">
                                    <div class="mainHead text-primary">
                                        <h4 class="mb-0">AMENITIES</h4>
                                    </div>
                                </div>
                                <div class="">
                                    <div class="row">
                                        <div class="col-6 col-lg-3 col-md-4 my-auto">
                                            <div class="pb-3">
                                                <div class="mb-2">
                                                    <div class="amenityImg mx-auto">
                                                        <img src="{{ asset('frontend/assets/images/amenities/gym.png') }}"
                                                            alt="Range" class="img-fluid" width="40px">
                                                    </div>
                                                </div>
                                                <div class="text-center px-0 px-lg-5 px-md-3">
                                                    <small class="fs-20">Sport
                                                        Facilities</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6 col-lg-3 col-md-4 my-auto">
                                            <div class="pb-3">
                                                <div class="mb-2">
                                                    <div class="amenityImg mx-auto">
                                                        <img src="{{ asset('frontend/assets/images/amenities/beach.png') }}"
                                                            alt="Range" class="img-fluid" width="40px">
                                                    </div>
                                                </div>
                                                <div class="text-center px-0 px-lg-5 px-md-3">
                                                    <small class="fs-20">Lagoon
                                                        Beach</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6 col-lg-3 col-md-4 my-auto">
                                            <div class="pb-3">
                                                <div class="mb-2">
                                                    <div class="amenityImg mx-auto">
                                                        <img src="{{ asset('frontend/assets/images/amenities/pool.png') }}"
                                                            alt="Range" class="img-fluid" width="40px">
                                                    </div>
                                                </div>
                                                <div class="text-center px-0 px-lg-5 px-md-3">
                                                    <small class="fs-20">Swimming
                                                        Pools
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6 col-lg-3 col-md-4 my-auto">
                                            <div class="pb-3">
                                                <div class="mb-2">
                                                    <div class="amenityImg mx-auto">
                                                        <img src="{{ asset('frontend/assets/images/amenities/park.png') }}"
                                                            alt="Range" class="img-fluid" width="40px">
                                                    </div>
                                                </div>
                                                <div class="text-center px-0 px-lg-5 px-md-3">
                                                    <small class="fs-20">Green
                                                        Areas</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6 col-lg-3 col-md-4 my-auto">
                                            <div class="pb-3">
                                                <div class="mb-2">
                                                    <div class="amenityImg mx-auto">
                                                        <img src="{{ asset('frontend/assets/images/amenities/gym.png') }}"
                                                            alt="Range" class="img-fluid" width="40px">
                                                    </div>
                                                </div>
                                                <div class="text-center px-0 px-lg-5 px-md-3">
                                                    <small class="fs-20">Sport
                                                        Facilities</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6 col-lg-3 col-md-4 my-auto">
                                            <div class="pb-3">
                                                <div class="mb-2">
                                                    <div class="amenityImg mx-auto">
                                                        <img src="{{ asset('frontend/assets/images/amenities/beach.png') }}"
                                                            alt="Range" class="img-fluid" width="40px">
                                                    </div>
                                                </div>
                                                <div class="text-center px-0 px-lg-5 px-md-3">
                                                    <small class="fs-20">Lagoon
                                                        Beach</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6 col-lg-3 col-md-4 my-auto">
                                            <div class="pb-3">
                                                <div class="mb-2">
                                                    <div class="amenityImg mx-auto">
                                                        <img src="{{ asset('frontend/assets/images/amenities/pool.png') }}"
                                                            alt="Range" class="img-fluid" width="40px">
                                                    </div>
                                                </div>
                                                <div class="text-center px-0 px-lg-5 px-md-3">
                                                    <small class="fs-20">Swimming
                                                        Pools
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6 col-lg-3 col-md-4 my-auto">
                                            <div class="pb-3">
                                                <div class="mb-2">
                                                    <div class="amenityImg mx-auto">
                                                        <img src="{{ asset('frontend/assets/images/amenities/park.png') }}"
                                                            alt="Range" class="img-fluid" width="40px">
                                                    </div>
                                                </div>
                                                <div class="text-center px-0 px-lg-5 px-md-3">
                                                    <small class="fs-20">Green
                                                        Areas</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="py-3">
                                    <div class="mainHead text-primary">
                                        <h4 class="mb-0">ABOUT PROJECT</h4>
                                    </div>
                                </div>
                                <div class="py-3">

                                    <div class="row">
                                        <div class="col-12 col-lg-12 my-auto">
                                            <div class="aboutProImg">
                                                <img src="{{ asset('frontend/assets/images/properties/p1.png') }}"
                                                    alt="property_name" class="img-fluid">
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="py-2">
                                    <div>
                                        <div class="fs-14">
                                            <p>
                                                The Oasis will be located in Dubailand with access to Yalayis Street/Jebel
                                                Ali-Al Hibab Road and Sheikh Zayed Bin
                                                Hamdan Al Nahyan Street. The community will be less than 30 minutes away
                                                from Downtown Dubai and Business Bay,
                                                and travel time to Al Maktoum International Airport will take 20 minutes,
                                                and to Dubai International Airport — about half
                                                an hour.</p>
                                            <p>Several international golf courses will be located within a 10–20 minute
                                                drive of the exclusive community, providing its
                                                residents with a chance to enjoy playing golf or even start learning it.
                                                These include Trump International Golf Club,
                                                Jumeirah Golf Estates with the Earth Course and Fire Course and The Els Club
                                                Golf Course. Hamdan Sports Complex will
                                                also be appreciated by sports enthusiasts.</p>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-4 col-md-4">
                            <div class="bg-light px-3 py-2 mb-5">
                                <div class="border-bottom border-2 py-3">
                                    <p class="text-primary fw-500 mb-1 fs-20">PROPERTY STATUS</p>
                                    <p class="fw-500 mb-0">For Sale</p>
                                </div>
                                <div class="border-bottom border-2 py-3">
                                    <p class="text-primary fw-500 mb-1 fs-20">PROPERTY TYPE</p>
                                    <p class="fw-500 mb-0">Villas</p>
                                </div>
                                <div class="border-bottom border-2 py-3">
                                    <ul class="list-unstyled">
                                        <li class="mb-2">
                                            <small><img src="{{ asset('frontend/assets/images/icons/bed-blue.png') }}"
                                                    alt="Range" class="img-fluid" width="30px">
                                                <span class="align-text-top ms-2 fs-16 fw-500">4 & 5 bedrooms</span>
                                            </small>
                                        </li>
                                        <li class="mb-2">
                                            <small><img src="{{ asset('frontend/assets/images/icons/bath-blue.png') }}"
                                                    alt="Range" class="img-fluid" width="30px">
                                                <span class="align-text-top ms-2 fs-16 fw-500">2 bathrooms</span></small>
                                        </li>
                                        <li class="mb-2">
                                            <small><img src="{{ asset('frontend/assets/images/icons/balcony.png') }}"
                                                    alt="Range" class="img-fluid" width="30px">
                                                <span class="align-text-top ms-2 fs-16 fw-500">1 balcony</span></small>
                                        </li>
                                        <li class="mb-2">
                                            <small><img src="{{ asset('frontend/assets/images/icons/area-blue.png') }}"
                                                    alt="Range" class="img-fluid" width="30px">
                                                <span class="align-text-top ms-2 fs-16 fw-500">BUA 200400
                                                    sq.ft.</span></small>
                                        </li>
                                        <li class="mb-2">
                                            <small><img src="{{ asset('frontend/assets/images/icons/building.png') }}"
                                                    alt="Range" class="img-fluid" width="30px">
                                                <span class="align-text-top ms-2 fs-16 fw-500">Emaar</span></small>
                                        </li>
                                        <li class="">
                                            <small><img src="{{ asset('frontend/assets/images/icons/hand-over.png') }}"
                                                    alt="Range" class="img-fluid" width="30px">
                                                <span class="align-text-top ms-2 fs-16 fw-500">Q4 2027
                                                    handover</span></small>
                                        </li>
                                    </ul>
                                </div>
                                <div class="py-3">
                                    <div class="d-flex justify-content-start py-3">
                                        <div class="my-auto projctSpecIMg me-3">
                                            <center><img src="{{ asset('frontend/assets/images/icons/hand-over.png') }}"
                                                    class="img-fluid" alt="range" width="60" /></center>
                                        </div>
                                        <div class="my-auto">
                                            <div class="projectSpec  text-uppercase">
                                                <p class="mb-0">AED 89,000</p>
                                                <p class="text-primary mb-0 fs-20">Starting Price</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="py-3">
                                    <div class="text-center mb-3">
                                        <a
                                            class="btn text-decoration-none bg-primary text-white  fs-18 fw-500 text-uppercase w-100 btn-lg" data-bs-toggle="modal"
                                            data-bs-target="#bookAmeeting">SCHEDULE
                                            VIEWING</a>
                                    </div>
                                    <div class="text-center">
                                        <a
                                            class="btn   text-decoration-none btn-success text-uppercase fs-18 fw-500 w-100 btn-lg"><i
                                                class="fa fa-whatsapp"></i> &nbsp;CALL WHATSAPP</a>
                                    </div>
                                </div>
                                <div class="py-3">
                                    <div>
                                        Share on:&nbsp;
                                        <a href="" class="text-decoration-none  text-black"><small><img
                                                    src="{{ asset('frontend/assets/images/icons/whatsapp.png') }}"
                                                    alt="Range" class="img-fluid" width="25px"></small></a>
                                        <a href="" class="text-decoration-none  text-black"><small><img
                                                    src="{{ asset('frontend/assets/images/icons/gmail.png') }}"
                                                    alt="Range" class="img-fluid" width="25px"></small></a>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-light px-3 py-2 mb-5">
                                <div class="pt-3">
                                    <p class="text-primary fw-500 mb-0 fs-20">MORTGAGE CALCULATOR</p>
                                </div>
                                <div class="mortgageForm py-3">
                                    <form>
                                        <div class="mb-3">
                                            <label class="form-label fw-500">Property Value</label>
                                            <div class="input-group mb-3">
                                                <span
                                                    class="input-group-text  rounded-0 border-end p-2 bg-white">AED</span>
                                                <input type="text" class="form-control border-start-0  rounded-0"
                                                    placeholder="Enter amount">
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between">
                                                <label class="form-label fw-500">Down payment</label>
                                                <label class="form-label fw-500">20%</label>
                                            </div>
                                            <input type="range" class="form-range mb-3" id="customRange1"
                                                min="20" max="80" value="20">
                                            <input type="text" class="form-control rounded-0 mb-2" placeholder="0">
                                            <small><i>Minimum of 20%</i></small>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-500">Mortgage Term</label>
                                            <input type="range" class="form-range" id="customRange1" min="1"
                                                max="25" value="25">
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <div class="mb-3">
                                                    <label class="form-label fw-500">Year</label>
                                                    <input type="text" class="form-control rounded-0"
                                                        id="customRange1" placeholder="25">
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="mb-3">
                                                    <label class="form-label fw-500">Month</label>
                                                    <input type="text" class="form-control rounded-0"
                                                        id="customRange1" placeholder="0">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-500">RATE <small>(choose from the current best
                                                    options)</small></label>
                                            <div class="input-group bg-white border">
                                                <input type="text" class="form-control border-0" placeholder="4.24">
                                                <button
                                                    class="btn border border-primary text-primary px-2 py-1 rounded-circle m-1 "
                                                    type="button"><i class="bi bi-dash-lg"></i></button>
                                                <button
                                                    class="btn border border-primary text-primary px-2 py-1 rounded-circle m-1 "
                                                    type="button"><i class="bi bi-plus-lg"></i></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="p-4 my-2 bg-primary text-white text-center">
                                    <p class="fs-14 mb-2">Your monthly payable EMI will be</p>
                                    <div class="mainHead">
                                        <h4 class=" mb-2">AED 5,327</h4>
                                    </div>
                                    <div class="mb-2"><a href="" class="text-white fs-16">VIEW CLOSING COSTS</a>
                                    </div>
                                    <p class="fs-14  mb-2">Estimated monthly payment based on
                                        800,000 AED finance amount with a 6.35%
                                        variable finance rate.</p>
                                    <p class="fs-14  mb-0">Disclaimer Rates may vary based on bank
                                        policies. T&C's apply</p>
                                </div>
                                <div class="py-3">
                                    <p class="text-primary fw-500 fs-20">ABOUT MY MORTGAGE</p>

                                    <p class="mb-0 fs-14">
                                        Leading mortgage brokerage dedicated to
                                        helping our clients achieve their dream of
                                        home ownership. Our team of experienced
                                        professionals are committed to providing
                                        exceptional customer service and personalised
                                        solutions to meet the specific needs of each of
                                        our clients
                                    </p>
                                </div>
                            </div>

                            <div class="bg-light px-3 py-2 mb-5">
                                <div class="py-3">
                                    <p class="text-primary fw-500 mb-0 fs-20">JVR COMMUNITY</p>
                                </div>
                                <div>
                                    <div class="swiper pb-5 communityProjectSwiper">
                                        <div class="swiper-wrapper">
                                            <?php
                                    for($i=1;$i<=4;$i++){
                                        ?>
                                            <div class="swiper-slide">
                                                <div class="communityImgCont">
                                                    <img src="{{ asset('frontend/assets/images/community/community' . $i . '.png') }}"
                                                        alt="community1" class="img-fluid" style="height:250px;">
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
                                <div class="">
                                    <p class="mb-0 fs-14">
                                        Lorem ipsum dolor sit amet, consectetur
                                        adipiscing elit, sed do eiusmod tempor
                                        incididunt ut labore et dolore magna aliqua. Ut
                                        enim ad minim veniam, quis nostrud
                                        exercitation ullamco laboris nisi ut aliquip ex ea
                                        commodo consequat.
                                    </p>
                                </div>
                            </div>

                        </div>
                        <div class="col-12 col-lg-8 col-md-8">
                            <div>
                                <div class="py-3">
                                    <div class="mainHead text-primary">
                                        <h4 class="mb-0">NEARBY</h4>
                                    </div>
                                </div>
                                <div class="row g-1">
                                    <div class="col-6 col-lg-3 col-md-3">
                                        <button class="btn btnNearby w-100 h-100" icon="graduation-cap"
                                            btnNearbyKey="School">School</button>
                                    </div>
                                    <div class="col-6 col-lg-3 col-md-3">
                                        <button class="btn btnNearby w-100 h-100" icon="building"
                                            btnNearbyKey="Gym">Gym</button>
                                    </div>
                                    <div class="col-6 col-lg-3 col-md-3">
                                        <button class="btn btnNearby w-100 h-100" icon="shopping-cart"
                                            btnNearbyKey="Super market">Super
                                            market</button>
                                    </div>
                                    <div class="col-6 col-lg-3 col-md-3">
                                        <button class="btn btnNearby w-100 h-100" icon="h-square"
                                            btnNearbyKey="Hospital">Hospital</button>
                                    </div>
                                    <div class="col-6 col-lg-3 col-md-3">
                                        <button class="btn btnNearby w-100 h-100" icon="paw"
                                            btnNearbyKey="pet shop">PET
                                            SHOP</button>
                                    </div>
                                    <div class="col-6 col-lg-3 col-md-3">
                                        <button class="btn btnNearby w-100 h-100" icon="shopping-bag"
                                            btnNearbyKey="mall">MALL</button>
                                    </div>
                                    <div class="col-6 col-lg-3 col-md-3">
                                        <button class="btn btnNearby w-100 h-100" icon="building-o"
                                            btnNearbyKey="Gas Station">GAS
                                            STATION</button>
                                    </div>
                                    <div class="col-6 col-lg-3 col-md-3">
                                        <button class="btn btnNearby w-100 h-100" icon="cutlery"
                                            btnNearbyKey="Restaurant">RESTAURANT</button>
                                    </div>
                                </div>
                                <div class="mapContainer py-3">
                                    <div id="map" style="width: 100%;height:400px;"></div>

                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-4 col-md-4">
                            <div class="bg-light px-3 py-2 h-100">
                                <div class="py-3">
                                    <p class="text-primary fw-500 mb-1 fs-20">NEARBY LOCATION</p>
                                </div>
                                <div class="border-bottom border-2 py-3">
                                    <h4 class="fw-500 mb-1">METRO STATION</h4>
                                    <p class="fw-500 mb-0">JVC Bus Station</p>
                                </div>

                                <div class="border-bottom border-2 py-3">
                                    <h4 class="fw-500 mb-1">MALL</h4>
                                    <p class="fw-500 mb-0">Mall of Emirates</p>
                                </div>
                                <div class="border-bottom border-2 py-3">
                                    <h4 class="fw-500 mb-1">PARK</h4>
                                    <p class="fw-500 mb-0">Lorem ipsum dolor</p>
                                    <p class="fw-500 mb-0">Lorem ipsum dolor</p>
                                    <p class="fw-500 mb-0">Lorem ipsum dolor</p>
                                </div>

                                <div class="border-bottom border-2 py-3">
                                    <h4 class="fw-500 mb-1">SALON</h4>
                                    <p class="fw-500 mb-0">Lorem ipsum dolor</p>
                                    <p class="fw-500 mb-0">Lorem ipsum dolor</p>
                                    <p class="fw-500 mb-0">Lorem ipsum dolor</p>
                                </div>


                            </div>


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
                                <div class="mainHead mb-5 text-primary">
                                    <h4>SIMILAR RENTALS</h4>
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

                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
    <!-- Modal -->
    <div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered  modal-lg">
            <div class="modal-content  bg-blue">
                <div class="modal-header border-0 justify-content-end p-1">
                    <button type="button" class="bg-transparent border-0" data-bs-dismiss="modal" aria-label="Close"><i
                            class="bi bi-x-circle text-white"></i></button>
                </div>
                <div class="modal-body">
                    <div>
                        <video width="100%" height="100%" controls>
                            <source src="https://range.ae/frontend/assets/images/prime/BannerVideo.mp4" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="bookAmeeting" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog  modal-dialog-centered modal-lg modalBookMeet ">
            <div class="modal-content">
                <div class="modal-header border-0 justify-content-end p-1">
                    <button type="button" class="bg-transparent border-0" data-bs-dismiss="modal" aria-label="Close"><i
                            class="bi bi-x-circle text-primary"></i></button>
                </div>
                <div class="modal-body  p-0 rounded-1 m-2">
                    <div class="row g-0">
                        <div class="col-12 col-lg-5 col-md-12 border-end descricalenderCol">
                            <div class="border-bottom">
                                <div class="p-3">
                                    <img src="{{ asset('frontend/assets/images/logo.png') }}"
                                            alt="Range Property" class="img-fluid" width="150">
                                </div>
                            </div>
                            <div class="p-3">
                                <p class="fw-semibold mb-0">Range International Property Investments</p>
                                <h3 class="text-primary fw-semibold">Schedule Viewing with Sales Team</h2>
                                <small class="text-secondary"><i class="bi bi-clock-fill"></i> 30 min</small>
                            </div>
                        </div>
                        <div class="col-12 col-lg-7 col-md-12 calenderCol">
                            <div class="calenderDiv p-4">
                                <form id="bookAviewing" action="" method="POST">
                                    <input type="hidden" name="_token" value="Yx2DE30TFpqKqIvRkELZvz3tR4X6WNRYjSDVNUi3">                                <input id="formFrom" name="formFrom" type="hidden" value="Book A Viewing" required>
                                    <div class="step-1">
                                        <div class="row">
    
                                            <div class="col-md-12">
                                                <h5 class="text-start">Select a Date & Time</h5>
                                            </div>
    
                                            <div class="col-md-12 newcol py-2">
                                                <div id="calendar"></div>
                                            </div>
                                            <div class="col-md-5">
                                                <div class="timepic">
                                                    <b>
                                                        <p class="ths_date">Fri Sep 2023</p>
                                                    </b>
                                                    <input type="hidden" name="id" value="">
                                                    <input type="hidden" name="ths_date" id="ths_date" required>
                                                    <input type="hidden" name="ths_time" id="ths_time" required>
                                                    <div class="listitem">
                                                                                                            <div class="pickitem">
                                                            <button type="button" class="timeitem"
                                                                value="09:00 AM">09:00 AM</button>
                                                            <button class="confirm-button" type="button"
                                                                value="09:00 AM">Confirm</button>
                                                        </div>
                                                                                                            <div class="pickitem">
                                                            <button type="button" class="timeitem"
                                                                value="09:30 AM">09:30 AM</button>
                                                            <button class="confirm-button" type="button"
                                                                value="09:30 AM">Confirm</button>
                                                        </div>
                                                                                                            <div class="pickitem">
                                                            <button type="button" class="timeitem"
                                                                value="10:00 AM">10:00 AM</button>
                                                            <button class="confirm-button" type="button"
                                                                value="10:00 AM">Confirm</button>
                                                        </div>
                                                                                                            <div class="pickitem">
                                                            <button type="button" class="timeitem"
                                                                value="10:30 AM">10:30 AM</button>
                                                            <button class="confirm-button" type="button"
                                                                value="10:30 AM">Confirm</button>
                                                        </div>
                                                                                                            <div class="pickitem">
                                                            <button type="button" class="timeitem"
                                                                value="11:00 AM">11:00 AM</button>
                                                            <button class="confirm-button" type="button"
                                                                value="11:00 AM">Confirm</button>
                                                        </div>
                                                                                                            <div class="pickitem">
                                                            <button type="button" class="timeitem"
                                                                value="11:30 AM">11:30 AM</button>
                                                            <button class="confirm-button" type="button"
                                                                value="11:30 AM">Confirm</button>
                                                        </div>
                                                                                                            <div class="pickitem">
                                                            <button type="button" class="timeitem"
                                                                value="12:00 PM">12:00 PM</button>
                                                            <button class="confirm-button" type="button"
                                                                value="12:00 PM">Confirm</button>
                                                        </div>
                                                                                                            <div class="pickitem">
                                                            <button type="button" class="timeitem"
                                                                value="12:30 PM">12:30 PM</button>
                                                            <button class="confirm-button" type="button"
                                                                value="12:30 PM">Confirm</button>
                                                        </div>
                                                                                                            <div class="pickitem">
                                                            <button type="button" class="timeitem"
                                                                value="13:00 PM">13:00 PM</button>
                                                            <button class="confirm-button" type="button"
                                                                value="13:00 PM">Confirm</button>
                                                        </div>
                                                                                                            <div class="pickitem">
                                                            <button type="button" class="timeitem"
                                                                value="13:30 PM">13:30 PM</button>
                                                            <button class="confirm-button" type="button"
                                                                value="13:30 PM">Confirm</button>
                                                        </div>
                                                                                                            <div class="pickitem">
                                                            <button type="button" class="timeitem"
                                                                value="14:00 PM">14:00 PM</button>
                                                            <button class="confirm-button" type="button"
                                                                value="14:00 PM">Confirm</button>
                                                        </div>
                                                                                                            <div class="pickitem">
                                                            <button type="button" class="timeitem"
                                                                value="14:30 PM">14:30 PM</button>
                                                            <button class="confirm-button" type="button"
                                                                value="14:30 PM">Confirm</button>
                                                        </div>
                                                                                                            <div class="pickitem">
                                                            <button type="button" class="timeitem"
                                                                value="15:00 PM">15:00 PM</button>
                                                            <button class="confirm-button" type="button"
                                                                value="15:00 PM">Confirm</button>
                                                        </div>
                                                                                                            <div class="pickitem">
                                                            <button type="button" class="timeitem"
                                                                value="15:30 PM">15:30 PM</button>
                                                            <button class="confirm-button" type="button"
                                                                value="15:30 PM">Confirm</button>
                                                        </div>
                                                                                                            <div class="pickitem">
                                                            <button type="button" class="timeitem"
                                                                value="16:00 PM">16:00 PM</button>
                                                            <button class="confirm-button" type="button"
                                                                value="16:00 PM">Confirm</button>
                                                        </div>
                                                                                                            <div class="pickitem">
                                                            <button type="button" class="timeitem"
                                                                value="16:30 PM">16:30 PM</button>
                                                            <button class="confirm-button" type="button"
                                                                value="16:30 PM">Confirm</button>
                                                        </div>
                                                                                                            <div class="pickitem">
                                                            <button type="button" class="timeitem"
                                                                value="17:00 PM">17:00 PM</button>
                                                            <button class="confirm-button" type="button"
                                                                value="17:00 PM">Confirm</button>
                                                        </div>
                                                                                                            <div class="pickitem">
                                                            <button type="button" class="timeitem"
                                                                value="17:30 PM">17:30 PM</button>
                                                            <button class="confirm-button" type="button"
                                                                value="17:30 PM">Confirm</button>
                                                        </div>
                                                                                                            <div class="pickitem">
                                                            <button type="button" class="timeitem"
                                                                value="18:00 PM">18:00 PM</button>
                                                            <button class="confirm-button" type="button"
                                                                value="18:00 PM">Confirm</button>
                                                        </div>
                                                                                                        </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
    
                                    <div class="step-2">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h6 class="text-primary">Enter Details</h6>
                                                <div class="form-group">
                                                    <label>Name*</label>
                                                    <input type="text" name="nameCon2" id="nameCon2"
                                                        class="form-control mb-2" placeholder="Enter your name"
                                                        autocomplete="off" required />
                                                </div>
                                                <div class="form-group">
                                                    <label>Email*</label>
                                                    <input type="email" name="emailCon2" id="emailCon2"
                                                        class="form-control mb-2" placeholder="Enter your email"
                                                        autocomplete="off" required />
                                                </div>
                                                <div class="form-group">
                                                    <label>Phone Number*</label>
                                                    <input id="fullNumber3" type="hidden" name="fullNumber">
                                                        <input type="tel" class="form-control mb-2" id="telephoneNew3"
                                                            name="phone" onkeyup="numbersOnly(this)"  placeholder="Enter your Phone Number" autocomplete="off"
                                                            required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Message</label>
                                                    <input type="text" name="messageCon2" id="messageCon2"
                                                        class="form-control mb-2" onkeyup="lettersOnly(this)" placeholder="Message" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0">
                                            <button type="submit" name="submit"
                                                class="btn btn-blue rounded-0 px-5 float-end btnContact2">Book A
                                                Meeting</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-header border-0">
                </div>
            </div>
        </div>
    </div>
    

    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAGZjmTZFO0V8_-_V_A-Dqto1I-FlBhshE&libraries=places&callback=initMap"
        async defer></script>
    <script>
        var map;
        var infowindow;
        var lat = 25.0602;
        var lng = 55.2094;
        var iniZoom = 15;
        var searchkey;
        var icon;

        function callback(results, status) {
            console.log(results);
            if (status === google.maps.places.PlacesServiceStatus.OK) {
                for (var i = 0; i < results.length; i++) {
                    createMarker(results[i]);
                }
            }
        }

        function priceTag() {

            const content1 = document.createElement("div");
            content1.classList.add("mapMarker");
            content1.innerHTML = `<div class="icon"><i aria-hidden="true" class="fa fa-icon fa-${icon}" ></i>
    </div>`;

            return content1;

        }

        function createMarker(place) {
            var placeLoc = place.geometry.location;

            var marker = new google.maps.marker.AdvancedMarkerElement({
                map: map,
                position: place.geometry.location,
                title: place.name,
                content: priceTag()
            });

            google.maps.event.addListener(marker, 'click', function() {
                infowindow.setContent(place.name);
                infowindow.open(map, this);
            });
        }

        async function initMap() {
            var myPlace = new google.maps.LatLng(lat, lng);
            map = new google.maps.Map(document.getElementById('map'), {
                center: myPlace,
                zoom: iniZoom,
                mapId: "4504f8b37365c3d0",
                gestureHandling: 'greedy',
            });
            const {
                AdvancedMarkerElement
            } = await google.maps.importLibrary("marker");
            infowindow = new google.maps.InfoWindow({
                map: map
            });
            if (searchkey == null) {} else {
                var request = {
                    location: myPlace,
                    radius: 500,
                    query: "'" + searchkey + "'"
                };
                var service = new google.maps.places.PlacesService(map);
                service.textSearch(request, callback);

            }

        }
    </script>
    <script>
        $('.btnNearby').on('click', function() {

            var type = $(this).attr('btnNearbyKey');
            var icons = $(this).attr('icon');
            $('.btnNearby').removeClass('active');
            $(this).addClass('active');
            searchkey = type;
            icon = icons;
            initMap();
        });
    </script>
    <!-- Call the API key. As a curtesy to the developer, please change this API key to one of your own. Thank you. -->
@endsection
