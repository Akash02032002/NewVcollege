<?php
require_role('gm');
include __DIR__ . '/inc_header.php';
?>

<style>
    body {
        background-color: #f4f6f9;
    }
    .sidebar {
        min-height: 100vh;
        background: #1e293b;
        color: #cbd5e1;
        padding-top: 20px;
    }
    .sidebar a {
        color: #cbd5e1;
        display: block;
        padding: 10px 20px;
        text-decoration: none;
        border-radius: 6px;
        margin: 5px 10px;
        transition: 0.2s;
    }
    .sidebar a:hover, .sidebar .active {
        background: #334155;
        color: #fff;
    }
    .card-stat {
        border-radius: 12px;
        padding: 20px;
        color: #fff;
    }
    .card-stat i {
        font-size: 2rem;
        opacity: 0.7;
    }
</style>

<div class="container-fluid">
    <div class="row">

        <!-- Sidebar -->
        <nav class="col-md-2 sidebar">
            <h6 class="text-uppercase text-center text-secondary mb-4">Navigation</h6>
            <a class="active" href="dashboard.php">Dashboard</a>
            <a href="applications.php">Applications</a>
            <a href="reports.php">Reports</a>
            <hr class="text-secondary">
            <a href="logout.php" class="text-danger">Logout</a>
        </nav>

        <!-- Main Content -->
        <main class="col-md-10 px-4 py-4">

            <!-- Stats Cards -->
            <div class="row g-4 mb-4">
                <?php
                // GM dashboard: state-scoped
                include __DIR__ . '/../includes/auth.php';
                include __DIR__ . '/../config/database.php';
                require_role('gm');

                $me = $_SESSION['admin_id'] ?? 0;
                $state = $_SESSION['state'] ?? '';

                $sql = "SELECT * FROM applications WHERE (state = :state OR assigned_admin_id = :me) ORDER BY created_at DESC";
                $stmt = $conn->prepare($sql);
                $stmt->execute([':state'=>$state, ':me'=>$me]);
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
                  <title>GM Dashboard</title>
                  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
                  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
                  <style>
                    /* small styles used by dashboard */
                    body{font-family:Segoe UI, sans-serif;background:#f0f2f5;margin:0}
                    .sidebar{position:fixed;top:0;left:0;width:250px;height:100vh;background:linear-gradient(180deg,#1a1a2e,#16213e);color:#fff;padding:20px}
                    .main-content{margin-left:250px;padding:20px}
                    .top-bar{display:flex;justify-content:space-between;align-items:center;background:#fff;padding:15px;border-radius:10px;margin-bottom:25px}
                  </style>
                </head>
                <body>
                  <div class="sidebar">
                    <div class="brand"><h3><i class="bi bi-mortarboard-fill"></i> Top Colleges</h3><small>GM Panel</small></div>
                    <nav class="mt-3"><a href="dashboard.php" class="nav-link active">Dashboard</a><a href="applications.php" class="nav-link">Applications</a><a href="../logout.php" class="nav-link">Logout</a></nav>
                  </div>
                  <div class="main-content">
                    <div class="top-bar"><h4>GM Dashboard</h4><div>Welcome, <strong><?php echo htmlspecialchars($_SESSION['user']); ?></strong></div></div>

                    <div class="row g-4 mb-4">
                      <div class="col-md-3"><div class="stat-card"><h2><?php echo $totalApps; ?></h2><p>Total Applications</p></div></div>
                      <div class="col-md-3"><div class="stat-card"><h2><?php echo $pendingApps; ?></h2><p>Pending</p></div></div>
                      <div class="col-md-3"><div class="stat-card"><h2><?php echo $acceptedApps; ?></h2><p>Accepted</p></div></div>
                      <div class="col-md-3"><div class="stat-card"><h2><?php echo $rejectedApps; ?></h2><p>Rejected</p></div></div>
                    </div>

                    <div class="card"><div class="card-body"><h5>GM Overview</h5><p>State: <strong><?php echo htmlspecialchars($state ?: 'N/A'); ?></strong></p><a href="applications.php" class="btn btn-primary">View Applications</a></div></div>
                  </div>
                </body>
                </html>
