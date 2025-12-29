<?php
require_once __DIR__ . '/../backend/auth/admin-session.php';
requireAdminLogin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - Admin Panel</title>
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
            <h5 class="mb-0"><i class="fas fa-cog"></i> System Settings</h5>
        </div>
        
        <div class="admin-content p-4">
            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0">System Information</h6>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>PHP Version:</strong></td>
                                    <td><?php echo PHP_VERSION; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Server:</strong></td>
                                    <td><?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Database:</strong></td>
                                    <td>MySQL</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0">Quick Actions</h6>
                        </div>
                        <div class="card-body">
                            <button class="btn btn-primary w-100 mb-2" onclick="clearTrash()">
                                <i class="fas fa-trash"></i> Clear Trash Emails
                            </button>
                            <button class="btn btn-warning w-100 mb-2" onclick="clearOldEmails()">
                                <i class="fas fa-broom"></i> Clear Old Emails (30+ days)
                            </button>
                            <button class="btn btn-info w-100" onclick="exportData()">
                                <i class="fas fa-download"></i> Export Data
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/admin.js"></script>
    <script>
    function clearTrash() {
        if (confirm('Are you sure you want to permanently delete all trash emails?')) {
            // Implement clear trash
            alert('Feature coming soon');
        }
    }
    
    function clearOldEmails() {
        if (confirm('Are you sure you want to delete emails older than 30 days?')) {
            // Implement clear old emails
            alert('Feature coming soon');
        }
    }
    
    function exportData() {
        alert('Export feature coming soon');
    }
    </script>
</body>
</html>

