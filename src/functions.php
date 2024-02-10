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