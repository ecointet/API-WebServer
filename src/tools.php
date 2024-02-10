<?php
//A Fun API WebServer
//by @ecointet (twitter)
header('Content-Type: application/json; charset=utf-8');
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Access-Control-Allow-Origin: *");
header("Pragma: no-cache");
?>

<?php
function getRemoteContent($url, $headers = null, $data = null)
{    
   
   // CURL
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_URL, $url);
   //curl_setopt($curl, CURLOPT_VERBOSE, true);
   // curl_setopt($curl, CURLINFO_HEADER_OUT, true);

   

    if ($headers)
    {
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers); 
        //curl_getinfo($curl);
    }
    else
        curl_setopt($curl, CURLOPT_HEADER, false);

    if (isset($data))
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    
     curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($curl);
    
    //print_r(curl_getinfo($curl));
    curl_close($curl);
    /// END

    return $response;
}

function MirrorApiPython($func)
{
    $mirrorApi = getenv("MIRROR_API");
    if ($mirrorApi && strlen($mirrorApi) > 5)
    {
        return getRemoteContent($mirrorApi."/".$func, $headers = null, $data = null);
    }
}
?>