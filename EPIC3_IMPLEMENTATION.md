# Epic 3: Daily Distribution - Implementation Guide

## Overview
Complete implementation of Epic 3 (Daily Distribution) with all three user stories:
- **US-10**: Distribute Workers to a Company
- **US-11**: See Real-Time Earnings Summary Before Confirming
- **US-13**: Edit or Cancel a Past Distribution

---

## Features Implemented

### US-10: Distribute Workers to a Company ✅

**Flow**: Select company → Select workers → Review summary → Confirm

#### Key Features:
- Multi-step wizard form for better UX
- Company daily wage pre-filled automatically
- Workers already assigned today are visually marked with a yellow badge
- Real-time worker deselection (can't select assigned workers)
- Validation: Cannot save with zero workers selected
- Wage snapshot captured at distribution time

#### Component Files:
- **View**: `resources/views/contractor/distributions/create.blade.php`
- **Controller Method**: `DistributionController@create()`, `store()`
- **Service Method**: `DistributionService@distributeWorkers()`

---

### US-11: See Real-Time Earnings Summary Before Confirming ✅

**Summary Card**: Shows `number of workers × daily wage - deductions = total`

#### Key Features:
- Real-time calculation as workers are selected
- Deductions applied today are reflected in the summary
- Summary updates instantly with each checkbox change
- Gross total, Total deductions, and Net total displayed
- Worker-by-worker breakdown with individual deductions
- API endpoint for frontend calculations

#### API Endpoint:
```
POST /contractor/distributions/calculate-earnings
Content-Type: application/json

{
  "company_id": 1,
  "worker_ids": [1, 2, 3],
  "date": "2026-04-06"
}

Response:
{
  "success": true,
  "data": {
    "company_name": "...",
    "daily_wage": 100.00,
    "worker_count": 3,
    "gross_total": 300.00,
    "total_deductions": 50.00,
    "net_total": 250.00,
    "workers": [...]
  }
}
```

#### Component Files:
- **View (Step 3)**: `resources/views/contractor/distributions/create.blade.php` (Lines ~200-250)
- **Controller Method**: `DistributionController@calculateEarnings()`
- **Service Method**: `DistributionService@calculateRealTimeEarnings()`

---

### US-13: Edit or Cancel a Past Distribution ✅

**Window**: Editable/cancellable within 7 days of distribution date

#### Key Features:
- **Edit**: Change company or worker assignment
- **Cancel**: Remove distribution entirely (soft delete)
- All actions logged with timestamp and reason
- Edit/cancel buttons hidden after 7-day window
- Cancellation automatically recalculates all balances
- Complete audit trail with before/after data

#### Edit Functionality:
- Modal dialog for cancel reason
- Preview of changes before saving
- Old vs New data comparison
- Reason is optional but logged

#### Audit Trail:
- Every action (created, edited, cancelled) is logged
- Logged data includes user, timestamp, old data, new data, reason
- Visible in distribution details page

#### Component Files:
- **Edit View**: `resources/views/contractor/distributions/edit.blade.php`
- **Show View (History)**: `resources/views/contractor/distributions/show.blade.php`
- **Controller Methods**: `edit()`, `update()`, `destroy()`
- **Service Methods**: `editDistribution()`, `cancelDistribution()`, `canEditDistribution()`

---

## Database Schema

### Migration File
`database/migrations/0001_01_01_000020_add_edit_cancel_to_distributions.php`

### Tables Modified/Created:

#### daily_distributions (Modified)
```sql
ALTER TABLE daily_distributions ADD deleted_at TIMESTAMP NULL AFTER updated_at;
```

#### distribution_actions_log (New)
```sql
CREATE TABLE distribution_actions_log (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    contractor_id BIGINT UNSIGNED NOT NULL,
    daily_distribution_id BIGINT UNSIGNED NOT NULL,
    action ENUM('created', 'edited', 'cancelled') DEFAULT 'created',
    reason TEXT NULL,
    old_data JSON NULL,
    new_data JSON NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    FOREIGN KEY (contractor_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (daily_distribution_id) REFERENCES daily_distributions(id) ON DELETE CASCADE
);
```

---

## Models & Relationships

### DailyDistribution Model
```php
use SoftDeletes;

public function actionLogs()  // New relationship
{
    return $this->hasMany(DistributionActionLog::class, 'daily_distribution_id');
}
```

### DistributionActionLog (New Model)
```php
class DistributionActionLog extends Model
{
    protected $casts = [
        'old_data' => 'array',
        'new_data' => 'array',
    ];
    
    public function distribution()
    {
        return $this->belongsTo(DailyDistribution::class, 'daily_distribution_id');
    }
}
```

---

## Service Layer

### DistributionService Methods

#### `distributeWorkers(int $contractorId, string $date, array $assignments)`
Creates distributions and logs them.
- Validates no duplicate worker assignments
- Captures wage snapshot
- Creates action log entry

#### `calculateRealTimeEarnings(int $companyId, array $workerIds, string $date): array`
Calculates earnings with deductions for summary display.
- Gets daily wage for company
- Fetches deductions for each worker
- Returns detailed breakdown

#### `editDistribution(int $distributionId, int $newCompanyId, int $newWorkerId, int $contractorId, ?string $reason)`
Updates distribution with validation and logging.
- Validates 7-day window
- Validates no duplicate worker assignment
- Logs old and new data

#### `cancelDistribution(int $distributionId, int $contractorId, ?string $reason)`
Soft deletes distribution with logging.
- Validates 7-day window
- Logs cancellation with reason
- Updates action log

#### `canEditDistribution(DailyDistribution $distribution): bool`
Checks if distribution is within 7-day window.

#### `getAssignedWorkersForDate(int $contractorId, string $date): Collection`
Returns all distributions for a date (for marking already assigned).

---

## Routes

All routes are under the `/contractor/distributions` prefix:

```php
Route::prefix('distributions')->group(function () {
    // API endpoints (define before resourceful to avoid conflicts)
    Route::post('/calculate-earnings', 'calculateEarnings');
    Route::get('/assigned-workers', 'getAssignedWorkers');
    
    // Resourceful routes
    Route::get('/', 'index');                           // List distributions
    Route::get('/create', 'create');                    // Show create form
    Route::post('/', 'store');                          // Store distribution
    Route::get('/{id}', 'show');                        // Show details & history
    Route::get('/{id}/edit', 'edit');                   // Show edit form
    Route::put('/{id}', 'update');                      // Save edited distribution
    Route::delete('/{id}', 'destroy');                  // Cancel distribution
});
```

---

## Frontend Components

### Step 1: Select Company
- Radio button selection
- Shows daily wage for each company
- Visual feedback with checkmark

### Step 2: Select Workers
- Checkbox selection
- Disabled workers are marked "Already assigned"
- Full list scrollable
- Count of selected workers

### Step 3: Review Summary
- Grid display of key metrics
- Worker-by-worker breakdown
- Summary card with color-coded totals
- Gross / Deductions / Net display

### Step 4: Confirm
- Final confirmation dialog
- Shows all details
- Reminder about 7-day edit window

### Index View
- Date filter
- Cards showing distributions by company
- Edit/Cancel buttons with status
- Summary metrics for each distribution

### Show View
- Full details with action history
- Timeline of all changes
- Before/after comparison for edits
- Linked to edit/cancel actions

### Edit View
- Current vs new company/worker selection
- Live preview of changes
- Optional reason field
- Can only edit within 7 days

---

## Validation Rules

### Distribution Creation
```
distribution_date: required|date_format:Y-m-d
assignments: required|array|min:1
assignments.*.company_id: required|integer|exists:companies,id
assignments.*.worker_id: required|integer|exists:workers,id
```

### Distribution Edit
```
company_id: required|integer|exists:companies,id
worker_id: required|integer|exists:workers,id
reason: nullable|string|max:500
```

### Business Logic
- One worker cannot be assigned to two companies on same date
- Only distributions from past 7 days can be edited/cancelled
- Distribution cannot be saved with zero workers
- Wage snapshot is immutable (for historical accuracy)

---

## Testing Checklist

- [ ] Create distribution with single worker
- [ ] Create distribution with multiple workers
- [ ] Verify wage snapshot captures correctly
- [ ] Real-time earnings calculation updates on worker selection
- [ ] Deductions are reflected in earnings summary
- [ ] Cannot save distribution with zero workers
- [ ] Cannot assign same worker to two companies on same date
- [ ] Edit page shows current selection
- [ ] Edit with reason is logged
- [ ] Cancel with reason is logged
- [ ] Edit/cancel buttons disappear after 7 days
- [ ] Action history shows all changes
- [ ] Soft delete works (data not deleted, just marked)
- [ ] API endpoints return correct format
- [ ] Route names match in controllers and views
- [ ] Arabic messages display correctly

---

## Error Handling

### User-Facing Errors
- Duplicate worker assignment: "العامل محدد بالفعل في هذا التاريخ"
- Edit after 7 days: "لا يمكن تعديل التوزيع بعد مرور 7 أيام"
- Cancel after 7 days: "لا يمكن إلغاء التوزيع بعد مرور 7 أيام"
- No workers selected: "يجب تحديد على الأقل عاملاً واحداً"

### Exception Handling
- `DuplicateDistributionException` for worker conflicts
- Generic `Exception` for 7-day window violations
- Validation errors handled by Form Requests

---

## Performance Considerations

- Wage snapshot stored to avoid N+1 queries on history display
- Soft deletes allow efficient date range queries
- Action logs indexed by distribution_id for fast history retrieval
- Real-time calculations done on frontend after API fetch

---

## Future Enhancements

- [ ] Bulk edit/cancel for multiple distributions
- [ ] Schedule cancellations (delete after 7 days)
- [ ] Undo functionality
- [ ] Batch import from CSV
- [ ] Email notifications on edit/cancel
- [ ] Permission-based cancellation (only contractor or admin)

---

## Installation & Setup

1. **Run Migration**
   ```bash
   php artisan migrate
   ```

2. **Clear Cache**
   ```bash
   php artisan cache:clear
   php artisan route:cache
   ```

3. **Test Routes**
   ```bash
   php artisan route:list | grep distribution
   ```

4. **Access the Feature**
   - Navigate to `/contractor/distributions`
   - Click "توزيع جديد" to create
   - Click distribution to view details
   - Use edit/cancel buttons (within 7 days)

---

## Troubleshooting

### Routes Not Working
- Clear route cache: `php artisan route:cache`
- Check route names: `php artisan route:list`

### API Endpoint Errors
- Check CSRF token in form
- Verify Content-Type: application/json
- Check contractor_id in auth

### Deductions Not Showing
- Verify deductions exist for workers on that date
- Check DeductionRepository binding in AppServiceProvider

### Date Calculations Wrong
- Ensure server timezone is correct
- Check Carbon usage for 7-day window

---
