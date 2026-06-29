# Nepal Restaurant Management Mobile App

Flutter mobile application for the Nepal Restaurant Management SaaS system.

## Features

### Role-Based Dashboards
- **Owner Dashboard**: Overview of restaurant performance, sales, and settings
- **Cashier Dashboard**: POS system for order processing and payments
- **Waiter Dashboard**: Table management, order taking, and order status tracking
- **Kitchen Dashboard**: Kitchen display system for order preparation
- **Customer Dashboard**: QR menu scanning, ordering, and loyalty points

### Key Features
- Authentication with Laravel Sanctum
- Real-time order updates
- QR code scanning for menu access
- Payment gateway integration (eSewa, Khalti, FonePay)
- Offline mode support
- Push notifications
- Nepali date support
- Multi-language support (English/Nepali)

## Tech Stack

- **Framework**: Flutter 3.0+
- **State Management**: Provider
- **Navigation**: GoRouter
- **HTTP**: Dio
- **Local Storage**: SharedPreferences & FlutterSecureStorage
- **QR Code**: QR Flutter & Mobile Scanner
- **Charts**: FL Chart

## Getting Started

### Prerequisites
- Flutter SDK 3.0+
- Dart 3.0+
- Android Studio / VS Code
- Android SDK / Xcode (for iOS)

### Installation

1. **Clone the repository**
```bash
git clone <repository-url>
cd mobile
```

2. **Install dependencies**
```bash
flutter pub get
```

3. **Configure API URL**
Update the API base URL in `lib/core/constants/app_constants.dart`:
```dart
static const String baseUrl = String.fromEnvironment(
  'API_BASE_URL',
  defaultValue: 'http://your-api-url.com/api',
);
```

4. **Run the app**
```bash
flutter run
```

## Project Structure

```
lib/
├── core/
│   ├── app.dart
│   ├── constants/
│   │   └── app_constants.dart
│   ├── models/
│   │   └── user_model.dart
│   ├── router/
│   │   └── app_router.dart
│   ├── services/
│   │   ├── api_service.dart
│   │   ├── auth_service.dart
│   │   └── storage_service.dart
│   └── theme/
│       └── app_theme.dart
├── screens/
│   ├── auth/
│   │   ├── login_screen.dart
│   │   └── register_screen.dart
│   ├── owner/
│   │   └── owner_dashboard_screen.dart
│   ├── cashier/
│   │   └── cashier_dashboard_screen.dart
│   ├── waiter/
│   │   └── waiter_dashboard_screen.dart
│   ├── kitchen/
│   │   └── kitchen_dashboard_screen.dart
│   └── customer/
│       └── customer_dashboard_screen.dart
└── main.dart
```

## API Integration

The app communicates with the Laravel backend API using Sanctum authentication.

### Authentication Endpoints
- `POST /api/login` - User login
- `POST /api/register` - User registration
- `POST /api/logout` - User logout
- `GET /api/profile` - Get user profile
- `PUT /api/profile` - Update user profile

### Protected Routes
All API endpoints require authentication token in the Authorization header:
```
Authorization: Bearer {token}
```

## Build & Release

### Quick Build (Recommended)
Use the provided build scripts for automated APK generation:

**Windows:**
```bash
build-apk.bat
```

**Mac/Linux:**
```bash
chmod +x build-apk.sh
./build-apk.sh
```

The APK will be automatically copied to `output/nepal-restaurant-saas.apk`

### Manual Build

**Android APK:**
```bash
flutter build apk --release
```

**Android App Bundle (for Play Store):**
```bash
flutter build appbundle --release
```

**iOS:**
```bash
flutter build ios --release
```

### APK Location
- Manual build: `build/app/outputs/flutter-apk/app-release.apk`
- Script build: `output/nepal-restaurant-saas.apk`

## Testing

```bash
flutter test
```

## Deployment

### Android
1. Generate signed APK/AAB
2. Upload to Google Play Console

### iOS
1. Generate IPA
2. Upload to App Store Connect

## Contributing

1. Fork the repository
2. Create a feature branch
3. Commit your changes
4. Push to the branch
5. Create a Pull Request

## License

This project is licensed under the MIT License.
