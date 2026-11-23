<?php
require "database/Store.php";
require "src/tools.php";
require "src/functions.php";
require "src/flights.php";

//FUNCTIONS
//INIT DATABASE
$databaseDirectory = __DIR__ . "/data";
$data = new \SleekDB\Store("cache", $databaseDirectory);

//GET DATA
$method = $_SERVER['REQUEST_METHOD'];

if (!isset($_GET['func']))
    $retour = generate_error();
else
{
    if ($method == 'GET') {
        $param = isset($_GET['param']) ? $_GET['param'] : null;
        if (strtolower($_GET['func']) == "locate")
            $retour = cityFromIP($param);
        if (strtolower($_GET['func']) == "details")
            $retour = DetailsFromCountry($param);
        if (strtolower($_GET['func']) == "chatgpt")
            $retour = ChatGPT($param);
        if (strtolower($_GET['func']) == "smarthome")
            $retour = SmartHome();
        if (strtolower($_GET['func']) == "explore")
            $retour = Explore($param);
        if (strtolower($_GET['func']) == "mock")
            $retour = MockCache($param, $data);
        if (strtolower($_GET['func']) == "getwater")
            $retour = GetWaterCenters($param, $data);
        if (strtolower($_GET['func']) == "login")
            $retour = GetLogin($param, $data);
        if (strtolower($_GET['func']) == "getdata")
            $retour = GetData($param, $data);
        if (strtolower($_GET['func']) == "flights") {
            $airport = isset($_GET['airport']) ? strtoupper($_GET['airport']) : null;
            if ($param !== null)
                $retour = GetFlightDetails(strtoupper($param));
            else
                $retour = GetFlights($airport);
        }
    }
    if ($method == 'POST') {
        echo "THIS IS A POST REQUEST";
    }
    if ($method == 'PUT') {
        echo "THIS IS A PUT REQUEST";
    }
    if ($method == 'DELETE') {
        echo "THIS IS A DELETE REQUEST";
    }
}

echo $retour;
?>

<?php
function generate_error()
{
    $error['API'] = "@ECOINTET - The Ready to Use API";
    $error['date'] = time();
    $error['usage'] = "check the doc in Postman";

    return json_encode($error);
}
?>