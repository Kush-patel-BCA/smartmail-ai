<?php
require_once __DIR__ . '/backend/auth/session.php';
requireLogin();
$userName = getUserName();
$userEmail = getUserEmail();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SmartMail AI</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/animations.css">
</head>
<body class="dashboard-body">
    <!-- Decorative Blobs -->
    <div style="position: absolute; top: -100px; left: -100px; width: 300px; height: 300px; background: rgba(255,255,255,0.2); border-radius: 50%; filter: blur(50px); z-index: 0;"></div>
    <div style="position: absolute; bottom: -50px; right: -50px; width: 400px; height: 400px; background: rgba(255,255,255,0.1); border-radius: 50%; filter: blur(60px); z-index: 0;"></div>

    <!-- Sidebar -->
    <div class="sidebar slide-in-left">
        <div class="sidebar-header">
            <h4 class="text-white mb-0"><i class="fas fa-paper-plane me-2"></i>SmartMail</h4>
            <small class="text-white-50 mt-2 d-block" style="font-size: 0.8rem;"><?php echo htmlspecialchars($userEmail); ?></small>
        </div>
        
        <div class="sidebar-menu">
            <button class="menu-item active" data-filter="all">
                <i class="fas fa-inbox"></i> Inbox
            </button>
            <button class="menu-item" data-filter="sent">
                <i class="fas fa-paper-plane"></i> Sent
            </button>
            <button class="menu-item" data-filter="draft">
                <i class="fas fa-file-alt"></i> Drafts
            </button>
            <button class="menu-item" data-filter="trash">
                <i class="fas fa-trash"></i> Trash
            </button>
            
            <div class="sidebar-categories mt-4">
                <h6 class="text-white-50 mb-3 px-3 text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Categories</h6>
                <button class="category-item" data-category="IT">
                    <span><i class="fas fa-laptop-code me-2 text-info"></i>IT Updates</span>
                    <span class="badge bg-info rounded-pill">IT</span>
                </button>
                <button class="category-item" data-category="Business">
                    <span><i class="fas fa-briefcase me-2 text-success"></i>Business</span>
                    <span class="badge bg-success rounded-pill">Biz</span>
                </button>
                <button class="category-item" data-category="General">
                    <span><i class="fas fa-comment me-2 text-secondary"></i>General</span>
                    <span class="badge bg-secondary rounded-pill">Gen</span>
                </button>
                <button class="category-item" data-category="Promotions">
                    <span><i class="fas fa-tag me-2 text-warning"></i>Promotions</span>
                    <span class="badge bg-warning rounded-pill">Pro</span>
                </button>
                <button class="category-item" data-category="Spam">
                    <span><i class="fas fa-exclamation-circle me-2 text-danger"></i>Spam</span>
                    <span class="badge bg-danger rounded-pill">!</span>
                </button>
            </div>
        </div>
        
        <div class="sidebar-footer">
            <a href="logout.php" class="btn btn-danger w-100 shadow-sm" style="background: rgba(231, 76, 60, 0.8); border: none;">
                <i class="fas fa-sign-out-alt me-2"></i> Logout
            </a>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="main-content fade-in">
        <!-- Top Bar -->
        <div class="top-bar">
            <div class="d-flex align-items-center">
                <i class="fas fa-search text-muted me-3"></i>
                <input type="text" id="searchInput" placeholder="Search emails, contacts, and more...">
            </div>
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-info text-white d-flex align-items-center gap-2" id="syncBtn">
                    <i class="fas fa-sync-alt"></i> Sync Mail
                </button>
                <button class="btn btn-primary d-flex align-items-center gap-2 pulse-glow" id="composeBtn">
                    <i class="fas fa-plus"></i> Compose
                </button>
                <div class="ms-3">
                    <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($userName); ?>&background=random" class="rounded-circle shadow-sm" width="40" height="40" alt="Profile">
                </div>
            </div>
        </div>
        
        <!-- Alert Container -->
        <div id="alert-container" style="position: fixed; top: 20px; right: 20px; z-index: 9999; max-width: 400px;"></div>
        
        <!-- Email List -->
        <div class="email-list-container">
            <h5 class="mb-4 ps-2 fw-bold text-secondary"><i class="fas fa-envelope-open-text me-2"></i>Your Messages</h5>
            <div id="emailList" class="email-list">
                <!-- Emails will be loaded here -->
            </div>
        </div>
    </div>
    
    <!-- Compose Modal -->
    <div class="modal fade" id="composeModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-pen-fancy me-2"></i>Compose Email</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="p-3 mb-4 rounded-3" style="background: linear-gradient(to right, #e0c3fc, #8ec5fc); border: 1px solid rgba(255,255,255,0.5);">
                        <label class="form-label fw-bold text-dark"><i class="fas fa-robot me-2"></i>AI Assistant</label>
                        <div class="input-group mb-2">
                            <input type="text" class="form-control border-0 shadow-sm" id="aiCommand" placeholder="Describe the email you want to write..." style="background: rgba(255,255,255,0.9);">
                            <button class="btn btn-primary" id="generateBtn">
                                <i class="fas fa-magic me-1"></i> Generate
                            </button>
                        </div>
                        <div class="d-flex gap-2 align-items-center">
                            <span class="small text-dark fw-bold">Tone:</span>
                            <select class="form-select form-select-sm border-0 shadow-sm w-auto" id="toneSelect" style="background: rgba(255,255,255,0.9);">
                                <option value="Professional">Professional</option>
                                <option value="Friendly">Friendly</option>
                                <option value="Formal">Formal</option>
                                <option value="Creative">Creative</option>
                                <option value="Casual">Casual</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted small text-uppercase fw-bold">To <span class="text-muted">(Optional for drafts)</span></label>
                        <input type="email" class="form-control bg-light border-0" id="emailTo">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small text-uppercase fw-bold">Subject <span class="text-muted">(Optional for drafts)</span></label>
                        <input type="text" class="form-control bg-light border-0" id="emailSubject">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small text-uppercase fw-bold">Body <span class="text-muted">(Optional for drafts)</span></label>
                        <textarea class="form-control bg-light border-0" id="emailBody" rows="8"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small text-uppercase fw-bold">Schedule (Optional)</label>
                        <input type="datetime-local" class="form-control bg-light border-0 w-50" id="scheduleTime">
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light text-muted" data-bs-dismiss="modal">Discard</button>
                    <button type="button" class="btn btn-outline-primary" id="saveDraftBtn"><i class="fas fa-save me-1"></i> Save Draft</button>
                    <button type="button" class="btn btn-primary px-4" id="sendBtn"><i class="fas fa-paper-plane me-2"></i>Send</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Email View Modal -->
    <div class="modal fade" id="emailViewModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewSubject"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex justify-content-between align-items-start mb-4 p-3 bg-light rounded-3">
                        <div>
                            <div class="fw-bold fs-5 text-dark" id="viewSender"></div>
                            <div class="text-muted small">to <span id="viewReceiver"></span></div>
                        </div>
                        <div class="text-end">
                            <div class="badge bg-primary mb-1" id="viewCategory"></div>
                            <div class="text-muted small" id="viewDate"></div>
                        </div>
                    </div>
                    <div id="viewBody" class="p-2" style="font-size: 1.05rem; line-height: 1.6; color: #444;"></div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-danger bg-gradient" id="deleteEmailBtn"><i class="fas fa-trash me-2"></i>Delete</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/ajax.js"></script>
    <script src="assets/js/ai-email.js"></script>
    <script src="assets/js/animations.js"></script>
</body>
</html>
