/* ========================================
   Admin Dashboard - JavaScript
   ======================================== */

// Search / Filter — Enquiry page (appTable)
var searchInput = document.getElementById("searchInput");
if (searchInput) {
  searchInput.addEventListener("input", function () {
    const q = this.value.toLowerCase();
    document.querySelectorAll("#appTable tbody tr").forEach(function (row) {
      row.style.display = row.textContent.toLowerCase().includes(q)
        ? ""
        : "none";
    });
  });
}

// Search / Filter — Dashboard page (dashTable)
var dashSearch = document.getElementById("dashSearchInput");
if (dashSearch) {
  dashSearch.addEventListener("input", function () {
    const q = this.value.toLowerCase();
    document.querySelectorAll("#dashTable tbody tr").forEach(function (row) {
      row.style.display = row.textContent.toLowerCase().includes(q)
        ? ""
        : "none";
    });
  });
}

// Toggle Password Visibility in Dashboard table
function togglePw(btn) {
  var span = btn.parentElement.querySelector(".password-masked");
  var icon = btn.querySelector("i");
  if (!span) return;
  var isHidden = !span.classList.contains("visible");
  if (isHidden) {
    span.textContent = span.getAttribute("data-password");
    span.classList.add("visible");
    icon.classList.remove("bi-eye");
    icon.classList.add("bi-eye-slash");
    btn.title = "Hide Password";
  } else {
    var pw = span.getAttribute("data-password");
    span.textContent = "\u2022".repeat(Math.min(pw.length, 10));
    span.classList.remove("visible");
    icon.classList.remove("bi-eye-slash");
    icon.classList.add("bi-eye");
    btn.title = "Show Password";
  }
}

// ---- Dashboard: View User Details ----
function viewUser(user) {
  var roleColors = {
    Admin: "role-admin",
    Student: "role-student",
    Enquiry: "role-enquiry",
  };
  var html =
    '<div class="detail-row"><div class="detail-label">Full Name</div><div class="detail-value">' +
    escHtml(user.name) +
    "</div></div>" +
    '<div class="detail-row"><div class="detail-label">Mobile</div><div class="detail-value">' +
    escHtml(user.mobile) +
    "</div></div>" +
    '<div class="detail-row"><div class="detail-label">Email</div><div class="detail-value">' +
    escHtml(user.email) +
    "</div></div>" +
    '<div class="detail-row"><div class="detail-label">Password</div><div class="detail-value">' +
    (user.role === "Enquiry"
      ? '<span class="text-muted">\u2014</span>'
      : escHtml(user.password)) +
    "</div></div>" +
    '<div class="detail-row"><div class="detail-label">Role</div><div class="detail-value"><span class="role-badge ' +
    (roleColors[user.role] || "") +
    '">' +
    escHtml(user.role) +
    "</span></div></div>";
  document.getElementById("viewUserBody").innerHTML = html;
  new bootstrap.Modal(document.getElementById("viewUserModal")).show();
}

// ---- Dashboard: Edit User ----
function editUser(user) {
  document.getElementById("editUserId").value = user.id;
  document.getElementById("editUserRole").value = user.role;
  document.getElementById("editName").value = user.name;
  document.getElementById("editMobile").value = user.mobile;
  document.getElementById("editEmail").value = user.email;
  document.getElementById("editPassword").value = "";
  // Hide password field for Enquiry
  document.getElementById("editPasswordGroup").style.display =
    user.role === "Enquiry" ? "none" : "block";
  new bootstrap.Modal(document.getElementById("editUserModal")).show();
}

// Handle edit form submit
var editForm = document.getElementById("editUserForm");
if (editForm) {
  editForm.addEventListener("submit", function (e) {
    e.preventDefault();
    var body =
      "action=update_user" +
      "&id=" +
      encodeURIComponent(document.getElementById("editUserId").value) +
      "&role=" +
      encodeURIComponent(document.getElementById("editUserRole").value) +
      "&name=" +
      encodeURIComponent(document.getElementById("editName").value) +
      "&mobile=" +
      encodeURIComponent(document.getElementById("editMobile").value) +
      "&email=" +
      encodeURIComponent(document.getElementById("editEmail").value) +
      "&password=" +
      encodeURIComponent(document.getElementById("editPassword").value);
    fetch("index.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: body,
    })
      .then(function (r) {
        return r.json();
      })
      .then(function (data) {
        if (data.success) {
          showToast("User updated successfully!", "success");
          bootstrap.Modal.getInstance(
            document.getElementById("editUserModal"),
          ).hide();
          setTimeout(function () {
            location.reload();
          }, 800);
        } else {
          showToast(data.message, "danger");
        }
      });
  });
}

// ---- Dashboard: Delete User ----
function deleteUser(id, role) {
  if (!confirm("Are you sure you want to delete this " + role + "?")) return;
  fetch("index.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: "action=delete_user&id=" + id + "&role=" + encodeURIComponent(role),
  })
    .then(function (r) {
      return r.json();
    })
    .then(function (data) {
      if (data.success) {
        showToast(role + " deleted successfully!", "success");
        setTimeout(function () {
          location.reload();
        }, 800);
      } else {
        showToast(data.message, "danger");
      }
    });
}

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
