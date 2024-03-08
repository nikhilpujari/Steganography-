import sys
import cv2
import numpy as np
import pywt

start_time = time.time()

def extract_text_from_image(image_path):
    # Load the image
    img = cv2.imread(image_path, cv2.IMREAD_GRAYSCALE)

    # Perform 2D Integer Wavelet Transform
    coeffs = pywt.wavedec2(img, 'haar')

    # Get the coefficients of the high frequency sub-bands
    h1 = coeffs[1][0]
    h2 = coeffs[2][0]
    h3 = coeffs[3][0]

    # Flatten the high frequency sub-bands and combine them
    h = np.concatenate((h1.flatten(), h2.flatten(), h3.flatten()))

    # Extract the text bits from the least significant bit of each high frequency coefficient
    binary_text = ''
    for i in range(len(h)):
        bit = h[i] & 1
        binary_text += str(bit)
        if binary_text[-8:] == '11111111':
            break

    # Convert the binary text to ASCII characters
    text = ''
    for i in range(0, len(binary_text)-8, 8):
        byte = binary_text[i:i+8]
        text += chr(int(byte, 2))

    return text

# Example usage
output_path = sys.argv[1]

extracted_text = extract_text_from_image(output_path)
