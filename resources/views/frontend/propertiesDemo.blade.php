<!doctype html>

<html>

<head>
    <title>Advanced Markers with HTML</title>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <script src="https://use.fontawesome.com/releases/v6.2.0/js/all.js"></script>

    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/assets/style.css') }}" />
    <script type="module">
        async function initMap() {
            // Request needed libraries.
            const {
                Map
            } = await google.maps.importLibrary("maps");
            const {
                AdvancedMarkerElement
            } = await google.maps.importLibrary("marker");
            const {
                LatLng
            } = await google.maps.importLibrary("core");
            const center = new LatLng(25.2048, 55.2708);
            const map = new Map(document.getElementById("map"), {
                zoom: 14,
                center,
                mapId: "4504f8b37365c3d0",
            });
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


        const infoWindow = new google.maps.InfoWindow;
        for (const property of properties) {

            const latLng = new google.maps.LatLng(property.Latitude, property.Longitude);

            const AdvancedMarkerElement = new google.maps.marker.AdvancedMarkerElement({
                map,
                position: latLng,
                content: priceTag(property),
                title: property.Community,
            });

            AdvancedMarkerElement.addListener("click", () => {
                infoWindow.setContent(buildContent(property));

                infoWindow.open({
                    anchor: AdvancedMarkerElement,
                    map,
                });

            });
        }
        }

        function priceTag(property) {
            const content1 = document.createElement("div");
            content1.classList.add("price-tag");
            content1.innerHTML = `<div>${kFormatter(property.Price)}</div>`;
            return content1;

        }


        function buildContent(property) {
            const content = document.createElement("div");

            content.classList.add("property");
            content.innerHTML = `

      <div class="details">
        <div> <img src="${property.Images.image['0']}" class="img-fluid propMapIMage" /> </div>
          <div class="price">AED ${property.Price.toLocaleString()}</div>
          <div class="address">${property.Property_Name}</div>
          <div class="features">
          <div>
              <i aria-hidden="true" class="fa fa-bed fa-lg bed" title="bedroom"></i>
              <span class="fa-sr-only"></span>
              <span>${property.No_of_Rooms}</span>
          </div>
          <div>
              <i aria-hidden="true" class="fa fa-bath fa-lg bath" title="bathroom"></i>
              <span class="fa-sr-only"></span>
              <span>${property.No_of_Bathroom}</span>
          </div>
          <div>
              <i aria-hidden="true" class="fa fa-ruler fa-lg size" title="size"></i>
              <span class="fa-sr-only">size</span>
              <span>${property.Unit_Builtup_Area} ft<sup>2</sup></span>
          </div>
          </div>
      </div>
      `;
            return content;
        }
        <?php
        $apiURL = 'http://xml.propspace.com/feed/xml.php?cl=1982&pid=8245&acc=8807';
        $xmlString = file_get_contents($apiURL);
        $phpArray = simplexml_load_string($xmlString, 'SimpleXMLElement', LIBXML_NOCDATA);
        $xmlVal = json_encode($phpArray, true);

        ?>
        var data = <?php echo json_encode($xmlVal); ?>;
        console.log(JSON.parse(data)["Listing"]);
        const properties = JSON.parse(data)["Listing"];

        initMap();
        // [END maps_advanced_markers_html]
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
</head>

<body>
    <input id="pac-input" class="controls" type="text" placeholder="Search Place" style="width: 50%;padding:10px 5px;" />
    <div id="map"></div>

    <!-- prettier-ignore -->
    <script>(g=>{var h,a,k,p="The Google Maps JavaScript API",c="google",l="importLibrary",q="__ib__",m=document,b=window;b=b[c]||(b[c]={});var d=b.maps||(b.maps={}),r=new Set,e=new URLSearchParams,u=()=>h||(h=new Promise(async(f,n)=>{await (a=m.createElement("script"));e.set("libraries",[...r]+"");for(k in g)e.set(k.replace(/[A-Z]/g,t=>"_"+t[0].toLowerCase()),g[k]);e.set("callback",c+".maps."+q);a.src=`https://maps.${c}apis.com/maps/api/js?`+e;d[q]=f;a.onerror=()=>h=n(Error(p+" could not load."));a.nonce=m.querySelector("script[nonce]")?.nonce||"";m.head.append(a)}));d[l]?console.warn(p+" only loads once. Ignoring:",g):d[l]=(f,...n)=>r.add(f)&&u().then(()=>d[l](f,...n))})
    ({key: "AIzaSyAGZjmTZFO0V8_-_V_A-Dqto1I-FlBhshE", v: "beta"});</script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAGZjmTZFO0V8_-_V_A-Dqto1I-FlBhshE&libraries=places"></script>
</body>

</html>
<!-- [END maps_advanced_markers_html] -->
