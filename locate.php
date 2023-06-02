<?php
//A Fun API WebServer
//by @ecointet (twitter)

require "src/tools.php";
require "src/functions.php";
?>

<?php
header("Access-Control-Allow-Origin: *");
// get request method
$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'GET') {
	if (isset($_GET['param']))
        $retour = cityFromIP($_GET['param']);
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

if (isset($_GET))
$n_json['params'] = implode(" ; ", $_GET);
$n_json['error'] = "Missing an arg";
$n_json['date'] = time();
$n_json['usage'] = "check the doc in Postman";
	
echo json_encode($n_json);
?>