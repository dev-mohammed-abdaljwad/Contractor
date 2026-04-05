# Dashboard Component Structure Documentation

## Overview
The dashboard has been modularized into reusable Blade components and separate page modules. This makes the codebase more maintainable, scalable, and easier to extend.

## Directory Structure

```
resources/
├── views/
│   ├── layouts/
│   │   └── dashboard.blade.php          # Main dashboard layout
│   ├── components/
│   │   ├── dashboard/
│   │   │   ├── sidebar.blade.php        # Sidebar navigation
│   │   │   ├── topbar.blade.php         # Top navigation bar
│   │   │   └── mobile-topbar.blade.php  # Mobile top bar
│   │   ├── stat-card.blade.php          # Statistics card component
│   │   ├── card.blade.php               # Generic card wrapper
│   │   ├── badge.blade.php              # Badge component
│   │   ├── avatar.blade.php             # Avatar component
│   │   └── button.blade.php             # Reusable button component
│   └── pages/
│       └── dashboard/
│           ├── index.blade.php          # Dashboard main page
│           ├── distribution.blade.php   # Daily distribution page
│           ├── workers.blade.php        # Workers management page
│           └── collection.blade.php     # Collection page
│   └── dashboard.blade.php              # Main dashboard view (includes all pages)
├── css/
│   └── dashboard.css                    # All dashboard styles
└── js/
    └── dashboard.js                     # All dashboard JavaScript
```

## Component Usage

### 1. Layout Component `resources/views/layouts/dashboard.blade.php`
The main layout wrapper that includes:
- Sidebar
- Mobile overlay
- Mobile topbar
- Main content area with topbar
- CSS and JS includes

**Usage:**
```blade
@extends('layouts.dashboard')
@section('content')
    <!-- Your page content -->
@endsection
```

---

## 2. Dashboard Components

### Sidebar
**File:** `resources/views/components/dashboard/sidebar.blade.php`

Features:
- Fixed sidebar on desktop
- Collapsible on mobile
- Active navigation state
- User info display
- Navigation links to all pages

**Dynamic:** Uses `{{ auth()->user()->name }}` for authenticated user name

---

### Topbar
**File:** `resources/views/components/dashboard/topbar.blade.php`

Features:
- Page title display
- Current date (auto-formatted in Arabic)
- Dynamic action button based on active page
- Mobile-responsive design

---

## 3. Reusable UI Components

### Stat Card
**File:** `resources/views/components/stat-card.blade.php`

Properties:
- `label` (required) - Label text
- `value` (required) - Statistics value
- `subtext` (optional) - Additional info text
- `variant` (default: 'default') - Color variant: 'default', 'green', 'amber', 'blue'

**Usage:**
```blade
<x-stat-card 
    label="إجمالي العمال" 
    value="47" 
    subtext="عامل مسجل"
    variant="default"
/>
```

---

### Card
**File:** `resources/views/components/card.blade.php`

Properties:
- `title` (optional) - Card header title
- `action` (optional) - Action button/link text
- `actionUrl` (optional) - Page name to navigate to

**Usage:**
```blade
<x-card title="الشركات النهارده" action="+ توزيع جديد" actionUrl="distribution">
    <!-- Card content goes here -->
</x-card>
```

---

### Badge
**File:** `resources/views/components/badge.blade.php`

Properties:
- `variant` (required) - Badge color: 'green', 'amber', 'blue', 'red', 'gray'
- `icon` (optional) - Material Symbol icon name

**Usage:**
```blade
<x-badge variant="green">نشط</x-badge>
<x-badge variant="amber" icon="warning">غير مسدد</x-badge>
```

---

### Avatar
**File:** `resources/views/components/avatar.blade.php`

Properties:
- `initial` (required) - Character to display (usually first letter of name)
- `variant` (required) - Color: 'green', 'blue', 'amber', 'purple'
- `size` (optional) - 'default', 'small', 'large'

**Usage:**
```blade
<x-avatar initial="م" variant="green" size="default" />
<x-avatar initial="ر" variant="blue" size="small" />
```

---

### Button
**File:** `resources/views/components/button.blade.php`

Properties:
- `variant` (required) - 'primary', 'outline', 'danger'
- `size` (optional) - 'default', 'small'
- `icon` (optional) - Material Symbol icon name
- `iconSize` (optional) - 'default', 'small'

**Usage:**
```blade
<x-button variant="primary" icon="check_circle">
    تأكيد التوزيع
</x-button>

<x-button variant="outline" size="small" icon="edit">
    تعديل
</x-button>
```

---

## 4. Page Components

### Dashboard Page
**File:** `resources/views/pages/dashboard/index.blade.php`

Features:
- Statistics cards grid
- Companies today table
- Pending collections table
- Uses stat-card, card, badge, and avatar components

---

### Distribution Page
**File:** `resources/views/pages/dashboard/distribution.blade.php`

Features:
- Company selection dropdown
- Daily wage display
- Worker selection chips with add functionality
- Distribution summary box
- Today's distributions list

JavaScript Functions:
- `updateWage(element)` - Updates wage display
- `addWorker()` - Adds new worker to distribution

---

### Workers Page
**File:** `resources/views/pages/dashboard/workers.blade.php`

Features:
- Worker list with search
- Worker detail card with color-coded header
- Tabbed interface (Attendance, Deductions, Advances, Account)
- Attendance table with wage summary
- Deduction registration form
- Advance registration form
- Account summary

JavaScript Functions:
- `selectWorker(element, name, num)` - Selects worker and updates details
- `switchTab(element, tabId)` - Switches between tabs
- `selDisc(button, label)` - Selects deduction type

---

### Collection Page
**File:** `resources/views/pages/dashboard/collection.blade.php`

Features:
- Collection statistics grid
- Companies collection status table
- Payment registration form (right sidebar)
- Dynamic payment form updates
- Collection status badges

JavaScript Functions:
- `openPayModal(company, amount)` - Opens payment form with company data
- `selPay(button)` - Selects payment method

---

## 5. Styling

**File:** `public/css/dashboard.css`

Complete stylesheet including:
- Tailwind customization
- Component styles
- Layout styles
- Responsive breakpoints
- Animations
- Mobile-specific styles

Key breakpoints:
- Desktop: 768px+
- Tablet: 481px - 768px
- Mobile: ≤480px

---

## 6. JavaScript

**File:** `public/js/dashboard.js`

Core Functions:

### Page Navigation
- `showPage(name)` - Shows/hides pages and updates UI
- `topbarAction()` - Handles topbar action button

### Sidebar
- `openSidebar()` - Opens sidebar on mobile
- `closeSidebar()` - Closes sidebar on mobile

### Worker Management
- `selectWorker(el, name, num)` - Selects a worker and updates display
- `switchTab(el, tabId)` - Switches worker detail tabs

### Distribution
- `updateWage(sel)` - Updates wage display based on selection
- `addWorker()` - Adds worker to distribution list

### Deductions & Payments
- `selDisc(btn, label)` - Selects deduction type
- `selPay(btn)` - Selects payment method

### Modals
- `openPayModal(company, amount)` - Opens payment form

---

## How to Add New Pages

1. Create a new page file in `resources/views/pages/dashboard/`
2. Add configuration to `pages` object in `dashboard.js`:
   ```javascript
   pages.newpage = {
       title: 'اسم الصفحة',
       action: 'النص'
   };
   ```
3. Create page element in HTML with id `page-newpage`
4. Add navigation link in sidebar with onclick handler
5. Implement page-specific JavaScript functions

---

## How to Add New Components

1. Create component file in `resources/views/components/`
2. Use `@props()` to define component properties
3. Use `{{ $slot }}` for content injection
4. Example:
   ```blade
   @props(['variant' => 'primary'])
   
   <div class="my-component my-component-{{ $variant }}">
       {{ $slot }}
   </div>
   ```
5. Use with: `<x-my-component variant="success">Content</x-my-component>`

---

## Responsive Design Strategy

### Sidebar
- Desktop: Fixed right sidebar (240px)
- Mobile (≤768px): Slide-out sidebar with overlay

### Tables
- Desktop: All columns visible
- Tablet & Mobile: Text columns hidden except primary

### Grids
- Desktop: 3 columns (stat-grid), 2 columns (layout-grid)
- Tablet: 2 columns
- Mobile: 1 column

### Forms
- Desktop: 2-column grid
- Mobile: 1-column stack

---

## Color Palette

Primary Colors:
- Primary: `#0d631b` (dark green)
- Primary Mid: `#1D9E75` (mid green)
- Primary Light: `#66BB6A` (light green)
- Primary BG: `#E1F5EE` (very light green)

Secondary Colors:
- Amber: `#BA7517`
- Blue: `#185FA5`
- Red/Danger: `#ba1a1a`

Neutral Colors:
- Surface: `#fafaf5`
- Outline: `#d0d0c8`
- Muted: `#707a6c`
- Text Primary: `#1a1c19`

---

## Font Families

- Arabic: `Tajawal` (Google Fonts)
  - Weights: 300, 400, 500, 700, 900
- Icons: `Material Symbols Outlined` (Google Fonts)

---

## Future Enhancements

1. **Dynamic Data Binding** - Connect components to backend data
2. **Form Validation** - Add real form validation
3. **Data Export** - Excel/PDF export for reports
4. **Charts & Analytics** - Add Chart.js or similar
5. **Notifications** - Realtime notifications with Livewire
6. **Mobile App** - Convert to mobile-responsive SPA
7. **Dark Mode** - Add dark theme support
8. **Accessibility** - Improve ARIA labels and keyboard navigation

---

## Troubleshooting

### Pages not showing
- Check if `showPage()` is being called correctly
- Verify page element IDs match: `page-pagename`
- Check browser console for JavaScript errors

### Styling issues
- Ensure `dashboard.css` is loaded: Check network tab
- Verify Tailwind CDN is loaded
- Check for conflicting CSS classes

### Mobile not responsive
- Verify media queries are correct in `dashboard.css`
- Check viewport meta tag in layout
- Test with browser DevTools device emulation

---

## Git Workflow

When updating components:
1. Update component file
2. Update all pages that use the component
3. Test on desktop and mobile
4. Commit with clear message about component changes

