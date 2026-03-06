<?php

$temp = $_POST['temp'];
$hum = $_POST['hum'];
echo "req";

$dbh = new PDO("mysql:dbname=tpetrs;host=localhost;charset=utf8","root","");

$dbh->query("INSERT INTO temp VALUES (NULL,'$temp','$hum')");

echo "ok";

?>