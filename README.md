# Nepal Restaurant Management SaaS

A comprehensive restaurant management SaaS platform built with Laravel 13, featuring POS, QR Menu, Accounting, Inventory, Kitchen Display, and Mobile App support with Nepal-specific features including Nepali Date (Bikram Sambat) and local payment gateways.

## Features

### Core Features
- **Multi-Tenant SaaS Architecture** - Support for unlimited restaurants with subscription plans
- **Multi-Branch Management** - Separate stock, sales, and kitchen per branch
- **POS Billing System** - Touch POS with barcode scanner and multiple payment methods
- **QR Menu** - Generate QR codes for tables, scan to order
- **Kitchen Display System (KDS)** - Real-time kitchen order management
- **Inventory Management** - Stock tracking, purchase orders, suppliers
- **Accounting** - Nepali Fiscal Year support, Chart of Accounts, Journals, Reports
- **HRM** - Employee management, attendance, payroll
- **CRM** - Customer management, loyalty points, feedback
- **Reports** - Sales, purchase, inventory, profit/loss reports

### Nepal-Specific Features
- **Nepali Date (Bikram Sambat)** - Automatic BS/AD conversion
- **Nepali Fiscal Year** - Shrawan-based fiscal year management
- **Local Payment Gateways** - eSewa, Khalti, FonePay, ConnectIPS
- **VAT 13%** - IRD-compatible invoicing
- **PAN Billing** - PAN number integration
- **Nepali/English Language** - Bilingual support
- **Thermal Printer** - 58mm/80mm printer support

### User Roles
- Super Admin, Owner, Manager, Cashier, Waiter, Kitchen, Chef, Accountant, Store Keeper, Delivery Boy, Customer, Auditor

## Technology Stack

### Backend
- **Framework**: Laravel 13
- **PHP**: 8.3
- **Database**: MySQL
- **Authentication**: Laravel Sanctum
- **Architecture**: Repository Pattern + Service Layer
- **Multi-Tenancy**: Single database with tenant_id

### Frontend
- **Blade Templates**
- **Bootstrap 5**
- **AdminLTE / Gentella**
- **VueJS (optional)**
- **Chart.js**
- **DataTables**
- **SweetAlert2**

### Mobile App (Flutter)
- Owner App, Cashier App, Waiter App, Kitchen App, Customer App
- Offline cache support
- Push notifications

## Installation

### Prerequisites
- PHP 8.3+
- MySQL 8.0+
- Composer
- Node.js & NPM

### Setup Steps

1. **Clone the repository**
```bash
git clone <repository-url>
cd nepal-restaurant-saas
```

2. **Install dependencies**
```bash
composer install
npm install
```

3. **Environment setup**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configure database**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nepal_restaurant_saas
DB_USERNAME=root
DB_PASSWORD=your_password
```

5. **Run migrations**
```bash
php artisan migrate
```

6. **Seed database**
```bash
php artisan db:seed
```

7. **Build assets**
```bash
npm run build
```

8. **Start development server**
```bash
php artisan serve
```

## Payment Gateway Configuration

### eSewa
```env
ESEWA_MERCHANT_CODE=your_merchant_code
ESEWA_SERVICE_CHARGE=0
ESEWA_TEST_MODE=true
```

### Khalti
```env
KHALTI_PUBLIC_KEY=your_public_key
KHALTI_SECRET_KEY=your_secret_key
KHALTI_TEST_MODE=true
```

### FonePay
```env
FONEPAY_MERCHANT_ID=your_merchant_id
FONEPAY_SECRET_KEY=your_secret_key
FONEPAY_TEST_MODE=true
```

### ConnectIPS
```env
CONNECTIPS_MERCHANT_ID=your_merchant_id
CONNECTIPS_SECRET_KEY=your_secret_key
CONNECTIPS_TEST_MODE=true
```

## API Documentation

The API is RESTful with Sanctum authentication. All endpoints are prefixed with `/api`.

### Authentication
**Login**
```bash
POST /api/login
{
  "email": "user@example.com",
  "password": "password"
}
```

**Response**
```json
{
  "message": "Login successful",
  "user": {...},
  "token": "access_token"
}
```

### Protected Routes
Include the token in the Authorization header:
```
Authorization: Bearer {token}
```

## Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Api/
│   │   └── Middleware/
│   └── Requests/
├── Models/
├── Repositories/
│   ├── Contracts/
│   └── BaseRepository.php
├── Services/
│   ├── Payment/
│   └── NepaliDateService.php
├── Scopes/
└── Traits/
```

## Key Services

### Nepali Date Service
Convert between AD (Gregorian) and BS (Bikram Sambat):
```php
use App\Helpers\NepaliDateHelper;

$bsDate = NepaliDateHelper::toBs('2024-01-01');
$fiscalYear = NepaliDateHelper::currentFiscalYear();
```

### Payment Service
Process payments through various gateways:
```php
use App\Services\Payment\PaymentService;

$paymentService = app(PaymentService::class);
$result = $paymentService->initiatePayment('khalti', $data);
```

## Testing

```bash
php artisan test
```

## Deployment

### Production Checklist
- [ ] Set `APP_ENV=production` in `.env`
- [ ] Set `APP_DEBUG=false`
- [ ] Configure production database
- [ ] Set up SSL certificate
- [ ] Configure queue worker
- [ ] Set up cron jobs for scheduler
- [ ] Configure backup system
- [ ] Enable caching
- [ ] Optimize composer: `composer install --optimize-autoloader --no-dev`
- [ ] Optimize assets: `npm run build`

## License

This project is licensed under the MIT License.

## Support

For support and documentation, visit the project wiki or contact the development team.

## Credits

Built with Laravel 13
Nepal Restaurant Management SaaS © 2024
