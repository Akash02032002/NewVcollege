<?php
include __DIR__ . '/../includes/auth.php';
require_login();

if(!empty($_SESSION['is_impersonating']) && !empty($_SESSION['impersonate_original'])) {
    $orig = $_SESSION['impersonate_original'];
    $_SESSION['user'] = $orig['user'];
    $_SESSION['role'] = $orig['role'];
    $_SESSION['admin_id'] = $orig['admin_id'];
    $_SESSION['email'] = $orig['email'];
    $_SESSION['state'] = $orig['state'];
    $_SESSION['region'] = $orig['region'];
    $_SESSION['district'] = $orig['district'];
    $_SESSION['assigned_student_email'] = $orig['assigned_student_email'];
    unset($_SESSION['impersonate_original']);
    unset($_SESSION['is_impersonating']);
}
header('Location: dashboard.php');
exit();
