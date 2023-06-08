<?php
require "src/tools.php";
require "src/functions.php";

//GET DATA
$method = $_SERVER['REQUEST_METHOD'];

if (!isset($_GET['param']) || !isset($_GET['func']))
    $retour = generate_error();
else
{
    if ($method == 'GET') {
        if (strtolower($_GET['func']) == "locate")
            $retour = cityFromIP($_GET['param']);
        if (strtolower($_GET['func']) == "details")
            $retour = DetailsFromCountry($_GET['param']);
        if (strtolower($_GET['func']) == "chatgpt")
            $retour = ChatGPT($_GET['param']);
        if (strtolower($_GET['func']) == "smarthome")
            $retour = SmartHome();
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