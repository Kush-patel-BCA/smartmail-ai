// Admin Users Management JavaScript

document.addEventListener('DOMContentLoaded', function() {
    loadUsers();
    
    // Search functionality
    const searchInput = document.getElementById('searchUsers');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                loadUsers(this.value);
            }, 500);
        });
    }
    
    // Save user button
    const saveBtn = document.getElementById('saveUserBtn');
    if (saveBtn) {
        saveBtn.addEventListener('click', saveUser);
    }
});

async function loadUsers(search = '') {
    const tableBody = document.getElementById('usersTable');
    if (!tableBody) return;
    
    try {
        let url = '../backend/admin/get-users.php';
        if (search) {
            url += '?search=' + encodeURIComponent(search);
        }
        
        const response = await fetch(url);
        const data = await response.json();
        
        if (data.success) {
            displayUsers(data.users);
        }
    } catch (error) {
        tableBody.innerHTML = '<tr><td colspan="5" class="text-center text-danger">Error loading users</td></tr>';
    }
}

function displayUsers(users) {
    const tableBody = document.getElementById('usersTable');
    
    if (users.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">No users found</td></tr>';
        return;
    }
    
    let html = '';
    users.forEach(user => {
        html += `
            <tr>
                <td>${user.id}</td>
                <td>${user.name}</td>
                <td>${user.email}</td>
                <td>${formatDate(user.created_at)}</td>
                <td>
                    <button class="btn btn-sm btn-primary" onclick="editUser(${user.id}, '${user.name.replace(/'/g, "\\'")}', '${user.email}')">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="deleteUser(${user.id})">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </td>
            </tr>
        `;
    });
    tableBody.innerHTML = html;
    
    // Animate table rows
    if (window.adminAnimations && window.adminAnimations.animateTableRows) {
        setTimeout(() => {
            window.adminAnimations.animateTableRows();
        }, 100);
    }
}

function editUser(id, name, email) {
    document.getElementById('editUserId').value = id;
    document.getElementById('editUserName').value = name;
    document.getElementById('editUserEmail').value = email;
    document.getElementById('editUserPassword').value = '';
    
    const modal = new bootstrap.Modal(document.getElementById('editUserModal'));
    modal.show();
}

async function saveUser() {
    const userId = document.getElementById('editUserId').value;
    const name = document.getElementById('editUserName').value;
    const email = document.getElementById('editUserEmail').value;
    const password = document.getElementById('editUserPassword').value;
    
    try {
        const response = await fetch('../backend/admin/update-user.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                user_id: userId,
                name: name,
                email: email,
                password: password
            })
        });
        
        const result = await response.json();
        
        if (result.success) {
            showAlert('success', result.message);
            bootstrap.Modal.getInstance(document.getElementById('editUserModal')).hide();
            loadUsers();
        } else {
            showAlert('danger', result.message);
        }
    } catch (error) {
        showAlert('danger', 'Error updating user');
    }
}

async function deleteUser(userId) {
    if (!confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
        return;
    }
    
    try {
        const response = await fetch('../backend/admin/delete-user.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ user_id: userId })
        });
        
        const result = await response.json();
        
        if (result.success) {
            showAlert('success', result.message);
            loadUsers();
        } else {
            showAlert('danger', result.message);
        }
    } catch (error) {
        showAlert('danger', 'Error deleting user');
    }
}

