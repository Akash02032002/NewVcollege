
<?php

include "../includes/auth.php";
require_login();

$role = $_SESSION['role'] ?? '';

switch($role) {
    case 'admin':
        include __DIR__ . '/dashboard_admin.php';
        break;
    case 'gm':
        include __DIR__ . '/dashboard_gm.php';
        break;
    case 'agm':
        include __DIR__ . '/dashboard_agm.php';
        break;
    case 'counselor':
        include __DIR__ . '/dashboard_counselor.php';
        break;
    default:
        // fallback
        header('Location: ../login.php');
        exit();
}

?>

