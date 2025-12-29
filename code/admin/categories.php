<?php
require_once __DIR__ . '/../backend/auth/admin-session.php';
requireAdminLogin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories - Admin Panel</title>
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
            <h5 class="mb-0"><i class="fas fa-tags"></i> Email Categories</h5>
        </div>
        
        <div class="admin-content p-4">
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h2 class="text-info" id="itCount">0</h2>
                            <p class="mb-0"><span class="badge bg-info">IT</span></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h2 class="text-success" id="businessCount">0</h2>
                            <p class="mb-0"><span class="badge bg-success">Business</span></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h2 class="text-secondary" id="generalCount">0</h2>
                            <p class="mb-0"><span class="badge bg-secondary">General</span></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h2 class="text-warning" id="promotionsCount">0</h2>
                            <p class="mb-0"><span class="badge bg-warning">Promotions</span></p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0">Category Statistics</h6>
                </div>
                <div class="card-body">
                    <p class="text-muted">Email categorization is automatic based on keywords and content analysis.</p>
                    <p class="text-muted">Categories help users organize and filter their emails efficiently.</p>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/admin.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        loadCategoryStats();
    });
    
    async function loadCategoryStats() {
        try {
            const response = await fetch('../backend/admin/get-stats.php');
            const data = await response.json();
            
            if (data.success && data.stats.categories) {
                document.getElementById('itCount').textContent = data.stats.categories.IT || 0;
                document.getElementById('businessCount').textContent = data.stats.categories.Business || 0;
                document.getElementById('generalCount').textContent = data.stats.categories.General || 0;
                document.getElementById('promotionsCount').textContent = data.stats.categories.Promotions || 0;
            }
        } catch (error) {
            console.error('Error loading category stats:', error);
        }
    }
    </script>
</body>
</html>

