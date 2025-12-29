// SmartMail AI - Main JavaScript

document.addEventListener("DOMContentLoaded", function () {
  // Login Form Handler
  const loginForm = document.getElementById("loginForm");
  if (loginForm) {
    loginForm.addEventListener("submit", async function (e) {
      e.preventDefault();
      const email = document.getElementById("email").value;
      const password = document.getElementById("password").value;

      const result = await loginUser(email, password);
      if (result.success) {
        showAlert("success", result.message);
        setTimeout(() => {
          window.location.href = "dashboard.php";
        }, 1000);
      } else {
        showAlert("danger", result.message);
      }
    });
  }

  // Register Form Handler
  const registerForm = document.getElementById("registerForm");
  if (registerForm) {
    registerForm.addEventListener("submit", async function (e) {
      e.preventDefault();
      const name = document.getElementById("name").value;
      const email = document.getElementById("email").value;
      const password = document.getElementById("password").value;

      const result = await registerUser(name, email, password);
      if (result.success) {
        showAlert("success", result.message);
        setTimeout(() => {
          window.location.href = "dashboard.php";
        }, 1000);
      } else {
        showAlert("danger", result.message);
      }
    });
  }

  // Dashboard Initialization
  if (document.querySelector(".dashboard-body")) {
    initDashboard();
  }
});

// Show Alert
function showAlert(type, message) {
  let alertContainer = document.getElementById("alert-container");
  
  // If no alert container exists, create one or use body
  if (!alertContainer) {
    // Try to find or create alert container in dashboard
    const mainContent = document.querySelector('.main-content');
    if (mainContent) {
      alertContainer = document.createElement('div');
      alertContainer.id = 'alert-container';
      alertContainer.style.position = 'fixed';
      alertContainer.style.top = '20px';
      alertContainer.style.right = '20px';
      alertContainer.style.zIndex = '9999';
      alertContainer.style.maxWidth = '400px';
      document.body.appendChild(alertContainer);
    } else {
      // Fallback to body
      alertContainer = document.body;
    }
  }

  const alert = document.createElement("div");
  alert.className = `alert alert-${type} alert-dismissible fade show`;
  alert.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

  if (alertContainer.id === 'alert-container') {
    alertContainer.innerHTML = "";
  }
  alertContainer.appendChild(alert);

  setTimeout(() => {
    alert.remove();
  }, 5000);
}

// Initialize Dashboard
function initDashboard() {
  // Load emails on page load
  loadEmails();

  // Menu item clicks
  document.querySelectorAll(".menu-item").forEach((item) => {
    item.addEventListener("click", function () {
      document
        .querySelectorAll(".menu-item")
        .forEach((i) => i.classList.remove("active"));
      this.classList.add("active");

      const filter = this.dataset.filter;
      loadEmails(filter);
    });
  });

  // Category clicks
  document.querySelectorAll(".category-item").forEach((item) => {
    item.addEventListener("click", function () {
      const category = this.dataset.category;
      loadEmails("all", category);
    });
  });

  // Sync button
  const syncBtn = document.getElementById("syncBtn");
  if (syncBtn) {
    syncBtn.addEventListener("click", function() {
        const icon = this.querySelector('i');
        icon.classList.add('fa-spin');
        this.disabled = true;

        fetch('backend/email/sync-imap.php')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    loadEmails();
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while syncing.');
            })
            .finally(() => {
                icon.classList.remove('fa-spin');
                this.disabled = false;
            });
    });
  }

  // Search input
  const searchInput = document.getElementById("searchInput");
  if (searchInput) {
    let searchTimeout;
    searchInput.addEventListener("input", function () {
      clearTimeout(searchTimeout);
      searchTimeout = setTimeout(() => {
        const search = this.value;
        loadEmails("all", "", search);
      }, 500);
    });
  }

  // Compose button
  const composeBtn = document.getElementById("composeBtn");
  if (composeBtn) {
    composeBtn.addEventListener("click", function () {
      // Reset form for new email
      document.getElementById('emailTo').value = '';
      document.getElementById('emailSubject').value = '';
      document.getElementById('emailBody').value = '';
      document.getElementById('aiCommand').value = '';
      document.getElementById('scheduleTime').value = '';
      
      // Reset draft ID
      const saveDraftBtn = document.getElementById('saveDraftBtn');
      if (saveDraftBtn) {
        delete saveDraftBtn.dataset.draftId;
        saveDraftBtn.innerHTML = '<i class="fas fa-save me-1"></i> Save Draft';
      }
      
      const sendBtn = document.getElementById('sendBtn');
      if (sendBtn) {
        delete sendBtn.dataset.draftId;
      }
      
      const modal = new bootstrap.Modal(
        document.getElementById("composeModal")
      );
      modal.show();
    });
  }
}

// Load Emails
async function loadEmails(status = "all", category = "", search = "") {
  const emailList = document.getElementById("emailList");
  if (!emailList) return;

  // Show loading skeleton
  emailList.innerHTML = "";
  for (let i = 0; i < 5; i++) {
    const skeleton = document.createElement("div");
    skeleton.className = "skeleton skeleton-item";
    emailList.appendChild(skeleton);
  }

  try {
    let url = `backend/email/fetch-emails.php?`;
    if (status !== "all") url += `status=${status}&`;
    if (category) url += `category=${category}&`;
    if (search) url += `search=${encodeURIComponent(search)}&`;

    const response = await fetch(url);
    const data = await response.json();

    if (data.success) {
      displayEmails(data.emails);
    } else {
      emailList.innerHTML =
        '<p class="text-center text-muted">No emails found</p>';
    }
  } catch (error) {
    console.error("Error loading emails:", error);
    emailList.innerHTML =
      '<p class="text-center text-danger">Error loading emails</p>';
  }
}

// Display Emails
function displayEmails(emails) {
  const emailList = document.getElementById("emailList");
  emailList.innerHTML = "";

  if (emails.length === 0) {
    emailList.innerHTML =
      '<p class="text-center text-muted mt-5">No emails found</p>';
    return;
  }

  emails.forEach((email, index) => {
    const emailItem = document.createElement("div");
    const isDraft = email.status === 'draft';
    emailItem.className = `email-item ${
      email.status === "unread" ? "unread" : ""
    } ${isDraft ? "draft-item" : ""}`;
    emailItem.style.animationDelay = `${index * 0.1}s`;

    const preview =
      email.body && email.body.length > 100
        ? email.body.substring(0, 100) + "..."
        : (email.body || '');
    const date = new Date(email.created_at).toLocaleDateString();

    // Draft badge
    const draftBadge = isDraft ? '<span class="badge bg-warning text-dark me-2"><i class="fas fa-file-alt me-1"></i>Draft</span>' : '';
    
    emailItem.innerHTML = `
            <div class="email-header">
                <div>
                    <div class="email-sender">${email.sender} ${draftBadge}</div>
                    <div class="email-date">${date}</div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-${getCategoryColor(email.category)}">${
      email.category
    }</span>
                    ${isDraft ? `
                    <button class="btn-edit-draft" data-email-id="${email.id}" title="Edit Draft">
                        <i class="fas fa-edit"></i>
                    </button>
                    ` : ''}
                    <button class="btn-view-email" data-email-id="${email.id}" title="View Email">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>
            <div class="email-subject">${email.subject || '(No Subject)'}</div>
            <div class="email-preview">${preview || '(No content)'}</div>
        `;

    // Store email data in the element
    emailItem.dataset.emailData = JSON.stringify(email);
    
    // Add click handler for the entire email item
    emailItem.addEventListener("click", () => viewEmail(email));
    
    // Add click handler for the eye icon button
    const viewBtn = emailItem.querySelector('.btn-view-email');
    if (viewBtn) {
      viewBtn.addEventListener('click', (e) => {
        e.stopPropagation(); // Prevent triggering the email item click
        viewEmail(email);
      });
    }
    
    // Add click handler for edit draft button
    const editDraftBtn = emailItem.querySelector('.btn-edit-draft');
    if (editDraftBtn) {
      editDraftBtn.addEventListener('click', (e) => {
        e.stopPropagation(); // Prevent triggering the email item click
        if (typeof loadDraftForEditing === 'function') {
          loadDraftForEditing(email);
        } else {
          // Fallback: open compose modal and fill fields
          const composeModal = new bootstrap.Modal(document.getElementById('composeModal'));
          composeModal.show();
          document.getElementById('emailTo').value = email.receiver || '';
          document.getElementById('emailSubject').value = email.subject || '';
          document.getElementById('emailBody').value = email.body || '';
          const saveDraftBtn = document.getElementById('saveDraftBtn');
          if (saveDraftBtn) {
            saveDraftBtn.dataset.draftId = email.id;
            saveDraftBtn.innerHTML = '<i class="fas fa-save me-1"></i> Update Draft';
          }
        }
      });
    }
    
    emailList.appendChild(emailItem);
  });
}

// Get Category Color
function getCategoryColor(category) {
  const colors = {
    IT: "info",
    Business: "success",
    General: "secondary",
    Promotions: "warning",
    Spam: "danger",
  };
  return colors[category] || "secondary";
}

// View Email
function viewEmail(email) {
  document.getElementById("viewSubject").textContent = email.subject;
  document.getElementById("viewSender").textContent = email.sender;
  document.getElementById("viewReceiver").textContent = email.receiver;
  document.getElementById(
    "viewCategory"
  ).innerHTML = `<span class="badge bg-${getCategoryColor(email.category)}">${
    email.category
  }</span>`;
  document.getElementById("viewDate").textContent = new Date(
    email.created_at
  ).toLocaleString();

  // Handle newlines: 
  // 1. Replace literal "\n" (backslash + n) which might be in DB due to double escaping
  // 2. Replace actual newlines
  let bodyContent = email.body;
  
  // Replace literal \n with actual newlines first
  bodyContent = bodyContent.replace(/\\n/g, '\n');
  
  // Then replace actual newlines with <br> for HTML display
  document.getElementById("viewBody").innerHTML = bodyContent.replace(
    /\n/g,
    "<br>"
  );

  // Store email ID for delete
  document.getElementById("deleteEmailBtn").dataset.emailId = email.id;

  const modal = new bootstrap.Modal(document.getElementById("emailViewModal"));
  modal.show();
}

// Delete Email Handler
document.addEventListener("DOMContentLoaded", function () {
  const deleteBtn = document.getElementById("deleteEmailBtn");
  if (deleteBtn) {
    deleteBtn.addEventListener("click", async function () {
      const emailId = this.dataset.emailId;
      if (!emailId) return;

      if (confirm("Are you sure you want to delete this email?")) {
        const result = await deleteEmail(emailId);
        if (result.success) {
          showAlert("success", result.message);
          bootstrap.Modal.getInstance(
            document.getElementById("emailViewModal")
          ).hide();
          loadEmails();
        } else {
          showAlert("danger", result.message);
        }
      }
    });
  }
});
