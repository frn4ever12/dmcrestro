@echo off
REM Nepal Restaurant SaaS - APK Build Script
REM This script builds the Android APK for the mobile app
REM Requires Flutter SDK and Android SDK to be installed

echo ========================================
echo Nepal Restaurant SaaS - APK Builder
echo ========================================
echo.

REM Check if Flutter is installed
where flutter >nul 2>nul
if %ERRORLEVEL% NEQ 0 (
    echo ERROR: Flutter SDK is not installed or not in PATH
    echo Please install Flutter from: https://docs.flutter.dev/get-started/install/windows
    echo.
    pause
    exit /b 1
)

echo Flutter SDK found
flutter --version
echo.

REM Navigate to mobile directory
cd /d "%~dp0"

REM Install dependencies
echo Installing Flutter dependencies...
flutter pub get
if %ERRORLEVEL% NEQ 0 (
    echo ERROR: Failed to install dependencies
    pause
    exit /b 1
)

echo Dependencies installed successfully
echo.

REM Build APK
echo Building release APK...
flutter build apk --release
if %ERRORLEVEL% NEQ 0 (
    echo ERROR: Failed to build APK
    pause
    exit /b 1
)

echo.
echo ========================================
echo APK Build Successful!
echo ========================================
echo APK Location: build\app\outputs\flutter-apk\app-release.apk
echo.

REM Create output directory if it doesn't exist
if not exist "output" mkdir output

REM Copy APK to output folder
copy "build\app\outputs\flutter-apk\app-release.apk" "output\nepal-restaurant-saas.apk"

echo APK copied to: output\nepal-restaurant-saas.apk
echo.
pause
