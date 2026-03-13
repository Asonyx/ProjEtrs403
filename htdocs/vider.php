<?php
// vider.php - Vide les données mais garde la dernière mesure

echo "<!DOCTYPE html>
<html>
<head>
    <title>Vider les données</title>
    <link rel='stylesheet' href='stylevider.css'>
</head>
<body>
    <div class='container'>";

if (isset($_GET['confirm']) && $_GET['confirm'] == 'yes') {
    try {
        $dbh = new PDO("mysql:dbname=tpetrs;host=localhost;charset=utf8", "root", "");
        
        // Récupérer la dernière mesure AVANT de vider
        $dernier = $dbh->query("SELECT * FROM temp ORDER BY id DESC LIMIT 1")->fetch();
        
        if ($dernier) {
            // Sauvegarder les valeurs
            $derniere_temp = $dernier['temp'];
            $derniere_hum = $dernier['hum'];
            $derniere_date = $dernier['date_mesure'];
            $derniere_heure = $dernier['heure'];
            $derniere_minute = $dernier['minute'];
            
            // Vider la table
            $dbh->exec("DELETE FROM temp");
            
            // Réinsérer la dernière mesure
            $sql = "INSERT INTO temp (temp, hum, date_mesure, heure, minute) 
                    VALUES ($derniere_temp, $derniere_hum, '$derniere_date', $derniere_heure, $derniere_minute)";
            $dbh->query($sql);
            
            echo "<h2 style='color: green;'> Données vidées</h2>";
            echo "<p> Dernière mesure conservée :</p>";
            echo "<p style='font-size: 24px;'> $derniere_temp °C</p>";
            echo "<p style='font-size: 20px;'> $derniere_hum%</p>";
            echo "<p> " . date('d/m/Y', strtotime($derniere_date)) . " à " . $derniere_heure . "h" . str_pad($derniere_minute, 2, '0', STR_PAD_LEFT) . "</p>";
            
        } else {
            echo "<p>La table est déjà vide.</p>";
        }
        
    } catch (PDOException $e) {
        echo "<p style='color: red;'>Erreur : " . $e->getMessage() . "</p>";
    }
    
    echo "<a href='capteur.php' class='btn btn-success'> Voir le capteur</a>";
    
} else {
    // Page de confirmation
    echo "<h2>🗑️ Vider l'historique ?</h2>";
    echo "<p>Cette action va supprimer <strong>toutes les mesures sauf la dernière</strong>.</p>";
    echo "<a href='vider.php?confirm=yes' class='btn btn-danger'> Confirmer</a>";
    echo "<a href='capteur.php' class='btn'> Annuler</a>";
}

echo "    </div>
</body>
</html>";
?>