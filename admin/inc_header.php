<?php
include __DIR__ . '/../includes/auth.php';
if(session_status() === PHP_SESSION_NONE) session_start();
$role = $_SESSION['role'] ?? '';
$user = $_SESSION['user'] ?? '';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Area</title>
  <link href="../css/bootstrap.min.css" rel="stylesheet">
  <link href="../css/main.css" rel="stylesheet">
  <style>
    .admin-sidebar { min-width:200px }
    .card-stats { border-left:4px solid #0d6efd }
  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
    <a class="navbar-brand" href="dashboard.php">College Admin</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="dashboard.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="applications.php">Applications</a></li>
        <?php if($role === 'admin'): ?>
          <li class="nav-item"><a class="nav-link" href="manage_admins.php">Manage Admins</a></li>
        <?php endif; ?>
      </ul>
      <div class="d-flex align-items-center">
        <div class="me-3 text-white text-end">
          <div style="font-weight:600"><?php echo htmlspecialchars($user); ?></div>
          <div style="font-size:0.85rem"><?php echo htmlspecialchars($role); ?></div>
        </div>
        <a class="btn btn-outline-light btn-sm" href="../logout.php">Logout</a>
      </div>
    </div>
  </div>
</nav>
<main class="container mt-4">
