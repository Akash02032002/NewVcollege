<?php
include "../includes/auth.php";
include "../config/database.php";

// Redirect if not a student
if ($_SESSION['role'] != "student") {
    header("Location: ../login.php");
    exit();
}

// Fetch student details from database
$stmt = $conn->prepare("SELECT * FROM students WHERE email = :email");
$stmt->execute([':email' => $_SESSION['user_email']]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    header("Location: ../login.php");
    exit();
}

// Get initials for avatar
$nameParts = explode(' ', trim($student['name']));
$initials = strtoupper(substr($nameParts[0], 0, 1));
if (count($nameParts) > 1) {
    $initials .= strtoupper(substr(end($nameParts), 0, 1));
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <!-- Navbar -->
    <nav class="topbar">
        <a href="dashboard.php" class="topbar-brand">
            <i class="bi bi-mortarboard-fill"></i>
            <span>Student Portal</span>
        </a>
        <div class="topbar-actions">
            <a href="../index.php" class="btn-nav btn-home"><i class="bi bi-house-door-fill"></i> Home</a>
            <a href="../logout.php" class="btn-nav btn-logout"><i class="bi bi-box-arrow-right"></i> Logout</a>
        </div>
    </nav>

    <!-- Single Page Content -->
    <main class="main-area">
        <div class="card-wrap">

            <!-- Avatar + Name Header -->
            <div class="card-header-section">
                <div class="avatar">
                    <span><?php echo htmlspecialchars($initials); ?></span>
                </div>
                <h2 class="stu-name"><?php echo htmlspecialchars($student['name']); ?></h2>
                <span class="badge-role"><i class="bi bi-star-fill"></i> Student</span>
            </div>

            <!-- Info Rows -->
            <div class="card-body-section">

                <div class="info-row">
                    <div class="info-icon icon-purple"><i class="bi bi-person-fill"></i></div>
                    <div class="info-text">
                        <small>Full Name</small>
                        <strong><?php echo htmlspecialchars($student['name']); ?></strong>
                    </div>
                </div>

                <div class="info-row">
                    <div class="info-icon icon-green"><i class="bi bi-phone-fill"></i></div>
                    <div class="info-text">
                        <small>Mobile</small>
                        <strong><?php echo htmlspecialchars($student['mobile']); ?></strong>
                    </div>
                </div>

                <div class="info-row">
                    <div class="info-icon icon-red"><i class="bi bi-envelope-fill"></i></div>
                    <div class="info-text">
                        <small>Email</small>
                        <strong><?php echo htmlspecialchars($student['email']); ?></strong>
                    </div>
                </div>

                <div class="info-row">
                    <div class="info-icon icon-amber"><i class="bi bi-lock-fill"></i></div>
                    <div class="info-text">
                        <small>Password</small>
                        <strong id="passwordValue" data-password="<?php echo htmlspecialchars($student['password']); ?>"><?php echo str_repeat('•', strlen($student['password'])); ?></strong>
                    </div>
                    <button type="button" class="btn-eye" id="togglePasswordBtn" title="Show Password">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>

            </div>

        </div>
    </main>

    <script src="script.js"></script>
</body>

</html>