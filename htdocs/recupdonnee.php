<?php
// Reçoit les données du capteur

$temp = $_POST['temp'];
$hum = $_POST['hum'];

// Récupérer la date au format MySQL (YYYY-MM-DD)
$date_mesure = date('Y-m-d');  // 2026-03-13
$heure = date('H');              //  (0-23)
$minute = date('i');             //  (00-59)

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