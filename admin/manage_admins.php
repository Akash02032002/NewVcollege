<?php
include __DIR__ . '/../includes/auth.php';
require_role('admin');
include __DIR__ . '/../config/database.php';

// Fetch admins
$stmt = $conn->query('SELECT id, name, email, mobile, role, state, region, district, assigned_student_email, created_at FROM admins ORDER BY id ASC');
$admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
$adminCount = count($admins);

// flash
$flash_success = $_SESSION['flash_success'] ?? null;
$flash_error = $_SESSION['flash_error'] ?? null;
unset($_SESSION['flash_success'], $_SESSION['flash_error']);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Manage Admins</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <style>
    body{font-family:Segoe UI, sans-serif;background:#f0f2f5;margin:0}
    .sidebar{position:fixed;top:0;left:0;width:250px;height:100vh;background:linear-gradient(180deg,#1a1a2e,#16213e);color:#fff;padding:20px}
    .main-content{margin-left:250px;padding:20px}
    .top-bar{display:flex;justify-content:space-between;align-items:center;background:#fff;padding:12px;border-radius:8px;margin-bottom:18px}
    .table-card{background:#fff;border-radius:12px;padding:18px}
    @media(max-width:768px){.sidebar{width:0}.main-content{margin-left:0;padding:12px}}
  </style>
</head>
<body>

  <div class="sidebar">
    <div class="brand"><h4><i class="bi bi-mortarboard-fill"></i> Top Colleges</h4><small>Admin Area</small></div>
    <nav class="mt-3">
      <a href="dashboard.php" class="nav-link text-white d-block mb-2">Dashboard</a>
      <a href="manage_admins.php" class="nav-link text-white d-block mb-2">Manage Admins</a>
      <a href="applications.php" class="nav-link text-white d-block mb-2">Applications</a>
      <a href="../index.php" class="nav-link text-white d-block mb-2">View Site</a>
      <a href="../logout.php" class="nav-link text-danger d-block mt-3">Logout</a>
    </nav>
  </div>

  <div class="main-content">
    <div class="top-bar">
      <div>
        <h5 class="mb-0">Manage Admins</h5>
        <small class="text-muted">Total admins: <?php echo $adminCount; ?></small>
      </div>
      <div>
        <a href="dashboard.php" class="btn btn-sm btn-secondary">Back to Dashboard</a>
        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addAdminModal">Add Admin</button>
      </div>
    </div>

    <?php if($flash_success): ?><div class="alert alert-success"><?php echo htmlspecialchars($flash_success); ?></div><?php endif; ?>
    <?php if($flash_error): ?><div class="alert alert-danger"><?php echo htmlspecialchars($flash_error); ?></div><?php endif; ?>

    <div class="table-card">
        <div class="table-responsive">
        <table class="table table-striped table-hover table-sm align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Mobile</th>
                    <th>Role</th>
                    <th>State</th>
                    <th>Region</th>
                    <th>District</th>
                    <th>Assigned Student</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($admins as $i => $a): ?>
                <tr>
                    <td><?php echo $i + 1; ?></td>
                    <td><?php echo htmlspecialchars($a['name']); ?></td>
                    <td><?php echo htmlspecialchars($a['email']); ?></td>
                    <td><?php echo htmlspecialchars($a['mobile']); ?></td>
                    <td><?php echo htmlspecialchars($a['role']); ?></td>
                    <td><?php echo htmlspecialchars($a['state'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($a['region'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($a['district'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($a['assigned_student_email'] ?? ''); ?></td>
                    <td>
                        <a class="btn btn-sm btn-outline-secondary" href="impersonate.php?id=<?php echo $a['id']; ?>">Impersonate</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    </div>

  </div>

  <!-- Add Admin Modal -->
  <div class="modal fade" id="addAdminModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-lg">
          <form method="POST" action="admin_create.php" class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title">Add Admin</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">

                  <div class="row g-3">
                      <div class="col-md-6">
                          <label class="form-label">Full Name</label>
                          <input type="text" name="name" class="form-control" required>
                      </div>
                      <div class="col-md-6">
                          <label class="form-label">Email</label>
                          <input type="email" name="email" class="form-control" required>
                      </div>
                      <div class="col-md-6">
                          <label class="form-label">Mobile</label>
                          <input type="tel" name="mobile" class="form-control" required>
                      </div>
                      <div class="col-md-6">
                          <label class="form-label">Role</label>
                          <select name="role" class="form-select" required>
                              <option value="admin">Admin</option>
                              <option value="gm">GM</option>
                              <option value="agm">AGM</option>
                              <option value="counselor">Counselor</option>
                          </select>
                      </div>
                      <div class="col-md-4">
                          <label class="form-label">State (optional)</label>
                          <input type="text" name="state" class="form-control">
                      </div>
                      <div class="col-md-4">
                          <label class="form-label">Region (optional)</label>
                          <input type="text" name="region" class="form-control">
                      </div>
                      <div class="col-md-4">
                          <label class="form-label">District (optional)</label>
                          <input type="text" name="district" class="form-control">
                      </div>
                      <div class="col-12">
                          <label class="form-label">Assigned Student Email (for counselor)</label>
                          <input type="email" name="assigned_student_email" class="form-control">
                      </div>
                      <div class="col-md-6">
                          <label class="form-label">Password</label>
                          <input type="password" name="password" class="form-control" required minlength="8">
                      </div>
                      <div class="col-md-6">
                          <label class="form-label">Confirm Password</label>
                          <input type="password" name="confirm_password" class="form-control" required minlength="8">
                      </div>
                  </div>

              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                  <button type="submit" name="create_admin" class="btn btn-primary">Create</button>
              </div>
          </form>
      </div>
  </div>

</body>
</html>
