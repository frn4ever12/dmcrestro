# APK Output Directory

This directory contains the built APK files for the Nepal Restaurant SaaS mobile app.

## How to Build APK

### Prerequisites
- Flutter SDK installed (https://docs.flutter.dev/get-started/install/windows)
- Android SDK (included with Android Studio)
- Java Development Kit (JDK)

### Build Instructions

#### Windows:
```bash
cd mobile
build-apk.bat
```

#### Mac/Linux:
```bash
cd mobile
chmod +x build-apk.sh
./build-apk.sh
```

### Manual Build
```bash
cd mobile
flutter pub get
flutter build apk --release
```

The APK will be generated at: `build/app/outputs/flutter-apk/app-release.apk`

## APK Files

- `nepal-restaurant-saas.apk` - Release APK for distribution

## Installation

1. Transfer the APK to your Android device
2. Enable "Install from Unknown Sources" in device settings
3. Open the APK file to install

## Integration with Backend

Before building, configure the API URL in `lib/core/constants/app_constants.dart`:
```dart
static const String baseUrl = 'http://your-laravel-backend.com/api';
```

## Version Information

- App Version: 1.0.0+1
- Flutter SDK: 3.0+
- Target Android: 5.0+ (API 21+)
