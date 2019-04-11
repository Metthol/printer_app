<?php

if(isset($_POST['action']) && !empty($_POST['action'])) {
    $action = $_POST['action'];
    switch($action) {
        case 'generate' : generate_thumbnails();break;
        case 'get_images' : get_images($_POST['full']);break;
        case 'export' : header('Content-Type: application/json'); echo exporter($_POST['liste_image'], $_POST['qte_image']);break;
    }
}

$req = "TRUNCATE TABLE history; TRUNCATE TABLE thumbnails;";
include "variables.php";
$mysqli = new mysqli($server, $username, $password, $database);

$mysqli->query($req);

?>