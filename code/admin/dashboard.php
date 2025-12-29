<?php
require_once __DIR__ . '/../backend/auth/admin-session.php';
requireAdminLogin();
$adminName = getAdminName();
$adminRole = getAdminRole();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - SmartMail AI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="admin-body">
    <!-- Sidebar -->
    <div class="admin-sidebar">
        <div class="admin-sidebar-header p-3">
            <h4 class="text-white mb-0">
                <i class="fas fa-shield-alt"></i> Admin Panel
            </h4>
            <small class="text-white-50"><?php echo htmlspecialchars($adminName); ?></small>
        </div>
        
        <div class="admin-menu">
            <a href="dashboard.php" class="menu-item active">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <a href="users.php" class="menu-item">
                <i class="fas fa-users"></i> Users
            </a>
            <a href="emails.php" class="menu-item">
                <i class="fas fa-envelope"></i> Emails
            </a>
            <a href="categories.php" class="menu-item">
                <i class="fas fa-tags"></i> Categories
            </a>
            <a href="scheduled.php" class="menu-item">
                <i class="fas fa-clock"></i> Scheduled Emails
            </a>
            <a href="settings.php" class="menu-item">
                <i class="fas fa-cog"></i> Settings
            </a>
            <?php if (isSuperAdmin()): ?>
            <a href="admins.php" class="menu-item">
                <i class="fas fa-user-shield"></i> Administrators
            </a>
            <?php endif; ?>
        </div>
        
        <div class="admin-sidebar-footer p-3">
            <a href="logout.php" class="btn btn-outline-light btn-sm w-100">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="admin-main-content">
        <!-- Top Bar -->
        <div class="admin-top-bar p-3 shadow-sm">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Dashboard Overview</h5>
                <div>
                    <span class="badge bg-primary"><?php echo htmlspecialchars($adminRole); ?></span>
                </div>
            </div>
        </div>
        
        <!-- Stats Cards -->
        <div class="admin-content p-4">
            <div class="row g-4 mb-4">
                <div class="col-md-3">
                    <div class="stat-card floating">
                        <div class="stat-icon bg-primary pulse-animate">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-info">
                            <h3 id="totalUsers" class="animate-count">0</h3>
                            <p>Total Users</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card floating" style="animation-delay: 0.1s;">
                        <div class="stat-icon bg-success pulse-animate">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="stat-info">
                            <h3 id="totalEmails" class="animate-count">0</h3>
                            <p>Total Emails</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card floating" style="animation-delay: 0.2s;">
                        <div class="stat-icon bg-warning pulse-animate">
                            <i class="fas fa-paper-plane"></i>
                        </div>
                        <div class="stat-info">
                            <h3 id="sentEmails" class="animate-count">0</h3>
                            <p>Sent Emails</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card floating" style="animation-delay: 0.3s;">
                        <div class="stat-icon bg-info pulse-animate">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stat-info">
                            <h3 id="scheduledEmails" class="animate-count">0</h3>
                            <p>Scheduled</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Charts Row -->
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <div class="card scale-in">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="fas fa-chart-pie text-primary"></i> Email Categories Distribution
                            </h6>
                        </div>
                        <div class="card-body">
                            <canvas id="categoryChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card scale-in" style="animation-delay: 0.2s;">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="fas fa-chart-bar text-success"></i> Emails by Status
                            </h6>
                        </div>
                        <div class="card-body">
                            <canvas id="statusChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Recent Activity -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card fade-in-up">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="fas fa-user-plus text-info"></i> Recent Users
                            </h6>
                        </div>
                        <div class="card-body">
                            <div id="recentUsers"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card fade-in-up" style="animation-delay: 0.2s;">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="fas fa-envelope-open text-warning"></i> Recent Emails
                            </h6>
                        </div>
                        <div class="card-body">
                            <div id="recentEmails"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/admin.js"></script>
    <script src="../assets/js/admin-animations.js"></script>
    <script src="../assets/js/admin-dashboard.js"></script>
</body>
</html>

