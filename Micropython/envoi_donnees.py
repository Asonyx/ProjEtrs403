import machine
import time
import dht
import network
import requests
from time import sleep
from picozero import pico_temp_sensor, pico_led
import rp2
import sys
import dht22
import ens160


ssid = 'wifirpi'
password = '88E4VB1YQBI15TM4UCK9KP1LWQ'
server_ip = "193.48.125.214"


def connect():
    '''
    Connecte la carte pico 2 au Wi-Fi
    '''
    wlan = network.WLAN(network.STA_IF)
    wlan.active(True)
    wlan.connect(ssid, password)
    while wlan.isconnected() == False:
        if rp2.bootsel_button() == 1:
            sys.exit()
        print('Waiting for connection...')
        pico_led.on()
        sleep(0.5)
        pico_led.off()
        sleep(0.5)
    ip = wlan.ifconfig()[0]
    print(f'Connected on {ip}')
    pico_led.on()
    return ip

# On se connecte tout d'abord au réseau
ip = connect()

def send_mesurement_to_server(temp, hum, aqi, tvoc, eco2, ipAdress: str) -> bool :
    '''
    Envoie les données des capteurs au serveur pour traitement des données
    '''
    payload = f"temp={temp}&hum={hum}&aqi={aqi}&tvoc={tvoc}&eco2={eco2}"
    try:
        response = requests.post(f'http://{ipAdress}/ETRSTPCAP/recupdonnee.php',
                        data=payload.encode('utf-8'),
                        headers={'Content-Type': 'application/x-www-form-urlencoded'})
        response_code = response.status_code
        print('Response code: ', response_code)
        return response_code == 200
    except OSError as e:
        print("Unable to communicate with server", e)
    return False
    

def should_send_data(lastTimeSent, lastTemperature, lastHuimidity, temp, hum, lastaqi, lasttvoc, lasteco2, aqi, tvoc, eco2):
    '''
    Détermine si il est nécessaire de renvoyer des nouvelles données au serveur
    '''
    res = False
    temps = time.time()
    if (temps - lastTimeSent)>30 or abs(temp - lastTemperature)>2 or abs(hum - lastHuimidity)>10 or abs(aqi - lastaqi)>1 or abs(tvoc - lasttvoc)>20 or abs(eco2 - lasteco2)>50 :
        res = True
    return res


# Initialisation des variables à None

# Variables correspondant aux dernières informations envoyées au serveur
lastTime, lastTemp, lastHum, lastHum, lastAqi, lastTvoc, lastEco2 = ([None]*7)
# Variables correspondant aux dernières mesures des capteurs
temp, hum, aqi, tvoc, eco2 = ([None] * 5)

# Boucle principale de l'application
while True:
    if rp2.bootsel_button() == 1:
        pico_led.off()
        print('ByBye')
        sys.exit()
    
    # On récupère les mesures des capteurs
    dht_22_mesurements = dht22.get_mesurmenents()
    ens160_mesurements = ens160.get_mesurements()
    
    # Si les données mesurés ne sont pas nulles, alors on mets à jours les variables
    if dht_22_mesurements is not None:
        temp, hum = dht_22_mesurements
    if ens160_mesurements is not None:
        aqi, tvoc, eco2 = ens160_mesurements

    # Tant qu'on n'a pas toute les données, on ne passe pas à l'étape suivante
    if temp is None or hum is None or aqi is None or tvoc is None or eco2 is None:
        sleep(1)
        continue

    # Vérifie s'il faut envoyer les données, et envoie les données si les conditions sont réalisées
    if lastTime is None or lastTemp is None or lastHum is None or lastAqi is None or lastTvoc is None or lastEco2 is None\
            or should_send_data(lastTime, lastTemp, lastHum, temp, hum, lastAqi, lastTvoc, lastEco2, aqi, tvoc, eco2):
        success = send_mesurement_to_server(temp, hum, aqi, tvoc, eco2, server_ip)
        if not success:
            print("Unable to send data to server")
        else :
            # On met à jour les informations à propos du dernier envoie de données
            lastTime = time.time()
            lastTemp, lastHum, lastAqi, lastTvoc, lastEco2 = temp, hum, aqi, tvoc, eco2
            

        # else wait        
    sleep(5)
