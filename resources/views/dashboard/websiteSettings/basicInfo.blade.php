@extends('dashboard.layout.index')
@section('breadcrumb')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Basic Info Setting</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Basic Setting</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
@endsection
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Basic Info Form</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form class="form-boder" files="true" method="POST" enctype="multipart/form-data" action="{{ route('dashboard.basic-info.update') }}">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="website_name">Name</label>
                                            <input type="text" value="{{ $website_name }}" class="form-control " id="website_name" placeholder="Enter Website Name" name="website_name">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="slogan">Slogan</label>
                                            <input type="text" value="{{ $slogan }}" class="form-control " id="slogan" placeholder="Enter Website Slogan" name="slogan">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="website_description">Website Descripton</label>
                                            <textarea name="website_description" class="form-control " id="website_description" rows="3">{{ $website_description }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="website_url">Website URL</label>
                                            <input type="url" value="{{ $website_url }}" class="form-control " id="website_url" placeholder="Enter Website URL" name="website_url">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="website_keyword">Website Keywords</label>
                                            <input type="text" value="{{ $website_keyword }}" class="form-control " id="website_keyword" placeholder="Enter Website Keywords" name="website_keyword">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="logo">Logo</label>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="logo" name="logo" accept=".png, .jpg, .jpeg">
                                                <label class="custom-file-label" for="logo">Choose file</label>
                                            </div>
                                        </div>
                                        <div>
                                            @if(isset($logo))
                                            <img src="{{$logo}}" alt="" class="img-fluid" width="100" height="100" >
                                            @endif
                                            
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="favicon">Favicon</label>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="image" name="favicon" accept=".png, .jpg, .jpeg">
                                                <label class="custom-file-label" for="favicon">Choose file</label>
                                            </div>
                                        </div>
                                        <div>
                                            @if(isset($favicon))
                                            <img src="{{$favicon}}" alt="" class="img-fluid" width="30" height="30" >
                                            @endif
                                            
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="footer_logo">Footer Logo</label>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="footer_logo" name="footer_logo" accept=".png, .jpg, .jpeg, .webp, .svg">
                                                <label class="custom-file-label" for="footer_logo">Choose file</label>
                                            </div>
                                        </div>
                                        <div>
                                            @if(isset($footer_logo))
                                            <img src="{{$footer_logo}}" alt="" class="img-fluid" width="100" height="100" >
                                            @endif
                                            
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="copyright_description">Copyright Footer Text</label>
                                            <textarea name="copyright_description" class="form-control " id="copyright_description" rows="3">{{ $copyright_description }}</textarea>
                                        </div>
                                        
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="address">Address</label>
                                            <input type="text" value="{{ $address }}" id="address" name="address" class="form-control" placeholder="Enter Full Address">
                                        </div>
                                        
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="location">Location</label>
                                            <input type="text" value="{{ $location }}" id="address-input" name="location" class="form-control map-input">
                                            <input type="hidden" value="{{ $address_latitude }}" name="address_latitude" id="address-latitude" value="0" />
                                            <input type="hidden" value="{{ $address_longitude }}" name="address_longitude" id="address-longitude" value="0" />

                                            @error('amenties_description')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                         <div id="address-map-container" style="width:100%;height:400px; ">
                                           <div style="width: 100%; height: 100%" id="address-map"></div>
                                         </div>
                                       </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </section>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyChuU-X16agmkNHRIw5mqaFTcsMsSlASBs&libraries=places&callback=initialize" async defer></script>
    <script>

        function initialize() {
            const locationInputs = document.getElementsByClassName("map-input");
            const autocompletes = [];
            const geocoder = new google.maps.Geocoder;
            for (let i = 0; i < locationInputs.length; i++) {
                const input = locationInputs[i];
                const fieldKey = input.id.replace("-input", "");
                const isEdit = document.getElementById(fieldKey + "-latitude").value != '' && document.getElementById(fieldKey + "-longitude").value != '';
                const latitude = parseFloat(document.getElementById(fieldKey + "-latitude").value) || -33.8688;
                const longitude = parseFloat(document.getElementById(fieldKey + "-longitude").value) || 151.2195;
                const map = new google.maps.Map(document.getElementById(fieldKey + '-map'), {
                    center: {lat: latitude, lng: longitude},
                    zoom: 13
                });
                const marker = new google.maps.Marker({
                    map: map,
                    position: {lat: latitude, lng: longitude},
                });
                marker.setVisible(isEdit);
                const autocomplete = new google.maps.places.Autocomplete(input);
                autocomplete.key = fieldKey;
                autocompletes.push({input: input, map: map, marker: marker, autocomplete: autocomplete});
            }

            for (let i = 0; i < autocompletes.length; i++) {
                const input = autocompletes[i].input;
                const autocomplete = autocompletes[i].autocomplete;
                const map = autocompletes[i].map;
                const marker = autocompletes[i].marker;
                google.maps.event.addListener(autocomplete, 'place_changed', function () {
                    marker.setVisible(false);
                    const place = autocomplete.getPlace();
                    geocoder.geocode({'placeId': place.place_id}, function (results, status) {
                        if (status === google.maps.GeocoderStatus.OK) {
                            const lat = results[0].geometry.location.lat();
                            const lng = results[0].geometry.location.lng();
                            setLocationCoordinates(autocomplete.key, lat, lng);
                        }
                    });

                    if (!place.geometry) {
                        window.alert("No details available for input: '" + place.name + "'");
                        input.value = "";
                        return;
                    }
                    if (place.geometry.viewport) {
                        map.fitBounds(place.geometry.viewport);
                    } else {
                        map.setCenter(place.geometry.location);
                        map.setZoom(17);
                    }
                    marker.setPosition(place.geometry.location);
                    marker.setVisible(true);
                });
            }
        }
        function setLocationCoordinates(key, lat, lng) {
            const latitudeField = document.getElementById(key + "-" + "latitude");
            const longitudeField = document.getElementById(key + "-" + "longitude");
            latitudeField.value = lat;
            longitudeField.value = lng;
        }
    </script>
@endsection
