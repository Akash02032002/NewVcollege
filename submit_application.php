
<?php
/**
 * Submit Application Handler
 * Receives AJAX POST from the "Apply for Admission" modal form
 * and saves the data to the `applications` table.
 */
// Allow POST requests; support AJAX or normal form submissions
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	header('Location: index.php');
	exit;
}
session_start();
include "config/database.php";

// detect AJAX
$is_ajax = false;
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
	$is_ajax = true;
} elseif(!empty($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) {
	$is_ajax = true;
}
// Sanitize inputs
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$state = trim($_POST['state'] ?? '');
$region = trim($_POST['region'] ?? '');
$district = trim($_POST['district'] ?? '');
$course_interest = trim($_POST['course_interest'] ?? '');
$college_id = trim($_POST['college_id'] ?? '');
$college_name = trim($_POST['college_name'] ?? '');
$message = trim($_POST['message'] ?? '');
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
	if($is_ajax) {
		header('Content-Type: application/json');
		echo json_encode(['success' => false, 'message' => implode(' ', $errors)]);
		exit;
	}
	$_SESSION['flash_error'] = implode(' ', $errors);
	$back = $_SERVER['HTTP_REFERER'] ?? 'index.php';
	header('Location: ' . $back);
	exit;
}
try {
	$sql = "INSERT INTO applications (name, email, phone, state, region, district, course_interest, college_id, college_name)
	 VALUES (:name, :email, :phone, :state, :region, :district, :course_interest, :college_id, :college_name)";
	$stmt = $conn->prepare($sql);
	$stmt->execute([
		':name' => $name,
		':email' => $email,
		':phone' => $phone,
		':state' => $state,
		':region' => $region,
		':district' => $district,
		':course_interest' => $course_interest,
		':college_id' => $college_id,
		':college_name' => $college_name
	]);

	if($is_ajax) {
		header('Content-Type: application/json');
		echo json_encode(['success' => true, 'message' => 'Application submitted successfully!']);
		exit;
	}

	$_SESSION['flash_success'] = 'Application submitted successfully!';
	$back = $_SERVER['HTTP_REFERER'] ?? 'index.php';
	header('Location: ' . $back);
	exit;
} catch (PDOException $e) {
	if($is_ajax) {
		header('Content-Type: application/json');
		echo json_encode(['success' => false, 'message' => 'Something went wrong. Please try again later.']);
		exit;
	}
	$_SESSION['flash_error'] = 'Something went wrong. Please try again later.';
	$back = $_SERVER['HTTP_REFERER'] ?? 'index.php';
	header('Location: ' . $back);
}
