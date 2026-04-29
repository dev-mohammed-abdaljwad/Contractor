/**
 * Dark Mode Management for iDara
 */
window.toggleDarkMode = function(enabled) {
    const html = document.documentElement;
    html.setAttribute('data-dark-mode', enabled ? '1' : '0');
    
    // Save preference
    fetch('/admin/preferences/dark-mode', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ dark_mode: enabled })
    }).catch(err => console.error('Failed to save dark mode preference:', err));
};

// Initialize if needed (though layout already sets the attribute)
document.addEventListener('DOMContentLoaded', () => {
    const isDark = document.documentElement.getAttribute('data-dark-mode') === '1';
    const checkbox = document.querySelector('input[name="dark_mode"]');
    if (checkbox) {
        checkbox.checked = isDark;
    }
});
