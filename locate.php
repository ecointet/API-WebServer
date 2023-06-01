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
	if (isset($_GET['ip']))
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

$n_json['error'] = "Missing an arg (?ip=)";
$n_json['date'] = time();
$n_json['usage'] = "check the doc in Postman";
	
echo json_encode($n_json);
?>