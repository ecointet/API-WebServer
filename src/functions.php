<?php
//A Fun API WebServer
//by @ecointet (twitter)

function cityFromIP($ip)
{
    MirrorApiPython("locate/".$ip);
   // if ($ip == null) $ip = "127.0.0.1";
    if (!filter_var($ip, FILTER_VALIDATE_IP)) $ip = ""; //REMOVE IP IF NOT VALID
    $data = getRemoteContent("http://ip-api.com/json/".$ip); //Get City
    $json = json_decode($data);
    if (!isset($json->country)) return error("IP details not found!", $ip);

    $n_json['country'] = $json->country;
    $n_json['countryCode'] = $json->countryCode;
    $n_json['regionName'] = $json->regionName;
    $n_json['city'] = $json->city;
    $n_json['ip'] = $json->query;
    $n_json['description'] = "This city is probably not too far away from you!";

    if (!getenv("GKEY")) die("Incorrect Google API KEY");
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

function Explore($city)
{
    MirrorApiPython("explore/".$city);
    //$n_json['country'] = $json->country;
    //$n_json['countryCode'] = $json->countryCode;
    //$n_json['regionName'] = $json->regionName;
    $n_json['city'] = $city;
    //$n_json['ip'] = $json->query;
    $n_json['description'] = "You are exploring this city.";

    if (!getenv("GKEY")) die("Incorrect Google API KEY");
    $googleAPI = getenv("GKEY");
    

    if ($city) // Get Photo Ref
    {
        $data = getRemoteContent("https://maps.googleapis.com/maps/api/place/findplacefromtext/json?input=".urlencode($city)."&key=".$googleAPI."&inputtype=textquery&fields=name,photos"); 
        $json = json_decode($data);
    }
    $photo_ref = $json->candidates[0]->photos[0]->photo_reference;
    
    $n_json['photo'] = "https://maps.googleapis.com/maps/api/place/photo?photoreference=".$photo_ref."&key=".$googleAPI."&maxwidth=2500";

    return(json_encode($n_json));
}

function DetailsFromCountry($country)
{
    MirrorApiPython("details/".$country);
    $data = getRemoteContent("https://restcountries.com/v3.1/alpha/".$country); //Get Details from
    $json = json_decode($data);
    //print_r($json);
    if (!isset($json[0]->name->official)) return error("Country details not found", $country);

   $n_json['country'] = $json[0]->name->common;
   $n_json['country_name'] = $json[0]->name->official;
   $n_json['code'] = $json[0]->cca2;
   $n_json['flag'] = $json[0]->flag;
   $n_json['flag_img'] = $json[0]->flags->png;
   $n_json['flag_alt'] = $json[0]->flags->alt;
    
    return(json_encode($n_json));
}

//Simple GetData (Echo Mode)
function GetData($txt)
{
    //MirrorApiPython("GetData/".$txt);
   // $data = getRemoteContent("https://restcountries.com/v3.1/alpha/".$country); //Get Details from
   
   $n_json['data'] = $txt;
   $n_json['user'] = "empty";
    
  return(json_encode($n_json));
}

//TEMP DEMO
function GetWaterCenters($country)
{
   $n_json['country'] = $country;
   $n_json['countryCode'] = $country;
   $n_json['cityName'] = "Saint-Maurice";
   $n_json['zipcode'] = "91";
   $n_json['gps_longitude'] = "48.81474132276349";
   $n_json['gps_latitude'] = "2.459427941504525";
   $n_json['name'] = "Usine de Saint-Maurice (en cours de construction)";
   $n_json['photo'] = "https://www.veolia.com/sites/g/files/dvc4206/files/styles/media/public/image/2023/07/Usine-depollution-eaux-usees-Rennes-429x528.jpg?h=62c1f40a&itok=fYijJxqy";
   $data[] =  $n_json;

   $n_json['country'] = $country;
   $n_json['countryCode'] = $country;
   $n_json['cityName'] = "Versailles";
   $n_json['zipcode'] = "78";
   $n_json['gps_longitude'] = "48.8082283015871";
   $n_json['gps_latitude'] = "2.10778119981541945";
   $n_json['name'] = "Usine de Versailles (en rénovation)";
   $n_json['photo'] = "https://lh3.googleusercontent.com/ci/AL18g_S1O8bUPQ81tiI2OgkMvzwoFFHTYQBaFu_JV519PZo92gRnVyRtVEClNyDxk9q1MVEs6v-9BoA";
   $data[] =  $n_json;

    return(json_encode($data));
}

function ChatGPT($city)
{
    MirrorApiPython("chatgpt/".$city);
    if (!getenv("OPENAI")) die("Incorrect OPEN API KEY");
    $openaiAPI = getenv("OPENAI");

    $headers    = [];
    $headers[]  = 'Content-Type: application/json';
    $headers[]   = 'Authorization: Bearer '.$openaiAPI;

    $question = "In one sentence, give me some information about the city: ".$city;

    $content= '{
        "model": "gpt-3.5-turbo",
        "messages": [{"role": "user", "content": "'.$question.'"}],
        "max_tokens": 250,
        "temperature": 0.7
      }';

    $data = getRemoteContent("https://api.openai.com/v1/chat/completions", $headers, $content); //Get Details from
    $json = json_decode($data);
    //print_r($json);
    
    if (!isset($json->id)) return error("City details not found from ChatGPT", $city);

   $n_json['id'] = $json->id;
   $n_json['model'] = $json->model;
   $n_json['answer'] = $json->choices[0]->message->content;
   $n_json['question'] = $question;
    
   return(json_encode($n_json));
}

//BONUS (SMARTHOME)
function SmartHome()
{
    $data = getRemoteContent("https://webhook.homey.app/62cbc64f9685c30bd64964fa/light"); //Get Details from
    $json = json_decode($data);
    //print_r($json);

    $n_json['country'] = "n/a";
    $n_json['countryCode'] = "n/a";
    $n_json['regionName'] = "n/a";
    $n_json['city'] = "Light turned On/off 💡";
    $n_json['ip'] = "n/a";
    $n_json['photo'] = "https://static.vecteezy.com/system/resources/previews/008/774/343/non_2x/illustration-wood-table-floor-and-blurred-background-atmosphere-front-room-light-shining-through-the-curtain-in-home-vector.jpg";
    $n_json['description'] = "The Home API returned ".str_replace('"', "'", $data);

    return(json_encode($n_json));
}

//GET MOCK CONTENT
function GetMockContent($uid, $data, $cache)
{
    $mock = getRemoteContent("https://".$uid.".mock.pstmn.io/locate/");
    $content =  [
        "uid" => $uid,
        "content" => $mock,
        "cache_time" => time()
    ];

    if ($cache == null)
        $cache = $data->insert($content);

    $data->updateById($cache['_id'], $content);
    $data->updateById($cache['_id'], [ "cache_time" => time() ]);

    return($mock);
}

//GET LOGIN (BANK DEMO)
function GetLogin($param, $data)
{
    if ($param == "ecointet")
    {
        $n_json['firstname'] = "Etienne";
        $n_json['lastname'] = "COINTET";
        $n_json['total'] = "- 4 543 EUR";
        $n_json['internal_note'] = "Broke customer 😢";
    }
    else if ($param == "sdubois")
    {
        $n_json['firstname'] = "Stéphane";
        $n_json['lastname'] = "DUBOIS";
        $n_json['total'] = "+ 54 454 300 EUR";
        $n_json['internal_note'] = "aka The Mayonesa Expert! 👨‍🍳";
    }
     else if ($param == "doliva")
    {
        $n_json['firstname'] = "Diego";
        $n_json['lastname'] = "Oliva";
        $n_json['total'] = "+ 68 955 785 GBP";
        $n_json['internal_note'] = "wealthy person 💰";
    }
    else 
        $n_json['status'] = "Error - user not found";

    $n_json['last_update'] = date("l jS \of F Y h:i:s A");
    return(json_encode($n_json));
}

//CACHE SYSTEM
function MockCache($uid, $data)
{
    $current_time = time();
    $cache = $data->findOneBy(["uid", "=", $uid]);

    if ($cache)
    {
        $diff = $current_time - $cache['cache_time'];
        if ($diff > 5)
            {
                //LIVE MODE
                $mock = GetMockContent($uid, $data, $cache);
            }
        else
        {
            $mock = $cache['content'];
        }
    }
    else
    {
        //CACHE MODE
        $mock = GetMockContent($uid, $data, null);
    }

    return($mock);
}

function error($data, $ip)
{
    $n_json['ip'] = $ip;
    $n_json['error'] = $data;

    return json_encode($n_json);
}
?>