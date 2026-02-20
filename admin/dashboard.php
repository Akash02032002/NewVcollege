<?php
include "../includes/auth.php";
include "../config/database.php";

if ($_SESSION['role'] != "admin") {
    header("Location: ../login.php");
    exit();
}

// Fetch all admins
$stmt = $conn->query("SELECT id, name, mobile, email, password, 'Admin' AS role FROM admins ORDER BY name ASC");
$admins = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all students
$stmt = $conn->query("SELECT id, name, mobile, email, password, 'Student' AS role FROM students ORDER BY name ASC");
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all enquiries (applications) — no password column in this table
$stmt = $conn->query("SELECT id, name, phone AS mobile, email, '' AS password, 'Enquiry' AS role FROM applications ORDER BY name ASC");
$enquiries = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Combine into one result set
$allUsers = array_merge($admins, $students, $enquiries);

// Stats
$totalUsers    = count($allUsers);
$totalAdmins   = count($admins);
$totalStudents = count($students);
$totalEnquiry  = count($enquiries);
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
            <a href="enquiry.php" class="nav-link">
                <i class="bi bi-envelope-open-fill"></i> Enquiry
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
                    <div class="icon-box bg-primary-soft"><i class="bi bi-people-fill"></i></div>
                    <h2><?php echo $totalUsers; ?></h2>
                    <p>Total Users</p>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <div class="icon-box bg-danger-soft"><i class="bi bi-shield-lock-fill"></i></div>
                    <h2><?php echo $totalAdmins; ?></h2>
                    <p>Admins</p>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <div class="icon-box bg-success-soft"><i class="bi bi-mortarboard-fill"></i></div>
                    <h2><?php echo $totalStudents; ?></h2>
                    <p>Students</p>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <div class="icon-box bg-warning-soft"><i class="bi bi-envelope-open-fill"></i></div>
                    <h2><?php echo $totalEnquiry; ?></h2>
                    <p>Enquiries</p>
                </div>
            </div>
        </div>

        <!-- All Users Table -->
        <div class="table-card">
            <div class="card-header-custom">
                <h5><i class="bi bi-table me-2"></i>All Registered Users</h5>
                <div class="search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" id="dashSearchInput" placeholder="Search by name, email, role...">
                </div>
            </div>

            <?php if ($totalUsers > 0): ?>
                <div class="table-responsive">
                    <table class="app-table" id="dashTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Full Name</th>
                                <th>Mobile Number</th>
                                <th>Email ID</th>
                                <th>Password</th>
                                <th>Role</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($allUsers as $i => $user): ?>
                                <tr>
                                    <td><?php echo $i + 1; ?></td>
                                    <td><strong><?php echo htmlspecialchars($user['name']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($user['mobile']); ?></td>
                                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td>
                                        <?php if ($user['role'] === 'Enquiry'): ?>
                                            <span class="text-muted">—</span>
                                        <?php else: ?>
                                            <div class="pw-cell">
                                                <span class="password-masked" data-password="<?php echo htmlspecialchars($user['password']); ?>">
                                                    <?php echo str_repeat('•', min(strlen($user['password']), 10)); ?>
                                                </span>
                                                <button type="button" class="btn-pw-toggle" title="Show Password" onclick="togglePw(this)">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php
                                        $roleClass = match ($user['role']) {
                                            'Admin'   => 'role-admin',
                                            'Student' => 'role-student',
                                            'Enquiry' => 'role-enquiry',
                                            default   => ''
                                        };
                                        ?>
                                        <span class="role-badge <?php echo $roleClass; ?>"><?php echo $user['role']; ?></span>
                                    </td>
                                    <td>
                                        <div class="action-btns">
                                            <button class="btn-action btn-view" title="View Details"
                                                onclick='viewUser(<?php echo json_encode($user); ?>)'>
                                                <i class="bi bi-eye-fill"></i>
                                            </button>
                                            <button class="btn-action btn-edit" title="Edit User"
                                                onclick='editUser(<?php echo json_encode($user); ?>)'>
                                                <i class="bi bi-pencil-fill"></i>
                                            </button>
                                            <button class="btn-action btn-delete" title="Delete User"
                                                onclick="deleteUser(<?php echo $user['id']; ?>, '<?php echo $user['role']; ?>')">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <i class="bi bi-person-x"></i>
                    <h5>No Users Found</h5>
                    <p>Users will appear here once they register or submit enquiries.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- View User Modal -->
    <div class="modal fade" id="viewUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border:none; border-radius:12px;">
                <div class="modal-header" style="background:linear-gradient(135deg,#1a1a2e,#16213e); color:#fff; border-radius:12px 12px 0 0;">
                    <h5 class="modal-title"><i class="bi bi-person-lines-fill me-2"></i>User Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="viewUserBody" style="padding:25px;"></div>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border:none; border-radius:12px;">
                <div class="modal-header" style="background:linear-gradient(135deg,#1a1a2e,#16213e); color:#fff; border-radius:12px 12px 0 0;">
                    <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Update User</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="padding:25px;">
                    <form id="editUserForm">
                        <input type="hidden" id="editUserId">
                        <input type="hidden" id="editUserRole">
                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="font-size:13px;">Full Name</label>
                            <input type="text" class="form-control" id="editName" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="font-size:13px;">Mobile Number</label>
                            <input type="text" class="form-control" id="editMobile" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="font-size:13px;">Email ID</label>
                            <input type="email" class="form-control" id="editEmail" required>
                        </div>
                        <div class="mb-3" id="editPasswordGroup">
                            <label class="form-label fw-semibold" style="font-size:13px;">Password <small class="text-muted">(leave blank to keep unchanged)</small></label>
                            <input type="text" class="form-control" id="editPassword">
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary btn-sm" style="background:#1a1a2e; border:none;">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>

</body>

</html>