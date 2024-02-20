@extends('frontend.layout.master')

@if ($pagemeta)
    @section('title', $pagemeta->meta_title)
    @section('pageDescription', $pagemeta->meta_description)
    @section('pageKeyword', $pagemeta->meta_keywords)
@else
    @section('title', 'Communities | ' . $name)
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
                                    <li><a>Our Communities</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-12 col-lg-12 col-md-12">
                            <div class="searchDiv">
                                <form action="" class="searchForm">
                                    @csrf
                                    <div class="row justify-content-center g-2">
                                        <div class="col-8 col-lg-4 col-md-5 my-auto">
                                            <input type="text" class="form-control form-control-lg"
                                                placeholder="Enter Location" aria-label="Enter Location">
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
                        <div class="col-12 col-lg-4 col-md-4">
                            <div>
                                <a href="#" class="text-decoration-none">
                                    <div class="commContain" style="background-image: url('{{ asset('frontend/assets/images/properties/p2.webp') }}');">
                    
                                        <div class="commDetails text-uppercase p-3 text-white">
                                            <h6 class="fs-18 fw-semibold mb-0">
                                                JUMEIRAH VILLAGE CIRCLE
                                            </h6>
                                            <small>COMFORTABLE FAMILY-FRIENDLY COMMUNITY
                                            </small>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-12 col-lg-4 col-md-4">
                            <div>
                                <a href="#" class="text-decoration-none">
                                    <div class="commContain" style="background-image: url('{{ asset('frontend/assets/images/properties/p1.webp') }}');">
                    
                                        <div class="commDetails text-uppercase p-3 text-white">
                                            <h6 class="fs-18 fw-semibold mb-0">
                                                PALM JUMEIRAH
                                            </h6>
                                            <small>A MAN-MADE HEAVEN</small>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-12 col-lg-4 col-md-4">
                            <div>
                                <a href="#" class="text-decoration-none">
                                    <div class="commContain" style="background-image: url('{{ asset('frontend/assets/images/properties/p3.webp') }}');">
                    
                                        <div class="commDetails  text-uppercase p-3 text-white">
                                            <h6 class="fs-18 fw-semibold mb-0">
                                                DUBAI MARINA
                                            </h6>
                                            <small>AFFLUENT RESIDENTIAL NEIGHBORHOOD
                                            </small>
                                        </div>
                                    </div>
                                </a>
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
                                <h4>ABOUT OUR COMMUNITIES</h4>
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
