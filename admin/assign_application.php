<?php
include __DIR__ . '/../includes/auth.php';
require_role('admin');
include __DIR__ . '/../config/database.php';

if($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: applications.php');
    exit();
}

$app_id = intval($_POST['application_id'] ?? 0);
$assigned_admin_id = intval($_POST['assigned_admin_id'] ?? 0);

if($app_id <= 0 || $assigned_admin_id <= 0) {
    $_SESSION['flash_error'] = 'Invalid parameters.';
    header('Location: applications.php');
    exit();
}

// fetch target admin
$stmt = $conn->prepare('SELECT id, name, role FROM admins WHERE id = :id');
$stmt->execute([':id' => $assigned_admin_id]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);
if(!$admin) {
    $_SESSION['flash_error'] = 'Selected admin not found.';
    header('Location: applications.php');
    exit();
}

try {
    $u = $conn->prepare('UPDATE applications SET assigned_role = :role, assigned_admin_id = :aid, assigned_at = NOW() WHERE id = :id');
    $u->execute([
        ':role' => $admin['role'],
        ':aid' => $admin['id'],
        ':id' => $app_id
    ]);
    $_SESSION['flash_success'] = 'Application assigned to ' . $admin['name'] . ' (' . $admin['role'] . ').';
} catch(PDOException $e) {
    $_SESSION['flash_error'] = 'DB error: ' . $e->getMessage();
}

header('Location: applications.php');
exit();
