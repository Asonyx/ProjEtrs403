<?php
// donnees_graphique.php - Fournit les données au format JSON pour les graphiques
header('Content-Type: application/json');

try {
    $dbh = new PDO("mysql:dbname=tpetrs;host=localhost;charset=utf8", "root", "");
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Récupérer les 30 dernières mesures de température/humidité
    $resultat = $dbh->query("SELECT * FROM temp ORDER BY id DESC LIMIT 30");
    
    // Récupérer les 30 dernières mesures du capteur (TVOC et CO2)
    $resultat1 = $dbh->query("SELECT * FROM ncapteur ORDER BY IdCap DESC LIMIT 30");
    
    $dates_temp = [];
    $temperatures = [];
    $humidites = [];
    
    $dates_ncapteur = [];
    $tvoc = [];
    $eco2 = [];
    
    // Traitement des données de température/humidité
    while ($row = $resultat->fetch(PDO::FETCH_ASSOC)) {
        $date_mysql = $row['date_mesure'];
        $timestamp = strtotime($date_mysql);
        $date_francaise = date('d/m', $timestamp);
        $heure = str_pad($row['heure'], 2, '0', STR_PAD_LEFT);
        $minute = str_pad($row['minute'], 2, '0', STR_PAD_LEFT);
        
        $dates_temp[] = $date_francaise . ' ' . $heure . 'h' . $minute;
        $temperatures[] = floatval($row['temp']);
        $humidites[] = intval($row['hum']);
    }
    
    // Traitement des données TVOC et CO2
    
    while ($row = $resultat1->fetch(PDO::FETCH_ASSOC)) {
        $date_mysql = $row['date_mesure'];
        $timestamp = strtotime($date_mysql);
        $date_francaise = date('d/m', $timestamp);
        $heure = str_pad($row['heure'], 2, '0', STR_PAD_LEFT);
        $minute = str_pad($row['minute'], 2, '0', STR_PAD_LEFT);
        
        $dates_ncapteur[] = $date_francaise . ' ' . $heure . 'h' . $minute;
        $tvoc[] = intval($row['tvoc']);
        $eco2[] = intval($row['eco2']);
    }
    
    // l'ordre chronologique
    $dates_temp = array_reverse($dates_temp);
    $temperatures = array_reverse($temperatures);
    $humidites = array_reverse($humidites);
    
    $dates_ncapteur = array_reverse($dates_ncapteur);
    $tvoc = array_reverse($tvoc);
    $eco2 = array_reverse($eco2);
    

    $dates = $dates_temp;
    
    $donnees = [
        'dates' => $dates,
        'temperatures' => $temperatures,
        'humidites' => $humidites,
        'tvoc' => $tvoc,
        'eco2' => $eco2
    ];
    
    echo json_encode($donnees, JSON_UNESCAPED_UNICODE);
    
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>