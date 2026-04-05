<!-- Delete Confirmation Modal -->
<div id="delete-modal" style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center">
    <div style="background:#fff;border-radius:12px;width:90%;max-width:400px;box-shadow:0 10px 40px rgba(0,0,0,0.2)">
        <!-- Modal Header -->
        <div style="display:flex;align-items:center;gap:12px;padding:20px 24px;border-bottom:0.5px solid #d0d0c8">
            <span class="ms" style="font-size:32px;color:#ba1a1a">warning</span>
            <h2 style="font-size:16px;font-weight:700;color:#1a1c19">تأكيد الحذف</h2>
        </div>

        <!-- Modal Content -->
        <div style="padding:24px">
            <p style="font-size:13px;color:#1a1c19;line-height:1.6;margin-bottom:16px">
                هل أنت متأكد من رغبتك في حذف <strong id="delete-item-name"></strong>؟
            </p>
            <p style="font-size:12px;color:#BA7517;background:#FAEEDA;padding:12px;border-radius:8px;border-right:3px solid #BA7517">
                ⚠️ لا يمكن التراجع عن هذا الإجراء
            </p>
        </div>

        <!-- Modal Footer -->
        <div style="display:flex;gap:12px;justify-content:flex-end;padding:20px 24px;border-top:0.5px solid #d0d0c8">
            <button type="button" onclick="closeDeleteModal()" class="btn btn-outline" style="height:40px;padding:0 20px">
                إلغاء
            </button>
            <button type="button" id="delete-confirm-btn" onclick="confirmDelete()" class="btn btn-danger" style="height:40px;padding:0 20px;white-space:nowrap">
                <span class="ms ms-fill" style="font-size:16px">delete</span> حذف نهائي
            </button>
        </div>
    </div>
</div>

<style>
    #delete-modal {
        display: none;
        padding: 0;
    }

    #delete-modal.show {
        display: flex !important;
    }

    #delete-modal > div {
        width: 90%;
        max-width: 400px;
        border-radius: 12px;
    }

    /* Tablet & Mobile: Adjust modal width */
    @media(max-width: 768px) {
        #delete-modal > div {
            width: 95% !important;
            max-width: 95% !important;
        }

        #delete-modal {
            padding: 8px;
        }
    }

    /* Mobile: Full optimization */
    @media(max-width: 480px) {
        #delete-modal > div {
            width: 98% !important;
            max-width: 98% !important;
            border-radius: 8px;
        }

        #delete-modal {
            padding: 2px;
        }

        /* Modal Header: Reduce padding and font size */
        [style*="gap:12px;padding:20px 24px;border-bottom"] {
            padding: 16px 12px !important;
        }

        [style*="gap:12px;padding:20px 24px;border-bottom"] span.ms {
            font-size: 24px !important;
        }

        [style*="gap:12px;padding:20px 24px;border-bottom"] h2 {
            font-size: 14px !important;
        }

        /* Modal Content: Reduce padding */
        #delete-modal [style*="padding:24px"] {
            padding: 16px !important;
        }

        /* Warning text: Adjust size */
        #delete-modal p[style*="font-size:13px"] {
            font-size: 12px !important;
        }

        #delete-modal p[style*="font-size:12px"] {
            font-size: 11px !important;
            padding: 10px !important;
        }

        /* Modal Footer: Stack buttons vertically if needed */
        #delete-modal [style*="justify-content:flex-end"] {
            padding: 16px 12px !important;
            flex-wrap: wrap;
        }

        #delete-modal .btn {
            font-size: 12px !important;
            flex: 1;
        }

        #delete-modal .btn.btn-danger {
            background: #ba1a1a;
            color: white;
        }

        #delete-modal .ms {
            font-size: 14px !important;
        }
    }
</style>

<script>
// Store delete form reference
let pendingDeleteForm = null;

function openDeleteModal(formElement, itemName) {
    // Store the form that will be submitted
    pendingDeleteForm = formElement;
    
    // Update modal with item name
    document.getElementById('delete-item-name').textContent = itemName;
    
    // Show modal
    document.getElementById('delete-modal').classList.add('show');
}

function closeDeleteModal() {
    document.getElementById('delete-modal').classList.remove('show');
    pendingDeleteForm = null;
}

function confirmDelete() {
    if (pendingDeleteForm) {
        // Submit the form
        pendingDeleteForm.submit();
    }
}

// Close modal on background click
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('delete-modal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeDeleteModal();
            }
        });
    }
});
</script>
