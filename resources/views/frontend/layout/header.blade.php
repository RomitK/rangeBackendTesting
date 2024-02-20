<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Primary Meta Tags -->
    <meta name="title" content="@yield('title') @if($slogan) | {{  $slogan }} @endif">
    <meta name="keyword" content="@yield('pageKeyword')">
    <meta name="description" content="@yield('pageDescription')">
    <meta name="author" content="@if($name) {{  $name }} | @endif Xpertise Creative Studio">
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{$website_url}}">
    <meta property="og:title" content="@yield('title')">
    <meta property="og:description" content="@yield('pageDescription')">
    <meta property="og:image"
        content="@if($logo) {{  $logo }} @else {{ asset('frontend/assets/images/logo.png') }} @endif">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{$website_url}}">
    <meta property="twitter:title" content="@yield('title')">
    <meta property="twitter:description" content="@yield('pageDescription')">
    <meta property="twitter:image"
        content="@if($logo) {{  $logo }} @else {{ asset('frontend/assets/images/logo.png') }} @endif">
    {{-- end meta --}}

    <title>@yield('title') @if($slogan) | {{  $slogan }} @endif</title>
    <link rel="canonical" href="{{url()->current()}}" />
    <!-- Favicon and touch icons -->
    <link rel="icon" type="image/png" href="@if($favicon) {{  $favicon }} @else {{ asset('frontend/assets/images/favicon.png') }} @endif">
    <link rel="shortcut icon" type="image/x-icon"
        href="{{ asset('frontend/assets/images/favicon.png') }}" />
    <link rel="apple-touch-icon"
        href="{{ asset('frontend/assets/images/favicon.png') }}" />
    <link rel="shortcut icon" type="image/x-icon"
        href="{{ asset('frontend/assets/images/favicon.png') }}" />
    <link rel="shortcut icon" type="image/x-icon"
        href="{{ asset('frontend/assets/images/favicon.png') }}" />
    <meta name="robots" content="index,follow" />
    <!-- Favicon and touch icons -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

    {{-- fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:wght@700&display=swap" rel="stylesheet">

    {{-- css --}}
    <link href="{{ asset('frontend/assets/css/style.css') }}" rel="stylesheet" />

    {{-- icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    {{-- slider --}}
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />

    {{-- jquery --}}
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"
        integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>

    {{-- country code --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/css/intlTelInput.css" integrity="sha512-gxWow8Mo6q6pLa1XH/CcH8JyiSDEtiwJV78E+D+QP0EVasFs8wKXq16G8CLD4CJ2SnonHr4Lm/yY2fSI2+cbmw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    {{-- Toaster --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/css/toastr.css" rel="stylesheet" />
    @yield('headcontent')
    
</head>
