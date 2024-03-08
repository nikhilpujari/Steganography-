import sys
import psutil
import time
import numpy as np
from scipy.fftpack import dct, idct
from PIL import Image

start_time = time.time()

# Convert a string message into a binary string of 0s and 1s
def str2bits(msg):
    return ''.join([format(ord(c), '08b') for c in msg])


# Embedding function
def embed_sst(cover_img_path, secret_msg, alpha, beta):
    cover_img = Image.open(cover_img_path).convert('L')
    cover_pixels = np.array(cover_img)
    
    # Compute the DCT of the cover image
    cover_dct = dct(dct(cover_pixels.T, norm='ortho').T, norm='ortho')
    
    # Compute the embedding strengths
    embed_coeffs = np.zeros_like(cover_dct)
    sorted_dct = np.sort(np.abs(cover_dct), axis=None)
    threshold = sorted_dct[-alpha]
    for i in range(embed_coeffs.shape[0]):
        for j in range(embed_coeffs.shape[1]):
            if abs(cover_dct[i, j]) > threshold:
                embed_coeffs[i, j] = beta * cover_dct[i, j] / abs(cover_dct[i, j])
    
    # Embed the secret message into the cover image
    msg_bits = str2bits(secret_msg)
    msg_len = len(msg_bits)
    bits_embedded = 0
    for i in range(0, embed_coeffs.shape[0], 8):
        for j in range(0, embed_coeffs.shape[1], 8):
            if bits_embedded >= msg_len:
                break
            block = embed_coeffs[i:i+8, j:j+8]
            dct_block = dct(dct(block.T, norm='ortho').T, norm='ortho')
            for k in range(8):
                for l in range(8):
                    if bits_embedded >= msg_len:
                        break
                    bit = int(msg_bits[bits_embedded])
                    if bit == 1:
                        if dct_block[k, l] < 0:
                            dct_block[k, l] -= 1
                        else:
                            dct_block[k, l] += 1
                    bits_embedded += 1
            embed_coeffs[i:i+8, j:j+8] = idct(idct(dct_block.T, norm='ortho').T, norm='ortho')
        if bits_embedded >= msg_len:
            break
    
    # Compute the inverse DCT of the stego image
    stego_dct = cover_dct + embed_coeffs
    stego_pixels = idct(idct(stego_dct.T, norm='ortho').T, norm='ortho')
    stego_img = Image.fromarray(np.uint8(stego_pixels))
    
    return stego_img

image_path=sys.argv[1]
message=sys.argv[2]
stego_img = embed_sst(image_path, message, 1000, 0.01)
stego_img.save(image_path[:-4] + '_encoded.png')
imgName=image_path[:-4] + '_encoded.png';

end_time = time.time()

cpu_percent = psutil.cpu_percent(interval=None)
print("CPU Utilization: ", cpu_percent)
print("Time taken: ", end_time - start_time, " seconds")
print("Image Name: ", imgName)

