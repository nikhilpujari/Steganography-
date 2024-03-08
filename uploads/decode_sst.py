import sys
import numpy as np
from scipy.fftpack import dct, idct
from PIL import Image


# Extracting function
def extract_sst(stego_img_path, alpha, beta):
    stego_img = Image.open(stego_img_path).convert('L')
    stego_pixels = np.array(stego_img)
    
    # Compute the DCT of the stego image
    stego_dct = dct(dct(stego_pixels.T, norm='ortho').T, norm='ortho')
    
    # Compute the embedding strengths
    sorted_dct = np.sort(np.abs(stego_dct), axis=None)
    threshold = sorted_dct[-alpha]
    embed_coeffs = np.zeros_like(stego_dct)
    for i in range(embed_coeffs.shape[0]):
        for j in range(embed_coeffs.shape[1]):
            if abs(stego_dct[i, j]) > threshold:
                embed_coeffs[i, j] = beta * stego_dct[i, j] / abs(stego_dct[i, j])
    
    # Extract the secret message from the stego image
    secret_msg_len = ""
    for i in range(0, 16):
        if stego_dct[i, 0] >= threshold:
            secret_msg_len += "1"
        else:
            secret_msg_len += "0"
    secret_msg_len = int(secret_msg_len, 2)
    secret_msg_bin = ""
    bits_extracted = 0
    for i in range(16, embed_coeffs.shape[0], 8):
        for j in range(16, embed_coeffs.shape[1], 8):
            if bits_extracted >= secret_msg_len:
                break
            block = embed_coeffs[i:i+8, j:j+8]
            dct_block = dct(dct(block.T, norm='ortho').T, norm='ortho')
            if dct_block.shape[0] < 3 or dct_block.shape[1] < 3:
                continue
            for k in range(8):
                for l in range(8):
                    if bits_extracted >= secret_msg_len:
                        break
                    if dct_block[k, l] >= threshold:
                        secret_msg_bin += "1"
                    else:
                        secret_msg_bin += "0"
                    bits_extracted += 1
            if bits_extracted >= secret_msg_len:
                break
        if bits_extracted >= secret_msg_len:
            break
            
    secret_msg = ""
    for i in range(0, len(secret_msg_bin), 8):
        secret_msg += chr(int(secret_msg_bin[i:i+8], 2))
    
    return secret_msg

secret_msg = extract_sst(sys.argv[1], 1000, 0.01)
print(secret_msg)
