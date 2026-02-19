<?php
include "../includes/auth.php";
include "../config/database.php";

if ($_SESSION['role'] != "admin") {
    header("Location: ../login.php");
    exit();
}

// Fetch all students
$stmt = $conn->query("SELECT * FROM students ORDER BY created_at DESC");
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);
$totalStudents = count($students);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students - Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="styles.css">
</head>

<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="brand">
            <h3><i class="bi bi-mortarboard-fill"></i> Top Colleges</h3>
            <small>Admin Panel</small>
        </div>
        <nav class="mt-3">
            <a href="dashboard.php" class="nav-link">
                <i class="bi bi-grid-1x2-fill"></i> Dashboard
            </a>
            <a href="students.php" class="nav-link active">
                <i class="bi bi-people-fill"></i> Students
            </a>
            <a href="../index.php" class="nav-link">
                <i class="bi bi-house-fill"></i> View Website
            </a>
            <a href="../logout.php" class="nav-link">
                <i class="bi bi-box-arrow-left"></i> Logout
            </a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">

        <!-- Top Bar -->
        <div class="top-bar">
            <h4><i class="bi bi-people-fill me-2"></i>Students</h4>
            <div class="admin-info">
                <span class="text-muted" style="font-size:14px;">Welcome,</span>
                <strong><?php echo htmlspecialchars($_SESSION['user']); ?></strong>
                <div class="avatar"><?php echo strtoupper(substr($_SESSION['user'], 0, 1)); ?></div>
            </div>
        </div>

        <!-- Stat Cards -->
        <div class="row g-4 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <div class="icon-box bg-primary-soft"><i class="bi bi-people-fill"></i></div>
                    <h2><?php echo $totalStudents; ?></h2>
                    <p>Total Registered Students</p>
                </div>
            </div>
        </div>

        <!-- Students Table -->
        <div class="table-card">
            <div class="card-header-custom">
                <h5><i class="bi bi-mortarboard-fill me-2"></i>Registered Students</h5>
                <div class="search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" id="studentSearchInput" placeholder="Search by name, email, mobile...">
                </div>
            </div>

            <?php if ($totalStudents > 0): ?>
                <div class="table-responsive">
                    <table class="app-table" id="studentTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>Registered On</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($students as $i => $stu): ?>
                                <tr id="stu-row-<?php echo $stu['id']; ?>">
                                    <td><?php echo $i + 1; ?></td>
                                    <td><strong><?php echo htmlspecialchars($stu['name']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($stu['email']); ?></td>
                                    <td><?php echo htmlspecialchars($stu['mobile']); ?></td>
                                    <td><?php echo date('d M Y, h:i A', strtotime($stu['created_at'])); ?></td>
                                    <td>
                                        <button class="btn-action btn-view" title="View Details"
                                            onclick='viewStudent(<?php echo json_encode($stu); ?>)'>
                                            <i class="bi bi-eye-fill"></i>
                                        </button>
                                        <button class="btn-action btn-delete" title="Delete"
                                            onclick="deleteStudent(<?php echo $stu['id']; ?>)">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <i class="bi bi-person-x"></i>
                    <h5>No Students Registered</h5>
                    <p>Students who register on the website will appear here.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- View Student Details Modal -->
    <div class="modal fade" id="studentDetailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border:none; border-radius:12px;">
                <div class="modal-header"
                    style="background:linear-gradient(135deg,#1a1a2e,#16213e); color:#fff; border-radius:12px 12px 0 0;">
                    <h5 class="modal-title"><i class="bi bi-person-lines-fill me-2"></i>Student Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body" id="studentDetailBody" style="padding:25px;"></div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Search / Filter
        document.getElementById("studentSearchInput").addEventListener("input", function() {
            const q = this.value.toLowerCase();
            document.querySelectorAll("#studentTable tbody tr").forEach(function(row) {
                row.style.display = row.textContent.toLowerCase().includes(q) ? "" : "none";
            });
        });

        // View Student Details
        function viewStudent(stu) {
            const html = `
                <div class="detail-row"><div class="detail-label">Name</div><div class="detail-value">${escHtml(stu.name)}</div></div>
                <div class="detail-row"><div class="detail-label">Email</div><div class="detail-value">${escHtml(stu.email)}</div></div>
                <div class="detail-row"><div class="detail-label">Mobile</div><div class="detail-value">${escHtml(stu.mobile)}</div></div>
                <div class="detail-row"><div class="detail-label">Registered On</div><div class="detail-value">${stu.created_at}</div></div>
            `;
            document.getElementById("studentDetailBody").innerHTML = html;
            new bootstrap.Modal(document.getElementById("studentDetailModal")).show();
        }

        // Delete Student
        function deleteStudent(id) {
            if (!confirm("Are you sure you want to delete this student?")) return;
            fetch("index.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "action=delete_student&id=" + id,
            })
            .then((r) => r.json())
            .then((data) => {
                if (data.success) {
                    const row = document.getElementById("stu-row-" + id);
                    if (row) row.remove();
                    showToast("Student deleted.", "success");
                } else {
                    showToast(data.message, "danger");
                }
            });
        }

        function escHtml(str) {
            const d = document.createElement("div");
            d.textContent = str;
            return d.innerHTML;
        }

        // Toast Notification
        function showToast(msg, type) {
            const toast = document.createElement("div");
            toast.style.cssText = "position:fixed;top:20px;right:20px;z-index:9999;padding:12px 24px;border-radius:8px;color:#fff;font-size:14px;font-weight:500;box-shadow:0 4px 12px rgba(0,0,0,0.15);transition:opacity 0.5s;";
            toast.style.background = type === "success" ? "#2e7d32" : "#c62828";
            toast.textContent = msg;
            document.body.appendChild(toast);
            setTimeout(() => {
                toast.style.opacity = "0";
                setTimeout(() => toast.remove(), 500);
            }, 2500);
        }
    </script>

</body>

</html>
