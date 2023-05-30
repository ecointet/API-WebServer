<?php
//A Fun API WebServer
//by @ecointet (twitter)

function cityFromIP($ip)
{
   // if ($ip == null) $ip = "127.0.0.1";
    $data = getRemoteContent("http://ip-api.com/json/".$ip); //Get City
    $json = json_decode($data);

    $n_json['country'] = $json->country;
    $n_json['regionName'] = $json->regionName;
    $n_json['city'] = $json->city;
    $n_json['ip'] = $json->query;

    $googleAPI = getenv("GKEY");

    if ($json->city) // Get Photo Ref
    {
        $data = getRemoteContent("https://maps.googleapis.com/maps/api/place/findplacefromtext/json?input=".urlencode($json->city)."&key=".$googleAPI."&inputtype=textquery&fields=name,photos"); 
        $json = json_decode($data);
    }
    $photo_ref = $json->candidates[0]->photos[0]->photo_reference;
    
    $n_json['photo'] = "https://maps.googleapis.com/maps/api/place/photo?photoreference=".$photo_ref."&key=".$googleAPI."&maxwidth=2500";

    return(json_encode($n_json));
}
?>