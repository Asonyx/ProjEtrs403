import machine
import time
import dht
import network
import requests
from time import sleep
from picozero import pico_temp_sensor, pico_led
import rp2
import sys


ssid = 'wifirpi'
password = '88E4VB1YQBI15TM4UCK9KP1LWQ'
server_ip = "193.48.125.214"

pin = machine.Pin(15, machine.Pin.IN, machine.Pin.PULL_UP);
DHT22 = dht.DHT22(pin)

def connect():
    #Connect to WLAN
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

ip = connect();

def get_mesurmenents():
    try:
        DHT22.measure()
        temp = DHT22.temperature()  # Gets the temperature in Celsius
        humidity = DHT22.humidity()  # Gets the relative humidity in %
        print("Temperature: {:.2f}°C, Humidity: {:.2f}%".format(temp, humidity))
        return (temp, humidity)
    except OSError as e:
        print("Failed to read from DHT22 sensor:", e)
    return None
    

print ('Connected - press BOOTSEL to quit')

while True:
    if rp2.bootsel_button() == 1:
        pico_led.off()
        print('ByBye')
        sys.exit()
    temp, hum = get_mesurmenents()
    payload = f"temp={temp}&hum={hum}"
    try:
        response = requests.post(f'http://{server_ip}/ETRSTPCAP/recupdonnee.php',
                             data=payload.encode('utf-8'),
                             headers={'Content-Type': 'application/x-www-form-urlencoded'})
        response_code = response.status_code
        response_content = response.content
        print('Response code: ', response_code)
        print('Response content:', response_content)
    except OSError as e:
        print("Unable to communicate with server", e);
    sleep(10)
