<?php

$dir_images = $_SERVER['DOCUMENT_ROOT'] . "/images/";
$dir_thumbnails = $_SERVER['DOCUMENT_ROOT'] . "/thumbnails/";

$server = "localhost";
$username = "root";
$password = "";
$database = "printer_app";

mysql_connect("localhost", "mysql_user", "mysql_password") or
die("Impossible de se connecter : " . mysql_error());
mysql_select_db("mydb");

?>