@extends('dashboard.layout.index')
@section('breadcrumb')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Communities</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('dashboard/communities') }}">Communities</a></li>
                        <li class="breadcrumb-item active">New Community</li>
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
                            <h3 class="card-title">New Community Form</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form class="form-boder" id="storeForm" method="POST" action="{{ route('dashboard.communities.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="name">Name</label>
                                            <input type="text" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Enter Name" name="name" required>
                                            @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label for="emirate">Select Emirate</label>
                                            <select class="form-control select1 @error('emirate') is-invalid @enderror" id="emirates" name="emirates" required>
                                            @foreach (config('constants.emirates') as $key=>$value)
                                                <option value="{{ $value }}">{{ $value }}</option>
                                                @endforeach
                                            </select>
                                            @error('emirates')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label for="status">Status</label>
                                            <select class="form-control select1 @error('status') is-invalid @enderror" id="status"
                                                name="status" required>
                                                @foreach (config('constants.statuses') as $key=>$value)
                                                <option value="{{ $key }}">{{ $value }}</option>
                                                @endforeach
                                            </select>
                                            @error('status')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>


                                     <div class="col-sm-2">
                                        <div class="form-group">
                                            <label for="display_on_home">Display on Home Page</label>
                                            <select class="form-control select1 @error('display_on_home') is-invalid @enderror" id="display_on_home" name="display_on_home" required>
                                                <option value="1">Yes</option>
                                                <option value="0">No</option>
                                            </select>
                                            @error('display_on_home')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label for="communityOrder">Community Order</label>
                                            <input type="number" value="{{ old('communityOrder') }}"
                                                class="form-control @error('communityOrder') is-invalid @enderror" id="communityOrder"
                                                 name="communityOrder" min="1">
                                            @error('communityOrder')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="developerIds">Select Developers</label>
                                            <select multiple="multiple" data-placeholder="Select Developers" style="width: 100%;" class="select2 form-control @error('developerIds') is-invalid @enderror" id="developerIds" name="developerIds[]">
                                                @foreach ($developers as $developer)
                                                <option value="{{ $developer->id }}">{{ $developer->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('developerIds')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="amenities">Amenities<small class="text-danger">(At least 8 Amenities )</small></label>
                                            <select multiple="multiple" data-placeholder="Select Amenities"
                                                style="width: 100%;"
                                                class="select2 form-control @error('amenityIds') is-invalid @enderror"
                                                id="amenities" name="amenityIds[]">
                                                @foreach ($amenities as $amenity)
                                                    <option value="{{ $amenity->id }}">{{ $amenity->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('amenityIds')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="highlights">Highlights<small class="text-danger">(At least 4 Highlights )</small></label>
                                            <select multiple="multiple" data-placeholder="Select Highlights" style="width: 100%;" class="select2 form-control @error('highlightIds') is-invalid @enderror" id="highlights" name="highlightIds[]">
                                                @foreach ($highlights as $highlight)
                                                <option value="{{ $highlight->id }}">{{ $highlight->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('highlightIds')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="shortDescription">Short Description<small class="text-danger">(Not more 300 characters)</small></label>
                                            <textarea id="shortDescription" class=" form-control @error('shortDescription') is-invalid @enderror" name="shortDescription">{{ old('shortDescription') }}</textarea>
                                            @error('shortDescription')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="description">Description</label>
                                            <textarea id="description" class="summernote form-control @error('description') is-invalid @enderror" name="description">{{ old('description') }}</textarea>
                                            @error('description')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <!-- <div class="col-sm-12">-->
                                    <!--    <div class="form-group">-->
                                    <!--        <label for="location_iframe">Location Iframe</label>-->
                                    <!--        <textarea class="form-control @error('location_iframe') is-invalid @enderror" id="location_iframe"-->
                                    <!--            placeholder="Enter Location Iframe" name="location_iframe">{{ old('location_iframe') }}</textarea>-->
                                    <!--        @error('location_iframe')-->
                                    <!--            <span class="invalid-feedback" role="alert">-->
                                    <!--                <strong>{{ $message }}</strong>-->
                                    <!--            </span>-->
                                    <!--        @enderror-->
                                    <!--    </div>-->
                                    <!--</div>-->
                                     <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="address">Address</label>
                                            <input type="text" value="{{ old('address') }}" id="address-input"
                                                name="address" class="form-control map-input">
                                            <input type="hidden" name="address_latitude" id="address-latitude"
                                                value="0" />
                                            <input type="hidden" name="address_longitude" id="address-longitude"
                                                value="0" />

                                            @error('address')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div id="address-map-container" style="width:100%;height:200px; ">
                                                <div style="width: 100%; height: 100%" id="address-map"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="meta_title">Meta Title</label>
                                            <input type="text" value="{{ old('meta_title') }}"
                                                class="form-control @error('meta_title') is-invalid @enderror" id="meta_title"
                                                placeholder="Enter Meta Title" name="meta_title">
                                            @error('meta_title')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="meta_keywords">Meta Keywords<small class="text-danger">(Multiple keywords separated with comas)</small></label>
                                            <input type="text" value="{{ old('meta_keywords') }}"
                                                class="form-control @error('meta_keywords') is-invalid @enderror" id="meta_keywords"
                                                placeholder="Enter Meta Keywords" name="meta_keywords">
                                            @error('meta_keywords')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="meta_description">Meta Description</label>
                                            <textarea class="form-control @error('meta_description') is-invalid @enderror" id="meta_description"
                                                placeholder="Enter Meta Description" name="meta_description">{{ old('meta_description') }}</textarea>
                                            @error('meta_description')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="mainImage">Home Page Image <small class="text-danger">(400x400)</small></label>
                                            <div class="custom-file   @error('mainImage') is-invalid @enderror">
                                                <input type="file" class="custom-file-input  @error('mainImage') is-invalid @enderror" id="mainImage" name="mainImage"
                                                accept="image/*" required>
                                                <label class="custom-file-label" for="mainImage">Choose file</label>
                                            </div>
                                            <img id="mainImagePreview" style="max-width: 100%; height: 100px; display: none;" />
                                            @error('mainImage')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <!--<div class="col-sm-4">-->
                                    <!--    <div class="form-group">-->
                                    <!--        <label for="imageGallery">Image Gallery<small class="text-danger">(1294×600)</small></label>-->
                                    <!--        <div class="custom-file   @error('imageGallery') is-invalid @enderror">-->
                                    <!--            <input type="file" class="custom-file-input @error('imageGallery') is-invalid @enderror @error('imageGallery.*') is-invalid @enderror" id="imageGallery" name="imageGallery[]"-->
                                    <!--            accept="image/*" multiple required>-->
                                    <!--            <label class="custom-file-label" for="imageGallery">Choose file</label>-->
                                    <!--        </div>-->
                                    <!--        @error('imageGallery')-->
                                    <!--            <span class="invalid-feedback" role="alert">-->
                                    <!--                <strong>{{ $message }}</strong>-->
                                    <!--            </span>-->
                                    <!--        @enderror-->
                                    <!--        @error('imageGallery.*')-->
                                    <!--            <span class="invalid-feedback" role="alert" style="display: block">-->
                                    <!--                <strong>{{ $message }}</strong>-->
                                    <!--            </span>-->
                                    <!--        @enderror-->
                                    <!--    </div>-->
                                    <!--</div>-->
                                    <!--<div class="col-sm-4">-->
                                    <!--    <div class="form-group">-->
                                    <!--        <label for="listMainImage">Listing Page Image <small class="text-danger">(Prefer Dimension 400 X 400)</small></label>-->
                                    <!--        <div class="custom-file   @error('listMainImage') is-invalid @enderror">-->
                                    <!--            <input type="file" class="custom-file-input   @error('listMainImage') is-invalid @enderror" id="listMainImage"-->
                                    <!--                name="listMainImage" accept="image/*">-->
                                    <!--            <label class="custom-file-label" for="listMainImage">Choose file</label>-->
                                    <!--        </div>-->
                                    <!--        <img id="listMainImagePreview"style="max-width: 100%; height: 100px; display: none;" />-->
                                    <!--        @error('listMainImage')-->
                                    <!--            <span class="invalid-feedback" role="alert">-->
                                    <!--                <strong>{{ $message }}</strong>-->
                                    <!--            </span>-->
                                    <!--        @enderror-->
                                    <!--    </div>-->
                                    <!--</div>-->
                                    
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="masterPlan">Master Plan <small class="text-danger">(Prefer Dimension 600X1000)</small></label>
                                            <div class="custom-file   @error('clustmasterPlanerPlan') is-invalid @enderror">
                                                <input type="file" class="custom-file-input   @error('masterPlan') is-invalid @enderror" id="masterPlan"
                                                    name="masterPlan" accept="image/*">
                                                <label class="custom-file-label" for="masterPlan">Choose file</label>
                                            </div>
                                            <img id="masterPlanPreview" style="max-width: 100%; height: 100px; display: none;" />
                                            @error('masterPlan')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            
                                            
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-12">
                                        <div class="table-responsive">
                                            <table id="items" class="table table-no-more table-bordered mb-none ">
                                            <thead>
                                                <tr>
                                                    <th colspan="4">
                                                        <label for="gallery">Gallery<small class="text-danger">(1294×600)</small></label>
                                                    </th>
                                                </tr>
                                                <tr style="">
                                                    <th>Image</th>
                                                    <th>Title</th>
                                                    <th>Order</th>
                                                    <th>Action</th>
                                                </tr>
                                                @error('imageGallery')
                                                <tr>
                                                    <th colspan="4">
                                                        @error('gallery')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                        @error('gallery.*')
                                                            <span class="invalid-feedback" role="alert" style="display: block">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                            
                                                    </th>
                                                </tr>
                                                @enderror
                                            </thead>
        
                                            <tr class="item-row" style="border-bottom: solid 1px black">
                                                <td>
                                                    <!--<input type="file" class="form-control @error('name') is-invalid @enderror" placeholder="Enter Installment" name="rows[0][name]" >-->
                                                    
                                                    <div class="custom-file   @error('gallery') is-invalid @enderror">
                                                        <input type="file" class="custom-file-input @error('gallery') is-invalid @enderror" id="gallery" name="gallery[0][file]" accept="image/*" onchange="previewImage(event)">
                                                        <label class="custom-file-label" for="gallery">Choose file</label>
                                                       
                                                    </div>
                                                    <img id="image-preview" src="#" alt="Image Preview" style="display: none;" height="100"/>
                                            
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control @error('key') is-invalid @enderror" placeholder="Enter Title" name="gallery[0][title]" >
                                                </td>
                                                <td>
                                                    <input type="number" min="0" class="form-control @error('value') is-invalid @enderror" placeholder="Enter Order" name="gallery[0][order]" >
                                                </td>
                                               
                                                <td><a class="btn btn-block btn-primary btn-sm addrow updateRow0" href="javascript:;"><i class="fa fa-plus" aria-hidden="true"></i> Add</a>
                                                </td>
                                            </tr>
        
                                        </table>
                                        </div>
                                    </div>
                                    
                                </div>


                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary storeBtn">Submit</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </section>
@endsection
@section('js')
<script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyChuU-X16agmkNHRIw5mqaFTcsMsSlASBs&libraries=places&callback=initialize"
        async defer></script>
    <script>
        function initialize() {
            const locationInputs = document.getElementsByClassName("map-input");
            const autocompletes = [];
            const geocoder = new google.maps.Geocoder;
            for (let i = 0; i < locationInputs.length; i++) {
                const input = locationInputs[i];
                const fieldKey = input.id.replace("-input", "");
                const isEdit = document.getElementById(fieldKey + "-latitude").value != '' && document.getElementById(
                    fieldKey + "-longitude").value != '';
                const latitude = parseFloat(document.getElementById(fieldKey + "-latitude").value) || -33.8688;
                const longitude = parseFloat(document.getElementById(fieldKey + "-longitude").value) || 151.2195;
                const map = new google.maps.Map(document.getElementById(fieldKey + '-map'), {
                    center: {
                        lat: latitude,
                        lng: longitude
                    },
                    zoom: 13
                });
                const marker = new google.maps.Marker({
                    map: map,
                    position: {
                        lat: latitude,
                        lng: longitude
                    },
                });
                marker.setVisible(isEdit);
                const autocomplete = new google.maps.places.Autocomplete(input);
                autocomplete.key = fieldKey;
                autocompletes.push({
                    input: input,
                    map: map,
                    marker: marker,
                    autocomplete: autocomplete
                });
            }

            for (let i = 0; i < autocompletes.length; i++) {
                const input = autocompletes[i].input;
                const autocomplete = autocompletes[i].autocomplete;
                const map = autocompletes[i].map;
                const marker = autocompletes[i].marker;
                google.maps.event.addListener(autocomplete, 'place_changed', function() {
                    marker.setVisible(false);
                    const place = autocomplete.getPlace();
                    geocoder.geocode({
                        'placeId': place.place_id
                    }, function(results, status) {
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

<script type="text/javascript">
    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('image-preview');
            output.src = reader.result;
            output.style.display = 'block';
        };
        reader.readAsDataURL(event.target.files[0]);
    }

    $(document).on('change', '.file-upload', function() {
        var previewId = $(this).data('target');
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#' + previewId).attr('src', e.target.result);
            $('#' + previewId).show();
        };
        reader.readAsDataURL(this.files[0]);
    });


    $(document).ready(function() {
        var i = 1;
        var count = 0;
        $(document).on('click', '.addrow', function() {
            $(this).text('x Remove');
            $(this).attr('class', 'btn btn-danger btn-sm del');
            $(".item-row:last").find('.mybtn').hide();
            i++;
            count++;
            var id = count;
        
            var newRow = '<tr class="item-row" style="border-bottom: solid 1px black">' +
                         '<td class="main_td">' +
                         '  <input type="file" accept="image/*" class="form-control file-upload" data-target="previewImage' + id + '" name="gallery[' + id +'][file]" required>' +
                         '  <img id="previewImage' + id + '" src="#" alt="Image Preview" style="display: none; max-width: 100px; max-height: 100px;" />' +
                         '</td>' +
                         '<td class="main_td"><input type="text" class="form-control" placeholder="Enter Title" name="gallery[' + id +'][title]"></td>' +
                         '<td class="main_td"><input type="number" min="0" class="form-control" placeholder="Enter Order" name="gallery[' + id +'][order]"></td>' +
                         '<td data-title="Action" class="main_td"> <button type="button" class="btn btn-primary btn-sm addrow" id="updateRow' +  id +  '">+ Add</button> ' +
                         '  <a class="Remove mybtn btn btn-danger btn-sm" href="javascript:;" title="Remove row"> x Remove</a>' +
                         '</td>' +
                         '</tr>';
        
            $(".item-row:last").after(newRow);
        });

        $(document).on('click', '.del', function() {
                $(this).parent().parent().remove();
            });
            $(document).on('click', '.Remove', function() {
                $(this).parent().parent().remove();
                $(".del").eq(-1).text('+ Add');
                $('.del').eq(-1).attr('class', 'btn btn-primary btn-sm addrow');
            });
        })
    
    
        document.getElementById('mainImage').addEventListener('change', function(event) {
            showPreview(event, 'mainImagePreview');
        });
        
        document.getElementById('listMainImage').addEventListener('change', function(event) {
            showPreview(event, 'listMainImagePreview');
        });
        document.getElementById('masterPlan').addEventListener('change', function(event) {
            showPreview(event, 'masterPlanPreview');
        });
        
        
        function showPreview(event, previewId) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById(previewId);
                output.src = reader.result;
                output.style.display = 'block'; // Show the image element
            };
            reader.readAsDataURL(event.target.files[0]);
        }

</script>
@endsection
