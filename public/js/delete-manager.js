/**
 * Delete/Destroy Helper Utility
 * Handles AJAX delete operations with toast notifications
 */

const DeleteManager = {
    /**
     * Delete a company
     * @param {number} companyId - The company ID
     * @param {string} companyName - The company name (for confirmation)
     */
    deleteCompany(companyId, companyName = '') {
        if (!confirm(`هل تريد حذف الشركة "${companyName}"؟ هذه العملية لا يمكن التراجع عنها.`)) {
            return;
        }

        fetch(`/contractor/companies/${companyId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]')?.value || '',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Delete failed');
            return response.json();
        })
        .then(data => {
            if (data.success) {
                window.showToast(data.message || 'تم حذف الشركة بنجاح', 'success');
                // Reload page after a short delay
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            }
        })
        .catch(error => {
            console.error('Error deleting company:', error);
            window.showToast('فشل حذف الشركة. حاول مرة أخرى.', 'error');
        });
    },

    /**
     * Delete a worker
     * @param {number} workerId - The worker ID
     * @param {string} workerName - The worker name (for confirmation)
     */
    deleteWorker(workerId, workerName = '') {
        if (!confirm(`هل تريد إيقاف العامل "${workerName}"؟ سيتم إيقاف حسابه عن العمل.`)) {
            return;
        }

        fetch(`/contractor/workers/${workerId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]')?.value || '',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Delete failed');
            return response.json();
        })
        .then(data => {
            if (data.success) {
                window.showToast(data.message || 'تم إيقاف العامل بنجاح', 'success');
                // Reload page after a short delay
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            }
        })
        .catch(error => {
            console.error('Error deleting worker:', error);
            window.showToast('فشل إيقاف العامل. حاول مرة أخرى.', 'error');
        });
    },

    /**
     * Generic delete function for other modules
     * @param {string} url - The delete endpoint URL
     * @param {string} confirmMessage - Confirmation message to show
     * @param {string} successMessage - Success message to show
     * @param {function} onSuccess - Callback after successful delete
     */
    genericDelete(url, confirmMessage, successMessage, onSuccess = null) {
        if (!confirm(confirmMessage)) {
            return;
        }

        fetch(url, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]')?.value || '',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Delete failed');
            return response.json();
        })
        .then(data => {
            if (data.success) {
                window.showToast(data.message || successMessage, 'success');
                
                // Execute callback if provided
                if (onSuccess && typeof onSuccess === 'function') {
                    setTimeout(() => {
                        onSuccess();
                    }, 1500);
                } else {
                    // Default: reload page
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                }
            }
        })
        .catch(error => {
            console.error('Error deleting item:', error);
            window.showToast('فشل حذف العنصر. حاول مرة أخرى.', 'error');
        });
    }
};
