'''
Module s'occupant de récupérer et pré-traiter les données du capteur ENS-160

'''


from machine import Pin, I2C
import time

# Initialisation I2C
i2c = I2C(0, scl=Pin(1), sda=Pin(0), freq=100000)

ENS160_ADDR = 0x53

# Registres principaux
REG_OPMODE = 0x10
REG_STATUS = 0x20
REG_AQI = 0x21
REG_TVOC = 0x22
REG_ECO2 = 0x24

# Modes
MODE_RESET = 0xF0
MODE_STANDARD = 0x02

def write_reg(reg, value):
    '''
    Permet d'écrirer dans le registre I2C 'reg' 
    '''
    i2c.writeto_mem(ENS160_ADDR, reg, bytes([value]))

def read_reg(reg, nbytes=1):
    '''
    Permet de lire le registre I2C 'reg'
    '''
    return i2c.readfrom_mem(ENS160_ADDR, reg, nbytes)

# initialisation du capteur
write_reg(REG_OPMODE, MODE_RESET)
time.sleep(1)

write_reg(REG_OPMODE, MODE_STANDARD)
time.sleep(1)

# Fonctions

def get_mesurements() -> tuple:
    status = read_reg(REG_STATUS)[0]
    if not (status & 0x02):
        return None # Pas de nouvelles données
    # On lit les valeurs du capteur directement depuis les registres
    aqi = int.from_bytes(read_reg(REG_AQI), 'little')
    tvoc = int.from_bytes(read_reg(REG_TVOC, 2), 'little')
    eco2 = int.from_bytes(read_reg(REG_ECO2, 2), 'little')
    print(f"AQI : {aqi}/5, TVOC : {tvoc} ppb, eCO2 : {eco2} ppm")
    return (aqi, tvoc, eco2)
    
