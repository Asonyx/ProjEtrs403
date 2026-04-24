<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Relevés des capteurs</title>
    <link rel="stylesheet" href="./style.css">
    <meta http-equiv="refresh" content="60">  <!--actualise la page toutes les ... secondes-->
</head>
<body>
    <div class="container">
        <h1> Relevés</h1>

        <div class="menu-boutons">
            <a href="vider.php" class="btn-danger"> Vider les données</a>
            <a href="graphique.php" class="btn">Voir les graphiques</a>
        </div>
        
        <?php
        try {
            // Connexion à la base
            $dbh = new PDO("mysql:dbname=tpetrs;host=localhost;charset=utf8", "root", "");
            
            // Récupérer la dernière mesure
            $dernier = $dbh->query("SELECT * FROM temp ORDER BY id DESC LIMIT 1")->fetch();
            $dernierr = $dbh->query("SELECT * FROM ncapteur ORDER BY idCap DESC LIMIT 1")->fetch();
            
            if ($dernier) {
                // Convertir la date MySQL (2026-03-13) en format français /
                $date_mysql = $dernier['date_mesure'];
                $timestamp = strtotime($date_mysql);
                $date_francaise = date('d/m/Y', $timestamp);
                
                echo "<div class='derniere-mesure'>";
                echo "<h2> Dernière mesure</h2>";
                echo "<div class='grande-temp'>" . $dernier['temp'] . " °C</div>";
                echo "<div class='grande-hum'>Humidité : " . $dernier['hum'] . " </div>";
                echo "<h1> Qualité de l'air </h2>";
                echo "<div >Indice de qualité : " . $dernierr['aqi'] . " / 5</div>";
                echo "<div >Taux de particule : " . $dernierr['tvoc'] . " PPB</div>";
                echo "<div >Taux de CO2 : " . $dernierr['eco2'] . " PPM</div>";
                echo "<p>Date : " . $date_francaise . " à " . $dernier['heure'] . "h" . str_pad($dernier['minute'], 2, '0', STR_PAD_LEFT) . "</p>";
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
                echo "<div class='stat-box'> Total : <strong>" . $stats['total'] . "</strong> mesures</div>";
                echo "<div class='stat-box'> Temp : min " . $stats['temp_min'] . "°C / max " . $stats['temp_max'] . "°C</div>";
                echo "<div class='stat-box'> Hum : min " . $stats['hum_min'] . "% / max " . $stats['hum_max'] . "%</div>";
                echo "</div>";
            }
            
            
        } catch (PDOException $e) {
            echo "<p style='color: red;'>Erreur de connexion : " . $e->getMessage() . "</p>";
        }
        ?>
        
        <div style="text-align: center; margin-top: 20px;">
            <a href="javascript:location.reload()" class="btn"> Rafraîchir</a>
        </div>
        
        <p style="text-align: center; color: #7f8c8d; margin-top: 20px;">
             Serveur - <?php echo date('d/m/Y H:i:s'); ?>
        </p>
    </div>
</body>
</html>