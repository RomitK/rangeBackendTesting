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
                        <li class="breadcrumb-item active">Edit Community</li>
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
                            <h3 class="card-title">Edit Community Form</h3>
                        </div>
                        
                         @php 
                                                    $total_gallery_row_count = count($community->imageGallery);
                                                    $count = 0;
                                                @endphp
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form class="form-boder" id="storeForm" method="POST" 
                            action="{{ route('dashboard.communities.update', $community->id) }}"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="name">Name</label>
                                            <input type="text" value="{{ $community->name }}"
                                                class="form-control @error('name') is-invalid @enderror" id="name"
                                                placeholder="Enter Name" name="name" required>
                                            @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label for="is_approved">Approval Status</label>
                                             @if(in_array(Auth::user()->role, config('constants.isAdmin')))
                                            <select class="form-control @error('is_approved') is-invalid @enderror" id="is_approved" name="is_approved">
                                                @foreach (config('constants.approvedRejected') as $key=>$value)
                                                <option value="{{ $key }}" @if($key === $community->is_approved) selected @endif>{{ $value }}</option>
                                                @endforeach
                                            </select>
                                            @elseif(!in_array(Auth::user()->role, config('constants.isAdmin')))
                                            <select class="form-control @error('is_approved') is-invalid @enderror" id="is_approved" name="is_approved">
                                                @foreach (config('constants.approvedRequested') as $key=>$value)
                                                <option value="{{ $key }}" @if($key === $community->is_approved) selected @endif>{{ $value }}</option>
                                                @endforeach
                                            </select>
                                            @endif
                                            @error('is_approved')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label for="emirates">Select Emirate</label>
                                            <select class="form-control select1 @error('emirates') is-invalid @enderror"
                                                id="emirates" name="emirates" required>
                                                @foreach (config('constants.emirates') as $key => $value)
                                                    <option value="{{ $value }}"
                                                        @if ($community->emirates == $value) selected @endif>
                                                        {{ $value }}</option>
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
                                            <select class="form-control select1 @error('status') is-invalid @enderror"
                                                id="status" name="status" required>
                                                @foreach (config('constants.statuses') as $key => $value)
                                                    <option value="{{ $key }}"
                                                        @if ($community->status == $key) selected @endif>
                                                        {{ $value }}</option>
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
                                                <option value="1" @if ($community->display_on_home == 1) selected @endif>Yes</option>
                                                <option value="0" @if ($community->display_on_home == 0) selected @endif>No</option>
                                            </select>
                                            @error('display_on_home')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-1">
                                        <div class="form-group">
                                            <label for="communityOrder"> Order</label>
                                            <input type="number" value="{{ $community->communityOrder }}"
                                                class="form-control @error('communityOrder') is-invalid @enderror" id="communityOrder"
                                                placeholder="Enter Order" name="communityOrder">
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
                                            <select multiple="multiple" data-placeholder="Select Developers"
                                                style="width: 100%;"
                                                class="select2 form-control @error('developerIds') is-invalid @enderror"
                                                id="developerIds" name="developerIds[]">
                                                @foreach ($developers as $developer)
                                                    <option value="{{ $developer->id }}"
                                                        @if (in_array($developer->id, $community->communityDevelopers->pluck('id')->toArray())) selected @endif>
                                                        {{ $developer->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('developerIds')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                   
                                    @php
                                        // Create an associative array for easy lookup
                                        $amenitiesMap = [];
                                        foreach ($amenities as $amenity) {
                                            $amenitiesMap[$amenity->id] = $amenity;
                                        }
                                    @endphp
                                    
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="amenities">Amenities<small class="text-danger">(At least 8 Amenities )</small></label>
                                            <select multiple="multiple" data-placeholder="Select Amenities" style="width: 100%;" class="select2 form-control @error('amenityIds') is-invalid @enderror" id="amenities" name="amenityIds[]">
                                               
                                                @foreach ($community->amenities->pluck('id')->toArray() as $id)
                                                    @if (isset($amenitiesMap[$id]))
                                                        <option value="{{ $id }}" selected>{{ $amenitiesMap[$id]->name }}</option>
                                                    @endif
                                                @endforeach
                                                @foreach ($amenities as $amenity)
                                                    @if (!in_array($amenity->id, $community->amenities->pluck('id')->toArray()))
                                                        <option value="{{ $amenity->id }}">{{ $amenity->name }}</option>
                                                    @endif
                                                @endforeach
                                                
                                            </select>
                                            @error('amenityIds')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    @php
                                        // Create an associative array for easy lookup
                                        $highlightsMap = [];
                                        foreach ($highlights as $highlight) {
                                            $highlightsMap[$highlight->id] = $highlight;
                                        }
                                    @endphp

                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            
                                            <label for="highlights">Highlights<small class="text-danger">(At least 4 Highlights )</small></label>
                                            <select multiple="multiple" data-placeholder="Select Highlights" style="width: 100%;" class="select2 form-control @error('highlightIds') is-invalid @enderror" id="highlights" name="highlightIds[]">
                                                @foreach ($community->highlights->pluck('id')->toArray() as $id)
                                                    @if (isset($highlightsMap[$id]))
                                                        <option value="{{ $id }}" selected>{{ $highlightsMap[$id]->name }}</option>
                                                    @endif
                                                @endforeach
                                                @foreach ($highlights as $highlight)
                                                    @if (!in_array($highlight->id, $community->highlights->pluck('id')->toArray()))
                                                        <option value="{{ $highlight->id }}">{{ $highlight->name }}</option>
                                                    @endif
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
                                            <textarea id="shortDescription" class=" form-control @error('shortDescription') is-invalid @enderror"
                                                name="shortDescription">{{ $community->shortDescription }}</textarea>
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
                                            <textarea id="description" class="summernote form-control @error('description') is-invalid @enderror"
                                                name="description">{{ $community->description }}</textarea>
                                            @error('description')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                  
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="address">Address</label>
                                            <input type="text" value="{{ $community->address }}" id="address-input" name="address" class="form-control map-input">
                                            <input type="hidden" name="address_latitude" id="address-latitude" value="{{  $community->address_latitude }}" />
                                            <input type="hidden" name="address_longitude" id="address-longitude" value="{{  $community->address_longitude }}" />

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
                                            <input type="text" value="{{ $community->meta_title }}"
                                                class="form-control @error('meta_title') is-invalid @enderror"
                                                id="meta_title" placeholder="Enter Meta Title" name="meta_title">
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
                                            <input type="text" value="{{ $community->meta_keywords }}"
                                                class="form-control @error('meta_keywords') is-invalid @enderror"
                                                id="meta_keywords" placeholder="Enter Meta Keywords"
                                                name="meta_keywords">
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
                                                placeholder="Enter Meta Description" name="meta_description">{{ $community->meta_description }}</textarea>
                                            @error('meta_description')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="mainImage">Main Image  <small class="text-danger">(400x400)</small></label>
                                            <div class="custom-file  @error('mainImage') is-invalid @enderror">
                                                <input type="file"
                                                    class="custom-file-input  @error('mainImage') is-invalid @enderror"
                                                    id="mainImage" name="mainImage" accept="image/*">
                                                <label class="custom-file-label" for="mainImage">Choose file</label>
                                            </div>
                                            <img id="mainImagePreview" style="max-width: 100%; height: 100px; display: none;" />
                                            @error('mainImage')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                           
                                        </div>
                                        @if ($community->banner_image)
                                            <img src="{{ $community->banner_image }}" alt="{{ $community->banner_image }}"  height="200">
                                        @endif
                                    </div>
                                    
                                    
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="clusterPlan">Master Plan <small class="text-danger">(Prefer Dimension 600X1000)</small></label>
                                            <div class="custom-file   @error('clusterPlan') is-invalid @enderror">
                                                <input type="file" class="custom-file-input   @error('clusterPlan') is-invalid @enderror" id="clusterPlan"
                                                    name="clusterPlan" accept="image/*">
                                                <label class="custom-file-label" for="clusterPlan">Choose file</label>
                                            </div>
                                            <img id="clusterPlanPreview" style="max-width: 100%; height: 100px; display: none;" />
                                            @error('clusterPlan')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            
                                            @if($community->clusterPlan)
                                            <img src="{{ $community->clusterPlan }}" alt="{{ $community->clusterPlan }}" width="" height="200">
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-12">
                                        
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
                                                @error('gallery')
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
                                            
                                               
                                               
                                                @if (count($community->imageGallery) > 0)
                                                    @foreach ($community->imageGallery as $gallery)
                                                        
                                                        <tr class="item-row">
                                                            <td>
                                                                <input type="hidden" name="gallery[{{ $count }}][old_gallery_id]" value="{{ $gallery['id'] }}">
                                                                <img src="{{ $gallery['path'] }}" alt="{{ $gallery['path'] }}" width="" height="100" style="padding: 10px">
                                                            </td>
                                                            <td>
                                                                <input type="text" value="{{ $gallery['title'] }}" class="form-control" placeholder="Enter Title" name="gallery[{{ $count }}][title]">
                                                            </td>
                                                            <td>
                                                                <input type="number" min="0" value="{{ $gallery['order'] }}" class="form-control" placeholder="Enter Order" name="gallery[{{ $count }}][order]" >
                                                            </td>
                                                           
                                                            <td>
                                                                
                                                                <a class="btn btn-sm btn-danger" id="fix_delete"  onclick="deleteGallery({{ $gallery['id'] }})" title="Remove row">X Remove</a>
                                                                
                                                            </td>
                                                        </tr>
                                                        <script>
                                                            var name = '<?php echo $gallery['id']; ?>';
                                                            
                                                        </script>
            
                                                        <?php $count = $count + 1; ?>
                                                    
                                                    @endforeach
                                                    
                                                    
                                                @else
                                                    <tr class="item-row" style="border-bottom: solid 1px black">
                                                        <td>
                                                            <input type="hidden" name="gallery[{{ $count }}][old_gallery_id]" value="0">
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
                                                       
                                                        <td>
                                                        </td>
                                                    </tr>
                                                @endif
                                               <tr id="hiderow">
                                                    <td colspan="16">
                                                        <a id="addrow" href="javascript:;"class="btn btn-info btn-sm" title="Add a row">Add New</a>
                                                    </td>
                                                </tr>
                                        </table>
                                        
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
@section('js')
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
            var count = <?php echo $total_gallery_row_count; ?> + 1;
            $("#addrow").click(function() {
                console.log('lll')
                i++;
                count++;
                var id = count;
                $(".item-row:last").after('<tr class="item-row" style="border-bottom: solid 1px black">' +
                    '<td class="main_td">' +
                        '<input type="file" accept="image/*" class="form-control file-upload" data-target="previewImage' + id + '" name="gallery[' + id +'][file]" required>' +
                        '<img id="previewImage' + id + '" src="#" alt="Image Preview" style="display: none; max-width: 100px; max-height: 100px;" />' +
                    '</td>' +
                    '<td class="main_td"><input type="text" class="form-control" placeholder="Enter Title" name="gallery[' + id +'][title]"></td>' +
                    '<td class="main_td"><input type="number" min="0" class="form-control" placeholder="Enter Order" name="gallery[' + id +'][order]"></td>' +
                    '<td data-title="Action" class="main_td">' +
                        '<a class="Remove mybtn btn btn-danger btn-sm" href="javascript:;"  title="Remove row"> x Remove</a>' +'</td><input type="hidden"  name="gallery[' + id +
                        '][old_gallery_id]" value="0"></tr>');
                if ($(".delete").length > 0) $(".delete").show();
            });

            $(document).on('click', '.delete', function() {
                $(this).parents('.item-row').remove();
                if ($(".delete").length < 1) $(".delete").hide();
    
            });
             $(document).on('click', '.Remove', function() {
                    $(this).parent().parent().remove();
                    $(".del").eq(-1).text('+ Add');
                    $('.del').eq(-1).attr('class', 'btn btn-primary btn-sm addrow');
            });
    
        });
        function deleteGallery(id) {
            var communityId = {{$community->id}};
            if (confirm('This Data is saved ! Are you sure you want to delete this?')) {
                $.ajax({
                    
                    url: '{{ url('/') }}/dashboard/communities/' + communityId+'/media/'+id,
                    data: {
                        "_token": "{{ csrf_token() }}"
                    },
                    type: 'GET',
                    success: function(result) {
                        console.log(result);
                        location.reload();

                    }
                });
            }
        }
        
        document.getElementById('mainImage').addEventListener('change', function(event) {
            showPreview(event, 'mainImagePreview');
        });
        
        document.getElementById('listMainImage').addEventListener('change', function(event) {
            showPreview(event, 'listMainImagePreview');
        });
        document.getElementById('clusterPlan').addEventListener('change', function(event) {
            showPreview(event, 'clusterPlanPreview');
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
