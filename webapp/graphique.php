<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Graphiques</title>
    <link rel="stylesheet" href="./style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container">
        <h1> Graphiques</h1>
        
        <div class="menu-boutons">
            <a href="capteur.php" class="btn">← Retour aux relevés</a>
            <button onclick="rafraichirGraphiques()" class="btn"> Rafraîchir</button>
        </div>
        
        <!-- Graphique Température -->
        <div class="graphique-container">
            <h2> Température</h2>
            <canvas id="graphiqueTemp" width="800" height="300"></canvas>
        </div>
        
        <!-- Graphique Humidité -->
        <div class="graphique-container">
            <h2> Humidité</h2>
            <canvas id="graphiqueHum" width="800" height="300"></canvas>
        </div>

        <!-- Graphique Particule -->
        <div class="graphique-container">
            <h2>Taux de particule (TVOC)</h2>
            <canvas id="graphiquePar" width="800" height="300"></canvas>
        </div>

        <!-- Graphique CO2 -->
        <div class="graphique-container">
            <h2>Taux de CO2</h2>
            <canvas id="graphiqueCO2" width="800" height="300"></canvas>
        </div>
    </div>

    <script>
    let graphiqueTemp = null;
    let graphiqueHum = null;
    let graphiquePar = null;
    let graphiqueCO2 = null;
    
    async function chargerGraphiques() {
        try {
            const response = await fetch('donnees_graphique.php');
            const donnees = await response.json();
            
            if (donnees.error) {
                console.error('Erreur:', donnees.error);
                return;
            }
            
            // Graphique Température
            const ctxTemp = document.getElementById('graphiqueTemp').getContext('2d');
            if (graphiqueTemp) {
                graphiqueTemp.data.labels = donnees.dates;
                graphiqueTemp.data.datasets[0].data = donnees.temperatures;
                graphiqueTemp.update();
            } else {
                graphiqueTemp = new Chart(ctxTemp, {
                    type: 'line',
                    data: {
                        labels: donnees.dates,
                        datasets: [{
                            label: 'Température (°C)',
                            data: donnees.temperatures,
                            borderColor: 'rgb(255, 99, 132)',
                            backgroundColor: 'rgba(255, 99, 132, 0.1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.3
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                position: 'top',
                            }
                        }
                    }
                });
            }
            
            // Graphique Humidité
            const ctxHum = document.getElementById('graphiqueHum').getContext('2d');
            if (graphiqueHum) {
                graphiqueHum.data.labels = donnees.dates;
                graphiqueHum.data.datasets[0].data = donnees.humidites;
                graphiqueHum.update();
            } else {
                graphiqueHum = new Chart(ctxHum, {
                    type: 'line',
                    data: {
                        labels: donnees.dates,
                        datasets: [{
                            label: 'Humidité (%)',
                            data: donnees.humidites,
                            borderColor: 'rgb(54, 162, 235)',
                            backgroundColor: 'rgba(54, 162, 235, 0.1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.3
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                position: 'top',
                            }
                        }
                    }
                });
            }

            // Graphique Particule (TVOC)
            const ctxPar = document.getElementById('graphiquePar').getContext('2d');
            if (graphiquePar) {
                graphiquePar.data.labels = donnees.dates;
                graphiquePar.data.datasets[0].data = donnees.tvoc;
                graphiquePar.update();
            } else {
                graphiquePar = new Chart(ctxPar, {
                    type: 'line',
                    data: {
                        labels: donnees.dates,
                        datasets: [{
                            label: 'TVOC (ppb)',
                            data: donnees.tvoc,
                            borderColor: 'rgb(153, 102, 255)',
                            backgroundColor: 'rgba(153, 102, 255, 0.1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.3
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                position: 'top',
                            }
                        }
                    }
                });
            }

            // Graphique CO2
            const ctxCO2 = document.getElementById('graphiqueCO2').getContext('2d');
            if (graphiqueCO2) {
                graphiqueCO2.data.labels = donnees.dates;
                graphiqueCO2.data.datasets[0].data = donnees.eco2;
                graphiqueCO2.update();
            } else {
                graphiqueCO2 = new Chart(ctxCO2, {
                    type: 'line',
                    data: {
                        labels: donnees.dates,
                        datasets: [{
                            label: 'CO₂ (ppm)',
                            data: donnees.eco2,
                            borderColor: 'rgb(75, 192, 192)',
                            backgroundColor: 'rgba(75, 192, 192, 0.1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.3
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                position: 'top',
                            }
                        }
                    }
                });
            }
            
        } catch (error) {
            console.error('Erreur:', error);
        }
    }
    
    function rafraichirGraphiques() {
        chargerGraphiques();
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        chargerGraphiques();
        // Rafraîchir toutes les 30 secondes
        setInterval(chargerGraphiques, 30000);
    });
    </script>
</body>
</html>