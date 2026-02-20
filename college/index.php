<?php
include "../config/database.php";
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

// View College Details
if ($action === 'view_college') {
    $id = intval($_GET['id'] ?? 0);
    $stmt = $conn->prepare("SELECT * FROM colleges WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $college = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($college) {
        echo json_encode(['success' => true, 'college' => $college]);
    } else {
        echo json_encode(['success' => false, 'message' => 'College not found']);
    }
    exit;
}

echo json_encode(['success' => false, 'message' => 'Invalid action']);
