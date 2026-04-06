# Modal Testing Guide

## Quick Start

### To Test the Create Modal:
1. Navigate to `/contractor/distributions`
2. Click the **"+ توزيع جديد"** button in the top-right
3. The create modal should appear with:
   - Company dropdown (initially empty)
   - Worker checkboxes
   - Real-time earnings summary (all zeros initially)
4. Select a company from the dropdown
5. Select one or more workers
6. Watch the earnings calculation update
7. Click **"تأكيد التوزيع"** to create the distribution
8. Modal closes and you return to the distributions list with success message

### To Test the Edit Modal:
1. On the distributions list page, find a distribution
2. If it shows "تعديل" button (within 7 days of creation):
   - Click the **"تعديل"** button
   - Edit modal should appear with:
     - Company name and wage (read-only)
     - Worker checkboxes with current workers pre-selected
     - Current earnings calculation
3. Uncheck or check workers to change the distribution
4. Watch the earnings calculation update
5. Click **"حفظ التغييرات"** to save
6. Modal closes and you see success message

### To Test Modal Closing:
1. Open any modal
2. Try:
   - Clicking the **"X"** button in top-right
   - Clicking **"إلغاء"** button
   - Clicking the dark overlay outside the modal
3. Modal should close, body scroll restored

## Expected Data

- **Companies**: 6 active companies (with daily wages)
- **Workers**: 10 active workers
- **Test User**:
  - Phone: 01001111111
  - Password: password
  - Role: contractor

## Validation Tests

### Create Modal Validation:
- ❌ Submit without selecting company → Shows error "اختر شركة"
- ❌ Submit without selecting workers → Shows error "اختر عاملاً واحداً على الأقل"
- ✅ Select company + workers → Submit works

### Edit Modal Validation:
- ❌ Uncheck all workers → Shows error
- ✅ Keep or select workers → Submit works

## Real-time Calculation Tests

### Create Modal:
1. Select Company A (wage = X)
2. Select 3 workers
3. Verify: Workers Count = 3
4. Verify: Daily Wage = X
5. Verify: Total = 3 × X

### Edit Modal:
1. Notice Company info is read-only
2. Uncheck one worker
3. Verify: Workers Count decreases
4. Verify: Total recalculates
5. Check another worker
6. Verify: Workers Count increases
7. Verify: Total recalculates

## Responsive Design Tests

### Desktop (1024px+):
- Modals should be 500px wide, centered
- Grid for worker checkboxes: 3+ columns

### Tablet (768px):
- Modals should be 90% width
- Grid for worker checkboxes: 2 columns

### Mobile (< 640px):
- Modals should be 95% width
- Grid for worker checkboxes: 1 column (full width)
- Buttons stack vertically if needed

## Common Issues & Fixes

### Modal doesn't open:
- Check browser console (F12) for JavaScript errors
- Verify `openCreateModal()` and `openEditModal()` functions exist
- Clear browser cache (Ctrl+Shift+Delete)

### No workers or companies shown:
- Verify database has records: `php artisan tinker`
  - `\App\Models\Company::count()`
  - `\App\Models\Worker::count()`
- Check if contractor_id matches current user

### Earnings not calculating:
- Check browser console for JS errors
- Verify `number_format()` function is defined
- Ensure `parseInt()` works with wage values

### Form not submitting:
- Check validation errors appear
- Open browser console → Network tab
- Submit form and check the POST request
- Verify CSRF token is present

## URL Reference

- Distribution List: `/contractor/distributions`
- Create (old): `/contractor/distributions/create` (now opens modal on index)
- Edit (old): `/contractor/distributions/{id}/edit` (now opens modal on index)
- Store API: `POST /contractor/distributions`
- Update API: `PUT /contractor/distributions/{id}`
- Show: `GET /contractor/distributions/{id}`
- Delete: `DELETE /contractor/distributions/{id}`

## Database Changes Required

None! The modals use the existing database schema:
- `companies` table
- `workers` table
- `daily_distributions` table
- `distribution_worker` (pivot table)

## Code Quality

All code follows these standards:
- ✅ RTL layout (Arabic text direction)
- ✅ Mobile responsive
- ✅ Clean, readable JavaScript
- ✅ CSS organized with media queries
- ✅ Proper error handling
- ✅ Accessibility considerations (labels, button types)
- ✅ Form validation before submission
