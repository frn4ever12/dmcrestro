#!/bin/bash
# Nepal Restaurant SaaS - APK Build Script
# This script builds the Android APK for the mobile app
# Requires Flutter SDK and Android SDK to be installed

echo "========================================"
echo "Nepal Restaurant SaaS - APK Builder"
echo "========================================"
echo ""

# Check if Flutter is installed
if ! command -v flutter &> /dev/null; then
    echo "ERROR: Flutter SDK is not installed or not in PATH"
    echo "Please install Flutter from: https://docs.flutter.dev/get-started/install"
    echo ""
    exit 1
fi

echo "Flutter SDK found"
flutter --version
echo ""

# Navigate to mobile directory
cd "$(dirname "$0")"

# Install dependencies
echo "Installing Flutter dependencies..."
flutter pub get
if [ $? -ne 0 ]; then
    echo "ERROR: Failed to install dependencies"
    exit 1
fi

echo "Dependencies installed successfully"
echo ""

# Build APK
echo "Building release APK..."
flutter build apk --release
if [ $? -ne 0 ]; then
    echo "ERROR: Failed to build APK"
    exit 1
fi

echo ""
echo "========================================"
echo "APK Build Successful!"
echo "========================================"
echo "APK Location: build/app/outputs/flutter-apk/app-release.apk"
echo ""

# Create output directory if it doesn't exist
mkdir -p output

# Copy APK to output folder
cp build/app/outputs/flutter-apk/app-release.apk output/nepal-restaurant-saas.apk

echo "APK copied to: output/nepal-restaurant-saas.apk"
echo ""
