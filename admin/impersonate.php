<?php
include __DIR__ . '/../includes/auth.php';
require_role('admin');
include __DIR__ . '/../config/database.php';

$id = intval($_GET['id'] ?? 0);
if($id <= 0) {
    header('Location: manage_admins.php');
    exit();
}

// fetch target admin
$stmt = $conn->prepare('SELECT * FROM admins WHERE id = :id');
$stmt->execute([':id' => $id]);
$target = $stmt->fetch(PDO::FETCH_ASSOC);
if(!$target) {
    header('Location: manage_admins.php');
    exit();
}

// store original session data so we can restore
if(empty($_SESSION['is_impersonating'])) {
    $_SESSION['impersonate_original'] = [
        'user' => $_SESSION['user'] ?? null,
        'role' => $_SESSION['role'] ?? null,
        'admin_id' => $_SESSION['admin_id'] ?? null,
        'email' => $_SESSION['email'] ?? null,
        'state' => $_SESSION['state'] ?? null,
        'region' => $_SESSION['region'] ?? null,
        'district' => $_SESSION['district'] ?? null,
        'assigned_student_email' => $_SESSION['assigned_student_email'] ?? null,
    ];
}

// set session values to target admin
$_SESSION['user'] = $target['name'];
$_SESSION['role'] = $target['role'];
$_SESSION['admin_id'] = $target['id'];
$_SESSION['email'] = $target['email'];
$_SESSION['state'] = $target['state'];
$_SESSION['region'] = $target['region'];
$_SESSION['district'] = $target['district'];
$_SESSION['assigned_student_email'] = $target['assigned_student_email'];
$_SESSION['is_impersonating'] = true;

// redirect to dashboard which will render the role-specific page
header('Location: dashboard.php');
exit();
