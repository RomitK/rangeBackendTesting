<?php
function url_exists($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // follow redirects

    // Set a maximum timeout of 10 seconds to prevent the script from hanging
    curl_setopt($ch, CURLOPT_TIMEOUT, 10); 

    // Execute the request and get the HTTP status code
    curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    // Check the HTTP status code
    if($httpCode == 404) {
        return false;
    }elseif($httpCode >= 200 && $httpCode < 300) {
        // The file exists and the server returned a successful HTTP status code
        return true;
    } else {
        // The file does not exist or the server returned an error HTTP status code
        return false;
    }
}
?>