import sys
import cv2
import psutil
import time
import numpy as np

start_time = time.time()

# Define the path of the cover image
cover_path = sys.argv[1];

# Define the secret message to be encoded
secret_message = sys.argv[2];

# Convert the secret message to binary
binary_message = ''.join(format(ord(i), '08b') for i in secret_message)

# Load the cover image
cover_image = cv2.imread(cover_path)

# Convert the cover image to grayscale
gray_cover = cv2.cvtColor(cover_image, cv2.COLOR_BGR2GRAY)

# Perform the DCT on the cover image
dct = cv2.dct(np.float32(gray_cover)/255.0)

# Embed the secret message in the DC coefficients of the DCT blocks
block_size = 8
height, width = gray_cover.shape[:2]
num_blocks = (height // block_size) * (width // block_size)

for i, bit in enumerate(binary_message):
    # Convert the bit to an integer
    secret_bit = int(bit)
    # Get the index of the block to embed the bit in
    block_index = i % num_blocks
    # Calculate the row and column indices of the block
    row_index = (block_index // (width // block_size)) * block_size
    col_index = (block_index % (width // block_size)) * block_size
    # Get the DCT block to embed the bit in
    dct_block = dct[row_index:row_index+block_size, col_index:col_index+block_size]
    # Embed the bit in the least significant bit of the DC coefficient
    dct_block[0, 0] += secret_bit

# Perform the inverse DCT on the modified DCT coefficients
stego_dct = cv2.idct(dct) * 255.0

# Save the stego image
imgName=cover_path[:-4] + '_encoded.png';
cv2.imwrite(imgName, stego_dct)

end_time = time.time()

cpu_percent = psutil.cpu_percent(interval=None)
print("CPU Utilization: ", cpu_percent)
print("Time taken: ", end_time - start_time, " seconds")
print("Image Name: ", imgName)
