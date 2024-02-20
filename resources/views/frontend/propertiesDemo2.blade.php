@extends('frontend.layout.master')

@if ($pagemeta)
    @section('title', $pagemeta->meta_title)
    @section('pageDescription', $pagemeta->meta_description)
    @section('pageKeyword', $pagemeta->meta_keywords)
@else
    @section('title', 'Properties | ' . $name)
    @section('pageDescription', $website_description)
    @section('pageKeyword', $website_keyword)
@endif
@section('headcontent')
    <style>
        #dataTable {
            width: 100%;
            height: 80vh;
            padding: 20px;
            overflow-y: auto;
        }

        /* Hide scrollbar for Chrome, Safari and Opera */


        /* Hide scrollbar for IE, Edge and Firefox */

        #dataTable {
            scrollbar-width: thin;
            scrollbar-color: rgba(86, 182, 231, 0.5) transparent;
        }

        #dataTable::-webkit-scrollbar {
            width: 4px;
            height: 4px;
            background: #000;
        }

        #dataTable::-webkit-scrollbar-thumb {
            background-color: #ddd;

            &:hover {
                background-color: #002db6;
            }
        }

        #map {
            width: 100%;
            height: 80vh;
        }

        .property .price {
            color: #e93636;
            font-size: 16px;
            font-weight: bold;
        }

        .price-tag {
            background-color: #e93636;
            color: #fff;
            padding: 5px 8px;
            border-radius: 5px;
            position: relative;
        }

        .price-tag::after {
            border-left: 5px solid transparent;
            border-right: 5px solid transparent;
            border-top: 5px solid #e93636;
            content: "";
            height: 0;
            left: 50%;
            position: absolute;
            top: 95%;
            transform: translate(-50%, 0);
            transition: all 0.3s ease-out;
            width: 0;
            z-index: 1;
        }

        .property .details {
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        .property .address {
            color: #000;
            font-size: 12px;
        }

        .propertyCardNew .features {
            align-items: flex-end;
            display: flex;
            flex-direction: row;
            gap: 10px;
        }

        .propertyCardNew .features>div {
            align-items: center;
            background: #F5F5F5;
            border-radius: 5px;
            color: #000;
            border: 1px solid #ccc;
            display: flex;
            font-size: 10px;
            gap: 5px;
            padding: 5px;
        }

        /*
                               * Property styles in highlighted state.
                               */
        .property.highlight {
            background-color: #FFFFFF;
            border-radius: 8px;
            box-shadow: 10px 10px 5px rgba(0, 0, 0, 0.2);
            height: 100%;
            width: auto;
        }

        .property.highlight::after {
            border-top: 9px solid #FFFFFF;
        }

        .property.highlight .details {
            display: flex;
        }

        .property.highlight .priceIcon {
            display: none;
        }

        .propMapIMage {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }


        .propertyCardNew .bed {
            color: #FFA000;
        }

        .propertyCardNew .bath {
            color: #03A9F4;
        }

        .propertyCardNew .size {
            color: #388E3C;
        }
    </style>
@endsection
@section('content')


    {{-- Projects  --}}

    <section class="">
        <div class="container-fluid px-0">
            <div class="row g-0">
                <div class="col-12 col-lg-12 col-md-12">
                    <div class="row g-0">
                        <div class="col-12 col-lg-12 col-md-12">
                            <div class="p-3 shadow-sm">
                                <form action="">
                                    <div class="row">
                                         <div class="col">
                                            <input id="pac-input" class="controls form-control" type="text"
                                                placeholder="Search Place" />
                                        </div>
                                        {{-- <div class="col">
                                            <select name="community" id="community" class="form-select bedroomSelect">
                                                <option value="">Select community</option>
                                                @foreach ($community as $comm)
                                                    <option value="{{ $comm->id }}">{{ $comm->name }}</option>
                                                @endforeach
                                            </select>
                                        </div> --}}
                                        <div class="col">
                                            <select name="category" id="category" class="form-select bedroomSelect" {{ Request::is('rent') || Request::is('ready') ? 'disabled' : '' }}>
                                                <option value="">Select Category</option>
                                                @foreach ($category as $cat)
                                                    <option value="{{ $cat->id }}" {{ Request::is('rent') ? ($cat->id == 1 ? 'selected' : '') : (Request::is('ready') ? ($cat->id == 2 ? 'selected' : '') : '') }}>{{ $cat->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col">
                                            <select name="accomodation" id="accomodation" class="form-select bedroomSelect">
                                                <option value="">Select Accomodation</option>
                                                @foreach ($accomodation as $accom)
                                                    <option value="{{ $accom->id }}">{{ $accom->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col">
                                            <select name="bedrooms" id="bedrooms" class="form-select bedroomSelect">
                                                <option value="">Bedrooms</option>
                                                @foreach ($bedrooms as $bed)
                                                    @if($bed->bedrooms !=0)
                                                    <option value="{{ $bed->bedrooms }}">{{ $bed->bedrooms }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col">
                                            <div class="dropdown">
                                                <div class="form-select" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
                                                 Price
                                                </div>
                                                <div class="dropdown-menu p-4">
                                                  <div class="mb-3">
                                                    <label for="minprice" class="form-label">Minimum Price</label>
                                                    <input type="text" class="form-control" id="minprice" placeholder="0">
                                                  </div>
                                                  <div class="mb-3">
                                                    <label for="maxprice" class="form-label">Maximum Price</label>
                                                    <input type="text" class="form-control" id="maxprice" placeholder="Any Price">
                                                  </div>
                                                </div>
                                              </div>
                                        </div>
                                    </div>

                                </form>
                            </div>
                        </div>
                        <div class="col-12 col-lg-7 col-md-7">
                            <div>
                                <div id="map"></div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-5 col-md-5">
                            <div>
                                <div id="dataTable">
                                    <div>
                                        <h5>Real Estate & Homes For Sale</h5>
                                    </div>
                                    <div id="PropertyResult">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call the JavaScript page -->
    <script type="text/javascript">
        // Create global variables for map, markers, etc
        var map;
        var Data = [];
        var viewportMarkers = [];
        var infoWindow;
        var handle1, handle2;
        var markerCount = 0;
        let airports = [];
        // Function to initialize the map and set values
        async function initMap() {
            var lat = 25.2048;
            var lng = 55.2708;
            var iniZoom = 10;
            var myLatLng = new google.maps.LatLng(lat, lng);

            var options = {
                zoom: iniZoom,
                center: myLatLng,
                mapId: "4504f8b37365c3d0",
                gestureHandling: 'greedy',
                mapTypeId: google.maps.MapTypeId.ROADMAP
            }
            const {
                AdvancedMarkerElement
            } = await google.maps.importLibrary("marker");
            map = new google.maps.Map(document.getElementById('map'), options);
            const input = document.getElementById("pac-input");
            const searchBox = new google.maps.places.SearchBox(input);
            map.addListener("bounds_changed", () => {
                searchBox.setBounds(map.getBounds());
            });
            let markers = [];
            // more details for that place.
            searchBox.addListener("places_changed", () => {
                const places = searchBox.getPlaces();

                if (places.length == 0) {
                    return;
                }

                // Clear out the old markers.
                markers.forEach((marker) => {
                    marker.setMap(null);
                });
                markers = [];

                // For each place, get the icon, name and location.
                const bounds = new google.maps.LatLngBounds();

                places.forEach((place) => {
                    if (!place.geometry || !place.geometry.location) {
                        console.log("Returned place contains no geometry");
                        return;
                    }


                    // Create a marker for each place.
                    markers.push(
                        new google.maps.Marker({
                            map,
                            title: place.name,
                            position: place.geometry.location,
                        }),
                    );
                    if (place.geometry.viewport) {
                        // Only geocodes have viewport.
                        bounds.union(place.geometry.viewport);
                    } else {
                        bounds.extend(place.geometry.location);
                    }
                });
                map.fitBounds(bounds);
            });
            // Add event listeners for when the map has changed: drag, zoom in/out or page refreshed.
            google.maps.event.addListener(map, 'dragend', function() {
                showMarkersInViewport()
            });
            google.maps.event.addListener(map, 'idle', function() {
                showMarkersInViewport()
            });
        }
        

        // Create a function to add markers to the map.
        function showMarkersInViewport() {
            if (viewportMarkers != null) {
                for (i = 0; i < viewportMarkers.length; i++) {
                    viewportMarkers[i].setMap(null);
                }
                viewportMarkers = [];

                // close any open infoWindows when the map is clicked
                google.maps.event.addListener(map, "click", function() {
                    infoWindow.close();
                });

                // Set the results box row color back to normal when the map is clicked
                google.maps.event.addListener(map, 'click', function selectDataRow(code) {
                    var table = document.getElementById("tbl");
                    for (var i = 1, row; row = table.rows[i]; i++) {
                        row.style.backgroundColor = "#ffffff";
                    }
                });
            }

            // Create a table to hold the info of the markers on the map.
            var divTable =
                '<div id="tbl" class="row g-3">'
            var divText =
                '<span style="background-color:#000000;font-family: Trebuchet MS; font-size:12pt; color: red;"><b>List of airports in range</b><br/><br/></span>';

            // call the getAirports() function to retrieve the locations (airports) in the current viewport.
            var airportsInViewport = getAirports(map.getBounds());

            if (airportsInViewport == "") {
                divTable += '<p style="color:black">No Matching Property Found</p>';
            } else {
                divTable += '<div class="col-12 col-lg-12 col-md-12"><p class="text-primary mb-0">' + airportsInViewport
                    .length + ' results found</p></div>';
            }
            // Next, iterate through the elements in the airports array for each airport in the viewport:
            //   1. Create a marker and place it on the map
            //   2. Add an event listener to the click event of the marker to display an InfoWindow with data about the selected airport
            //   3. Add a new row to the table beside the map that summarizes the airports on the map

            for (i = 0; i < airportsInViewport.length; i++) {

                // create a new marker

                var marker = new google.maps.marker.AdvancedMarkerElement({
                    position: new google.maps.LatLng(airportsInViewport[i].address_latitude, airportsInViewport[i].address_longitude),
                    content: priceTag(airportsInViewport[i].price),
                    title: airportsInViewport[i].name + ' in ' + airportsInViewport[i].address
                });

                // "marker.objInfo sets its objInfo property to the airport's description. This object will populate the InfoWindow with the airport's data
                marker.objInfo =

                    '<div class="card propertyCardNew rounded-0">' +
                    '<div>' +
                    '<div class="">' +
                    '<a href="{{route('singleProject')}}" class="text-decoration-none">' +
                    '<div class="projectImgCont">' +
                    '<img src="' + airportsInViewport[i].property_banner + '" alt="project1" class="img-fluid propImg">' +
                    '<div class="projectImgOverlay">' +
                    '<div><span class="badge float-start fs-10 projectType">' + airportsInViewport[i].category.name +
                    '</span></div>' +
                    '<div><span class="badge float-start fs-10 projectType">' + airportsInViewport[i].accommodations.name +
                    '</span></div>' +
                    '</div>' +
                    '</div>' +
                    '</a>' +
                    '</div>' +
                    '<div class="card-body px-0 py-1 rounded-3 rounded-top-0">' +
                    '<a href="{{route('singleProject')}}" class="text-decoration-none">' +
                    '<h6 class="text-black fs-16 fw-semibold mb-0">' + airportsInViewport[i].name + '</h6>' +
                    '</a>' +
                    '<div class="mb-1">' +
                    '<small class="text-secondary">' + airportsInViewport[i].communities.name + '</small>' +
                    '</div>' +
                    '<p class="fs-18 mb-2 text-primary fw-semibold">AED ' + airportsInViewport[i].price.toLocaleString() + '</p>' +
                    '<div class="features">' +
                    '<div>' +
                    '<i aria-hidden="true" class="fa fa-bed fa-lg bed" title="bedroom"></i>' +
                    '<span class="fa-sr-only"></span>' +
                    '<span>' + airportsInViewport[i].bedrooms + '</span>' +
                    '</div>' +
                    '<div>' +
                    '<i aria-hidden="true" class="fa fa-bath fa-lg bath" title="bathroom"></i>' +
                    '<span class="fa-sr-only"></span>' +
                    '<span>' + airportsInViewport[i].bathrooms + '</span>' +
                    '</div>' +
                    '<div>' +
                    '<i aria-hidden="true" class="fa fa-map-o fa-lg size" title="size"></i>' +
                    '<span class="fa-sr-only">size</span>' +
                    '<span>' + airportsInViewport[i].area + ' ft<sup>2</sup></span>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>';

                // add the click event's listener for each marker to open the info-Window
                (function(index, selectedMarker) {
                    google.maps.event.addListener(selectedMarker, 'click', function() {
                        if (infoWindow != null) infoWindow.setMap(null);
                        infoWindow = new google.maps.InfoWindow();
                        infoWindow.setContent(selectedMarker.objInfo);
                        infoWindow.open(map, selectedMarker);
                        selectDataRow(airportsInViewport[index].Code)
                    });
                })(i, marker)

                // place the marker on the map
                marker.setMap(map);
                // add the marker to the viewportMarkers array
                viewportMarkers.push(marker);
                // Create a new row entry for the table corresponding to the current airport
                var currentIndex = viewportMarkers.length - 1;
                // The linkText variable holds the airport name and the text of the hyperlink (column Airport)
                var linkText = 'AIRPORT: ' +
                    airportsInViewport[i].Name +
                    '&nbsp;&nbsp;(' + airportsInViewport[i].Code + ')' +
                    '<br/>';
                // The linkInfo variable holds the remaining columns of the row
                var linkInfo = 'CITY: ' +
                    airportsInViewport[i].City +
                    '<br/>COUNTRY: ' +
                    airportsInViewport[i].Country;
                // Add the hyperlink to bounce the appropriate marker and open its infoWindow
                divText += '<a href="javascript:highlightMarker(' + currentIndex + ')">' +
                    '<b>' +
                    linkText +
                    '</b>' +
                    '</a>' +
                    linkInfo +
                    '<br/><br/>';
                // Finally, add the <tr> element for the row
                divTable +=
                    '<div class="col-12 col-lg-6 col-md-4"> ' +
                    '<div class="card propertyCardNew rounded-0">' +
                    '<div class="">' +
                    '<a href="{{route('singleProject')}}" class="text-decoration-none">' +
                    '<div class="projectImgCont">' +
                    '<img src="' + airportsInViewport[i].property_banner + '" alt="project1" class="img-fluid propImg">' +
                    '<div class="projectImgOverlay">' +
                    '<div><span class="badge float-start fs-10 projectType">' + airportsInViewport[i].category.name +
                    '</span></div>' +
                    '<div><span class="badge float-start fs-10 projectType">' + airportsInViewport[i].accommodations.name +
                    '</span></div>' +
                    '</div>' +
                    '</div>' +
                    '</a>' +
                    '</div>' +
                    '<div class="card-body rounded-3 rounded-top-0">' +
                    '<a href="{{route('singleProject')}}" class="text-decoration-none">' +
                    '<h6 class="text-black fs-16 fw-semibold mb-0">' + airportsInViewport[i].name + '</h6>' +
                    '</a>' +
                    '<div class="mb-1">' +
                    '<small class="text-secondary">' + airportsInViewport[i].communities.name + '</small>' +
                    '</div>' +
                    '<p class="fs-18 mb-2 text-primary fw-semibold">AED ' + airportsInViewport[i].price.toLocaleString() + '</p>' +
                    '<div class="features">' +
                    '<div>' +
                    '<i aria-hidden="true" class="fa fa-bed fa-lg bed" title="bedroom"></i>' +
                    '<span class="fa-sr-only"></span>' +
                    '<span>' + airportsInViewport[i].bedrooms + '</span>' +
                    '</div>' +
                    '<div>' +
                    '<i aria-hidden="true" class="fa fa-bath fa-lg bath" title="bathroom"></i>' +
                    '<span class="fa-sr-only"></span>' +
                    '<span>' + airportsInViewport[i].bathrooms + '</span>' +
                    '</div>' +
                    '<div>' +
                    '<i aria-hidden="true" class="fa fa-map-o fa-lg size" title="size"></i>' +
                    '<span class="fa-sr-only"></span>' +
                    '<span>' + airportsInViewport[i].area + ' ft<sup>2</sup></span>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>'

                markerCount++;
            }
            divTable += '</div>'
            document.getElementById('PropertyResult').innerHTML = divTable;
        }

        // Function to change the row background color when the corresponding marker is clicked.
        function selectDataRow(code) {
            var table = document.getElementById("tbl");
            for (var i = 1, row; row = table.rows[i]; i++) {
                row.style.backgroundColor = "#ffffff";
            }
            document.getElementById(code).parentNode.style.backgroundColor = '#66CD00'; // sets the row color to red.
        }

        // The following function returns all airports in the current viewport in the selected array
        // The "a" argument is a LatLngBounds object with the viewport's bounds. It doesn't have to be "a", you can
        // call it anything. Example "abc", "xyz", etc. you just can't use numbers like "1" or "123".
        function getAirports(a) {
            if (a == null || a == undefined) return null;
            var selected = [];
            for (i = 0; i < airports.length; i++) {
                if (a.contains(new google.maps.LatLng(airports[i].address_latitude, airports[i].address_longitude))) {
                    selected.push(airports[i]);
                }
            }
            return selected;
        }

        function priceTag(property) {
            const content1 = document.createElement("div");
            content1.classList.add("price-tag");
            content1.innerHTML = `<div>${kFormatter(property)}</div>`;
            return content1;

        }
        //  The highlightMarker() function opens the InfoWindow object that corresponds
        //  to the marker in the viewportMarkers array

        function highlightMarker(index) {
            if (infoWindow != null) infoWindow.setMap(null);
            infoWindow = new google.maps.InfoWindow();
            infoWindow.setContent(viewportMarkers[index].objInfo);
            infoWindow.open(map, viewportMarkers[index]);

            // Bounce the marker for two seconds when the corresonding link is clicked in the result box.
            viewportMarkers[index].setAnimation(google.maps.Animation.BOUNCE);
            setTimeout(function() {
                viewportMarkers[index].setAnimation(null);
            }, 1250); // This sets the time for the bounce. You can make it longer or shorter.
        }
        // Pan the map to center the marker when the "CODE" link is clicked in the results box.
        function zoomMarker(index) {
            map.panTo(viewportMarkers[index].getPosition());

        }
        <?php
        $apiURL = 'http://xml.propspace.com/feed/xml.php?cl=1982&pid=8245&acc=8807';
        $xmlString = file_get_contents($apiURL);
        $phpArray = simplexml_load_string($xmlString, 'SimpleXMLElement', LIBXML_NOCDATA);
        $xmlVal = json_encode($phpArray, true);
        ?>
        var dataXml = <?php echo json_encode($properties); ?>;
        // Create an array of markers
        // This can be moved to a seperate js page for simplicity.
        airports = JSON.parse(dataXml);

        function kFormatter(n) {
            if (n < 1e3) return n;
            if (n >= 1e3 && n < 1e6)
                return +(n / 1e3).toFixed(1) + "K";
            if (n >= 1e6 && n < 1e9)
                return +(n / 1e6).toFixed(1) + "M";
            if (n >= 1e9 && n < 1e12)
                return +(n / 1e9).toFixed(1) + "B";
            if (n >= 1e12) return +(n / 1e12).toFixed(1) + "T";
        }
    </script>
    <!-- Call the API key. As a curtesy to the developer, please change this API key to one of your own. Thank you. -->
    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAGZjmTZFO0V8_-_V_A-Dqto1I-FlBhshE&libraries=places&callback=initMap"
        async defer></script>
    <script>
        $('#accomodation').on('change', function() {

            var acc = $(this).val();
            var cat = $("#category").val();
            var community = $("#community").val();
            var bedrooms = $("#bedrooms").val();
            var maxprice = $("#maxprice").val();
            var minprice = $("#minprice").val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "properties-demo",
                type: "POST",
                dataType: 'json',

                data: {
                    acc: acc,
                    community: community,
                    bedrooms: bedrooms,
                    minprice: minprice,
                    maxprice: maxprice,
                    cat: cat
                },
                success: function(response) {
                    airports = JSON.parse(response.html);
                    initMap();
                }
            })
        });
        $('#community').on('change', function() {

            var community = $(this).val();
            var acc = $("#accomodation").val();
            var cat = $("#category").val();
            var bedrooms = $("#bedrooms").val();
            var maxprice = $("#maxprice").val();
            var minprice = $("#minprice").val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "properties-demo",
                type: "POST",
                dataType: 'json',

                data: {
                    acc: acc,
                    cat: cat,
                    community: community,
                    bedrooms: bedrooms,
                    minprice: minprice,
                    maxprice: maxprice
                },
                success: function(response) {
                    airports = JSON.parse(response.html);
                    initMap();
                }
            })
        });
        $('#category').on('change', function() {

            var cat = $(this).val();
            var acc = $("#accomodation").val();
            var community = $("#community").val();
            var bedrooms = $("#bedrooms").val();
            var maxprice = $("#maxprice").val();
            var minprice = $("#minprice").val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "properties-demo",
                type: "POST",
                dataType: 'json',

                data: {
                    acc: acc,
                    cat: cat,
                    community: community,
                    bedrooms: bedrooms,
                    minprice: minprice,
                    maxprice: maxprice
                },
                success: function(response) {
                    airports = JSON.parse(response.html);
                    initMap();
                }
            })
        });
        $('#bedrooms').on('change', function() {

            var bedrooms = $(this).val();
            var acc = $("#accomodation").val();
            var community = $("#community").val();
            var cat = $("#category").val();
            var maxprice = $("#maxprice").val();
            var minprice = $("#minprice").val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "properties-demo",
                type: "POST",
                dataType: 'json',

                data: {
                    acc: acc,
                    cat: cat,
                    bedrooms: bedrooms,
                    minprice: minprice,
                    maxprice: maxprice,
                    community: community
                },
                success: function(response) {
                    airports = JSON.parse(response.html);
                    initMap();
                }
            })
        });
        $('#maxprice').on('keyup', function() {

            var maxprice = $(this).val();
            var acc = $("#accomodation").val();
            var community = $("#community").val();
            var bedrooms = $("#bedrooms").val();
            var minprice = $("#minprice").val();
            var cat = $("#category").val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "properties-demo",
                type: "POST",
                dataType: 'json',

                data: {
                    acc: acc,
                    cat: cat,
                    bedrooms: bedrooms,
                    minprice: minprice,
                    maxprice: maxprice,
                    community: community
                },
                success: function(response) {
                    airports = JSON.parse(response.html);
                    initMap();
                }
            })
        });
        $('#minprice').on('keyup', function() {

            var minprice = $(this).val();
            var acc = $("#accomodation").val();
            var community = $("#community").val();
            var bedrooms = $("#bedrooms").val();
            var maxprice = $("#maxprice").val();
            var cat = $("#category").val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "properties-demo",
                type: "POST",
                dataType: 'json',

                data: {
                    acc: acc,
                    cat: cat,
                    bedrooms: bedrooms,
                    minprice: minprice,
                    maxprice: maxprice,
                    community: community
                },
                success: function(response) {
                    airports = JSON.parse(response.html);
                    initMap();
                }
            })
        });
    </script>
@endsection
