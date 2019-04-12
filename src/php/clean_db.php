<?php

$json = false;

if(isset($_GET['json']) && !empty($_GET['json']) && strtolower($_GET['json']) == "true") {
    $json = true;
}

$req1 = "TRUNCATE TABLE `thumbnails`";
$req2 = "TRUNCATE TABLE `history`";
include "variables.php";
$mysqli = new mysqli($server, $username, $password, $database);

$mysqli->query($req1);
$mysqli->query($req2);

// array_map('unlink', array_filter((array) glob("../../output/*")));

if($json){
	header('Content-Type: application/json');
	echo json_encode(
		array("success" => true)
	);
}else{
	echo "Base de données et fichiers générés nettoyés";
}

exit();

?>