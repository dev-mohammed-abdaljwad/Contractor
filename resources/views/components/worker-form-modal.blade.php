<div id="workerModal" class="modal-overlay">
    <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
            <h2 id="workerModalTitle" style="font-size: 18px; font-weight: 700; color: #1a1c19; margin: 0">إضافة عامل جديد</h2>
            <button onclick="closeWorkerModal()" style="background: none; border: none; cursor: pointer; color: #707a6c; font-size: 24px; padding: 0">✕</button>
        </div>

        <!-- Modal Body -->
        <form id="workerForm" style="display: flex; flex-direction: column; gap: 16px">
            @csrf
            <input type="hidden" id="workerId" name="id">

            <!-- Name -->
            <div style="display: flex; flex-direction: column; gap: 6px">
                <label for="workerName" style="font-size: 12px; font-weight: 700; color: #1a1c19">
                    الاسم الكامل *
                </label>
                <input 
                    type="text" 
                    id="workerName" 
                    name="name" 
                    placeholder="مثال: أحمد محمد علي"
                    style="width: 100%; height: 42px; border: 1px solid #d0d0c8; border-radius: 8px; background: #fafaf5; font-family: 'Tajawal', sans-serif; font-size: 14px; color: #1a1c19; padding: 0 12px; outline: none; transition: border 0.15s"
                    onfocus="this.style.borderColor='#0d631b'; this.style.background='#fff'"
                    onblur="this.style.borderColor='#d0d0c8'; this.style.background='#fafaf5'"
                >
                <span id="nameError" style="font-size: 11px; color: #ba1a1a; display: none">❌ الاسم مطلوب</span>
                <span style="font-size: 11px; color: #0d631b">💡 أدخل الاسم الكامل للعامل بوضوح</span>
            </div>

            <!-- Phone -->
            <div style="display: flex; flex-direction: column; gap: 6px">
                <label for="workerPhone" style="font-size: 12px; font-weight: 700; color: #1a1c19">
                    رقم الجوال *
                </label>
                <input 
                    type="tel" 
                    id="workerPhone" 
                    name="phone" 
                    placeholder="مثال: 0123456789"
                    style="width: 100%; height: 42px; border: 1px solid #d0d0c8; border-radius: 8px; background: #fafaf5; font-family: 'Tajawal', sans-serif; font-size: 14px; color: #1a1c19; padding: 0 12px; outline: none; transition: border 0.15s"
                    onfocus="this.style.borderColor='#0d631b'; this.style.background='#fff'"
                    onblur="this.style.borderColor='#d0d0c8'; this.style.background='#fafaf5'"
                    maxlength="20"
                >
                <span id="phoneError" style="font-size: 11px; color: #ba1a1a; display: none">❌ رقم الجوال مطلوب</span>
                <span style="font-size: 11px; color: #0d631b">💡 استخدم أي رقم جوال صحيح لتتمكن من التواصل</span>
            </div>

            <!-- National ID -->
            <div style="display: flex; flex-direction: column; gap: 6px">
                <label for="workerNationalId" style="font-size: 12px; font-weight: 700; color: #1a1c19">
                    الرقم القومي (اختياري)
                </label>
                <input 
                    type="text" 
                    id="workerNationalId" 
                    name="national_id" 
                    placeholder="مثال: 12345678901234"
                    style="width: 100%; height: 42px; border: 1px solid #d0d0c8; border-radius: 8px; background: #fafaf5; font-family: 'Tajawal', sans-serif; font-size: 14px; color: #1a1c19; padding: 0 12px; outline: none; transition: border 0.15s"
                    onfocus="this.style.borderColor='#0d631b'; this.style.background='#fff'"
                    onblur="this.style.borderColor='#d0d0c8'; this.style.background='#fafaf5'"
                    maxlength="20"
                >
                <span style="font-size: 11px; color: #0d631b">💡 الرقم القومي يساعد في التحقق من هوية العامل</span>
            </div>

            <!-- Join Date -->
            <div style="display: flex; flex-direction: column; gap: 6px">
                <label for="workerJoinedDate" style="font-size: 12px; font-weight: 700; color: #1a1c19">
                    تاريخ الالتحاق
                </label>
                <input 
                    type="date" 
                    id="workerJoinedDate" 
                    name="joined_date" 
                    style="width: 100%; height: 42px; border: 1px solid #d0d0c8; border-radius: 8px; background: #fafaf5; font-family: 'Tajawal', sans-serif; font-size: 14px; color: #1a1c19; padding: 0 12px; outline: none; transition: border 0.15s"
                    onfocus="this.style.borderColor='#0d631b'; this.style.background='#fff'"
                    onblur="this.style.borderColor='#d0d0c8'; this.style.background='#fafaf5'"
                >
                <span id="joinedDateError" style="font-size: 11px; color: #ba1a1a; display: none">❌ التاريخ غير صحيح</span>
                <span style="font-size: 11px; color: #0d631b">💡 يتم تعيينها اليوم تلقائياً إذا لم تغيرها</span>
            </div>

            <!-- Modal Footer -->
            <div style="display: flex; gap: 12px; margin-top: 20px; border-top: 0.5px solid #e8e8e3; padding-top: 16px">
                <button 
                    type="button" 
                    onclick="closeWorkerModal()" 
                    style="flex: 1; height: 42px; border: 1px solid #d0d0c8; border-radius: 8px; background: #fff; color: #707a6c; font-family: 'Tajawal', sans-serif; font-size: 13px; font-weight: 700; cursor: pointer; transition: all 0.15s"
                    onhover="this.style.background='#fafaf5'"
                >
                    إلغاء
                </button>
                <button 
                    type="submit" 
                    style="flex: 1; height: 42px; border: none; border-radius: 8px; background: #0d631b; color: #fff; font-family: 'Tajawal', sans-serif; font-size: 13px; font-weight: 700; cursor: pointer; transition: all 0.15s"
                    onmouseover="this.style.background='#0a5216'"
                    onmouseout="this.style.background='#0d631b'"
                >
                    حفظ العامل
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    #workerModal {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.4);
        z-index: 50;
        align-items: center;
        justify-content: center;
    }

    #workerModal.show {
        display: flex;
    }

    .modal-content {
        width: 90%;
        max-width: 700px;
        max-height: 90vh;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        display: flex;
        flex-direction: column;
    }

    .modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 24px;
        border-bottom: 0.5px solid #e8e8e3;
        flex-shrink: 0;
        position: sticky;
        top: 0;
        background: #fff;
        border-radius: 12px 12px 0 0;
    }

    #workerForm {
        padding: 24px;
        overflow-y: auto;
        flex: 1;
    }

    #workerModal .modal-content > *:last-child {
        flex-shrink: 0;
    }

    /* Desktop */
    @media(min-width: 769px) {
        .modal-content {
            width: 700px;
            max-width: 90%;
        }

        .modal-header {
            padding: 24px;
        }

        #workerForm {
            padding: 24px;
        }
    }

    /* Tablet (481px - 768px) */
    @media(max-width: 768px) and (min-width: 481px) {
        .modal-content {
            width: 95%;
            max-width: 700px;
        }

        .modal-header {
            padding: 18px;
        }

        #workerForm {
            padding: 18px;
        }

        #workerForm > div:first-child input,
        #workerForm > div input[name="phone"],
        #workerForm > div input[name="national_id"],
        #workerForm > div input[name="joined_date"] {
            font-size: 13px;
        }
    }

    /* Mobile (max-width: 480px) */
    @media(max-width: 480px) {
        #workerModal {
            align-items: flex-end;
        }

        .modal-content {
            width: 100%;
            max-width: 100%;
            max-height: 95vh;
            border-radius: 16px 16px 0 0;
        }

        .modal-header {
            padding: 14px 12px;
        }

        #workerForm {
            padding: 12px;
            gap: 10px !important;
        }

        #workerForm > div {
            gap: 3px !important;
        }

        #workerForm input {
            height: 38px !important;
            font-size: 14px !important;
            padding: 0 10px !important;
        }

        label {
            font-size: 12px !important;
            font-weight: 600 !important;
        }

        span {
            font-size: 10px !important;
        }

        /* Button container */
        #workerForm > div:last-child {
            gap: 8px !important;
            margin-top: 16px !important;
            padding-top: 12px !important;
        }

        #workerForm > div:last-child button {
            height: 38px !important;
            font-size: 12px !important;
            padding: 0 12px !important;
            flex: 1 !important;
            border-radius: 6px !important;
        }

        .modal-header h2 {
            font-size: 15px !important;
        }

        .modal-header button {
            font-size: 20px !important;
            padding: 0 4px !important;
        }
    }
</style>

<script>
function openWorkerModal(isEdit = false, workerId = null) {
    const modal = document.getElementById('workerModal');
    const form = document.getElementById('workerForm');
    const title = document.getElementById('workerModalTitle');
    const button = form.querySelector('button[type="submit"]');

    // Reset form
    form.reset();
    document.getElementById('workerId').value = '';
    clearWorkerErrors();

    if (isEdit && workerId) {
        title.textContent = 'تعديل بيانات العامل';
        button.textContent = 'حفظ التعديلات';

        // Fetch worker data
        fetch(`/contractor/workers/${workerId}`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('workerId').value = data.id;
            document.getElementById('workerName').value = data.name;
            document.getElementById('workerPhone').value = data.phone;
            document.getElementById('workerNationalId').value = data.national_id || '';
            document.getElementById('workerJoinedDate').value = data.joined_date || new Date().toISOString().split('T')[0];
        });
    } else {
        title.textContent = 'إضافة عامل جديد';
        button.textContent = 'حفظ العامل';
        document.getElementById('workerJoinedDate').value = new Date().toISOString().split('T')[0];
    }

    modal.classList.add('show');
}

function closeWorkerModal() {
    const modal = document.getElementById('workerModal');
    modal.classList.remove('show');
}

function clearWorkerErrors() {
    document.getElementById('nameError').style.display = 'none';
    document.getElementById('phoneError').style.display = 'none';
    document.getElementById('joinedDateError').style.display = 'none';
}

function displayWorkerErrors(errors) {
    clearWorkerErrors();
    if (errors.name) document.getElementById('nameError').style.display = 'block';
    if (errors.phone) document.getElementById('phoneError').style.display = 'block';
    if (errors.joined_date) document.getElementById('joinedDateError').style.display = 'block';
}

// Form submission
document.getElementById('workerForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const workerId = document.getElementById('workerId').value;
    const url = workerId 
        ? `/contractor/workers/${workerId}` 
        : '/contractor/workers';
    const method = workerId ? 'PUT' : 'POST';

    const formData = {
        name: document.getElementById('workerName').value,
        phone: document.getElementById('workerPhone').value,
        national_id: document.getElementById('workerNationalId').value,
        joined_date: document.getElementById('workerJoinedDate').value,
        _token: document.querySelector('input[name="_token"]').value
    };

    if (workerId) {
        formData._method = 'PUT';
    }

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify(formData)
    })
    .then(response => {
        if (response.status === 422) {
            return response.json().then(data => {
                displayWorkerErrors(data.errors);
                throw new Error('Validation failed');
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            closeWorkerModal();
            // Reload workers list
            window.location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
});

// Close modal on outside click
document.getElementById('workerModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeWorkerModal();
    }
});
</script>
