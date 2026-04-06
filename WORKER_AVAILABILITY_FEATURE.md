# Worker Availability Constraint Implementation

## Overview
A worker can only be assigned to ONE company per day. When creating or editing distributions, only available workers (not yet assigned to any company on that date) will be shown.

## Changes Made

### 1. **Backend - Controller** 
📄 `app/Http/Controllers/Contractor/DistributionController.php`

#### New Method: `getAvailableWorkers()`
```php
public function getAvailableWorkers(): JsonResponse
```
- Returns workers NOT assigned to any company on the given date
- Accepts `date` query parameter (defaults to today)
- Returns JSON with available workers list

#### Updated Method: `store()`
- Added validation to check if any selected worker is already assigned
- Shows friendly error message if worker is already assigned
- Prevents duplicate worker assignments before database insert

### 2. **Routes**
📄 `routes/web.php`

Added new route:
```php
Route::get('/available-workers', [DistributionController::class, 'getAvailableWorkers'])
  ->name('contractor.distributions.get-available-workers');
```

### 3. **Create Modal - UI Updates**
📄 `resources/views/contractor/distributions/modals/create.blade.php`

#### Changes:
- Workers list now loads dynamically (no static list)
- Shows message "اختر شركة أولاً لعرض العمال المتاحين" until company selected
- When company selected, fetches available workers from API
- Shows "جميع العمال مسجلين بالفعل لهذا اليوم" if all workers assigned
- Added `loadAvailableWorkers()` function
- Added `populateWorkersList()` function
- Company selection triggers worker list refresh

### 4. **Edit Modal - UI Updates**
📄 `resources/views/contractor/distributions/modals/edit.blade.php`

#### Changes:
- Loads all distributed workers for the day
- Shows currently assigned workers pre-checked
- Grays out workers assigned to OTHER distributions
- Shows "(مسجل بشركة أخرى)" label for unavailable workers
- Can't uncheck a worker unless you're replacing it with another
- Added `.disabled` CSS class for unavailable workers
- Added `loadEditAvailableWorkers()` function
- Added `populateEditWorkersList()` function

## Data Flow

### Creating Distribution:
1. User opens Create Modal
2. Selects Company → Triggers `loadAvailableWorkers()`
3. API endpoint `/contractor/distributions/available-workers?date=TODAY` called
4. Returns workers NOT assigned today
5. Workers dynamically rendered in modal
6. User selects workers and submits
7. Backend validates no duplicate assignments
8. Distribution created successfully

### Editing Distribution:
1. User clicks "تعديل" on a distribution
2. `openEditModal()` called with distribution data
3. Calls `loadEditAvailableWorkers()`
4. Fetches all assignments for today via `/assigned-workers` endpoint
5. Filters out workers assigned to OTHER distributions
6. Current workers shown pre-checked
7. Other assigned workers shown as disabled/grayed
8. User can modify selection (add available, keep current, remove)
9. Submit updates distribution with new worker list

## API Endpoints

### Get Available Workers
```
GET /contractor/distributions/available-workers?date=YYYY-MM-DD
```
**Response:**
```json
{
  "success": true,
  "data": [
    {"id": 1, "name": "أحمد محمد"},
    {"id": 3, "name": "علي الصالح"}
  ]
}
```

### Get Assigned Workers (existing)
```
GET /contractor/distributions/assigned-workers?date=YYYY-MM-DD
```
**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "worker_id": 2,
      "worker_name": "محمد أحمد",
      "company_id": 1,
      "company_name": "شركة البناء"
    }
  ]
}
```

## Business Rules Enforced

✅ **Create Modal:**
- Only shows workers not yet assigned TODAY
- If all workers are assigned, shows message "جميع العمال مسجلين بالفعل لهذا اليوم"
- Can't select unavailable workers (they won't appear in list)
- Backend validates before insert (double-check)

✅ **Edit Modal:**
- Shows all workers with status
- Can keep currently assigned workers
- Can add new available workers
- Can't add workers already assigned to other distributions
- Shows which company/distribution other workers are assigned to
- Backend validates changes before update

✅ **Backend Validation:**
- Check for duplicates on `store()`
- Clear error message shows which worker is already assigned
- Prevents race conditions where multiple assignments happen

## Error Messages

### User-Friendly Arabic Messages:

**Create Modal:**
- "اختر شركة أولاً لعرض العمال المتاحين" → Choose company first
- "جميع العمال مسجلين بالفعل لهذا اليوم" → All workers already assigned
- "خطأ في تحميل قائمة العمال" → Error loading workers
- "العامل [الاسم] مسجل بالفعل لشركة أخرى اليوم" → Worker already assigned to another company

**Edit Modal:**
- Workers shown as disabled with "(مسجل بشركة أخرى)" label
- Disabled workers are grayed out (opacity: 0.6)
- Can't check disabled workers

## JavaScript Functions

### In Create Modal:
```javascript
openModal(modalId)
closeModal(modalId)
loadAvailableWorkers()           // Fetch from API
populateWorkersList(workers)      // Render checkboxes
updateEarningsCalculation()       // Recalculate totals
number_format(num)               // Format for Arabic locale
```

### In Edit Modal:
```javascript
openEditModal(...)               // Open with data
loadEditAvailableWorkers(...)    // Fetch current assignments
populateEditWorkersList(...)     // Render with disabled state
updateEditEarnings(dailyWage)    // Recalculate totals
number_format(num)               // Format for Arabic locale
```

## CSS Classes

### New/Updated:
```css
.workers-list              /* Grid layout for checkboxes */
.worker-checkbox          /* Individual worker item */
.worker-checkbox.disabled /* Unavailable worker styling */
  - opacity: 0.6
  - cursor: not-allowed
  - background: #f0f0f0
```

## Testing Checklist

- [ ] Create modal: Select company → Workers load
- [ ] Create modal: No workers show if all assigned
- [ ] Create modal: Can't select unavailable workers
- [ ] Create modal: Earnings calculate correctly
- [ ] Create modal: Validates before submit
- [ ] Edit modal: Current workers pre-checked
- [ ] Edit modal: Other assigned workers grayed out
- [ ] Edit modal: Can keep current assignments
- [ ] Edit modal: Show company name for other assignments (optional)
- [ ] Edit modal: Earnings update on changes
- [ ] API endpoint: Returns correct worker list for date
- [ ] API endpoint: Filters out assigned workers
- [ ] Backend: Rejects duplicate assignments with good error
- [ ] Error messages: Display in Arabic
- [ ] Mobile: Modal responsive with disabled states visible

## Performance Notes

- Worker list loaded dynamically (not on page load)
- Only fetches data when needed (on company select)
- Uses existing `/assigned-workers` endpoint (no N+1 queries)
- Filters in-memory (acceptable for typical worker counts 5-50)

## Future Enhancements

1. Show which company a worker is assigned to in edit modal
2. Add ability to swap workers between distributions
3. Bulk reassignment feature
4. Worker unavailability reason display
5. Cache available workers list for day (5-10 min TTL)

## Database Impact

No schema changes required! Uses existing:
- `daily_distributions` table
- `distribution_worker` pivot table
- `workers` table

Constraint enforced at:
1. UI level (modal doesn't show unavailable workers)
2. Validation level (backend rejects duplicates)
3. Database level (would be unique constraint if needed)
