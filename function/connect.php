<?php

$hostname ="localhost";
$dbname = "diplom";
$username = "root";
$password = "";


$conn = new PDO("mysql:host=$hostname; dbname=$dbname", $username, $password);
$GLOBALS['connect'] = $conn;

?>