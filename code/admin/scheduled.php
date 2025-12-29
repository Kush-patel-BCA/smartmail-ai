<?php
require_once __DIR__ . '/../backend/auth/admin-session.php';
requireAdminLogin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scheduled Emails - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body class="admin-body">
    <?php include 'admin-sidebar.php'; ?>
    
    <div class="admin-main-content">
        <div class="admin-top-bar p-3 shadow-sm">
            <h5 class="mb-0"><i class="fas fa-clock"></i> Scheduled Emails</h5>
        </div>
        
        <div class="admin-content p-4">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Email ID</th>
                                    <th>Send Time</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                </tr>
                            </thead>
                            <tbody id="scheduledTable">
                                <tr>
                                    <td colspan="5" class="text-center">
                                        <div class="spinner-border" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/admin.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        loadScheduledEmails();
    });
    
    async function loadScheduledEmails() {
        try {
            const response = await fetch('../backend/admin/get-scheduled.php');
            const data = await response.json();
            
            if (data.success) {
                displayScheduled(data.scheduled);
            }
        } catch (error) {
            document.getElementById('scheduledTable').innerHTML = 
                '<tr><td colspan="5" class="text-center text-danger">Error loading scheduled emails</td></tr>';
        }
    }
    
    function displayScheduled(scheduled) {
        const tableBody = document.getElementById('scheduledTable');
        
        if (scheduled.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">No scheduled emails</td></tr>';
            return;
        }
        
        let html = '';
        scheduled.forEach(item => {
            const statusBadge = item.status === 'pending' ? 
                '<span class="badge bg-warning">Pending</span>' :
                (item.status === 'sent' ? 
                '<span class="badge bg-success">Sent</span>' :
                '<span class="badge bg-danger">Failed</span>');
            
            html += `
                <tr>
                    <td>${item.id}</td>
                    <td>${item.email_id}</td>
                    <td>${formatDate(item.send_time)}</td>
                    <td>${statusBadge}</td>
                    <td>${formatDate(item.created_at)}</td>
                </tr>
            `;
        });
        tableBody.innerHTML = html;
    }
    </script>
</body>
</html>

