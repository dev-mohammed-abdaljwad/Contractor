# Worker Availability Testing Guide

## Quick Test Scenario

### Scenario: Assign workers to 2 different companies on same day

**Setup:**
- Today's date: April 6, 2026
- Available workers: 10
- Available companies: 6
- Test contractor: محمد أحمد (Phone: 01001111111)

### Step 1: Create First Distribution
1. Go to `/contractor/distributions`
2. Click **"+ توزيع جديد"** button
3. **Select Company A** (e.g., "شركة البناء")
4. **Verify:** Workers list populates (should have all 10)
5. **Select 3 workers:** e.g., أحمد, علي, محمود
6. **Verify:** Earnings calculate: 3 × (daily wage) = total
7. **Click "تأكيد التوزيع"**
8. **Verify:** Success message, distribution appears in list

### Step 2: Create Second Distribution (Test Blocking)
1. Click **"+ توزيع جديد"** again
2. **Select Company B** (different company)
3. **Verify:** Worker list appears but only shows 7 workers (3 are missing)
4. **Verify:** Missing workers are أحمد, علي, محمود (the ones we assigned)
5. **Try to select one of the missing workers:** CAN'T - they don't appear in list
6. **Select 2 of the remaining workers**
7. **Click "تأكيد التوزيع"**
8. **Verify:** Success! Two distributions created for different companies

### Step 3: Edit Distribution (Test Constraint)
1. In distributions list, find the first distribution from Company A
2. Click **"تعديل"** button
3. **Verify:** Modal shows Company A info (read-only)
4. **Verify:** Shows 3 workers checked (أحمد, علي, محمود)
5. **Verify:** Other 7 workers are grayed out with "(مسجل بشركة أخرى)"
6. **Try to uncheck all workers:** Shows error on submit "اختر عاملاً واحداً على الأقل"
7. **Uncheck 1 worker (e.g., محمود)** and check 1 new worker (e.g., فاطمة)
8. **Verify:** Earnings update: 3 workers still (2 old + 1 new)
9. **Click "حفظ التغييرات"**
10. **Verify:** Success! Distribution updated with mixed workers

## API Testing (Advanced)

### Test Available Workers Endpoint
```bash
curl "http://localhost:8000/contractor/distributions/available-workers?date=2026-04-06"
```

**Expected Response (first distribution made, 3 workers assigned):**
```json
{
  "success": true,
  "data": [
    {"id": 4, "name": "فاطمة أحمد"},
    {"id": 5, "name": "نور محمد"},
    {"id": 6, "name": "سارة علي"},
    {"id": 7, "name": "هناء"},
    {"id": 8, "name": "يسرا"},
    {"id": 9, "name": "منى"},
    {"id": 10, "name": "ليلى"}
  ]
}
```
*Only 7 workers shown, 3 are missing (assigned to Company A)*

### Test Assigned Workers Endpoint
```bash
curl "http://localhost:8000/contractor/distributions/assigned-workers?date=2026-04-06"
```

**Expected Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "worker_id": 1,
      "worker_name": "أحمد",
      "company_id": 1,
      "company_name": "شركة البناء"
    },
    {
      "id": 1,
      "worker_id": 2,
      "worker_name": "علي",
      "company_id": 1,
      "company_name": "شركة البناء"
    },
    {
      "id": 1,
      "worker_id": 3,
      "worker_name": "محمود",
      "company_id": 1,
      "company_name": "شركة البناء"
    }
  ]
}
```
*Shows all current assignments with company info*

## Error Testing

### Test 1: Duplicate Worker via Form Submission
**Setup:**
- Have Worker أحمد assigned to Company A
- Open Create modal, select Company B
- Manually edit HTML to include أحمد's ID in worker_ids (bypass UI)
- Submit form

**Expected:** Error message: "العامل أحمد مسجل بالفعل لشركة أخرى اليوم"

### Test 2: All Workers Assigned
1. Assign all 10 workers to Company A
2. Click "توزيع جديد"
3. Select Company B
4. **Verify:** Message shows "جميع العمال مسجلين بالفعل لهذا اليوم"
5. **Verify:** Workers list is empty
6. Try to submit → Nothing to submit

### Test 3: Network Error
1. Open Create modal
2. Select a company
3. Open DevTools → Network tab
4. Block the `/available-workers` request (use DevTools throttling)
5. **Verify:** Error message shows "خطأ في تحميل قائمة العمال"
6. Workers list shows error message

## Edge Cases

### Different Days
1. Create distribution on April 6
2. Create another distribution on April 7
3. **Verify:** Same workers can be assigned to both dates
4. **Verify:** Same worker can be in Company A on Apr 6 and Company B on Apr 7

### Edit Modal Specifics
1. Create Distribution D1 with workers [1, 2, 3] for Company A
2. Create Distribution D2 with workers [4, 5, 6] for Company B
3. Open Edit modal for D1
4. **Verify:** Workers [1,2,3] are checked
5. **Verify:** Workers [4,5,6] are grayed with "(مسجل بشركة أخرى)"
6. **Verify:** Can uncheck worker 1 and check worker 4 to swap
7. After save:
   - D1 has workers [2, 3, 4]
   - D2 still has workers [4, 5, 6] (will need separate fix if that creates duplicate)

## Performance Testing

### Load Test (Many Workers)
1. Assume 100 workers total
2. Assign 50 workers to various companies on same day
3. Open create modal
4. Select company
5. **Verify:** Available workers list loads in <500ms
6. **Verify:** Can scroll through list smoothly
7. **Verify:** Checkboxes responsive

### Database Query Check
```
SHOW PROCESSLIST;
```
When loading available workers, should see:
- 1 query to get all workers
- 1 query to get distributions with workers for today
- No N+1 queries

## UI/UX Testing

### Mobile (< 640px)
1. Open modal on mobile
2. **Verify:** Workers grid shows 1 column
3. **Verify:** Buttons stack properly
4. **Verify:** Can scroll workers list
5. **Verify:** Disabled state visible on mobile

### Tablet (768px)
1. **Verify:** Workers grid shows 2 columns
2. **Verify:** Layout readable
3. **Verify:** Touch targets (checkboxes) are >44px

### Desktop (1024px+)
1. **Verify:** Workers grid shows 3+ columns
2. **Verify:** Modal is 500px wide, centered
3. **Verify:** Disabled workers grayed (opacity: 0.6)
4. **Verify:** Hover effects work

### RTL Layout
1. **Verify:** All text right-aligned
2. **Verify:** Modals open LTR (from right)
3. **Verify:** Error messages display correctly
4. **Verify:** Arabic labels positioned correctly

## Accessibility Testing

### Screen Reader
1. Open create modal with screen reader
2. **Verify:** Form labels announced
3. **Verify:** Error messages announced
4. **Verify:** Disabled workers announced as disabled
5. **Verify:** Company dropdown announces options

### Keyboard Navigation
1. Tab through form
2. **Verify:** Can tab to company dropdown
3. **Verify:** Can arrow through company options
4. **Verify:** Can tab to worker checkboxes
5. **Verify:** Can space to check/uncheck
6. **Verify:** Can tab to buttons
7. **Verify:** Can Enter/Space to submit
8. **Verify:** Can Escape to close modal (if implemented)

## Success Criteria

All tests should show:
- ✅ Workers properly filtered by date and availability
- ✅ UI prevents selection of unavailable workers
- ✅ Backend validates worker assignments
- ✅ Error messages in Arabic and user-friendly
- ✅ Earnings calculations update correctly
- ✅ Modal UX smooth and responsive
- ✅ No database errors or race conditions
- ✅ Performance acceptable with typical worker counts
