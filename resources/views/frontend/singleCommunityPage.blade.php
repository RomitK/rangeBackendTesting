@extends('frontend.layout.master')

@if ($community->meta_title != '')
    @section('title', $community->meta_title)
@else
    @section('title', $community->name)
@endif
@if ($community->meta_description != '')
    @section('pageDescription', $community->meta_description)
@else
    @section('pageDescription', $website_description)
@endif
@if ($community->meta_keyword != '')
    @section('pageKeyword', $community->meta_keyword)
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
                                <div class="mainHead mb-3 text-primary text-center">
                                    <h4>{{ $community->name }}</h4>
                                </div>
                            </div>
                        </div>
                         @if (count($community->imageGallery) > 0)
                        <div class="col-12 col-lg-12 col-md-12">
                            <div>
                                <div class="swiper communityMainSwiper">
                                    <div class="swiper-wrapper">
                                       
                                         @foreach ($community->imageGallery as $gallery)
                                        <div class="swiper-slide">
                                            <div class="communityImgCont">
                                                <img src="{{ $gallery['path'] }}"
                                                    alt="{{ $community->name }}" class="img-fluid" style="height:600px;">
                                            </div>
                                        </div>
                                         @endforeach
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
                         @endif
                        <div class="col-12 col-lg-10 col-md-11">
                            <div class="text-center my-5">
                               
                                {!! $community->description !!}
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
                            for($i=1;$i<=4;$i++){
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
                                    <div class="swiper-slide">
                                        <div class="card border-0 rounded-0 bg-primary p-5">
                                            <div class="">
                                                <center><img
                                                        src="{{ asset('frontend/assets/images/amenities/school.png') }}"
                                                        class="img-fluid" alt="range" width="80px"></center>
                                            </div>
                                            <div class="card-body text-center pb-0">
                                                <small class="card-title text-white text-uppercase fs-20">School</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="card border-0 rounded-0 bg-primary p-5">
                                            <div class="">
                                                <center><img
                                                        src="{{ asset('frontend/assets/images/amenities/retail.png') }}"
                                                        class="img-fluid" alt="range" width="80px"></center>
                                            </div>
                                            <div class="card-body text-center pb-0">
                                                <small class="card-title text-white text-uppercase fs-20">retail</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="card border-0 rounded-0 bg-primary p-5">
                                            <div class="">
                                                <center><img
                                                        src="{{ asset('frontend/assets/images/amenities/parkwhite.png') }}"
                                                        class="img-fluid" alt="range" width="80px"></center>
                                            </div>
                                            <div class="card-body text-center pb-0">
                                                <small class="card-title text-white text-uppercase fs-20">PARK</small>
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
                           {!! $community->location_iframe !!}
                        </div>
                        <div class="col-12 col-lg-6 col-md-6 bg-white">
                            @if (count($community->stats) > 0)
                            <div class="p-3 p-md-5 p-lg-5">
                                @foreach ($community->stats[0]->values as $key => $data)
                                <div class="border-bottom border-1 border-dark py-2">
                                    <p class="text-black fw-500 mb-0 fs-20">{{ $data->key }}</p>
                                    <p class="text-primary fw-500 mb-0 fs-20">{{ $data->value }}</p>
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
    </section>
    @if (count($community->amenities) > 0)
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
                                    @foreach ($community->amenities as $item)
                                    <div class="swiper-slide">
                                        <div class="py-3">
                                            <div class="mb-2">
                                                <div class="amenityImg mx-auto">
                                                    <img src="{{ !empty($item->image) ? asset($item->image) : asset('frontend/assets/images/amenities/gym.png') }}"
                                                        alt="Range" class="img-fluid" width="40px">
                                                </div>
                                            </div>
                                            <div class="text-center">
                                                <small class="fs-20"> {{ $item->name }}</p></small>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach

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
    @endif
      @if (count($community->properties) > 0)
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
                                    @foreach($community->properties as $property)
                                    <div class="swiper-slide">
                                        <div>
                                            <div class="card propCard rounded-0">
                                                <div>
                                                    <div class="">
                                                        <a href="{{ route('property.view', $property->slug)}}" class="text-decoration-none">
                                                            <div class="projectImgCont">
                                                                <img src="{{ asset('frontend/assets/images/properties/p' . $i . '.png') }}"
                                                                    alt="project1" class="img-fluid propImg">
                                                                <div class="projectImgOverlay">
                                                                    <div></div>
                                                                    <div><span
                                                                            class="badge float-start fs-10 projectType">{{ $property->accommodations->name}}</span>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                    <div class="card-body rounded-3 rounded-top-0">
                                                        <a href="{{ route('property.view', $property->slug) }}" class="text-decoration-none">
                                                            <h6 class="text-black fs-16 fw-semibold mb-0">
                                                                {{$property->name}}
                                                            </h6>
                                                        </a>
                                                        <div class="mb-1">
                                                            <small class="text-secondary">{{$property->communities->name}}</small>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                   @endforeach

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
