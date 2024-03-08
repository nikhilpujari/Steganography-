import sys
import psutil
import time
from PIL import Image

start_time = time.time()

def encode_image(image, secret_message):
    # Convert the secret message to bytes
    message_bytes = secret_message.encode('utf-8')
    # Add a delimiter to mark the end of the message
    message_bytes += b'\xff'
    # Convert the message bytes to binary
    binary_message = ''.join(format(byte, '08b') for byte in message_bytes)
    # Check if the message is too long to fit in the image
    max_length = image.width * image.height * 3 // 8
    if len(binary_message) > max_length:
        raise ValueError('Message is too long to fit in the image.')
    # Embed the binary message in the image
    index = 0
    for y in range(image.height):
        for x in range(image.width):
            pixel = list(image.getpixel((x, y)))
            # Modify the least significant bit of each color channel
            for i in range(3):
                if index < len(binary_message):
                    pixel[i] = pixel[i] & ~1 | int(binary_message[index])
                    index += 1
            # Update the pixel in the image
            image.putpixel((x, y), tuple(pixel))
            # Stop embedding if the message is fully embedded
            if index >= len(binary_message):
                break
        if index >= len(binary_message):
            break
    return image

# Open the image
image = Image.open(sys.argv[1]).convert('RGB')

# Encode the message in the image
encoded_image = encode_image(image, sys.argv[2])

end_time = time.time()

# Save the encoded image
image_path=sys.argv[1]
encoded_image.save('forgot_'+sys.argv[3]+'_encoded.png')
imgName='forgot_'+sys.argv[3]+'_encoded.png'

cpu_percent = psutil.cpu_percent(interval=None)
print("CPU Utilization: ", cpu_percent)
print("Time taken: ", end_time - start_time, " seconds")
print("Image Name: ", imgName)