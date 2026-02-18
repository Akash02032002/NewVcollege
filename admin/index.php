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

echo json_encode(['success' => false, 'message' => 'Unknown action.']);
