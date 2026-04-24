'''
Module s'occupant de récupérer et de pré-traiter les données du DHT-22
Permet de lire les données du capteur (si bien branché) avec un simple fonction
'''

import machine
import time
import dht
import network
import requests
from time import sleep
from picozero import pico_temp_sensor, pico_led
import rp2
import sys


pin = machine.Pin(15, machine.Pin.IN, machine.Pin.PULL_UP)
DHT22 = dht.DHT22(pin)

def get_mesurmenents() -> tuple:
    try:
        DHT22.measure()
        temp = DHT22.temperature()  # Gets the temperature in Celsius
        humidity = DHT22.humidity()  # Gets the relative humidity in %
        print("Temperature: {:.2f}°C, Humidity: {:.2f}%".format(temp, humidity))
        return (temp, humidity)
    except OSError as e:
        print("Failed to read from DHT22 sensor:", e)
    return None

