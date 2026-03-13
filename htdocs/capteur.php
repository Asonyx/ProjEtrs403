<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Relevés des capteurs</title>
    <link rel="stylesheet" href="./style.css">
</head>
<body>
    <div class="container">
        <h1>Station Météo - Relevés</h1>
        
        <?php
        try {
            
            $dbh = new PDO("mysql:dbname=tpetrs;host=localhost;charset=utf8", "root", "");
            
            $dernier = $dbh->query("SELECT * FROM temp ORDER BY id DESC LIMIT 1")->fetch();
            
            if ($dernier) {
                $date_mysql = $dernier['date_mesure'];
                $timestamp = strtotime($date_mysql);
                $date_francaise = date('d/m/Y', $timestamp);
                
                echo "<div class='derniere-mesure'>";
                echo "<h2>🌡️ Dernière mesure</h2>";
                echo "<div class='grande-temp'>" . $dernier['temp'] . " °C</div>";
                echo "<div class='grande-hum'>💧 Humidité : " . $dernier['hum'] . " %</div>";
                echo "<p>📅 Date : " . $date_francaise . " à " . $dernier['heure'] . "h" . str_pad($dernier['minute'], 2, '0', STR_PAD_LEFT) . "</p>";
                echo "</div>";
            }

            $stats = $dbh->query("SELECT 
                                    COUNT(*) as total,
                                    MIN(temp) as temp_min,
                                    MAX(temp) as temp_max,
                                    ROUND(AVG(temp), 1) as temp_moy,
                                    MIN(hum) as hum_min,
                                    MAX(hum) as hum_max,
                                    ROUND(AVG(hum)) as hum_moy
                                  FROM temp")->fetch();
            
            if ($stats['total'] > 0) {
                echo "<div class='stats'>";
                echo "<div class='stat-box'>📈 Total : <strong>" . $stats['total'] . "</strong> mesures</div>";
                echo "<div class='stat-box'>🌡️ Temp : min " . $stats['temp_min'] . "°C / max " . $stats['temp_max'] . "°C</div>";
                echo "<div class='stat-box'>💧 Hum : min " . $stats['hum_min'] . "% / max " . $stats['hum_max'] . "%</div>";
                echo "</div>";
            }
            
            $resultat = $dbh->query("SELECT * FROM temp ORDER BY id DESC");
            
            echo "<h2>Historique complet</h2>";
            echo "<table>";
            echo "<tr>
                    <th>ID</th>
                    <th>Date</th>
                    <th>Heure</th>
                    <th>Température</th>
                    <th>Humidité</th>
                  </tr>";
            
            while ($row = $resultat->fetch()) {
                // On convertit le format de la date pour le bon affichage
                $date_mysql = $row['date_mesure'];
                $timestamp = strtotime($date_mysql);
                $date_francaise = date('d/m/Y', $timestamp);
                
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $date_francaise . "</td>";
                echo "<td>" . $row['heure'] . "h" . str_pad($row['minute'], 2, '0', STR_PAD_LEFT) . "</td>";
                echo "<td class='temp'>" . $row['temp'] . " °C</td>";
                echo "<td class='hum'>" . $row['hum'] . " %</td>";
                echo "</tr>";
            }
            
            echo "</table>";
            
        } catch (PDOException $e) {
            echo "<p style='color: red;'>Erreur de connexion : " . $e->getMessage() . "</p>";
        }
        ?>
        
        <div style="text-align: center; margin-top: 20px;">
            <a href="javascript:location.reload()" class="btn">Rafraîchir</a>
        </div>
        
        <p style="text-align: center; color: #7f8c8d; margin-top: 20px;">
            Date serveur - <?php echo date('d/m/Y H:i:s'); ?>
        </p>
    </div>
</body>
</html>