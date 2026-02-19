<?php
// Counselor dashboard: shows assigned student(s)
include __DIR__ . '/../includes/auth.php';
include __DIR__ . '/../config/database.php';
require_role('counselor');

$me = $_SESSION['admin_id'] ?? 0;
$assigned_email = $_SESSION['assigned_student_email'] ?? '';

if(!empty($assigned_email)) {
    $stmt = $conn->prepare('SELECT * FROM applications WHERE (email = :email OR assigned_admin_id = :me) ORDER BY created_at DESC');
    $stmt->execute([':email'=>$assigned_email, ':me'=>$me]);
} else {
    $stmt = $conn->prepare('SELECT * FROM applications WHERE assigned_admin_id = :me ORDER BY created_at DESC');
    $stmt->execute([':me'=>$me]);
}

$applications = $stmt->fetchAll(PDO::FETCH_ASSOC);
$totalApps    = count($applications);
$pendingApps  = count(array_filter($applications, fn($a)=>($a['status'] ?? '')==='pending'));
$acceptedApps = count(array_filter($applications, fn($a)=>($a['status'] ?? '')==='accepted'));
$rejectedApps = count(array_filter($applications, fn($a)=>($a['status'] ?? '')==='rejected'));
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Counselor Dashboard</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <style>
    body{font-family:Segoe UI, sans-serif;background:#f0f2f5;margin:0}
    .sidebar{position:fixed;top:0;left:0;width:250px;height:100vh;background:linear-gradient(180deg,#1a1a2e,#16213e);color:#fff;padding:20px}
    .main-content{margin-left:250px;padding:20px}
    .top-bar{display:flex;justify-content:space-between;align-items:center;background:#fff;padding:15px;border-radius:10px;margin-bottom:25px}
    .stat-card{background:#fff;border-radius:12px;padding:22px;box-shadow:0 2px 8px rgba(0,0,0,0.06)}
  </style>
</head>
<body>
  <div class="sidebar"><div class="brand"><h3><i class="bi bi-mortarboard-fill"></i> Top Colleges</h3><small>Counselor Panel</small></div><nav class="mt-3"><a href="dashboard.php" class="nav-link active">Dashboard</a><a href="applications.php" class="nav-link">Assigned Applications</a><a href="../logout.php" class="nav-link">Logout</a></nav></div>
  <div class="main-content">
    <div class="top-bar"><h4>Counselor Dashboard</h4><div>Welcome, <strong><?php echo htmlspecialchars($_SESSION['user']); ?></strong></div></div>
    <div class="row g-4 mb-4">
      <div class="col-md-3"><div class="stat-card"><h2><?php echo $totalApps; ?></h2><p>Assigned</p></div></div>
      <div class="col-md-3"><div class="stat-card"><h2><?php echo $pendingApps; ?></h2><p>Pending</p></div></div>
      <div class="col-md-3"><div class="stat-card"><h2><?php echo $acceptedApps; ?></h2><p>Accepted</p></div></div>
      <div class="col-md-3"><div class="stat-card"><h2><?php echo $rejectedApps; ?></h2><p>Rejected</p></div></div>
    </div>
    <div class="card"><div class="card-body"><h5>Counselor Overview</h5><p>Assigned student: <strong><?php echo htmlspecialchars($assigned_email?:'N/A'); ?></strong></p><a href="applications.php" class="btn btn-primary">View Assigned Application</a></div></div>
  </div>
</body>
</html>
