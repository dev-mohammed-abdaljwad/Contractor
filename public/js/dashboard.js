// ============ PAGE CONFIGURATION ============
const pages = {
    dashboard: {
        title: 'لوحة المتابعة',
        action: 'توزيع جديد'
    },
    distribution: {
        title: 'التوزيع اليومي',
        action: '+ توزيع جديد'
    },
    workers: {
        title: 'ملف العامل',
        action: '+ إضافة عامل'
    },
    collection: {
        title: 'التحصيل',
        action: 'تسجيل دفعة'
    },
};

// ============ PAGE NAVIGATION ============
function showPage(name) {
    // Hide all pages
    document.querySelectorAll('.page').forEach(function(p) {
        p.classList.remove('active');
    });

    // Remove active from all nav links
    document.querySelectorAll('.nav-link').forEach(function(n) {
        n.classList.remove('active');
    });

    // Remove active from mobile footer links
    document.querySelectorAll('.mobile-footer-link').forEach(function(n) {
        n.classList.remove('active');
    });

    // Show the selected page
    const page = document.getElementById('page-' + name);
    if (page) {
        page.classList.add('active');
        page.classList.remove('fade-in');
        void page.offsetWidth; // Trigger reflow
        page.classList.add('fade-in');
    }

    // Set active nav link
    document.querySelectorAll('.nav-link').forEach(function(n) {
        if (n.getAttribute('onclick') && n.getAttribute('onclick').includes("'" + name + "'")) {
            n.classList.add('active');
        }
    });

    // Set active mobile footer link
    document.querySelectorAll('.mobile-footer-link').forEach(function(n) {
        if (n.getAttribute('onclick') && n.getAttribute('onclick').includes("'" + name + "'")) {
            n.classList.add('active');
        }
    });

    // Update page title and action
    const info = pages[name] || {};
    document.getElementById('desktop-page-title').textContent = info.title || '';
    document.getElementById('mob-page-title').textContent = info.title || '';
    document.getElementById('topbar-action').innerHTML = '<span class="ms" style="font-size:16px">add</span> ' + (info.action || '');

    // Close sidebar on mobile
    closeSidebar();
}

function topbarAction() {
    const active = document.querySelector('.page.active');
    if (active && active.id === 'page-dashboard') {
        showPage('distribution');
    }
}

// ============ SIDEBAR TOGGLE ============
function openSidebar() {
    document.getElementById('sidebar').classList.add('open');
    document.getElementById('mobile-overlay').classList.add('show');
}

function closeSidebar() {
    document.getElementById('sidebar').classList.remove('open');
    document.getElementById('mobile-overlay').classList.remove('show');
}

// ============ WORKER TABS ============
function switchTab(el, tabId) {
    // Remove active from all tab buttons
    document.querySelectorAll('.tab-btn').forEach(function(btn) {
        btn.classList.remove('active');
    });

    // Add active to clicked button
    if (el && el.classList) {
        el.classList.add('active');
    }

    // Hide all tab content and show the selected one
    document.querySelectorAll('.tab-content').forEach(function(content) {
        if (content.id === tabId) {
            content.classList.add('active');
            content.style.display = 'block';
        } else {
            content.classList.remove('active');
            content.style.display = 'none';
        }
    });
}

// ============ WORKER SELECTION ============
function selectWorker(el, name, num) {
    // Remove highlight from all workers
    document.querySelectorAll('.worker-row').forEach(function(r) {
        r.style.background = '';
    });

    // Highlight selected worker
    el.style.background = '#f0f9f0';

    // Update worker details
    document.getElementById('worker-name').textContent = name;
    document.getElementById('worker-num').textContent = 'عامل · رقم ' + num;
    document.getElementById('worker-av').textContent = name.charAt(0);
}

// ============ DEDUCTION SELECTION ============
function selDisc(btn, label) {
    // Remove selected state from all buttons
    document.querySelectorAll('.disc-btn').forEach(function(b) {
        b.classList.remove('sel');
    });

    // Add selected state to clicked button
    btn.classList.add('sel');
}

// ============ PAYMENT METHOD SELECTION ============
function selPay(btn) {
    // Get all disc-btn elements in the same parent
    btn.parentElement.querySelectorAll('.disc-btn').forEach(function(b) {
        b.classList.remove('sel');
    });

    // Add selected state
    btn.classList.add('sel');
}

// ============ WAGE UPDATE ============
function updateWage(sel) {
    const wage = sel.value;
    document.getElementById('wage-display').value = wage + ' جنيه';
    document.getElementById('wage-val').textContent = wage + ' ج';

    // Update total (assuming 3 workers)
    const count = 3;
    document.getElementById('total-val').textContent = (count * parseInt(wage)).toLocaleString('ar-EG') + ' ج';
}

// ============ ADD WORKER ============
let workerCount = 3;

function addWorker() {
    workerCount++;
    document.getElementById('worker-count').textContent = workerCount + ' عمال';

    const wage = parseInt(document.getElementById('wage-display').value) || 250;
    document.getElementById('total-val').textContent = (workerCount * wage).toLocaleString('ar-EG') + ' ج';

    // Create new chip
    const chip = document.createElement('span');
    chip.className = 'chip';
    chip.innerHTML = '<span class="chip-dot"></span>عامل جديد ' + workerCount;

    // Add before the "add" button
    const container = document.getElementById('chips-container');
    const addBtn = container.lastElementChild;
    container.insertBefore(chip, addBtn);
}

// ============ PAYMENT MODAL ============
function openPayModal(company, amount) {
    document.getElementById('pay-company').value = company;
    document.getElementById('pay-amount').value = parseInt(amount).toLocaleString('ar-EG') + ' ج';
    document.getElementById('pay-before').textContent = parseInt(amount).toLocaleString('ar-EG') + ' ج';
    document.getElementById('pay-title').textContent = 'تسجيل دفعة — ' + company;

    // Scroll to payment panel
    document.getElementById('pay-panel').scrollIntoView({
        behavior: 'smooth',
        block: 'start'
    });
}

// ============ COMPANY MODAL FUNCTIONS ============
function openCompanyModal(isEdit = false, companyId = null) {
    // Reset form
    document.getElementById('company-form').reset();
    document.querySelectorAll('.form-error').forEach(el => el.style.display = 'none');
    
    const modal = document.getElementById('company-form-modal');
    const statusField = document.getElementById('status-field');
    const modalTitle = document.getElementById('modal-title');
    const submitBtnText = document.getElementById('submit-btn-text');
    const form = document.getElementById('company-form');
    
    if (isEdit && companyId) {
        // Edit mode
        modalTitle.textContent = 'تعديل بيانات الشركة';
        submitBtnText.textContent = 'تحديث البيانات';
        statusField.style.display = 'block';
        document.getElementById('form-method').value = 'PATCH';
        form.action = `/contractor/companies/${companyId}`;
        
        // Fetch company data
        fetch(`/contractor/companies/${companyId}`, {
            headers: {
                'Accept': 'application/json'
            }
        })
            .then(response => response.json())
            .then(data => {
                document.getElementById('form-name').value = data.name;
                document.getElementById('form-contact_person').value = data.contact_person;
                document.getElementById('form-phone').value = data.phone;
                document.getElementById('form-daily_wage').value = data.daily_wage;
                document.getElementById('form-payment_cycle').value = data.payment_cycle;
                document.getElementById('form-weekly_pay_day').value = data.weekly_pay_day || '';
                document.getElementById('form-contract_start_date').value = data.contract_start_date;
                document.getElementById('form-is_active').value = data.is_active ? '1' : '0';
                document.getElementById('form-notes').value = data.notes || '';
            })
            .catch(error => {
                console.error('Error loading company:', error);
                alert('حدث خطأ في تحميل بيانات الشركة');
                closeCompanyModal();
            });
    } else {
        // Create mode
        modalTitle.textContent = 'شركة جديدة';
        submitBtnText.textContent = 'حفظ الشركة';
        statusField.style.display = 'none';
        document.getElementById('form-method').value = 'POST';
        form.action = '/contractor/companies';
    }
    
    modal.classList.add('show');
}

function closeCompanyModal() {
    document.getElementById('company-form-modal').classList.remove('show');
}

// Close modal on background click
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('company-form-modal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeCompanyModal();
            }
        });

        // Handle form submission
        document.getElementById('company-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Clear previous errors
            document.querySelectorAll('.form-error').forEach(el => el.style.display = 'none');
            
            const formData = new FormData(this);
            const action = this.action;
            const method = document.getElementById('form-method').value;
            
            // Add method to formData for PATCH
            if (method === 'PATCH') {
                formData.append('_method', 'PATCH');
            }
            
            fetch(action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => {
                if (response.status === 422) {
                    // Validation errors
                    return response.json().then(data => {
                        if (data.errors) {
                            Object.keys(data.errors).forEach(field => {
                                const errorEl = document.getElementById('error-' + field);
                                if (errorEl) {
                                    errorEl.textContent = '❌ ' + data.errors[field][0];
                                    errorEl.style.display = 'block';
                                }
                            });
                        }
                        throw new Error('Validation failed');
                    });
                }
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    closeCompanyModal();
                    // Reload companies page
                    window.location.href = '/contractor/companies';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                if (error.message !== 'Validation failed') {
                    alert('حدث خطأ في حفظ البيانات');
                }
            });
        });
    }
});

// ============ INITIALIZATION ============
document.addEventListener('DOMContentLoaded', function() {
    // Initialize first page
    showPage('dashboard');
    
    // Check if we're on companies page (for external navigation)
    const currentPath = window.location.pathname;
    if (currentPath.includes('/companies')) {
        // Mark companies link as active
        document.querySelectorAll('.mobile-footer-link').forEach(function(link) {
            link.classList.remove('active');
            if (link.getAttribute('data-page') === 'companies') {
                link.classList.add('active');
            }
        });
    }
});
