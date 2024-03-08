import sys
import psutil
import time
from PIL import Image

start_time = time.time()

def encode(image_path, secret_message):
    img = Image.open(image_path)
    width, height = img.size
    secret_message += "~"
    binary_message = ''.join(format(ord(i), '08b') for i in secret_message)
    print("Binary message:", binary_message)
    message_length = len(binary_message)
    if message_length > (width * height * 3) // 2:
        raise ValueError("Secret message too large to be encoded in the given image")
    encoded_pixels = 0
    for x in range(width):
        for y in range(height):
            if encoded_pixels < message_length:
                pixel = img.getpixel((x, y))
                if isinstance(pixel, int):
                    # For images with only one channel (e.g. grayscale images)
                    r, g, b = pixel, pixel, pixel
                    a = 255
                else:
                    # For images with RGB or RGBA channels
                    r, g, b = pixel[:3]
                    a = 255 if len(pixel) == 3 else pixel[3]
                if x == 0 and y == 0:
                    r = ord('0') + int(binary_message[encoded_pixels], 2)
                elif encoded_pixels < message_length:
                    if encoded_pixels % 3 == 0:
                        r = r - (r % 2) + int(binary_message[encoded_pixels], 2)
                    elif encoded_pixels % 3 == 1:
                        g = g - (g % 2) + int(binary_message[encoded_pixels], 2)
                    elif encoded_pixels % 3 == 2:
                        b = b - (b % 2) + int(binary_message[encoded_pixels], 2)
                encoded_pixels += 1
                img.putpixel((x, y), (r, g, b, a) if isinstance(pixel, tuple) else (r,))
    img.save(image_path.split('.')[0] + "_encoded.png")


image_path=sys.argv[1]
encode(image_path,sys.argv[2])
imgName=image_path[:-4] + '_encoded.png';
end_time = time.time()

cpu_percent = psutil.cpu_percent(interval=None)
print("CPU Utilization: ", cpu_percent)
print("Time taken: ", end_time - start_time, " seconds")
print("Image Name: ", imgName)
