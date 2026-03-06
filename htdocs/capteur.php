<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Température du Jour</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 50px;
            background-color: #f0f0f0;
        }
        h1 {
            color: #333;
        }
        .temperature {
            font-size: 48px;
            color: #007bff;
            margin-top: 30px;
        }
        .humidite {
            font-size: 24px;
            color: #4682b4;
            margin-top: 10px;
        }
        .message {
            font-size: 24px;
            margin-top: 20px;
            padding: 15px;
            border-radius: 10px;
        }
        .froid {
            background-color: #add8e6;
            color: #00008b;
        }
        .chaud {
            background-color: #ffb6c1;
            color: #8b0000;
        }
        .doux {
            background-color: #90ee90;
            color: #006400;
        }
    </style>
</head>
<body>
    <h1> Température du Jour</h1>

    <?php
    try {
        // Connexion à la base de données
        $dbh = new PDO("mysql:dbname=tpetrs;host=localhost;charset=utf8", "root", "");
        
        // Récupérer la dernière mesure
        $resultat = $dbh->query("SELECT * FROM temp ORDER BY id DESC LIMIT 1");
        $donnee = $resultat->fetch(PDO::FETCH_ASSOC);
        
        if ($donnee) {
            $temperature = $donnee['temp'];
            $humidite = $donnee['hum'];
        } else {
            // Message si aucune donnée
            echo "<p class='erreur'>Aucune donnée disponible dans la base</p>";
            $temperature = 20; // Valeur par défaut
            $humidite = 50;    // Valeur par défaut
        }
        
    } catch (PDOException $e) {
        echo "<p class='erreur'>Erreur de base de données: " . $e->getMessage() . "</p>";
        $temperature = 20;
        $humidite = 50;
    }
    ?>
    
    <div class="temperature">
        <?php
        // Simulation d'une température aléatoire entre -5 et 35 degrés
        echo $temperature . "°C";
        ?>
    </div>
    
    <div class="humidite">
        <?php
        // Simulation d'une humidité aléatoire entre 30% et 90%
        echo " Humidité: " . $humidite . "%";
        ?>
    </div>
    
    <div class="message 
        <?php 
        if ($temperature < 0) {
            echo 'froid';
        } elseif ($temperature > 30) {
            echo 'chaud';
        } else {
            echo 'doux';
        }
        ?>">
        <?php
        if ($temperature < 0) {
            echo " Il gèle";
        } elseif ($temperature > 30) {
            echo " Il fait tarpin chaud ";
        } else {
            echo " il fait bon";
        }
        ?>
    </div>
    
    <p>Date: <?php echo date('d/m/Y'); ?></p>
</body>
</html>