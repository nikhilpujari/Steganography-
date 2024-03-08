import sys
from PIL import Image
import chardet

def decode_image(image_path):
    # Open the encoded image and convert it to RGB format
    image = Image.open(image_path).convert('RGB')
    # Extract the binary message from the image
    binary_message = ''
    for y in range(image.height):
        for x in range(image.width):
            pixel = list(image.getpixel((x, y)))
            # Extract the least significant bit of each color channel
            for i in range(3):
                binary_message += str(pixel[i] & 1)
            # Check if the delimiter is present at the end of the message
            if binary_message[-16:] == '1111111111111110':
                # Remove the delimiter from the binary message
                binary_message = binary_message[:-16]
                # Convert the binary message to text
                message_bytes = [int(binary_message[i:i+8], 2) for i in range(0, len(binary_message), 8)]
                
                # Try decoding the message using different encodings
                encodings = ['utf-8', 'iso-8859-1', 'cp1252']
                for encoding in encodings:
                    try:
                        message = bytes(message_bytes).decode(encoding).rstrip('\0')
                        return message
                    except UnicodeDecodeError:
                        continue
                raise ValueError('Unable to decode message using any of the supported encodings.')
                
    # If the delimiter is not present, the message was not fully embedded
    raise ValueError('Message not found in the image.')

# Call the function and print the message
if __name__ == '__main__':
    decoded_message = decode_image(sys.argv[1])
    print(decoded_message)
