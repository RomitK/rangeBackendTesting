<!doctype html>

<html lang="en">
@include('frontend.layout.header')

<body>
    @include('frontend.layout.navbar')
   
    <main>
        @yield('content')
       
    </main>
    @include('frontend.layout.footer')
    
</body>

</html>
