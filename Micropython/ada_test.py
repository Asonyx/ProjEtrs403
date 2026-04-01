import time
import machine
import adafruit_ens160

# 1. Configuration de l'I2C (Pins par défaut du Pico : GP4=SDA, GP5=SCL)
# On utilise machine.I2C car on est sous MicroPython
i2c = machine.I2C(0, scl=machine.Pin(5), sda=machine.Pin(4), freq=100000)

# 2. Initialisation du capteur
# Note : L'adresse par défaut est souvent 0x53 (ou 0x52 selon ton module)
ens = adafruit_ens160.ENS160(i2c, address=0x53) 

# Optionnel : régler la température/humidité pour plus de précision
ens.temperature_compensation = 25.0
ens.humidity_compensation = 50.0

print("Capteur ENS160 prêt !")

while True:
    # Lecture des valeurs
    aqi = ens.AQI
    tvoc = ens.TVOC
    eco2 = ens.eCO2
    
    print("=" * 20)
    print(f"Qualité de l'air (AQI): {aqi}") # 1 (Excellent) à 5 (Médiocre)
    print(f"TVOC: {tvoc} ppb")
    print(f"eCO2: {eco2} ppm")
    
    # Statut du capteur (0: Normal, 1: Warmup, 2: Initial Start, 3: Invalid)
    print(f"Statut: {ens.status}")
    
    time.sleep(2)