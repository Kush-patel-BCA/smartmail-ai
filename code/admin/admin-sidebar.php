<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<div class="admin-sidebar">
    <div class="admin-sidebar-header p-3">
        <h4 class="text-white mb-0">
            <i class="fas fa-shield-alt"></i> Admin Panel
        </h4>
        <small class="text-white-50"><?php echo htmlspecialchars(getAdminName()); ?></small>
    </div>
    
    <div class="admin-menu">
        <a href="dashboard.php" class="menu-item <?php echo $currentPage === 'dashboard.php' ? 'active' : ''; ?>">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
        <a href="users.php" class="menu-item <?php echo $currentPage === 'users.php' ? 'active' : ''; ?>">
            <i class="fas fa-users"></i> Users
        </a>
        <a href="emails.php" class="menu-item <?php echo $currentPage === 'emails.php' ? 'active' : ''; ?>">
            <i class="fas fa-envelope"></i> Emails
        </a>
        <a href="categories.php" class="menu-item <?php echo $currentPage === 'categories.php' ? 'active' : ''; ?>">
            <i class="fas fa-tags"></i> Categories
        </a>
        <a href="scheduled.php" class="menu-item <?php echo $currentPage === 'scheduled.php' ? 'active' : ''; ?>">
            <i class="fas fa-clock"></i> Scheduled Emails
        </a>
        <a href="settings.php" class="menu-item <?php echo $currentPage === 'settings.php' ? 'active' : ''; ?>">
            <i class="fas fa-cog"></i> Settings
        </a>
        <?php if (isSuperAdmin()): ?>
        <a href="admins.php" class="menu-item <?php echo $currentPage === 'admins.php' ? 'active' : ''; ?>">
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

