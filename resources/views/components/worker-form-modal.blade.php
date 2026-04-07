<div id="workerModal" style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.4);z-index:1000;align-items:center;justify-content:center;flex-direction:column">
    <div style="background:#fff;border-radius:12px;width:90%;max-width:700px;max-height:90vh;overflow-y:auto;box-shadow:0 10px 40px rgba(0,0,0,0.2)">
        <!-- Modal Header -->
        <div style="display:flex;justify-content:space-between;align-items:center;padding:20px 24px;border-bottom:0.5px solid #d0d0c8;position:sticky;top:0;background:#fff;z-index:10">
            <h2 style="font-size:18px;font-weight:700;color:#1a1c19;margin:0" id="workerModalTitle">إضافة عامل جديد</h2>
            <button onclick="closeWorkerModal()" style="background:none;border:none;font-size:24px;cursor:pointer;color:#707a6c;padding:0;width:32px;height:32px;display:flex;align-items:center;justify-content:center">✕</button>
        </div>

        <!-- Modal Content -->
        <div style="padding:24px">
            <form id="workerForm" method="POST" action="">
                @csrf
                <input type="hidden" id="workerId" name="id">

                <!-- Name -->
                <div style="display:flex;flex-direction:column;gap:6px;margin-bottom:28px">
                    <label for="workerName" style="font-size:12px;font-weight:700;color:#1a1c19">الاسم الكامل *</label>
                    <p style="font-size:11px;color:#707a6c;margin:0 0 6px 0">💡 أدخل الاسم الكامل للعامل بوضوح</p>
                    <input type="text" id="workerName" name="name" placeholder="مثال: أحمد محمد علي"
                        style="width:100%;padding:10px 12px;border:0.5px solid #d0d0c8;border-radius:8px;font-size:13px;box-sizing:border-box;outline:none;font-family:'Tajawal',sans-serif">
                    <span id="nameError" style="font-size:11px;color:#ba1a1a;display:none">❌ الاسم مطلوب</span>
                </div>

                <!-- Modal Footer -->
                <div style="display:flex;gap:12px;justify-content:flex-end;border-top:0.5px solid #d0d0c8;padding-top:20px">
                    <button type="button" onclick="closeWorkerModal()" style="height:40px;padding:0 20px;font-size:13px;font-weight:600;border:0.5px solid #d0d0c8;background:#fff;border-radius:8px;cursor:pointer;color:#707a6c">
                        إلغاء
                    </button>
                    <button type="submit" style="height:40px;padding:0 20px;white-space:nowrap;font-size:13px;font-weight:600;background:#0d631b;color:#fff;border:none;border-radius:8px;cursor:pointer">
                        حفظ العامل
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    #workerModal {
        display: none;
        padding: 0;
    }

    #workerModal.show {
        display: flex !important;
    }

    #workerModal input,
    #workerModal select,
    #workerModal textarea {
        font-family: 'Tajawal', sans-serif;
        transition: border-color 0.2s;
    }

    #workerModal input:focus,
    #workerModal select:focus,
    #workerModal textarea:focus {
        border-color: #0d631b !important;
        background: #fff !important;
    }

    #workerModal > div {
        width: 90%;
        max-width: 700px;
        max-height: 90vh;
        border-radius: 12px;
        display: flex;
        flex-direction: column;
    }

    #workerModal > div > div:last-child {
        flex: 1;
        overflow-y: auto;
    }

    /* Desktop (769px+): Centered modal */
    @media(min-width: 769px) {
        #workerModal {
            align-items: center !important;
            justify-content: center !important;
        }

        #workerModal > div {
            width: 90%;
            max-width: 700px;
            max-height: 85vh;
            border-radius: 12px;
            animation: fadeSlideDown 0.3s ease-out;
        }

        @keyframes fadeSlideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    }

    /* Tablet (481px - 768px): Centered and slightly adjusted */
    @media(max-width: 768px) and (min-width: 481px) {
        #workerModal {
            align-items: center !important;
            justify-content: center !important;
        }

        #workerModal > div {
            width: 95%;
            max-width: 700px;
            max-height: 90vh;
            border-radius: 12px;
            animation: fadeSlideDown 0.3s ease-out;
        }

        @keyframes fadeSlideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    }

    /* Mobile: Full optimization with slide-up effect */
    @media(max-width: 480px) {
        #workerModal {
            padding: 0;
            align-items: flex-end !important;
            justify-content: flex-end !important;
        }

        #workerModal > div {
            width: 100% !important;
            max-width: 100% !important;
            max-height: 95vh !important;
            border-radius: 16px 16px 0 0;
            animation: slideUp 0.3s ease-out;
        }

        @keyframes slideUp {
            from {
                transform: translateY(100%);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Modal Header: Reduce padding and font size */
        #workerModal [style*="padding:20px 24px"] {
            padding: 14px 12px !important;
        }

        #workerModal [style*="padding:20px 24px"] h2 {
            font-size: 15px !important;
            font-weight: 700;
        }

        #workerModal [style*="padding:20px 24px"] button {
            width: 28px !important;
            height: 28px !important;
            font-size: 20px !important;
        }

        /* Modal Content: Reduce padding */
        #workerModal > div > div:nth-child(3) {
            padding: 14px 12px !important;
            padding-bottom: 16px !important;
        }

        /* Form Labels: Slightly smaller */
        #workerModal label {
            font-size: 11px !important;
            font-weight: 600;
        }

        /* Form Helper Text: Adjust size */
        #workerModal p[style*="color:#707a6c"] {
            font-size: 9px !important;
            margin-bottom: 4px !important;
        }

        /* Form Inputs: Better sizing for touch */
        #workerModal input,
        #workerModal select,
        #workerModal textarea {
            font-size: 14px !important;
            padding: 10px 10px !important;
            min-height: 38px;
        }

        /* Form sections */
        #workerModal > div > div:nth-child(3) > form > div {
            margin-bottom: 14px !important;
        }

        /* Modal Footer: Adjust button layout */
        #workerModal [style*="justify-content:flex-end"] {
            padding: 14px 12px !important;
            gap: 8px !important;
            border-top: 0.5px solid #d0d0c8;
        }

        #workerModal button[type="button"] {
            background: #f5f6f8 !important;
            border: 0.5px solid #d0d0c8 !important;
            color: #1a1c19 !important;
            font-size: 12px !important;
            height: 36px !important;
            padding: 0 14px !important;
            flex: 1;
            border-radius: 8px;
        }

        #workerModal button[type="submit"] {
            background: #0d631b !important;
            color: #fff !important;
            border: none !important;
            font-size: 12px !important;
            height: 36px !important;
            padding: 0 14px !important;
            flex: 1;
            border-radius: 8px;
        }

        /* Error messages */
        #workerModal [style*="color:#ba1a1a"] {
            font-size: 10px !important;
            margin-top: 3px !important;
        }
    }
</style>

<script>
function openWorkerModal(isEdit = false, workerId = null) {
    const modal = document.getElementById('workerModal');
    const form = document.getElementById('workerForm');
    const title = document.getElementById('workerModalTitle');
    const submitBtn = form.querySelector('button[type="submit"]');

    // Reset form
    form.reset();
    document.getElementById('workerId').value = '';
    clearWorkerErrors();

    if (isEdit && workerId) {
        // Load worker data
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
            
            title.textContent = 'تعديل: ' + data.name;
            submitBtn.textContent = 'حفظ التعديلات';
            
            // Show modal
            modal.classList.add('show');
            document.body.style.overflow = 'hidden';
        })
        .catch(error => {
            console.error('Error:', error);
        });
    } else {
        title.textContent = 'إضافة عامل جديد';
        submitBtn.textContent = 'حفظ العامل';
        
        // Show modal
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
    }
}

function closeWorkerModal() {
    const modal = document.getElementById('workerModal');
    modal.classList.remove('show');
    document.body.style.overflow = 'auto';
}

function clearWorkerErrors() {
    document.getElementById('nameError').style.display = 'none';
}

function displayWorkerErrors(errors) {
    clearWorkerErrors();
    if (errors.name) document.getElementById('nameError').style.display = 'block';
}

// Form submission
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('workerForm');
    const modal = document.getElementById('workerModal');
    
    // Close modal on background click
    modal.addEventListener('click', function(e) {
        if (e.target === this) {
            closeWorkerModal();
        }
    });
    
    // Handle form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const workerId = document.getElementById('workerId').value;
        const url = workerId 
            ? `/contractor/workers/${workerId}` 
            : '/contractor/workers';
        const method = workerId ? 'PUT' : 'POST';

        const formData = {
            name: document.getElementById('workerName').value,
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
                // Show success toast
                window.showToast(data.message || 'تم حفظ العامل بنجاح', 'success');
                
                closeWorkerModal();
                
                // Reload workers list after a short delay
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (error.message !== 'Validation failed') {
                window.showToast('حدث خطأ في حفظ بيانات العامل', 'error');
            }
        });
    });
});

// Close modal when clicking outside
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('workerModal');
    modal.addEventListener('click', function(e) {
        if (e.target === this) {
            closeWorkerModal();
        }
    });
});
</script>
