<?php

/**
 * Admin API Handler
 * Handles AJAX POST requests for status updates and deletions.
 */

include "../includes/auth.php";
include "../config/database.php";

// Only admins allowed
if ($_SESSION['role'] != "admin") {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Unauthorized.']);
    exit();
}

// Only handle POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['action'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
    exit();
}

header('Content-Type: application/json');

// Update status
if ($_POST['action'] === 'update_status') {
    $id     = intval($_POST['id'] ?? 0);
    $status = trim($_POST['status'] ?? '');
    $allowed = ['pending', 'reviewed', 'accepted', 'rejected'];

    if ($id > 0 && in_array($status, $allowed)) {
        $stmt = $conn->prepare("UPDATE applications SET status = :status WHERE id = :id");
        $stmt->execute([':status' => $status, ':id' => $id]);
        echo json_encode(['success' => true, 'message' => 'Status updated.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid data.']);
    }
    exit();
}

// Delete application
if ($_POST['action'] === 'delete') {
    $id = intval($_POST['id'] ?? 0);
    if ($id > 0) {
        $stmt = $conn->prepare("DELETE FROM applications WHERE id = :id");
        $stmt->execute([':id' => $id]);
        echo json_encode(['success' => true, 'message' => 'Application deleted.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid ID.']);
    }
    exit();
}

// Delete student
if ($_POST['action'] === 'delete_student') {
    $id = intval($_POST['id'] ?? 0);
    if ($id > 0) {
        $stmt = $conn->prepare("DELETE FROM students WHERE id = :id");
        $stmt->execute([':id' => $id]);
        echo json_encode(['success' => true, 'message' => 'Student deleted.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid ID.']);
    }
    exit();
}

// Delete user (from Dashboard - any role)
if ($_POST['action'] === 'delete_user') {
    $id   = intval($_POST['id'] ?? 0);
    $role = trim($_POST['role'] ?? '');
    $tableMap = ['Admin' => 'admins', 'Student' => 'students', 'Enquiry' => 'applications'];
    if ($id > 0 && isset($tableMap[$role])) {
        $table = $tableMap[$role];
        $stmt = $conn->prepare("DELETE FROM `$table` WHERE id = :id");
        $stmt->execute([':id' => $id]);
        echo json_encode(['success' => true, 'message' => $role . ' deleted.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid data.']);
    }
    exit();
}

// Update user (from Dashboard - any role)
if ($_POST['action'] === 'update_user') {
    $id       = intval($_POST['id'] ?? 0);
    $role     = trim($_POST['role'] ?? '');
    $name     = trim($_POST['name'] ?? '');
    $mobile   = trim($_POST['mobile'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $tableMap = ['Admin' => 'admins', 'Student' => 'students', 'Enquiry' => 'applications'];

    if ($id > 0 && isset($tableMap[$role]) && $name && $email) {
        $table = $tableMap[$role];
        if ($role === 'Enquiry') {
            $stmt = $conn->prepare("UPDATE `$table` SET name = :name, phone = :mobile, email = :email WHERE id = :id");
            $stmt->execute([':name' => $name, ':mobile' => $mobile, ':email' => $email, ':id' => $id]);
        } else {
            if ($password !== '') {
                $stmt = $conn->prepare("UPDATE `$table` SET name = :name, mobile = :mobile, email = :email, password = :password WHERE id = :id");
                $stmt->execute([':name' => $name, ':mobile' => $mobile, ':email' => $email, ':password' => $password, ':id' => $id]);
            } else {
                $stmt = $conn->prepare("UPDATE `$table` SET name = :name, mobile = :mobile, email = :email WHERE id = :id");
                $stmt->execute([':name' => $name, ':mobile' => $mobile, ':email' => $email, ':id' => $id]);
            }
        }
        echo json_encode(['success' => true, 'message' => $role . ' updated.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid data.']);
    }
    exit();
}

echo json_encode(['success' => false, 'message' => 'Unknown action.']);
