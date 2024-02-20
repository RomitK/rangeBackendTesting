<header>
    {{-- Desktop Nav  --}}

    @if (Request::is('/'))
        <nav class="navbar  bg-white">
            <div class="container py-3">
                <div class="my-auto">
                    <a class="navbar-brand" href="{{ url('/') }}">
                        <img src="{{ asset('frontend/assets/images/logo.png') }}"
                            alt="Range Internation Property Investments" class="img-fluid navMobLogo" width="175">
                    </a>
                </div>
                <div class="my-auto">

                    <div class="d-flex navMobRev">
                        <ul class="navMainPc d-none d-lg-flex d-md-flex navMenu my-auto me-3">
                            <li class="dropdown navDropMain nav-item">
                                <a href="{{ route('properties') }}" class="dropdown-toggle nav-link " data-toggle="dropdown">Properties <b
                                        class="caret"></b></a>
                                <ul class="dropdown-menu">
                                     <li class="dropdown navDropSub dropdown-submenu"><a class="dropdown-toggle mainLink"
                                            data-toggle="dropdown">Buy</a>
                                        <ul class="dropdown-menu">
                                            <li class="">
                                                <a class="" href="{{ route('ready') }}">Ready</a>
                                            </li>
                                            <li class="">
                                                <a class="" href="{{ route('off-plan') }}">Off-plan</a>
                                            </li>

                                        </ul>
                                    </li>
                                    <li class=""><a href="{{ route('rent') }}" class="mainLink">Rent</a></li>
                                    <li class=""><a href=""  class="mainLink">Sell</a></li>


                                </ul>
                            </li>
                            <li class="dropdown navDropMain nav-item">
                                <a href="" class="dropdown-toggle nav-link " data-toggle="dropdown">Services<b
                                        class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li class=""><a href="" class="mainLink">Residential Sales & Leasing</a></li>
                                    <li class=""><a href=""  class="mainLink">Commercial Sales &
                                        Leasing</a></li>
                                    <li class=""><a href=""  class="mainLink">Property Management</a></li>
                                    <li class=""><a href=""  class="mainLink">Holiday Homes</a></li>
                                    <li class=""><a href=""  class="mainLink">Mortgages</a></li>
                                </ul>
                            </li>
                            <li class="dropdown navDropMain nav-item">
                                <a class="dropdown-toggle nav-link " data-toggle="dropdown">Insights<b
                                        class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li class=""><a href=""  class="mainLink">Dubai Trends</a></li>
                                    <li class=""><a href="{{ route('singleCommunity') }}"  class="mainLink">Community</a></li>
                                    <li class=""><a href="{{ route('singleDeveloper') }}"  class="mainLink">Developers</a></li>
                                    <li class=""><a href="{{ route('singleProject') }}"  class="mainLink">Projects</a></li>
                                </ul>
                            </li>

                            <li class="dropdown navDropMain nav-item">
                                <a class="dropdown-toggle nav-link " data-toggle="dropdown">About<b
                                        class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li class=""><a href=""  class="mainLink">Range</a></li>
                                    <li class=""><a href=""  class="mainLink">Management</a></li>
                                    <li class=""><a href=""  class="mainLink">Agents</a></li>
                                    <li class=""><a href=""  class="mainLink">Achievements</a></li>
                                </ul>
                            </li>

                            <li class="dropdown navDropMain nav-item">
                                <a class="dropdown-toggle nav-link " data-toggle="dropdown">Contact<b
                                        class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li class=""><a href=""  class="mainLink">Contact Us</a></li>
                                    <li class=""><a href=""  class="mainLink">Career</a></li>
                                </ul>
                            </li>

                        </ul>
                        <div class=" my-auto me-0 me-lg-3 me-md-3 ms-3 ms-lg-0 ms-md-0">
                            <img src="{{ asset('frontend/assets/images/icons/menu.png') }}"
                                alt="Range Internation Property Investments"
                                class="img-fluid navMobMen cursor-pointer" data-bs-toggle="offcanvas"
                                data-bs-target="#offcanvasRight" width="35">
                            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight"
                                aria-labelledby="offcanvasRightLabel">
                                <div class="offcanvas-header">
                                    <div class="">
                                        <a class="navbar-brand" href="{{ url('/') }}">
                                            <img src="{{ asset('frontend/assets/images/logo.png') }}"
                                                alt="Range Internation Property Investments"
                                                class="img-fluid navMobLogo" width="175">
                                        </a>
                                    </div>
                                    <div class="my-auto">
                                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                                            aria-label="Close"></button>
                                    </div>
                                </div>
                                <div class="offcanvas-body">
                                    <ul class="list-unstyled dropList">
                                        <li class="nav-item py-3 border-bottom">
                                            <a class="nav-link" href="">Career</a>
                                        </li>
                                        <li class="nav-item py-3 border-bottom">
                                            <a class="nav-link" href="">Media</a>
                                        </li>
                                        <li class="nav-item py-3 border-bottom">
                                            <a class="nav-link" href="">Blogs and News</a>
                                        </li>
                                        <li class="nav-item py-3 border-bottom">
                                            <a class="nav-link" href="">Dubai Guide</a>
                                        </li>
                                        <li class="nav-item py-3 border-bottom">
                                            <a class="nav-link" href="">Investment Guide</a>
                                        </li>
                                        <li class="nav-item py-3 border-bottom">
                                            <a class="nav-link" href="">FAQ's</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="my-auto">
                            <div class="d-flex justify-content-end">
                                <div class="my-auto me-1">
                                    <img src="{{ asset('frontend/assets/images/icons/phone.png') }}"
                                        alt="Range Internation Property Investments" class="img-fluid"
                                        width="15">
                                </div>
                                <div class="my-auto text-uppercase text-end fs-14 navMob14 text-primary">
                                    <div><span class="fw-bold">Toll free</span> 800 72 888</div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </nav>
    @else
        <nav class="navbar bg-blue ">
            <div class="container py-3">
                <div class="my-auto">
                    <a class="navbar-brand" href="{{ url('/') }}">
                        <img src="{{ asset('frontend/assets/images/logo_white.png') }}"
                            alt="Range Internation Property Investments" class="img-fluid navMobLogo" width="175">
                    </a>
                </div>
                <div class="my-auto">

                    <div class="d-flex navMobRev">
                        <ul class="navMainPc navBlue navMenu d-none d-lg-flex d-md-flex my-auto me-3">
                            @if (Request::is('properties') ||
                                    Request::is('ready') ||
                                    Request::is('rent') ||
                                    Request::is('off-plan') ||
                                    Request::is('luxury-properties'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('ready') }}">Ready</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('rent') }}">Rent</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('off-plan') }}">OFF-PLAN</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('luxury-properties') }}">LUXURY PROPERTIES</a>
                                </li>
                            @else
                            <li class="dropdown navDropMain nav-item">
                                <a href="{{ route('properties') }}" class="dropdown-toggle nav-link " data-toggle="dropdown">Properties <b
                                        class="caret"></b></a>
                                <ul class="dropdown-menu">
                                     <li class="dropdown navDropSub dropdown-submenu"><a class="dropdown-toggle mainLink"
                                            data-toggle="dropdown">Buy</a>
                                        <ul class="dropdown-menu">
                                            <li class="">
                                                <a class="" href="{{ route('buy') }}">Ready</a>
                                            </li>
                                            <li class="">
                                                <a class="" href="{{ route('off-plan') }}">Off-plan</a>
                                            </li>

                                        </ul>
                                    </li>

                                    <li class=""><a href="{{ route('rent') }}" class="mainLink">Rent</a></li>
                                    <li class=""><a href="{{ route('rent') }}"  class="mainLink">Sell</a></li>

                                </ul>
                            </li>
                            @endif
                            <li class="dropdown navDropMain nav-item">
                                <a href="" class="dropdown-toggle nav-link " data-toggle="dropdown">Services<b
                                        class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li class=""><a href="" class="mainLink">Residential Sales & Leasing</a></li>
                                    <li class=""><a href=""  class="mainLink">Commercial Sales &
                                        Leasing</a></li>
                                    <li class=""><a href=""  class="mainLink">Property Management</a></li>
                                    <li class=""><a href=""  class="mainLink">Holiday Homes</a></li>
                                    <li class=""><a href=""  class="mainLink">Mortgages</a></li>
                                </ul>
                            </li>
                            <li class="dropdown navDropMain nav-item">
                                <a class="dropdown-toggle nav-link " data-toggle="dropdown">Insights<b
                                        class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li class=""><a href=""  class="mainLink">Dubai Trends</a></li>
                                    
                                    <li class=""><a href="{{ route('singleCommunity') }}"  class="mainLink">Community</a></li>
                                    <li class=""><a href="{{ route('singleDeveloper') }}"  class="mainLink">Developers</a></li>
                                    <li class=""><a href="{{ route('singleProject') }}"  class="mainLink">Projects</a></li>
                                </ul>
                            </li>

                            <li class="dropdown navDropMain nav-item">
                                <a class="dropdown-toggle nav-link " data-toggle="dropdown">About<b
                                        class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li class=""><a href=""  class="mainLink">Range</a></li>
                                    <li class=""><a href=""  class="mainLink">Management</a></li>
                                    <li class=""><a href=""  class="mainLink">Agents</a></li>
                                    <li class=""><a href=""  class="mainLink">Achievements</a></li>
                                </ul>
                            </li>

                            <li class="dropdown navDropMain nav-item">
                                <a class="dropdown-toggle nav-link " data-toggle="dropdown">Contact<b
                                        class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li class=""><a href=""  class="mainLink">Contact Us</a></li>
                                    <li class=""><a href=""  class="mainLink">Career</a></li>
                                </ul>
                            </li>
                        </ul>
                        <div class=" my-auto me-0 me-lg-3 me-md-3 ms-3 ms-lg-0 ms-md-0">
                            <img src="{{ asset('frontend/assets/images/icons/menu.png') }}"
                                alt="Range Internation Property Investments" data-bs-toggle="offcanvas"
                                data-bs-target="#offcanvasRight" class="img-fluid navMobMen cursor-pointer"
                                width="35" style="filter: brightness(0) invert(1);">
                            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight"
                                aria-labelledby="offcanvasRightLabel">
                                <div class="offcanvas-header">
                                    <div class="">
                                        <a class="navbar-brand" href="{{ url('/') }}">
                                            <img src="{{ asset('frontend/assets/images/logo.png') }}"
                                                alt="Range Internation Property Investments"
                                                class="img-fluid navMobLogo" width="175">
                                        </a>
                                    </div>
                                    <div class="my-auto">
                                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                                            aria-label="Close"></button>
                                    </div>
                                </div>
                                <div class="offcanvas-body">
                                    <ul class="list-unstyled dropList">
                                        <li class="nav-item py-3 border-bottom">
                                            <a class="nav-link" href="">Career</a>
                                        </li>
                                        <li class="nav-item py-3 border-bottom">
                                            <a class="nav-link" href="">Media</a>
                                        </li>
                                        <li class="nav-item py-3 border-bottom">
                                            <a class="nav-link" href="">Blogs and News</a>
                                        </li>
                                        <li class="nav-item py-3 border-bottom">
                                            <a class="nav-link" href="">Dubai Guide</a>
                                        </li>
                                        <li class="nav-item py-3 border-bottom">
                                            <a class="nav-link" href="">Investment Guide</a>
                                        </li>
                                        <li class="nav-item py-3 border-bottom">
                                            <a class="nav-link" href="">FAQ's</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="my-auto">
                            <div class="d-flex justify-content-end">
                                <div class="my-auto me-1">
                                    <img src="{{ asset('frontend/assets/images/icons/phone.png') }}"
                                        alt="Range Internation Property Investments" class="img-fluid" width="15"
                                        style="filter: brightness(0) invert(1);">
                                </div>
                                <div class="my-auto text-uppercase text-end fs-14 navMob14 text-white">
                                    <div><span class="fw-bold">Toll free</span> 800 72 888</div>

                                </div>
                            </div>
                            <div><button type="button" class="btn btn-primary rounded-0 fs-12 btn-sm w-100">SELL WITH
                                    US</button></div>
                        </div>

                    </div>
                </div>

            </div>
        </nav>
    @endif
    {{-- Mobile Nav --}}
</header>
