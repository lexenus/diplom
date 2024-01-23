<?php

$hostname ="localhost";
$dbname = "diplom";
$username = "root";
$password = "root";


return new PDO("mysql:host=$hostname; dbname=$dbname", $username, $password);
//$GLOBALS['connect'] = $conn; плохая практика использовать глобальные переменные, там где можно обойтись без этого

?>