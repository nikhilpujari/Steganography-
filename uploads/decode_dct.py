import sys
import cv2
import numpy as np

# Define the path of the stego image
stego_path = sys.argv[1];

# Load the stego image
stego_image = cv2.imread(stego_path)

# Convert the stego image to grayscale
gray_stego = cv2.cvtColor(stego_image, cv2.COLOR_BGR2GRAY)

# Perform the DCT on the stego image
dct = cv2.dct(np.float32(gray_stego)/255.0)

# Extract the secret message from the DC coefficients of the DCT blocks
block_size = 8
height, width = gray_stego.shape[:2]
num_blocks = (height // block_size) * (width // block_size)

binary_message = ""

for i in range(num_blocks):
    # Calculate the row and column indices of the block
    row_index = (i // (width // block_size)) * block_size
    col_index = (i % (width // block_size)) * block_size
    # Get the DCT block to extract the secret bit from
    dct_block = dct[row_index:row_index+block_size, col_index:col_index+block_size]
    # Extract the least significant bit of the DC coefficient
    secret_bit = int(round(dct_block[0, 0])) % 2
    # Add the secret bit to the binary message
    binary_message += str(secret_bit)

#print("Binary message:", binary_message)

# Convert the binary message to ASCII
message = ""
for i in range(0, len(binary_message), 8):
    byte = binary_message[i:i+8]
    message += chr(int(byte, 2))

print(message)
