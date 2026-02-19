<?php
include "../includes/auth.php";
include "../config/database.php";

if ($_SESSION['role'] != "admin") {
    header("Location: ../login.php");
    exit();
}

// Fetch all applications
$stmt = $conn->query("SELECT * FROM applications ORDER BY created_at DESC");
$applications = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Stats
$totalApps    = count($applications);
$pendingApps  = count(array_filter($applications, fn($a) => $a['status'] === 'pending'));
$acceptedApps = count(array_filter($applications, fn($a) => $a['status'] === 'accepted'));
$rejectedApps = count(array_filter($applications, fn($a) => $a['status'] === 'rejected'));
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Top Colleges India</title>
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
            <a href="dashboard.php" class="nav-link active">
                <i class="bi bi-grid-1x2-fill"></i> Dashboard
            </a>
            <a href="students.php" class="nav-link">
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
            <h4><i class="bi bi-grid-1x2-fill me-2"></i>Dashboard</h4>
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
                    <div class="icon-box bg-primary-soft"><i class="bi bi-file-earmark-text-fill"></i></div>
                    <h2><?php echo $totalApps; ?></h2>
                    <p>Total Applications</p>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <div class="icon-box bg-warning-soft"><i class="bi bi-clock-fill"></i></div>
                    <h2><?php echo $pendingApps; ?></h2>
                    <p>Pending</p>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <div class="icon-box bg-success-soft"><i class="bi bi-check-circle-fill"></i></div>
                    <h2><?php echo $acceptedApps; ?></h2>
                    <p>Accepted</p>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <div class="icon-box bg-danger-soft"><i class="bi bi-x-circle-fill"></i></div>
                    <h2><?php echo $rejectedApps; ?></h2>
                    <p>Rejected</p>
                </div>
            </div>
        </div>

        <!-- Applications Table -->
        <div class="table-card">
            <div class="card-header-custom">
                <h5><i class="bi bi-people-fill me-2"></i>Admission Applications</h5>
                <div class="search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" id="searchInput" placeholder="Search by name, email, phone...">
                </div>
            </div>

            <?php if ($totalApps > 0): ?>
                <div class="table-responsive">
                    <table class="app-table" id="appTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>State</th>
                                <th>Course Interest</th>
                                <th>College</th>
                                <th>Status</th>
                                <th>Applied On</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($applications as $i => $app): ?>
                                <tr id="row-<?php echo $app['id']; ?>">
                                    <td><?php echo $i + 1; ?></td>
                                    <td><strong><?php echo htmlspecialchars($app['name']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($app['email']); ?></td>
                                    <td><?php echo htmlspecialchars($app['phone']); ?></td>
                                    <td><?php echo htmlspecialchars($app['state'] ?: '—'); ?></td>
                                    <td><?php echo htmlspecialchars($app['course_interest'] ?: '—'); ?></td>
                                    <td><?php echo htmlspecialchars($app['college_name'] ?: '—'); ?></td>
                                    <td>
                                        <select class="status-select" data-id="<?php echo $app['id']; ?>" onchange="updateStatus(this)">
                                            <option value="pending" <?php echo $app['status'] === 'pending'  ? 'selected' : ''; ?>>Pending</option>
                                            <option value="reviewed" <?php echo $app['status'] === 'reviewed' ? 'selected' : ''; ?>>Reviewed</option>
                                            <option value="accepted" <?php echo $app['status'] === 'accepted' ? 'selected' : ''; ?>>Accepted</option>
                                            <option value="rejected" <?php echo $app['status'] === 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                                        </select>
                                    </td>
                                    <td><?php echo date('d M Y, h:i A', strtotime($app['created_at'])); ?></td>
                                    <td>
                                        <button class="btn-action btn-view" title="View Details" onclick='viewDetails(<?php echo json_encode($app); ?>)'>
                                            <i class="bi bi-eye-fill"></i>
                                        </button>
                                        <button class="btn-action btn-delete" title="Delete" onclick="deleteApp(<?php echo $app['id']; ?>)">
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
                    <i class="bi bi-inbox"></i>
                    <h5>No Applications Yet</h5>
                    <p>Applications submitted via the website will appear here.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- View Details Modal -->
    <div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border:none; border-radius:12px;">
                <div class="modal-header" style="background:linear-gradient(135deg,#1a1a2e,#16213e); color:#fff; border-radius:12px 12px 0 0;">
                    <h5 class="modal-title"><i class="bi bi-person-lines-fill me-2"></i>Application Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="detailBody" style="padding:25px;"></div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>

</body>

</html>