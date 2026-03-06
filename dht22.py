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

print ('Connected - press BOOTSEL to quit')

while True:
    if rp2.bootsel_button() == 1:
        pico_led.off()
        print('ByBye')
        sys.exit()
    payload = "temp=20&hum=35"
    response = requests.post('http://193.48.125.214/ETRSTPCAP/recupdonnee.php',
                             data=payload.encode('utf-8'),
                             headers={'Content-Type': 'application/x-www-form-urlencoded'})
    response_code = response.status_code
    response_content = response.content
    print('Response code: ', response_code)
    print('Response content:', response_content)
    sleep(2)


"""
DHT22_PIN = 0 # The Raspberry Pi Pico pin (GP0) connected to the DHT22 sensor

# Initialize the DHT22 sensor
pin = machine.Pin(15, machine.Pin.IN, machine.Pin.PULL_UP);
DHT22 = dht.DHT22(pin)

# Read data from the sensor every 2 seconds
while True:
    try:
        DHT22.measure()
        temp = DHT22.temperature()  # Gets the temperature in Celsius
        humidity = DHT22.humidity()  # Gets the relative humidity in %
        print("Temperature: {:.2f}°C, Humidity: {:.2f}%".format(temp, humidity))
    except OSError as e:
        print("Failed to read from DHT22 sensor:", e)

    time.sleep(2)
    """