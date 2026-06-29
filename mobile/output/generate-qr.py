"""
QR Code Generator for APK File
This script generates a QR code for the APK file.
Requires: pip install qrcode pillow
"""

import qrcode
from qrcode.constants import ERROR_CORRECT_L

# APK file path (local file - for actual use, replace with hosted URL)
apk_path = r"C:\Users\Lenovo\CascadeProjects\nepal-restaurant-saas\mobile\output\nepal-restaurant-saas.apk"

# For actual use, replace with your hosted URL:
# download_url = "https://your-server.com/nepal-restaurant-saas.apk"
download_url = apk_path

# Create QR code
qr = qrcode.QRCode(
    version=1,
    error_correction=ERROR_CORRECT_L,
    box_size=10,
    border=4,
)

qr.add_data(download_url)
qr.make(fit=True)

# Create QR code image with custom colors
img = qr.make_image(fill_color="#667eea", back_color="white")

# Save QR code
img.save(r"C:\Users\Lenovo\CascadeProjects\nepal-restaurant-saas\mobile\output\apk-qr-code.png")
print("QR Code generated successfully: apk-qr-code.png")
print(f"QR Code data: {download_url}")
