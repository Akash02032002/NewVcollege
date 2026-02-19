<?php
include __DIR__ . '/../includes/auth.php';
require_role('counselor');
include __DIR__ . '/../config/database.php';

if($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: applications.php');
    exit();
}

$id = intval($_POST['application_id'] ?? 0);
$status = trim($_POST['status'] ?? '');
$allowed = ['pending','accepted','rejected'];
if($id <= 0 || !in_array($status, $allowed)) {
    $_SESSION['flash_error'] = 'Invalid request.';
    header('Location: applications.php'); exit();
}

// fetch application
$stmt = $conn->prepare('SELECT * FROM applications WHERE id = :id');
$stmt->execute([':id' => $id]);
$app = $stmt->fetch(PDO::FETCH_ASSOC);
if(!$app) {
    $_SESSION['flash_error'] = 'Application not found.';
    header('Location: applications.php'); exit();
}

// permission: admin can update; others only if they can view or are assigned
$me = $_SESSION['admin_id'] ?? 0;
$role = $_SESSION['role'] ?? '';
$allowedUpdate = false;
if($role === 'admin') $allowedUpdate = true;
if(!$allowedUpdate) {
    // assigned admin may update
    if(!empty($app['assigned_admin_id']) && intval($app['assigned_admin_id']) === intval($me)) $allowedUpdate = true;
    // or role-based scope
    if(!$allowedUpdate && function_exists('can_view_application')) {
        if(can_view_application($app)) $allowedUpdate = true;
    }
}

if(!$allowedUpdate) {
    $_SESSION['flash_error'] = 'Not allowed to update this application.';
    header('Location: applications.php'); exit();
}

$u = $conn->prepare('UPDATE applications SET status = :s, updated_at = NOW() WHERE id = :id');
$u->execute([':s' => $status, ':id' => $id]);

$_SESSION['flash_success'] = 'Application status updated to '.htmlspecialchars($status);
header('Location: view_application.php?id='.$id);
exit();
