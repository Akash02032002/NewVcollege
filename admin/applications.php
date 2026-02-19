<?php
include __DIR__ . '/../includes/auth.php';
require_role('counselor'); // allow counselor and above
include __DIR__ . '/../config/database.php';

// Build base query
$sql = "SELECT * FROM applications ORDER BY created_at DESC";
$params = [];

// Apply filters based on role
$role = $_SESSION['role'] ?? '';
$me = $_SESSION['admin_id'] ?? 0;
if($role === 'gm') {
        if(!empty($_SESSION['state'])) {
                $sql = "SELECT * FROM applications WHERE (state = :state OR assigned_admin_id = :me) ORDER BY created_at DESC";
                $params = [':state' => $_SESSION['state'], ':me' => $me];
        } else {
                // no state scope set for this GM — show only items explicitly assigned to them
                $sql = "SELECT * FROM applications WHERE assigned_admin_id = :me ORDER BY created_at DESC";
                $params = [':me' => $me];
        }
} elseif($role === 'agm') {
        // prefer district then region
        if(!empty($_SESSION['district'])) {
                $sql = "SELECT * FROM applications WHERE (district = :district OR assigned_admin_id = :me) ORDER BY created_at DESC";
                $params = [':district' => $_SESSION['district'], ':me' => $me];
        } elseif(!empty($_SESSION['region'])) {
                $sql = "SELECT * FROM applications WHERE (region = :region OR assigned_admin_id = :me) ORDER BY created_at DESC";
                $params = [':region' => $_SESSION['region'], ':me' => $me];
        } else {
                // no region/district scope — show only items explicitly assigned to this AGM
                $sql = "SELECT * FROM applications WHERE assigned_admin_id = :me ORDER BY created_at DESC";
                $params = [':me' => $me];
        }
} elseif($role === 'counselor') {
        if(!empty($_SESSION['assigned_student_email'])) {
                // counselor has a specific student email assigned — show that student's applications or items explicitly assigned to this counselor
                $sql = "SELECT * FROM applications WHERE (assigned_admin_id = :me OR email = :email) ORDER BY created_at DESC";
                $params = [':me' => $me, ':email' => $_SESSION['assigned_student_email']];
        } else {
                // no assigned student — show only applications explicitly assigned to this counselor
                $sql = "SELECT * FROM applications WHERE assigned_admin_id = :me ORDER BY created_at DESC";
                $params = [':me' => $me];
        }
}

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$apps = $stmt->fetchAll(PDO::FETCH_ASSOC);

// fetch admins for assignment dropdown (only for admin users)
$admins = [];
if(($_SESSION['role'] ?? '') === 'admin') {
        $a = $conn->query('SELECT id, name, role, email FROM admins ORDER BY role, name');
        $admins = $a->fetchAll(PDO::FETCH_ASSOC);
}

// flash messages
$flash_success = $_SESSION['flash_success'] ?? null;
$flash_error = $_SESSION['flash_error'] ?? null;
unset($_SESSION['flash_success'], $_SESSION['flash_error']);

// basic stats
$totalApps    = count($apps);
$pendingApps  = count(array_filter($apps, fn($x)=>(($x['status'] ?? '')==='pending')));
$acceptedApps = count(array_filter($apps, fn($x)=>(($x['status'] ?? '')==='accepted')));
$rejectedApps = count(array_filter($apps, fn($x)=>(($x['status'] ?? '')==='rejected')));
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Applications</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body{font-family:Segoe UI, sans-serif;background:#f0f2f5;margin:0}
        .sidebar{position:fixed;top:0;left:0;width:250px;height:100vh;background:linear-gradient(180deg,#1a1a2e,#16213e);color:#fff;padding:20px}
        .main-content{margin-left:250px;padding:20px}
        .top-bar{display:flex;justify-content:space-between;align-items:center;background:#fff;padding:12px;border-radius:8px;margin-bottom:18px}
        .stat-card{background:#fff;border-radius:10px;padding:16px;box-shadow:0 2px 8px rgba(0,0,0,0.06)}
        .table-card{background:#fff;border-radius:12px;padding:18px}
        @media(max-width:768px){.sidebar{width:0}.main-content{margin-left:0;padding:12px}}
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="brand"><h4><i class="bi bi-mortarboard-fill"></i> Top Colleges</h4><small>Admin Area</small></div>
                                <nav class="mt-3">
                                                <a href="dashboard.php" class="nav-link text-white d-block mb-2">Dashboard</a>
                                                <?php if(($_SESSION['role'] ?? '') === 'admin'): ?>
                                                        <a href="manage_admins.php" class="nav-link text-white d-block mb-2">Manage Admins</a>
                                                <?php endif; ?>
                                                <a href="applications.php" class="nav-link text-white d-block mb-2">Applications</a>
                                                <a href="../index.php" class="nav-link text-white d-block mb-2">View Site</a>
                                                <a href="../logout.php" class="nav-link text-danger d-block mt-3">Logout</a>
                                </nav>
    </div>

    <div class="main-content">
        <div class="top-bar">
            <div>
                <h5 class="mb-0">Applications / Enquiries</h5>
                <small class="text-muted">Role: <?php echo htmlspecialchars($_SESSION['role']); ?></small>
            </div>
            <div class="d-flex gap-3 align-items-center">
                <div class="text-end"><small class="text-muted">Welcome</small><div><strong><?php echo htmlspecialchars($_SESSION['user'] ?? ''); ?></strong></div></div>
            </div>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-3"><div class="stat-card"><h4><?php echo $totalApps; ?></h4><small class="text-muted">Total</small></div></div>
            <div class="col-md-3"><div class="stat-card"><h4><?php echo $pendingApps; ?></h4><small class="text-muted">Pending</small></div></div>
            <div class="col-md-3"><div class="stat-card"><h4><?php echo $acceptedApps; ?></h4><small class="text-muted">Accepted</small></div></div>
            <div class="col-md-3"><div class="stat-card"><h4><?php echo $rejectedApps; ?></h4><small class="text-muted">Rejected</small></div></div>
        </div>

        <?php if($flash_success): ?><div class="alert alert-success"><?php echo htmlspecialchars($flash_success); ?></div><?php endif; ?>
        <?php if($flash_error): ?><div class="alert alert-danger"><?php echo htmlspecialchars($flash_error); ?></div><?php endif; ?>

        <div class="table-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0">Applications</h6>
                <div style="width:320px"><input id="searchInput" class="form-control form-control-sm" placeholder="Search by name, email, phone..."></div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-bordered" id="appsTable">
                <thead class="table-light">
                        <tr>
                                <th>#</th>
                                <th>Status</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>State</th>
                                <th>Region</th>
                                <th>District</th>
                                <th>Course</th>
                                <th>College</th>
                                <th>Assigned</th>
                                <th>Received</th>
                                <th>Action</th>
                        </tr>
                </thead>
                <tbody>
                <?php foreach($apps as $i => $a): ?>
                        <tr>
                                <td><?php echo $i+1; ?></td>
                                <td><span class="badge bg-secondary text-white"><?php echo htmlspecialchars($a['status'] ?? 'pending'); ?></span></td>
                                <td><?php echo htmlspecialchars($a['name']); ?></td>
                                <td><?php echo htmlspecialchars($a['email']); ?></td>
                                <td><?php echo htmlspecialchars($a['phone']); ?></td>
                                <td><?php echo htmlspecialchars($a['state'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($a['region'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($a['district'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($a['course_interest'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($a['college_name'] ?? ''); ?></td>
                                <td>
                                        <?php if(!empty($a['assigned_admin_id'])): ?>
                                                <?php
                                                        $adm = $conn->prepare('SELECT name, role, email FROM admins WHERE id = :id');
                                                        $adm->execute([':id' => $a['assigned_admin_id']]);
                                                        $admrow = $adm->fetch(PDO::FETCH_ASSOC);
                                                ?>
                                                <div><strong><?php echo htmlspecialchars($admrow['name'] ?? ''); ?></strong></div>
                                                <div class="text-muted small"><?php echo htmlspecialchars($admrow['role'] ?? ''); ?> - <?php echo htmlspecialchars($admrow['email'] ?? ''); ?></div>
                                                <div class="text-muted small">at <?php echo htmlspecialchars($a['assigned_at'] ?? ''); ?></div>
                                        <?php else: ?>
                                                <em class="text-muted">Not assigned</em>
                                        <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($a['created_at'] ?? ''); ?></td>
                                <td>
                                        <?php if(($_SESSION['role'] ?? '') === 'admin'): ?>
                                                <form method="POST" action="assign_application.php" onsubmit="return confirm('Assign this application to the selected admin?');">
                                                        <input type="hidden" name="application_id" value="<?php echo $a['id']; ?>">
                                                        <div class="input-group">
                                                                <select name="assigned_admin_id" class="form-select form-select-sm">
                                                                        <option value="">Select admin...</option>
                                                                        <?php foreach($admins as $ad): ?>
                                                                                <option value="<?php echo $ad['id']; ?>"><?php echo htmlspecialchars($ad['name'].' ('. $ad['role'] .')'); ?></option>
                                                                        <?php endforeach; ?>
                                                                </select>
                                                                <button class="btn btn-sm btn-primary" type="submit">Assign</button>
                                                        </div>
                                                </form>
                                        <?php else: ?>
                                                <a class="btn btn-sm btn-outline-primary" href="view_application.php?id=<?php echo $a['id']; ?>">View</a>
                                        <?php endif; ?>
                                </td>
                        </tr>
                <?php endforeach; ?>
                </tbody>
        </table>

        <p class="mt-3"><a href="dashboard.php">Back to dashboard</a></p>
    </div>
    </div>

    <script>
        // simple client-side search
        document.getElementById('searchInput').addEventListener('input', function(e){
            const q = e.target.value.toLowerCase();
            document.querySelectorAll('#appsTable tbody tr').forEach(tr=>{
                tr.style.display = [...tr.querySelectorAll('td')].some(td=>td.textContent.toLowerCase().includes(q)) ? '' : 'none';
            });
        });
    </script>
</body>
</html>
