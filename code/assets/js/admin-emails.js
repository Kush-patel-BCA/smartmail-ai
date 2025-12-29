// Admin Email Management JavaScript

let currentEmailId = null;

document.addEventListener('DOMContentLoaded', function() {
    loadEmails();
    
    // Filter by category
    const categoryFilter = document.getElementById('filterCategory');
    if (categoryFilter) {
        categoryFilter.addEventListener('change', function() {
            loadEmails(this.value);
        });
    }
    
    // Search
    const searchInput = document.getElementById('searchEmails');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                const category = categoryFilter ? categoryFilter.value : '';
                loadEmails(category, this.value);
            }, 500);
        });
    }
    
    // Delete email button
    const deleteBtn = document.getElementById('deleteEmailBtn');
    if (deleteBtn) {
        deleteBtn.addEventListener('click', function() {
            if (currentEmailId) {
                deleteEmail(currentEmailId);
            }
        });
    }
});

async function loadEmails(category = '', search = '') {
    const tableBody = document.getElementById('emailsTable');
    if (!tableBody) return;
    
    try {
        let url = '../backend/admin/get-emails.php?';
        if (category) url += 'category=' + encodeURIComponent(category) + '&';
        if (search) url += 'search=' + encodeURIComponent(search) + '&';
        
        const response = await fetch(url);
        const data = await response.json();
        
        if (data.success) {
            displayEmails(data.emails);
        }
    } catch (error) {
        tableBody.innerHTML = '<tr><td colspan="8" class="text-center text-danger">Error loading emails</td></tr>';
    }
}

function displayEmails(emails) {
    const tableBody = document.getElementById('emailsTable');
    
    if (emails.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="8" class="text-center text-muted">No emails found</td></tr>';
        return;
    }
    
    let html = '';
    emails.forEach(email => {
        const subject = email.subject.length > 50 ? email.subject.substring(0, 50) + '...' : email.subject;
        html += `
            <tr>
                <td>${email.id}</td>
                <td>${email.sender}</td>
                <td>${email.receiver}</td>
                <td>${subject}</td>
                <td>${getCategoryBadge(email.category)}</td>
                <td>${getStatusBadge(email.status)}</td>
                <td>${formatDate(email.created_at)}</td>
                <td>
                    <button class="btn btn-sm btn-info" onclick="viewEmail(${email.id})">
                        <i class="fas fa-eye"></i> View
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="deleteEmail(${email.id})">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </td>
            </tr>
        `;
    });
    tableBody.innerHTML = html;
    
    // Animate table rows and badges
    if (window.adminAnimations) {
        setTimeout(() => {
            if (window.adminAnimations.animateTableRows) {
                window.adminAnimations.animateTableRows();
            }
            if (window.adminAnimations.animateBadges) {
                window.adminAnimations.animateBadges();
            }
        }, 100);
    }
}

async function viewEmail(emailId) {
    try {
        const response = await fetch('../backend/admin/get-emails.php');
        const data = await response.json();
        
        if (data.success) {
            const email = data.emails.find(e => e.id == emailId);
            if (email) {
                currentEmailId = email.id;
                document.getElementById('viewEmailSubject').textContent = email.subject;
                document.getElementById('viewEmailFrom').textContent = email.sender;
                document.getElementById('viewEmailTo').textContent = email.receiver;
                document.getElementById('viewEmailCategory').innerHTML = getCategoryBadge(email.category);
                document.getElementById('viewEmailStatus').innerHTML = getStatusBadge(email.status);
                document.getElementById('viewEmailDate').textContent = formatDate(email.created_at);
                document.getElementById('viewEmailBody').innerHTML = email.body.replace(/\n/g, '<br>');
                
                const modal = new bootstrap.Modal(document.getElementById('viewEmailModal'));
                modal.show();
            }
        }
    } catch (error) {
        showAlert('danger', 'Error loading email');
    }
}

async function deleteEmail(emailId) {
    if (!confirm('Are you sure you want to delete this email? This action cannot be undone.')) {
        return;
    }
    
    try {
        const response = await fetch('../backend/admin/delete-email.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ email_id: emailId })
        });
        
        const result = await response.json();
        
        if (result.success) {
            showAlert('success', result.message);
            bootstrap.Modal.getInstance(document.getElementById('viewEmailModal')).hide();
            loadEmails();
        } else {
            showAlert('danger', result.message);
        }
    } catch (error) {
        showAlert('danger', 'Error deleting email');
    }
}

