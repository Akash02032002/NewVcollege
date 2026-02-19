<?php
include __DIR__ . '/../includes/auth.php';
require_role('admin');
include __DIR__ . '/../config/database.php';

if($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: manage_admins.php');
    exit();
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$mobile = trim($_POST['mobile'] ?? '');
$password = $_POST['password'] ?? '';
$confirm = $_POST['confirm_password'] ?? '';
$role = $_POST['role'] ?? 'admin';
$state = trim($_POST['state'] ?? '');
$region = trim($_POST['region'] ?? '');
$district = trim($_POST['district'] ?? '');
$assigned_student_email = trim($_POST['assigned_student_email'] ?? '');

if($password !== $confirm) {
    $_SESSION['flash_error'] = 'Passwords do not match.';
    header('Location: manage_admins.php'); exit();
}
if(strlen($password) < 8) {
    $_SESSION['flash_error'] = 'Password must be at least 8 characters.';
    header('Location: manage_admins.php'); exit();
}
if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['flash_error'] = 'Invalid email.';
    header('Location: manage_admins.php'); exit();
}
try {
    $check = $conn->prepare('SELECT id FROM admins WHERE email = :email');
    $check->execute([':email' => $email]);
    if($check->rowCount() > 0) {
        $_SESSION['flash_error'] = 'Email already exists.';
        header('Location: manage_admins.php'); exit();
    }
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare('INSERT INTO admins (name,mobile,email,password,role,state,region,district,assigned_student_email) VALUES (:name,:mobile,:email,:password,:role,:state,:region,:district,:assigned_student_email)');
    $stmt->execute([
        ':name'=>$name,':mobile'=>$mobile,':email'=>$email,':password'=>$hash,':role'=>$role,':state'=>$state?:null,':region'=>$region?:null,':district'=>$district?:null,':assigned_student_email'=>$assigned_student_email?:null
    ]);
    $_SESSION['flash_success'] = 'Admin created successfully.';
} catch(PDOException $e) {
    $_SESSION['flash_error'] = 'DB error: ' . $e->getMessage();
}

header('Location: manage_admins.php');
exit();
