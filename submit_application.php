<?php

/**
 * Submit Application Handler
 * Receives AJAX POST from the "Apply for Admission" modal form
 * and saves the data to the `applications` table.
 */

header('Content-Type: application/json');

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

include "config/database.php";

// Sanitize inputs
$name            = trim($_POST['name'] ?? '');
$email           = trim($_POST['email'] ?? '');
$phone           = trim($_POST['phone'] ?? '');
$state           = trim($_POST['state'] ?? '');
$course_interest = trim($_POST['course_interest'] ?? '');
$college_id      = trim($_POST['college_id'] ?? '');
$college_name    = trim($_POST['college_name'] ?? '');

// Validation
$errors = [];

if (empty($name)) {
    $errors[] = 'Full Name is required.';
}
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'A valid Email is required.';
}
if (empty($phone)) {
    $errors[] = 'Phone number is required.';
}

if (!empty($errors)) {
    echo json_encode(['success' => false, 'message' => implode(' ', $errors)]);
    exit;
}

try {
    $sql = "INSERT INTO applications (name, email, phone, state, course_interest, college_id, college_name) 
            VALUES (:name, :email, :phone, :state, :course_interest, :college_id, :college_name)";

    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':name'            => $name,
        ':email'           => $email,
        ':phone'           => $phone,
        ':state'           => $state,
        ':course_interest' => $course_interest,
        ':college_id'      => $college_id,
        ':college_name'    => $college_name
    ]);

    echo json_encode(['success' => true, 'message' => 'Application submitted successfully!']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Something went wrong. Please try again later.']);
}
