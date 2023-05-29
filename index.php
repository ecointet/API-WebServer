<?php
//A Fun API WebServer
//by @ecointet (twitter)
require "tools.php";
require "functions.php";
?>

<?php
header("Access-Control-Allow-Origin: *");
// get request method
$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'GET') {
	if (isset($_GET['function']) && strtolower($_GET['function']) == "cityfromip")
        $retour = cityFromIP($_GET['ip']);
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

if (isset($retour))
    die($retour);

    die ("ERROR");
?>