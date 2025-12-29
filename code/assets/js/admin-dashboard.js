// Admin Dashboard JavaScript

document.addEventListener("DOMContentLoaded", function () {
  // Add page load animation
  document.body.style.opacity = "0";
  setTimeout(() => {
    document.body.style.transition = "opacity 0.5s ease-in";
    document.body.style.opacity = "1";
  }, 100);

  loadDashboardStats();
  setInterval(loadDashboardStats, 30000); // Refresh every 30 seconds
});

async function loadDashboardStats() {
  try {
    const response = await fetch("../backend/admin/get-stats.php");
    const data = await response.json();

    if (data.success) {
      // Animate number counters
      animateCounter("totalUsers", data.stats.totalUsers);
      animateCounter("totalEmails", data.stats.totalEmails);
      animateCounter("sentEmails", data.stats.sentEmails);
      animateCounter("scheduledEmails", data.stats.scheduledEmails);

      // Update charts
      updateCategoryChart(data.stats.categories);
      updateStatusChart(data.stats.statuses);

      // Update recent users
      displayRecentUsers(data.stats.recentUsers);

      // Update recent emails
      displayRecentEmails(data.stats.recentEmails);
    }
  } catch (error) {
    console.error("Error loading stats:", error);
  }
}

function updateCategoryChart(categories) {
  const ctx = document.getElementById("categoryChart");
  if (!ctx) return;

  const labels = Object.keys(categories);
  const values = Object.values(categories);

  new Chart(ctx, {
    type: "doughnut",
    data: {
      labels: labels,
      datasets: [
        {
          data: values,
          backgroundColor: [
            "#3b82f6",
            "#10b981",
            "#6b7280",
            "#f59e0b",
            "#ef4444",
          ],
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: true,
    },
  });
}

function updateStatusChart(statuses) {
  const ctx = document.getElementById("statusChart");
  if (!ctx) return;

  const labels = Object.keys(statuses);
  const values = Object.values(statuses);

  new Chart(ctx, {
    type: "bar",
    data: {
      labels: labels,
      datasets: [
        {
          label: "Emails",
          data: values,
          backgroundColor: "#3b82f6",
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: true,
      scales: {
        y: {
          beginAtZero: true,
        },
      },
    },
  });
}

function displayRecentUsers(users) {
  const container = document.getElementById("recentUsers");
  if (!container) return;

  if (users.length === 0) {
    container.innerHTML = '<p class="text-muted">No users yet</p>';
    return;
  }

  let html = '<div class="list-group">';
  users.forEach((user) => {
    html += `
            <div class="list-group-item">
                <div class="d-flex justify-content-between">
                    <div>
                        <strong>${user.name}</strong><br>
                        <small class="text-muted">${user.email}</small>
                    </div>
                    <small class="text-muted">${formatDate(
                      user.created_at
                    )}</small>
                </div>
            </div>
        `;
  });
  html += "</div>";
  container.innerHTML = html;
}

function displayRecentEmails(emails) {
  const container = document.getElementById("recentEmails");
  if (!container) return;

  if (emails.length === 0) {
    container.innerHTML = '<p class="text-muted">No emails yet</p>';
    return;
  }

  let html = '<div class="list-group">';
  emails.forEach((email) => {
    const subject =
      email.subject.length > 50
        ? email.subject.substring(0, 50) + "..."
        : email.subject;
    html += `
            <div class="list-group-item">
                <div class="d-flex justify-content-between">
                    <div>
                        <strong>${subject}</strong><br>
                        <small class="text-muted">${email.sender} → ${
      email.receiver
    }</small><br>
                        ${getCategoryBadge(email.category)}
                    </div>
                    <small class="text-muted">${formatDate(
                      email.created_at
                    )}</small>
                </div>
            </div>
        `;
  });
  html += "</div>";
  container.innerHTML = html;
}
