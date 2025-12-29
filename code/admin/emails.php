<?php
require_once __DIR__ . '/../backend/auth/admin-session.php';
requireAdminLogin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Management - Admin Panel</title>
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
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-envelope"></i> Email Management</h5>
                <div class="d-flex gap-2">
                    <select class="form-select form-select-sm" id="filterCategory" style="width: 150px;">
                        <option value="">All Categories</option>
                        <option value="IT">IT</option>
                        <option value="Business">Business</option>
                        <option value="General">General</option>
                        <option value="Promotions">Promotions</option>
                        <option value="Spam">Spam</option>
                    </select>
                    <input type="text" class="form-control form-control-sm" id="searchEmails" placeholder="Search..." style="width: 200px;">
                </div>
            </div>
        </div>
        
        <div class="admin-content p-4">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Subject</th>
                                    <th>Category</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="emailsTable">
                                <tr>
                                    <td colspan="8" class="text-center">
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
    
    <!-- View Email Modal -->
    <div class="modal fade" id="viewEmailModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewEmailSubject"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <strong>From:</strong> <span id="viewEmailFrom"></span><br>
                        <strong>To:</strong> <span id="viewEmailTo"></span><br>
                        <strong>Category:</strong> <span id="viewEmailCategory"></span><br>
                        <strong>Status:</strong> <span id="viewEmailStatus"></span><br>
                        <strong>Date:</strong> <span id="viewEmailDate"></span>
                    </div>
                    <hr>
                    <div id="viewEmailBody"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="deleteEmailBtn">Delete</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/admin.js"></script>
    <script src="../assets/js/admin-animations.js"></script>
    <script src="../assets/js/admin-emails.js"></script>
</body>
</html>

