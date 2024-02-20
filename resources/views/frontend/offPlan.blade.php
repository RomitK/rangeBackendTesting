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
    <section class="mainBg" id="home" style="background:#2D3347;min-height:130px !important;">
    </section>

    <section class="my-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-12 col-md-12">
                    <div class="row">
                        <div class="col-12 col-lg-12 col-md-12">
                            <div class="d-flex justify-content-start">
                                <ul class="breadcrumb ps-0">
                                    <li><a href="{{ url('/') }}"><i class="bi bi-house-door"></i></a></li>
                                    <li><a>Off-Plan</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-12 col-lg-12 col-md-12">
                            <div class="searchDiv">
                                <form action="" class="searchForm">
                                    @csrf
                                    <div class="row g-2">
                                        <div class="col my-auto">
                                            <input type="text" class="form-control form-control-lg"
                                                placeholder="Enter Location" aria-label="Enter Location">
                                        </div>
                                        <div class="col my-auto">
                                            <select id="inputState" class="form-select form-select-lg">
                                                <option value="" disabled selected hidden>Developer</option>
                                                <option value="">Emaar</option>
                                                <option value="">Damac</option>
                                            </select>
                                        </div>
                                        <div class="col my-auto">
                                            <input type="text" class="form-control form-control-lg"
                                                placeholder="Enter Project Name" aria-label="Enter Project Name">
                                        </div>
                                        <div class="col my-auto">
                                            <select id="inputState" class="form-select form-select-lg">
                                                <option value="" disabled selected hidden>Property Type</option>
                                                <option value="">Apartment</option>
                                                <option value="">Villa</option>
                                                <option value="">Townhouse</option>
                                                <option value="">Penthouse</option>
                                            </select>
                                        </div>
                                        <div class="col my-auto">
                                            <select id="inputState" class="form-select form-select-lg">
                                                <option value="" disabled selected hidden>Completion Year</option>
                                                <option value="">2023</option>
                                                <option value="">2022</option>
                                                <option value="">2021</option>
                                                <option value="">2020</option>
                                            </select>
                                        </div>
                                        <div class="col my-auto">
                                            <select id="inputState" class="form-select form-select-lg">
                                                <option value="" disabled selected hidden>Price</option>
                                                <option value="">AED 200000</option>
                                            </select>
                                        </div>
                                        <div class="col-auto my-auto">
                                            <button type="button" class="btn btn-primary rounded-3 btn-lg"><i
                                                    class="bi bi-search text-white"></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="my-5">
        <div class="container">
            <div class="row g-3 justify-content-center">
                <div class="col-12 col-lg-12 col-md-12">
                    <div class="row">
                        <div class="col-12 col-lg-3 col-md-3">
                            <div>
                                <div class="card propCard rounded-0 bg-transparent">
                                    <div>
                                        <div class="swiper swiperPropList">
                                            <div class="swiper-wrapper">
                                                <div class="swiper-slide">
                                                    <div class="">
                                                        <center>
                                                            <a href="" class="text-decoration-none">
                                                                <img src="{{ asset('frontend/assets/images/properties/p1.webp') }}"
                                                                    class="card-img-top img-fluid propImg rounded-0"
                                                                    alt="Nice, Damac Lagoons">
                                                            </a>
                                                        </center>
                                                    </div>
                                                </div>
                                                <div class="swiper-slide">
                                                    <div class="">
                                                        <center>
                                                            <a href="" class="text-decoration-none">
                                                                <img src="{{ asset('frontend/assets/images/properties/p2.webp') }}"
                                                                    class="card-img-top img-fluid propImg rounded-0"
                                                                    alt="Nice, Damac Lagoons">
                                                            </a>
                                                        </center>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="swiper-button-prev text-white"></div>
                                            <div class="swiper-button-next text-white"></div>
                                        </div>
                                        <div class="card-body rounded-3 rounded-top-0">
                                            <a href="#" class="text-decoration-none">
                                                <h6 class="text-black fs-18 fw-semibold mb-1">
                                                    Nice, Damac Lagoons
                                                </h6>
                                            </a>
                                            <ul class="list-unstyled propListSmall lh-1 mb-2">
                                                <li class="d-inline">
                                                    <small>Sea View
                                                    </small>
                                                </li>
                                                <li class="d-inline">
                                                    <small>High Floor</small>
                                                </li>
                                                <li class="d-inline">
                                                    <small>Beach Access</small>
                                                </li>
                                            </ul>
                                            <p class="fs-18 mb-0">AED 2,200,000 </p>
                                            <ul class="list-unstyled mb-0 d-flex justify-content-between">
                                                <li class="d-inline">
                                                    <small><img src="{{ asset('frontend/assets/images/icons/bed.svg') }}"
                                                            alt="Range" class="img-fluid" width="25px"> <span
                                                            class="align-text-top ms-1">4</span>
                                                    </small>
                                                </li>
                                                <li class="d-inline">
                                                    <small><img src="{{ asset('frontend/assets/images/icons/bath.svg') }}"
                                                            alt="Range" class="img-fluid" width="20px"> <span
                                                            class="align-text-top ms-1">2</span></small>
                                                </li>
                                                <li class="d-inline">
                                                    <small><img src="{{ asset('frontend/assets/images/icons/area.svg') }}"
                                                            alt="Range" class="img-fluid" width="20px"> <span
                                                            class="align-text-top ms-1">1550 sq.ft</span></small>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-3 col-md-3">
                            <div>
                                <div class="card propCard rounded-0 bg-transparent">
                                    <div>
                                        <div class="swiper swiperPropList">
                                            <div class="swiper-wrapper">
                                                <div class="swiper-slide">
                                                    <div class="">
                                                        <center>
                                                            <a href="" class="text-decoration-none">
                                                                <img src="{{ asset('frontend/assets/images/properties/p3.webp') }}"
                                                                    class="card-img-top img-fluid propImg rounded-0"
                                                                    alt="Nice, Damac Lagoons">
                                                            </a>
                                                        </center>
                                                    </div>
                                                </div>
                                                <div class="swiper-slide">
                                                    <div class="">
                                                        <center>
                                                            <a href="" class="text-decoration-none">
                                                                <img src="{{ asset('frontend/assets/images/properties/p2.webp') }}"
                                                                    class="card-img-top img-fluid propImg rounded-0"
                                                                    alt="Nice, Damac Lagoons">
                                                            </a>
                                                        </center>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="swiper-button-prev text-white"></div>
                                            <div class="swiper-button-next text-white"></div>
                                        </div>
                                        <div class="card-body rounded-3 rounded-top-0">
                                            <a href="#" class="text-decoration-none">
                                                <h6 class="text-black fs-18 fw-semibold mb-1">
                                                    Nice, Damac Lagoons
                                                </h6>
                                            </a>
                                            <ul class="list-unstyled propListSmall lh-1 mb-2">
                                                <li class="d-inline">
                                                    <small>Sea View
                                                    </small>
                                                </li>
                                                <li class="d-inline">
                                                    <small>High Floor</small>
                                                </li>
                                                <li class="d-inline">
                                                    <small>Beach Access</small>
                                                </li>
                                            </ul>
                                            <p class="fs-18 mb-0">AED 2,200,000 </p>
                                            <ul class="list-unstyled mb-0 d-flex justify-content-between">
                                                <li class="d-inline">
                                                    <small><img src="{{ asset('frontend/assets/images/icons/bed.svg') }}"
                                                            alt="Range" class="img-fluid" width="25px"> <span
                                                            class="align-text-top ms-1">4</span>
                                                    </small>
                                                </li>
                                                <li class="d-inline">
                                                    <small><img src="{{ asset('frontend/assets/images/icons/bath.svg') }}"
                                                            alt="Range" class="img-fluid" width="20px"> <span
                                                            class="align-text-top ms-1">2</span></small>
                                                </li>
                                                <li class="d-inline">
                                                    <small><img src="{{ asset('frontend/assets/images/icons/area.svg') }}"
                                                            alt="Range" class="img-fluid" width="20px"> <span
                                                            class="align-text-top ms-1">1550 sq.ft</span></small>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-3 col-md-3">
                            <div>
                                <div class="card propCard rounded-0 bg-transparent">
                                    <div>
                                        <div class="swiper swiperPropList">
                                            <div class="swiper-wrapper">
                                                <div class="swiper-slide">
                                                    <div class="">
                                                        <center>
                                                            <a href="" class="text-decoration-none">
                                                                <img src="{{ asset('frontend/assets/images/properties/p4.webp') }}"
                                                                    class="card-img-top img-fluid propImg rounded-0"
                                                                    alt="Nice, Damac Lagoons">
                                                            </a>
                                                        </center>
                                                    </div>
                                                </div>
                                                <div class="swiper-slide">
                                                    <div class="">
                                                        <center>
                                                            <a href="" class="text-decoration-none">
                                                                <img src="{{ asset('frontend/assets/images/properties/p5.webp') }}"
                                                                    class="card-img-top img-fluid propImg rounded-0"
                                                                    alt="Nice, Damac Lagoons">
                                                            </a>
                                                        </center>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="swiper-button-prev text-white"></div>
                                            <div class="swiper-button-next text-white"></div>
                                        </div>
                                        <div class="card-body rounded-3 rounded-top-0">
                                            <a href="#" class="text-decoration-none">
                                                <h6 class="text-black fs-18 fw-semibold mb-1">
                                                    Nice, Damac Lagoons
                                                </h6>
                                            </a>
                                            <ul class="list-unstyled propListSmall lh-1 mb-2">
                                                <li class="d-inline">
                                                    <small>Sea View
                                                    </small>
                                                </li>
                                                <li class="d-inline">
                                                    <small>High Floor</small>
                                                </li>
                                                <li class="d-inline">
                                                    <small>Beach Access</small>
                                                </li>
                                            </ul>
                                            <p class="fs-18 mb-0">AED 2,200,000 </p>
                                            <ul class="list-unstyled mb-0 d-flex justify-content-between">
                                                <li class="d-inline">
                                                    <small><img src="{{ asset('frontend/assets/images/icons/bed.svg') }}"
                                                            alt="Range" class="img-fluid" width="25px"> <span
                                                            class="align-text-top ms-1">4</span>
                                                    </small>
                                                </li>
                                                <li class="d-inline">
                                                    <small><img src="{{ asset('frontend/assets/images/icons/bath.svg') }}"
                                                            alt="Range" class="img-fluid" width="20px"> <span
                                                            class="align-text-top ms-1">2</span></small>
                                                </li>
                                                <li class="d-inline">
                                                    <small><img src="{{ asset('frontend/assets/images/icons/area.svg') }}"
                                                            alt="Range" class="img-fluid" width="20px"> <span
                                                            class="align-text-top ms-1">1550 sq.ft</span></small>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-3 col-md-3">
                            <div>
                                <div class="card propCard rounded-0 bg-transparent">
                                    <div>
                                        <div class="swiper swiperPropList">
                                            <div class="swiper-wrapper">
                                                <div class="swiper-slide">
                                                    <div class="">
                                                        <center>
                                                            <a href="" class="text-decoration-none">
                                                                <img src="{{ asset('frontend/assets/images/properties/p2.webp') }}"
                                                                    class="card-img-top img-fluid propImg rounded-0"
                                                                    alt="Nice, Damac Lagoons">
                                                            </a>
                                                        </center>
                                                    </div>
                                                </div>
                                                <div class="swiper-slide">
                                                    <div class="">
                                                        <center>
                                                            <a href="" class="text-decoration-none">
                                                                <img src="{{ asset('frontend/assets/images/properties/p5.webp') }}"
                                                                    class="card-img-top img-fluid propImg rounded-0"
                                                                    alt="Nice, Damac Lagoons">
                                                            </a>
                                                        </center>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="swiper-button-prev text-white"></div>
                                            <div class="swiper-button-next text-white"></div>
                                        </div>
                                        <div class="card-body rounded-3 rounded-top-0">
                                            <a href="#" class="text-decoration-none">
                                                <h6 class="text-black fs-18 fw-semibold mb-1">
                                                    Nice, Damac Lagoons
                                                </h6>
                                            </a>
                                            <ul class="list-unstyled propListSmall lh-1 mb-2">
                                                <li class="d-inline">
                                                    <small>Sea View
                                                    </small>
                                                </li>
                                                <li class="d-inline">
                                                    <small>High Floor</small>
                                                </li>
                                                <li class="d-inline">
                                                    <small>Beach Access</small>
                                                </li>
                                            </ul>
                                            <p class="fs-18 mb-0">AED 2,200,000 </p>
                                            <ul class="list-unstyled mb-0 d-flex justify-content-between">
                                                <li class="d-inline">
                                                    <small><img src="{{ asset('frontend/assets/images/icons/bed.svg') }}"
                                                            alt="Range" class="img-fluid" width="25px"> <span
                                                            class="align-text-top ms-1">Studio, 1, 2</span>
                                                    </small>
                                                </li>
                                                <li class="d-inline">
                                                    <small><img src="{{ asset('frontend/assets/images/icons/bath.svg') }}"
                                                            alt="Range" class="img-fluid" width="20px"> <span
                                                            class="align-text-top ms-1">2</span></small>
                                                </li>
                                                <li class="d-inline">
                                                    <small><img src="{{ asset('frontend/assets/images/icons/area.svg') }}"
                                                            alt="Range" class="img-fluid" width="20px"> <span
                                                            class="align-text-top ms-1">1550 sq.ft</span></small>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-12 col-lg-12 col-md-12">
                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-center  pt-5">
                            <li class="page-item disabled">
                                <a class="page-link">Previous</a>
                            </li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item">
                                <a class="page-link" href="#">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
    </section>
    <section class="bg-primary py-5">
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-12 col-md-12">
                    <div class="row">
                        <div class="col-12 col-lg-5 col-md-5 my-auto">
                           <div class="px-1 px-lg-5 px-md-3">
                            <div class="mainHeadWhiteLeft mb-3 text-white">
                                <h4>ABOUT OFF-PLAN PROPERTIES</h4>
                            </div>
                            <div class="text-white fs-14">
                                <p>
                                    At Range, we offer a unique opportunity to invest in off-plan properties that redefine
                                    modern living. By investing in off-plan properties, you gain access to exclusive
                                    designs, the latest amenities, and innovative architectural concepts.
                                </p>
                                <p>Our team of experts is dedicated to guiding you through this exciting journey, providing
                                    in-depth knowledge, and ensuring a seamless transaction process.</p>
                                <p>Discover the potential of off-plan properties and elevate your real estate portfolio with
                                    us!</p>
                                </p>
                            </div>
                           </div>
                        </div>
                        <div class="col-12 col-lg-7 col-md-7 my-auto">
                            <div>
                                <center>
                                    <img src="{{ asset('frontend/assets/images/offplan.webp') }}"
                                                                    class="img-fluid"
                                                                    alt="Range">
                                </center>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
  
@endsection
