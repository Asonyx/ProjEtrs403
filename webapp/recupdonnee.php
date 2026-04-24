<?php
// Reçoit les données du capteur

$temp = $_POST['temp'];
$hum = $_POST['hum'];
$aqi = $_POST['aqi'];
$tvoc = $_POST['tvoc'];
$eco2 = $_POST['eco2'];

// Récupérer la date au format MySQL (YYYY-MM-DD)
$date_mesure = date('Y-m-d');  // 2026-03-13
$heure = date('H');              //  (0-23)
$minute = date('i');             //  (00-59)

try {
    $dbh = new PDO("mysql:dbname=tpetrs;host=localhost;charset=utf8", "root", "");
    
    $sql = "INSERT INTO temp (temp, hum, date_mesure, heure, minute) 
            VALUES ('$temp', '$hum', '$date_mesure', '$heure', '$minute')";
    
    $dbh->query($sql);

    $sql1 = "INSERT INTO ncapteur (aqi, tvoc, eco2, date_mesure, heure, minute) 
            VALUES ('$aqi', '$tvoc', '$eco2' ,'$date_mesure', '$heure', '$minute')";
    
    $dbh->query($sql1);
    
    echo "OK - Données enregistrées le $date_mesure à $heure:$minute";
    
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>