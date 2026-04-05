# نظام إدارة مقاول العمالة - Labor Contractor Management System

## 📋 Overview

A complete Laravel 12 web-based system for managing labor contractors in Egyptian agricultural companies. Includes worker distribution, wage calculations, deductions, advances, and collection management.

## ✨ Features

### Core Features
- **Authentication**: Phone-based login with role-based access (Admin/Contractor)
- **Company Management**: Track companies with daily wages and payment cycles
- **Worker Management**: Manage workers with personal information and contact details
- **Daily Distribution**: Assign workers to companies with automatic wage snapshots
- **Deductions**: Track worker deductions (quarter, half, full, or custom amounts)
- **Advances**: Manage worker advances with settlement tracking
- **Collections**: Generate and track payment collections from companies

### Technical Features
- **Layered Architecture**: Strict separation of concerns (Controllers → Services → Repositories)
- **Repository Pattern**: All data access through repository interfaces
- **Arabic Support**: Full RTL layout and Arabic validation messages
- **Form Validation**: Comprehensive validation with Arabic error messages
- **Soft Deletes**: Reversible deletion for companies and workers
- **Database**: MySQL with proper relationships and constraints

---

## 🚀 Quick Setup

### Prerequisites
- PHP 8.2+
- MySQL 8.0+
- Composer
- Node.js & npm

### Installation Steps

1. **Composer Install**
```bash
composer install
```

2. **Environment Setup**
```bash
cp .env.example .env
php artisan key:generate
```

3. **Database Configuration**
Edit `.env`:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=labor_contractor
DB_USERNAME=root
DB_PASSWORD=
```

4. **Run Migrations & Seeders**
```bash
php artisan migrate
php artisan db:seed
```

5. **Build Assets**
```bash
npm install
npm run dev
```

6. **Start Server**
```bash
php artisan serve
```

Visit `http://localhost:8000` and log in!

---

## 👤 Default Login Credentials

### Admin
- **Phone**: +201000000000
- **Password**: password

### Contractor 1
- **Phone**: +201001111111
- **Password**: password

### Contractor 2
- **Phone**: +201002222222
- **Password**: password

---

## 📁 Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Auth/AuthController.php
│   │   ├── Contractor/
│   │   │   ├── DashboardController.php
│   │   │   ├── CompanyController.php
│   │   │   ├── WorkerController.php
│   │   │   ├── DistributionController.php
│   │   │   ├── DeductionController.php
│   │   │   ├── AdvanceController.php
│   │   │   └── CollectionController.php
│   │   └── Admin/AdminController.php
│   ├── Middleware/CheckRole.php
│   └── Requests/
│       ├── StoreCompanyRequest.php
│       ├── StoreWorkerRequest.php
│       ├── StoreDistributionRequest.php
│       ├── StoreDeductionRequest.php
│       ├── StoreAdvanceRequest.php
│       ├── StoreCollectionRequest.php
│       └── ...
├── Models/
│   ├── User.php
│   ├── Company.php
│   ├── Worker.php
│   ├── DailyDistribution.php
│   ├── Deduction.php
│   ├── Advance.php
│   └── Collection.php
├── Repositories/
│   ├── Interfaces/
│   │   ├── CompanyRepositoryInterface.php
│   │   ├── WorkerRepositoryInterface.php
│   │   ├── DistributionRepositoryInterface.php
│   │   ├── DeductionRepositoryInterface.php
│   │   ├── AdvanceRepositoryInterface.php
│   │   └── CollectionRepositoryInterface.php
│   ├── CompanyRepository.php
│   ├── WorkerRepository.php
│   ├── DistributionRepository.php
│   ├── DeductionRepository.php
│   ├── AdvanceRepository.php
│   └── CollectionRepository.php
├── Services/
│   ├── DistributionService.php
│   ├── WageCalculationService.php
│   ├── DeductionService.php
│   ├── AdvanceService.php
│   └── CollectionService.php
└── Exceptions/
    ├── DuplicateDistributionException.php
    └── InsufficientWageException.php

database/
├── migrations/
│   ├── 0001_01_01_000000_create_users_table.php
│   ├── 0001_01_01_000003_create_companies_table.php
│   ├── 0001_01_01_000004_create_workers_table.php
│   ├── 0001_01_01_000005_create_daily_distributions_table.php
│   ├── 0001_01_01_000006_create_deductions_table.php
│   ├── 0001_01_01_000007_create_advances_table.php
│   └── 0001_01_01_000008_create_collections_table.php
└── seeders/
    ├── UserSeeder.php
    ├── CompanySeeder.php
    ├── WorkerSeeder.php
    ├── DistributionSeeder.php
    ├── DeductionSeeder.php
    └── AdvanceSeeder.php

resources/views/
├── layouts/
│   ├── app.blade.php
│   └── auth.blade.php
├── auth/
│   └── login.blade.php
└── contractor/ & admin/
    ├── dashboard/
    ├── companies/
    ├── workers/
    ├── distributions/
    ├── deductions/
    ├── advances/
    └── collections/
```

---

## 🔑 Key Routes

### Authentication
- `GET  /login` - Login page
- `POST /login` - Process login
- `POST /logout` - Logout

### Contractor Routes (Prefix: `/contractor`)
| Route | Method | Purpose |
|-------|--------|---------|
| `/dashboard` | GET | View dashboard stats |
| `/companies` | GET | List companies |
| `/companies` | POST | Create company |
| `/workers` | GET | List workers |
| `/workers` | POST | Create worker |
| `/distributions` | GET | View daily distributions |
| `/distributions` | POST | Create distribution |
| `/deductions` | GET | List deductions |
| `/deductions` | POST | Create deduction |
| `/advances` | GET | List advances |
| `/advances` | POST | Create advance |
| `/advances/{id}/settle` | PATCH | Settle advance |
| `/collections` | GET | List collections |
| `/collections/generate` | POST | Generate collection statement |
| `/collections` | POST | Save collection |
| `/collections/{id}/pay` | PATCH | Record payment |

### Admin Routes (Prefix: `/admin`)
- `GET /dashboard` - View all contractors
- `GET /contractors/{user}` - View contractor details

---

## 📊 Database Schema

### users
```sql
id, name, phone, role (admin/contractor), password, remember_token, timestamps
```

### companies
```sql
id, contractor_id, name, contact_person, phone, daily_wage, payment_cycle,
weekly_pay_day, contract_start_date, notes, is_active, timestamps, softDeletes
```

### workers
```sql
id, contractor_id, name, phone, national_id, is_active, timestamps, softDeletes
```

### daily_distributions
```sql
id, contractor_id, distribution_date, company_id, worker_id,
daily_wage_snapshot, timestamps
UNIQUE(distribution_date, worker_id)
```

### deductions
```sql
id, contractor_id, worker_id, company_id, deduction_date, type, amount, reason, timestamps
```

### advances
```sql
id, contractor_id, worker_id, amount, advance_date, notes,
is_settled, settled_date, timestamps
```

### collections
```sql
id, contractor_id, company_id, period_start, period_end, total_days_worked,
total_wages, total_deductions, net_amount, payment_method, payment_date,
is_paid, notes, timestamps
```

---

## 🎯 Business Rules

1. **Daily Wage Snapshot**: Always copy company's daily wage at time of distribution
2. **Worker Uniqueness**: One worker can only work for one company per day
3. **Deduction Calculation**: 
   - Quarter = daily_wage × 0.25
   - Half = daily_wage × 0.5
   - Full = daily_wage × 1.0
   - Custom = user-entered value
4. **Collection Period**: Sums distributions minus deductions for a company in a period
5. **Data Isolation**: All queries filtered by contractor_id for multi-tenant support

---

## 🛠️ Service Layer Examples

### Creating a Distribution
```php
$distributionService->distributeWorkers(
    contractorId: 1,
    date: '2024-01-15',
    assignments: [
        ['company_id' => 1, 'worker_id' => 2],
        ['company_id' => 2, 'worker_id' => 3],
    ]
);
```

### Calculating Worker Ledger
```php
$ledger = $wageCalculationService->getWorkerLedger(
    workerId: 5,
    from: '2024-01-01',
    to: '2024-01-31'
);
// Returns: gross_wages, total_deductions, total_advances, net_payable, breakdown
```

### Generating Collection Statement
```php
$statement = $collectionService->generateStatement(
    companyId: 1,
    from: '2024-01-01',
    to: '2024-01-31'
);
// Returns: preview with distributions, deductions, and totals
```

---

## 🔐 Middleware & Permissions

- **auth**: Require logged-in user
- **guest**: For unauthenticated users only
- **role:{role}**: Check user role (admin/contractor)

Example:
```php
Route::middleware(['auth', 'role:contractor'])->group(fn () => ...);
```

---

## 📱 Arabic Support

- All text is RTL-enabled with `dir="rtl"`
- Form validation messages in Arabic
- Database supports Arabic characters
- Date format: YYYY-MM-DD (international standard)

---

## 🧪 Testing the System

1. Log in as contractor
2. Create a company with a daily wage
3. Create workers
4. Create a distribution for today
5. View the daily summary
6. Add deductions
7. Add advances
8. Generate a collection statement
9. Record payment

---

## 🚨 Exception Handling

### DuplicateDistributionException
Thrown when trying to assign a worker to multiple companies on the same day.

### InsufficientWageException
Thrown when a deduction amount exceeds the worker's daily wage.

---

## 💡 Future Enhancements

- PDF export for statements
- SMS notifications
- Mobile app
- Advanced reporting
- Payment gateway integration
- Bulk import/export
- Performance analytics
- Audit logging

---

## 📞 Support

For issues or questions about the system, please refer to the code comments and the Laravel documentation.

---

**Created**: 2024
**Status**: Production Ready
**License**: Private

نظام إدارة مقاول العمالة - تم تطويره بناءً على أفضل الممارسات في Laravel والعمارة البرمجية الحديثة.
