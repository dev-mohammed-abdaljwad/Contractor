# Contractor Labor Management System
## Complete User Stories

> **System Overview**
> A web application that helps agricultural labor contractors manage daily worker distribution, track deductions and advances, and collect payments from client companies.

---

## Actors

| Actor | Description |
|-------|-------------|
| **Contractor** | The main user — manages workers, companies, distributions, and collections |
| **Admin** | Platform administrator — manages contractor accounts |

---

## Priority Legend

| Label | Meaning |
|-------|---------|
| 🔴 Must Have | Core functionality — system doesn't work without it (MVP) |
| 🟡 Should Have | Important — deliver in version 2 |
| 🔵 Could Have | Nice to have — future enhancement |

---

## Epic 1 — Worker Management

### US-01 · Add a New Worker
**Priority:** 🔴 Must Have

```
As a contractor,
I want to add a new worker with their name and phone number,
So that I can track every worker in my roster in an organized way.
```

**Acceptance Criteria:**
- Full name and phone number are required fields
- National ID is optional
- Join date defaults to today but can be changed
- Each worker gets a unique auto-incremented ID number
- Worker appears in the active workers list immediately after saving

---

### US-02 · View Workers List with Daily Status
**Priority:** 🔴 Must Have

```
As a contractor,
I want to see a list of all my workers with each one's status for today,
So that I instantly know who is assigned and who is available.
```

**Acceptance Criteria:**
- Each worker card shows: name, worker ID, today's assigned company (or "Not Assigned")
- A small progress bar shows attendance rate for the current month
- Workers are sorted by: assigned first, then unassigned
- Tapping a worker opens their full profile

---

### US-03 · Edit or Deactivate a Worker
**Priority:** 🔴 Must Have

```
As a contractor,
I want to edit a worker's information or deactivate them,
So that I can keep data accurate when a phone number changes or a worker stops working.
```

**Acceptance Criteria:**
- All fields (name, phone, national ID) are editable
- Deactivating a worker hides them from daily distribution but does NOT delete their history
- Deactivated workers can be reactivated at any time
- A confirmation dialog appears before deactivation

---

### US-04 · View Monthly Attendance Calendar
**Priority:** 🟡 Should Have

```
As a contractor,
I want to see a color-coded monthly attendance calendar for each worker,
So that I can quickly spot attendance patterns without doing manual calculations.
```

**Acceptance Criteria:**
- Green cell = full day present
- Yellow cell = partial day (deduction applied)
- Red cell = absent / no distribution
- Today's date is highlighted in teal
- Summary row below shows: full days / partial days / absences / attendance rate %

---

### US-05 · Filter and Search Workers
**Priority:** 🟡 Should Have

```
As a contractor,
I want to filter workers by status (assigned / unassigned / has advance) and search by name or ID,
So that I can reach any specific worker quickly among 40–50+ workers.
```

**Acceptance Criteria:**
- Filter chips: All / Assigned today / Unassigned / Has pending advance / On leave
- Search bar filters results in real time as the user types
- Filter and search can be combined

---

## Epic 2 — Company Management

### US-06 · Add a New Company
**Priority:** 🔴 Must Have

```
As a contractor,
I want to add a new client company with its name, daily wage rate, and payment cycle,
So that the system automatically calculates earnings when I assign workers to it.
```

**Acceptance Criteria:**
- Required fields: company name, daily wage per worker (EGP)
- Payment cycle options: Daily / Weekly (specify day) / Bi-monthly (1st & 15th)
- Optional fields: responsible person name, phone number, contract start date, notes
- Company is immediately available for worker distribution after saving

---

### US-07 · Edit Company Daily Wage
**Priority:** 🔴 Must Have

```
As a contractor,
I want to edit the daily wage rate of a company,
So that the new rate applies from the edit date forward without affecting historical records.
```

**Acceptance Criteria:**
- New wage takes effect from today's date (or a specified date)
- All past records retain their original wage at the time of distribution
- Edit history is logged with old value, new value, and date of change

---

### US-08 · View Company Profile
**Priority:** 🔴 Must Have

```
As a contractor,
I want to view a full profile for each company including today's workers, amount due, and history,
So that I can track each company independently without confusion.
```

**Acceptance Criteria:**
- Profile has 4 tabs: Overview / Workers / History / Collection
- Overview tab shows: daily wage, workers today, monthly total, amount due now
- Workers tab shows today's assigned workers with individual earnings
- History tab shows day-by-day distribution log

---

### US-09 · Deactivate a Company
**Priority:** 🟡 Should Have

```
As a contractor,
I want to deactivate a company without deleting its records,
So that I keep the full history of the relationship even after the contract ends.
```

**Acceptance Criteria:**
- Deactivated company disappears from the daily distribution screen
- All historical records (distributions, collections) are preserved
- Company can be reactivated if the relationship resumes
- Deactivated companies are accessible under a separate "Inactive" filter

---

## Epic 3 — Daily Distribution

### US-10 · Distribute Workers to a Company
**Priority:** 🔴 Must Have

```
As a contractor,
I want to assign workers to a company each day with a few simple taps,
So that attendance and earnings are recorded automatically without manual data entry.
```

**Acceptance Criteria:**
- Flow: Select company → Select workers → Review summary → Confirm
- The company's daily wage is pre-filled automatically
- Workers already assigned today are visually marked to avoid duplicates
- A distribution cannot be saved with zero workers selected

---

### US-11 · See Real-Time Earnings Summary Before Confirming
**Priority:** 🔴 Must Have

```
As a contractor,
I want to see the total earnings for a company update live as I select workers,
So that I can verify the number before confirming.
```

**Acceptance Criteria:**
- Summary card shows: number of workers selected × daily wage = total
- Any deductions already applied today are reflected
- Summary updates instantly with each worker added or removed

---


### US-13 · Edit or Cancel a Past Distribution
**Priority:** 🟡 Should Have

```
As a contractor,
I want to edit or cancel a distribution from a previous day,
So that I can correct mistakes that happen during recording.
```

**Acceptance Criteria:**
- Distributions from the past 7 days are editable
- Cancelling a distribution recalculates all affected balances automatically
- Edit and cancel actions are logged with a timestamp and reason

---

## Epic 4 — Deductions

### US-14 · Record a Deduction on a Worker
**Priority:** 🔴 Must Have

```
As a contractor,
I want to record a deduction (quarter / half / full day) on a worker for a specific day,
So that the deduction is automatically reflected in their earnings for that day.
```

**Acceptance Criteria:**
- Deduction options: ¼ day / ½ day / full day
- Deduction amount is calculated from the wage of the company the worker was at that day
- The resulting net pay for the day is shown before confirming
- Cannot apply a deduction to a day the worker was not distributed

---

### US-15 · Add a Reason for Each Deduction
**Priority:** 🔴 Must Have

```
As a contractor,
I want to write a reason for every deduction,
So that I can refer back to it if a worker questions or disputes the deduction.
```

**Acceptance Criteria:**
- Reason field is optional text input accompanying each deduction
- Reason is displayed in the deduction history timeline
- No minimum or maximum character limit

---

### US-16 · Cancel a Wrongly Recorded Deduction
**Priority:** 🔴 Must Have

```
As a contractor,
I want to cancel a deduction that was recorded by mistake,
So that the worker's pay is restored to the correct amount without manual editing.
```

**Acceptance Criteria:**
- Cancellation appears in the history as a "reversal" entry — it is never deleted
- The worker's net balance updates immediately
- A reason for the cancellation can be optionally recorded

---

### US-17 · View Full Deduction History per Worker
**Priority:** 🟡 Should Have

```
As a contractor,
I want to see a complete deduction history for each worker,
So that I can identify workers with frequent deductions and address the issue.
```

**Acceptance Criteria:**
- Timeline shows each deduction with: date, company, amount, reason, type
- Reversals are displayed inline with a distinct style
- Filterable by: this week / this month / custom date range
- Monthly total deductions shown at the bottom

---

## Epic 5 — Advances

### US-18 · Record an Advance for a Worker
**Priority:** 🔴 Must Have

```
As a contractor,
I want to record an advance payment given to a worker with the amount and date,
So that I don't lose track of any money I've paid out to workers.
```

**Acceptance Criteria:**
- Required fields: worker, amount, date
- Optional field: note / reason
- Advance is immediately added to the worker's pending deductions
- The worker's remaining balance is shown after the advance is recorded

---

### US-19 · Set the Advance Recovery Method
**Priority:** 🟡 Should Have

```
As a contractor,
I want to choose how an advance is deducted (immediately / in installments / manually),
So that I can control the recovery timing based on the worker's situation.
```

**Acceptance Criteria:**
- Immediately: deducted in full from the next payment
- Installments: split into equal weekly or bi-weekly deductions
- Manually: contractor decides when to apply each deduction
- Recovery method can be changed at any time

---

### US-20 · View All Pending Advances per Worker
**Priority:** 🔴 Must Have

```
As a contractor,
I want to see the total pending advances for each worker,
So that I know who still has uncollected advances.
```

**Acceptance Criteria:**
- Clear separation between "Pending" and "Collected" advances
- Pending advances are highlighted on the worker's profile
- A badge on the workers list shows workers who have pending advances
- Global summary: total advances issued this month / total collected / total outstanding

---

## Epic 6 — Collection

### US-21 · View Total Amount Due from Each Company
**Priority:** 🔴 Must Have

```
As a contractor,
I want to see the total amount due from each company in one view,
So that I know who I need to collect from today.
```

**Acceptance Criteria:**
- Companies sorted by urgency: overdue first
- Each company card shows: amount due, payment cycle, days since last payment
- A colored urgency bar indicates: red (overdue) / yellow (due soon) / blue (on schedule)
- Total due across all companies shown at the top

---

### US-22 · Record a Received Payment from a Company
**Priority:** 🔴 Must Have

```
As a contractor,
I want to record a payment received from a company with the amount and payment method,
So that the outstanding balance is automatically reduced and the payment is logged.
```

**Acceptance Criteria:**
- Fields: company, date, amount received, payment method (cash / bank transfer / cheque)
- System compares received amount to total due and shows the difference
- If payment fully covers the balance: balance resets to zero
- If payment is partial: remaining balance carries forward
- Optional note field for reference

---

### US-23 · Receive an Automatic Alert for Overdue Payments
**Priority:** 🟡 Should Have

```
As a contractor,
I want to receive an automatic alert when a company is overdue on its payment,
So that I don't forget to follow up on late collections.
```

**Acceptance Criteria:**
- Alert triggers when the expected payment date has passed
- Alert appears as a red banner on the main dashboard
- Alert shows: company name, amount overdue, number of days late
- Alert disappears once a payment is recorded

---

### US-24 · View Full Payment History per Company
**Priority:** 🔴 Must Have

```
As a contractor,
I want to see a complete log of all payments received from each company,
So that I can track collection history and resolve any disputes.
```

**Acceptance Criteria:**
- Timeline shows each payment with: date, amount, method, notes
- Partial payments are marked distinctly
- Monthly totals: collected / outstanding
- Accessible from the company profile under the "Collection" tab

---

### US-25 · Record a Partial Payment and Carry Forward the Remainder
**Priority:** 🔴 Must Have

```
As a contractor,
I want to record a partial payment and have the remaining balance automatically carried forward,
So that I can handle companies that pay in installments.
```

**Acceptance Criteria:**
- System calculates remainder: total due − amount received = remaining balance
- Remaining balance appears as "outstanding" on the collection dashboard
- The contractor can optionally mark the remainder as "scheduled for later"
- No manual entry required to carry the balance forward

---

## Epic 7 — Reports

### US-26 · Daily Summary Report
**Priority:** 🔴 Must Have

```
As a contractor,
I want a daily report showing total workers distributed and total earnings,
So that I can see my day's workload at a glance.
```

**Acceptance Criteria:**
- Shows: number of companies active today / total workers distributed / gross earnings / net earnings after deductions
- Broken down by company
- Accessible from the main dashboard and the reports section

---

### US-27 · Monthly Worker Earnings Report
**Priority:** 🔴 Must Have

```
As a contractor,
I want a monthly report for each worker showing their net earnings after deductions and advances,
So that I know exactly how much I have paid or owe each worker.
```

**Acceptance Criteria:**
- Formula: Gross earnings − Deductions − Advances recovered = Net pay
- Shows attendance days, deduction days, absent days
- Filterable by worker and by month
- Sortable by net pay (highest / lowest)

---

### US-28 · Cash Flow Report (Collected vs. Outstanding)
**Priority:** 🟡 Should Have

```
As a contractor,
I want a report showing total collected and total outstanding for any time period,
So that I understand my cash position at any moment.
```

**Acceptance Criteria:**
- Date range filter (this week / this month / custom)
- Filterable by company
- Shows: total earned / total collected / total outstanding / collection rate %
- Visual bar comparing collected vs. outstanding

---

### US-29 · Export Reports as PDF or Excel
**Priority:** 🔵 Could Have

```
As a contractor,
I want to export reports as a PDF or Excel file,
So that I can keep a copy or send it to a company in case of a dispute.
```

**Acceptance Criteria:**
- PDF format for clean presentation and printing
- Excel format for further editing and custom calculations
- Export includes the applied filters and date range in the file header
- File is generated and available for download within a few seconds

---

## Summary Table

| ID | Epic | Story | Priority |
|----|------|-------|----------|
| US-01 | Workers | Add new worker | 🔴 Must |
| US-02 | Workers | View list with daily status | 🔴 Must |
| US-03 | Workers | Edit or deactivate worker | 🔴 Must |
| US-04 | Workers | Monthly attendance calendar | 🟡 Should |
| US-05 | Workers | Filter and search | 🟡 Should |
| US-06 | Companies | Add new company | 🔴 Must |
| US-07 | Companies | Edit daily wage | 🔴 Must |
| US-08 | Companies | View company profile | 🔴 Must |
| US-09 | Companies | Deactivate company | 🟡 Should |
| US-10 | Distribution | Assign workers to company | 🔴 Must |
| US-11 | Distribution | Real-time earnings summary | 🔴 Must |
| US-12 | Distribution | One worker → multiple companies | 🟡 Should |
| US-13 | Distribution | Edit or cancel past distribution | 🟡 Should |
| US-14 | Deductions | Record a deduction | 🔴 Must |
| US-15 | Deductions | Add reason for deduction | 🔴 Must |
| US-16 | Deductions | Cancel wrong deduction | 🔴 Must |
| US-17 | Deductions | Full deduction history | 🟡 Should |
| US-18 | Advances | Record an advance | 🔴 Must |
| US-19 | Advances | Set recovery method | 🟡 Should |
| US-20 | Advances | View pending advances | 🔴 Must |
| US-21 | Collection | View total due per company | 🔴 Must |
| US-22 | Collection | Record received payment | 🔴 Must |
| US-23 | Collection | Overdue payment alert | 🟡 Should |
| US-24 | Collection | Full payment history | 🔴 Must |
| US-25 | Collection | Partial payment + carry forward | 🔴 Must |
| US-26 | Reports | Daily summary report | 🔴 Must |
| US-27 | Reports | Monthly worker earnings report | 🔴 Must |
| US-28 | Reports | Cash flow report | 🟡 Should |
| US-29 | Reports | Export PDF / Excel | 🔵 Could |

---

## MVP Scope (Must Have Only)

**17 user stories** form the MVP:
US-01, US-02, US-03, US-06, US-07, US-08, US-10, US-11, US-14, US-15, US-16, US-18, US-20, US-21, US-22, US-24, US-25, US-26, US-27

> Build these first. The system is fully functional with only these stories implemented.