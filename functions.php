<?php
//A Fun API WebServer
//by @ecointet (twitter)

function cityFromIP($ip)
{
   // if ($ip == null) $ip = "127.0.0.1";
    $data = getRemoteContent("http://ip-api.com/json/".$ip); //Get City
    $json = json_decode($data);
    $city = $json->city;
    $googleAPI = getenv("GKEY");

    if ($json->city) // Get Photo Ref
    {
        $data = getRemoteContent("https://maps.googleapis.com/maps/api/place/findplacefromtext/json?input=".$json->city."&key=".$googleAPI."&inputtype=textquery&fields=name,photos");
        $json = json_decode($data);
    }
    $photo_ref = $json->candidates[0]->photos[0]->photo_reference;
    
    $n_json['url'] = "https://maps.googleapis.com/maps/api/place/photo?photoreference=".$photo_ref."&key=".$googleAPI."&maxwidth=2500";
    $n_json['city'] = $city;

    return(json_encode($n_json));
}
?>