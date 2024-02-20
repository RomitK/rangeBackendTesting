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
                    <div class="row g-3 justify-content-center">
                        <div class="col-12 col-lg-12 col-md-12">
                            <div>
                                
                                <div class="mainHead mb-3 text-primary text-center">
                                    <h4>Dubai Marina</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-12 col-md-12">
                            <div>
                                <div class="swiper communityMainSwiper">
                                    <div class="swiper-wrapper">
                                        <?php
                                for($i=1;$i<=4;$i++){
                                    ?>
                                        <div class="swiper-slide">
                                            <div class="communityImgCont">
                                                <img src="{{ asset('frontend/assets/images/properties/p' . $i . '.png') }}"
                                                    alt="community1" class="img-fluid" style="height:600px;">
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
                        <div class="col-12 col-lg-10 col-md-11">
                            <div class="text-center my-5">
                                <p class="text-secondary"> Sed ut perspiciatis unde omnis iste natus error sit voluptatem
                                    accusantium doloremque
                                    laudantium, totam rem aperiam, eaque ipsa
                                    quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.
                                    Nemo
                                    enim ipsam voluptatem quia voluptas sit
                                    aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione
                                    voluptatem
                                    sequi nesciunt. Neque porro quisquam
                                    est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non
                                    numquam
                                    eius modi tempora incidunt ut labore et
                                    dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum
                                    exercitationem
                                    ullam corporis suscipit
                                    laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure
                                    reprehenderit qui
                                    in ea voluptate velit esse quam nihil
                                    molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?</p>
                                <p class="text-secondary">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
                                    eiusmod tempor incididunt ut
                                    labore et dolore magna aliqua. Ut enim ad
                                    minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                                    consequat.
                                    Duis aute irure dolor in reprehenderit
                                    in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat
                                    cupidatat non proident, sunt in culpa qui officia
                                    deserunt mollit anim id est laborum.</p>
                            </div>
                        </div>
                        <div class="col-12 col-lg-4 col-md-3 ">
                            <a href="#highlight" class="text-decoration-none">
                            <div class="communityTab">
                                <h3>Highlights</h3>
                            </div>
                            
                        </a>
                        </div>
                        <div class="col-12 col-lg-4 col-md-3 ">
                            <a href="#amenities" class="text-decoration-none">
                            <div class="communityTab">
                                <h3>Amenities</h3>
                            </div>
                        </a>
                        </div>
                        <div class="col-12 col-lg-4 col-md-3 ">
                            <a href="#properties" class="text-decoration-none">
                            <div class="communityTab">
                                <h3>Available Properties</h3>
                            </div>
                        </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="my-5" id="highlight">
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-12 col-md-12">
                    <div class="row ">
                        <div class="col-12 col-lg-12 col-md-12">
                            <div>
                                <div class="mainHead mb-5 text-center text-primary">
                                    <h4>HIGHLIGHTS</h4>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-lg-12 col-md-12">
                            <div class="swiper pb-5 highlightSwiper px-5">
                                <div class="swiper-wrapper pb-3">
                                    <?php
                            for($i=1;$i<=8;$i++){
                                ?>
                                    <div class="swiper-slide">
                                        <div class="card border-0 rounded-0 bg-primary p-5">
                                            <div class="">
                                                <center><img src="{{ asset('frontend/assets/images/amenities/mall.png') }}"
                                                        class="img-fluid" alt="range" width="80px"></center>
                                            </div>
                                            <div class="card-body text-center pb-0">
                                                <small class="card-title text-white text-uppercase fs-20">MALL</small>
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
                                <div class="swiper-pagination"></div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="my-5 bg-light py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-10 col-md-12">
                    <div class="row g-0">
                        <div class="col-12 col-lg-6 col-md-6">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d28908.38368669829!2d55.11897054782833!3d25.083305996724388!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3e5f6b5402c126e3%3A0xb9511e6655c46d7c!2sDubai%20Marina%20-%20Dubai!5e0!3m2!1sen!2sae!4v1695385346036!5m2!1sen!2sae" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                        <div class="col-12 col-lg-6 col-md-6 bg-white">
                            <div class="p-3 p-md-5 p-lg-5">
                                <div class="border-bottom border-1 border-dark py-2">
                                    <p class="text-black fw-500 mb-0 fs-20">Downtown</p>
                                    <p class="text-primary fw-500 mb-0 fs-20">18 kms</p>
                                </div>
                                <div class="border-bottom border-1 border-dark py-2">
                                    <p class="text-black fw-500 mb-0 fs-20">Business Bay</p>
                                    <p class="text-primary fw-500 mb-0 fs-20">20 kms</p>
                                </div>
                                <div class="border-bottom border-1 border-dark py-2">
                                    <p class="text-black fw-500 mb-0 fs-20">Jebel Ali
                                    </p>
                                    <p class="text-primary fw-500 mb-0 fs-20">10 kms</p>
                                </div>
                                <div class="border-bottom border-1 border-dark py-2">
                                    <p class="text-black fw-500 mb-0 fs-20">Palm Jumeirah</p>
                                    <p class="text-primary fw-500 mb-0 fs-20">5 kms</p>
                                </div>
                                <div class=" py-2">
                                    <p class="text-black fw-500 mb-0 fs-20">Jumeirah Lake Towers
                                    </p>
                                    <p class="text-primary fw-500 mb-0 fs-20">5 kms</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
    <section class="my-5" id="amenities">
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-12 col-md-12">
                    <div class="row ">
                        <div class="col-12 col-lg-12 col-md-12">
                            <div>
                                <div class="mainHead mb-5 text-center text-primary">
                                    <h4>AMENITIES</h4>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-lg-12 col-md-12">
                            <div class="swiper pb-5 amenitiesSwiper px-5">
                                <div class="swiper-wrapper">
                                    <?php
                            for($i=1;$i<=4;$i++){
                                ?>
                                    <div class="swiper-slide">
                                        <div class="py-3">
                                            <div class="mb-2">
                                                <div class="amenityImg mx-auto">
                                                    <img src="{{ asset('frontend/assets/images/amenities/gym.png') }}"
                                                        alt="Range" class="img-fluid" width="40px">
                                                </div>
                                            </div>
                                            <div class="text-center">
                                                <small class="fs-20">Sport
                                                    Facilities</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="py-3">
                                            <div class="mb-2">
                                                <div class="amenityImg mx-auto">
                                                    <img src="{{ asset('frontend/assets/images/amenities/beach.png') }}"
                                                        alt="Range" class="img-fluid" width="40px">
                                                </div>
                                            </div>
                                            <div class="text-center">
                                                <small class="fs-20">Lagoon
                                                    Beach</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="py-3">
                                            <div class="mb-2">
                                                <div class="amenityImg mx-auto">
                                                    <img src="{{ asset('frontend/assets/images/amenities/park.png') }}"
                                                        alt="Range" class="img-fluid" width="40px">
                                                </div>
                                            </div>
                                            <div class="text-center">
                                                <small class="fs-20">Green
                                                    Areas</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="py-3">
                                            <div class="mb-2">
                                                <div class="amenityImg mx-auto">
                                                    <img src="{{ asset('frontend/assets/images/amenities/pool.png') }}"
                                                        alt="Range" class="img-fluid" width="40px">
                                                </div>
                                            </div>
                                            <div class="text-center">
                                                <small class="fs-20">Swimming
                                                    Pools</small>
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
                                <div class="swiper-pagination"></div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </section>
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
    <section class="my-5 ">
        <div class="container">
            <div class="row g-3 justify-content-center">
                <div class="col-12 col-lg-12 col-md-12">
                    <div class="row justify-content-center">
                        <div class="col-12 col-lg-12 col-md-12">
                            <div>
                                <div class="mainHead mb-5 text-primary">
                                    <h4>PROPERTY INSIGHTS</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-12 col-md-12">
                           <div class="border-bottom border-dark pb-3">
                            <table class="table table-striped text-center table-lg tableinsights table-borderless">
                                <thead>
                                  <tr>
                                    <th scope="col">APARTMENT TYPE</th>
                                    <th scope="col">AVG SELLING PRICE</th>
                                    <th scope="col">AVG SELLING PRICE</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr>
                                    <td>STUDIO
                                    </td>
                                    <td>AED 1.8 MILLION</td>
                                    <td>AED 90,000</td>
                                  </tr>
                                  <tr>
                                    <td>1 BEDROOM</td>
                                    <td>AED 1.8 MILLION</td>
                                    <td>AED 90,000</td>
                                  </tr>
                                  <tr>
                                    <td>2 BEDROOM</td>
                                    <td>AED 1.8 MILLION</td>
                                    <td>AED 90,000</td>
                                  </tr>
                                  <tr>
                                    <td>3 BEDROOM</td>
                                    <td>AED 1.8 MILLION</td>
                                    <td>AED 90,000</td>
                                  </tr>
                                  <tr>
                                    <td>4 BEDROOM</td>
                                    <td>AED 1.8 MILLION</td>
                                    <td>AED 90,000</td>
                                  </tr>
                                </tbody>
                              </table>
                           </div>
                           <div class="py-3">
                            <table class="table table-striped text-center table-lg tableinsights table-borderless">
                                <thead>
                                  <tr>
                                    <th scope="col">TOWNHOUSE/ VILLA TYPE</th>
                                    <th scope="col">AVG SELLING PRICE</th>
                                    <th scope="col">AVG SELLING PRICE</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr>
                                    <td>1 BEDROOM</td>
                                    <td>AED 1.8 MILLION</td>
                                    <td>AED 90,000</td>
                                  </tr>
                                  <tr>
                                    <td>2 BEDROOM</td>
                                    <td>AED 1.8 MILLION</td>
                                    <td>AED 90,000</td>
                                  </tr>
                                  <tr>
                                    <td>3 BEDROOM</td>
                                    <td>AED 1.8 MILLION</td>
                                    <td>AED 90,000</td>
                                  </tr>
                                  <tr>
                                    <td>4 BEDROOM</td>
                                    <td>AED 1.8 MILLION</td>
                                    <td>AED 90,000</td>
                                  </tr>
                                  <tr>
                                    <td>5 BEDROOM</td>
                                    <td>AED 1.8 MILLION</td>
                                    <td>AED 90,000</td>
                                  </tr>
                                </tbody>
                              </table>
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
                                    <h4>NEIGHBOURHOOD</h4>
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
@endsection
