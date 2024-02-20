<?php
$apiURL = 'http://xml.propspace.com/feed/xml.php?cl=1982&pid=8245&acc=8807';
$xmlString = file_get_contents($apiURL);
$phpArray = simplexml_load_string($xmlString, 'SimpleXMLElement', LIBXML_NOCDATA);
$xmlVal = json_encode($phpArray, true);
?>
<!--
  The `defer` attribute causes the callback to execute after the full HTML
  document has been parsed. For non-blocking uses, avoiding race conditions,
  and consistent behavior across browsers, consider loading using Promises.
  See https://developers.google.com/maps/documentation/javascript/load-maps-js-api
  for more information.
  -->

{{-- <script
  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAGZjmTZFO0V8_-_V_A-Dqto1I-FlBhshE&callback=initMap&v=weekly"
  defer
></script> --}}
{{-- <script>
    let map;
var data = <?php echo json_encode($xmlVal); ?>;
var  xmlString = JSON.parse(data);
function initMap() {
map = new google.maps.Map(document.getElementById("map"), {
zoom: 14,
center: new google.maps.LatLng(25.2048, 55.2708)||map.setCenter(marker.getPosition()),
mapTypeId: "terrain",
});
for (let i = 0; i < xmlString['Listing'].length; i++) {
const coords = xmlString['Listing'][i].Latitude;

const coordsLongitude = xmlString['Listing'][i].Longitude;
const latLng = new google.maps.LatLng(coords, coordsLongitude);
// console.log(coords +","+ coordsLongitude);
new google.maps.Marker({
  position: latLng,
  map: map,
});
}
}

window.initMap = initMap;

</script> --}}