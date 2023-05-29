<?php
//A Fun API WebServer
//by @ecointet (twitter)
?>

<?php
function getRemoteContent($url)
{    
   // CURL
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($curl);
    curl_close($curl);
    $result = $response;
    /// END

    return $result;
}
?>