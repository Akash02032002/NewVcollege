/* ========================================
   Admin Dashboard - JavaScript
   ======================================== */

// Search / Filter
document.getElementById("searchInput").addEventListener("input", function () {
  const q = this.value.toLowerCase();
  document.querySelectorAll("#appTable tbody tr").forEach(function (row) {
    row.style.display = row.textContent.toLowerCase().includes(q) ? "" : "none";
  });
});

// Update Status
function updateStatus(el) {
  const id = el.getAttribute("data-id");
  const status = el.value;
  fetch("index.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: "action=update_status&id=" + id + "&status=" + status,
  })
    .then((r) => r.json())
    .then((data) => {
      if (data.success) {
        showToast("Status updated successfully!", "success");
      } else {
        showToast(data.message, "danger");
      }
    });
}

// Delete Application
function deleteApp(id) {
  if (!confirm("Are you sure you want to delete this application?")) return;
  fetch("index.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: "action=delete&id=" + id,
  })
    .then((r) => r.json())
    .then((data) => {
      if (data.success) {
        const row = document.getElementById("row-" + id);
        if (row) row.remove();
        showToast("Application deleted.", "success");
      } else {
        showToast(data.message, "danger");
      }
    });
}

// View Details Modal
function viewDetails(app) {
  const statusColors = {
    pending: "badge-pending",
    reviewed: "badge-reviewed",
    accepted: "badge-accepted",
    rejected: "badge-rejected",
  };
  const html = `
        <div class="detail-row"><div class="detail-label">Full Name</div><div class="detail-value">${escHtml(app.name)}</div></div>
        <div class="detail-row"><div class="detail-label">Email</div><div class="detail-value">${escHtml(app.email)}</div></div>
        <div class="detail-row"><div class="detail-label">Phone</div><div class="detail-value">${escHtml(app.phone)}</div></div>
        <div class="detail-row"><div class="detail-label">State</div><div class="detail-value">${escHtml(app.state || "—")}</div></div>
        <div class="detail-row"><div class="detail-label">Course Interest</div><div class="detail-value">${escHtml(app.course_interest || "—")}</div></div>
        <div class="detail-row"><div class="detail-label">College</div><div class="detail-value">${escHtml(app.college_name || "—")}</div></div>
        <div class="detail-row"><div class="detail-label">Status</div><div class="detail-value"><span class="badge-status ${statusColors[app.status]}">${app.status}</span></div></div>
        <div class="detail-row"><div class="detail-label">Applied On</div><div class="detail-value">${app.created_at}</div></div>
    `;
  document.getElementById("detailBody").innerHTML = html;
  new bootstrap.Modal(document.getElementById("detailModal")).show();
}

function escHtml(str) {
  const d = document.createElement("div");
  d.textContent = str;
  return d.innerHTML;
}

// Toast Notification
function showToast(msg, type) {
  const toast = document.createElement("div");
  toast.style.cssText =
    "position:fixed;top:20px;right:20px;z-index:9999;padding:12px 24px;border-radius:8px;color:#fff;font-size:14px;font-weight:500;box-shadow:0 4px 12px rgba(0,0,0,0.15);transition:opacity 0.5s;";
  toast.style.background = type === "success" ? "#2e7d32" : "#c62828";
  toast.textContent = msg;
  document.body.appendChild(toast);
  setTimeout(() => {
    toast.style.opacity = "0";
    setTimeout(() => toast.remove(), 500);
  }, 2500);
}
