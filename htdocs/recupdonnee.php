<?php

$temp = $_POST['temp'];
$hum = $_POST['hum'];

$date_mesure = date('Y-m-d');
$heure = date('H');
$minute = date('i');

try {
    $dbh = new PDO("mysql:dbname=tpetrs;host=localhost;charset=utf8", "root", "");
    
    $sql = "INSERT INTO temp (temp, hum, date_mesure, heure, minute) 
            VALUES ('$temp', '$hum', '$date_mesure', '$heure', '$minute')";
    
    $dbh->query($sql);
    
    echo "OK - Données enregistrées le $date_mesure à $heure:$minute";
    
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>