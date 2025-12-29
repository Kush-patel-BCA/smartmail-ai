// Admin Panel JavaScript

// Admin Login
document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('adminLoginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            
            const result = await adminLogin(username, password);
            if (result.success) {
                showAlert('success', result.message);
                setTimeout(() => {
                    window.location.href = 'dashboard.php';
                }, 1000);
            } else {
                showAlert('danger', result.message);
            }
        });
    }
});

// Admin Login Function
async function adminLogin(username, password) {
    try {
        const response = await fetch('../backend/auth/admin-login.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ username, password })
        });
        return await response.json();
    } catch (error) {
        return { success: false, message: 'Network error. Please try again.' };
    }
}

// Show Alert
function showAlert(type, message) {
    const alertContainer = document.getElementById('alert-container');
    if (!alertContainer) {
        // Create alert container if it doesn't exist
        const container = document.createElement('div');
        container.id = 'alert-container';
        container.className = 'position-fixed top-0 start-50 translate-middle-x mt-3';
        container.style.zIndex = '9999';
        document.body.appendChild(container);
        alertContainer = container;
    }
    
    const alert = document.createElement('div');
    alert.className = `alert alert-${type} alert-dismissible fade show`;
    alert.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    alertContainer.innerHTML = '';
    alertContainer.appendChild(alert);
    
    setTimeout(() => {
        alert.remove();
    }, 5000);
}

// Format Date
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString() + ' ' + date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
}

// Get Category Badge Color
function getCategoryBadge(category) {
    const colors = {
        'IT': 'info',
        'Business': 'success',
        'General': 'secondary',
        'Promotions': 'warning',
        'Spam': 'danger'
    };
    return `<span class="badge bg-${colors[category] || 'secondary'}">${category}</span>`;
}

// Get Status Badge Color
function getStatusBadge(status) {
    const colors = {
        'read': 'success',
        'unread': 'primary',
        'draft': 'warning',
        'sent': 'info',
        'trash': 'danger'
    };
    return `<span class="badge bg-${colors[status] || 'secondary'}">${status}</span>`;
}

