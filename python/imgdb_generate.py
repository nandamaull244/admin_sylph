# generate_imgdb.py
import os
import cv2
import numpy as np
import requests
from supabase import create_client, Client

SUPABASE_URL = "https://zqwvmdjwuaeolmdswpvp.supabase.co"
SUPABASE_KEY = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6Inpxd3ZtZGp3dWFlb2xtZHN3cHZwIiwicm9sZSI6InNlcnZpY2Vfcm9sZSIsImlhdCI6MTc0Mzg1NDU1NCwiZXhwIjoyMDU5NDMwNTU0fQ.w_BjQ8ACtuwtkKmb99u4ZlNG18ZApEEsEHYmCS7MEW4"
bucket = "media"
folder = "imgdb_files"

supabase: Client = create_client(SUPABASE_URL, SUPABASE_KEY)

def download_image(url, save_path):
    r = requests.get(url)
    with open(save_path, 'wb') as f:
        f.write(r.content)

def generate_imgdb_from_image(image_path, output_path):
    image = cv2.imread(image_path, cv2.IMREAD_GRAYSCALE)
    if image is None:
        raise Exception("Gagal memuat gambar")
    
    # Simulasi: hanya simpan array flattened (contoh saja)
    descriptors = cv2.ORB_create().detectAndCompute(image, None)[1]
    if descriptors is None:
        raise Exception("Tidak ada fitur yang terdeteksi")
    np.save(output_path, descriptors)

def upload_to_supabase(local_path, filename):
    with open(local_path, 'rb') as f:
        res = supabase.storage.from_(bucket).upload(f"{folder}/{filename}", f, {"upsert": True})
    return f"{SUPABASE_URL}/storage/v1/object/public/{bucket}/{folder}/{filename}"

def process(image_url, output_name):
    temp_image = "temp_image.jpg"
    temp_output = f"{output_name}.npy"

    download_image(image_url, temp_image)
    generate_imgdb_from_image(temp_image, temp_output)
    url = upload_to_supabase(temp_output, f"{output_name}.imgdb")

    os.remove(temp_image)
    os.remove(temp_output)

    return url
