<?php
session_start();
include "config/database.php";
error_reporting(E_ALL);
ini_set('display_errors', 1);

if(isset($_POST['login'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check Student
    $stmt = $conn->prepare("SELECT * FROM students WHERE email = :email");
    $stmt->execute([':email' => $email]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    if($student && password_verify($password, $student['password'])) {
        $_SESSION['user'] = $student['name'];
        $_SESSION['role'] = "student";
        header("Location: student/dashboard.php");
        exit();
    }

    // Check Admin
    $stmt = $conn->prepare("SELECT * FROM admins WHERE email = :email");
    $stmt->execute([':email' => $email]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if($admin && password_verify($password, $admin['password'])) {
        $_SESSION['user'] = $admin['name'];
        $_SESSION['role'] = $admin['role'] ?? 'admin';
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['email'] = $admin['email'];
        $_SESSION['state'] = $admin['state'];
        $_SESSION['region'] = $admin['region'];
        $_SESSION['district'] = $admin['district'];
        $_SESSION['assigned_student_email'] = $admin['assigned_student_email'];
        header("Location: admin/dashboard.php");
        exit();
    }

    $error = "Invalid Email or Password!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        body {
            background: linear-gradient(135deg, #1d3557, #457b9d);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .card {
            border: none;
            border-radius: 15px;
            width: 100%;
            max-width: 400px;
        }

        .card-header {
            background: #dc3545; /* red header */
            color: white;
            text-align: center;
            border-radius: 15px 15px 0 0;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #dc3545;
        }

        .btn-primary, .btn-success {
            background: #dc3545; /* red button */
            border: none;
        }

        .btn-primary:hover, .btn-success:hover {
            background: #b02a37;
        }

        .input-group-text {
            background: #dc3545; /* red icon background */
            color: white;
            border: none;
        }

        .toggle-password {
            cursor: pointer;
        }

        @media (max-width:576px){
            .card-body {padding:20px;}
        }
    </style>
</head>
<body>

<div class="col-md-6">
    <div class="card shadow-lg">
        <div class="card-header p-3">
            <h4><i class="bi bi-box-arrow-in-right"></i> Login</h4>
        </div>

        <div class="card-body p-4">

            <?php if(isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST">

                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" class="form-control" placeholder="Enter email" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Enter password" required>
                        <span class="input-group-text toggle-password" onclick="togglePassword()">
                            <i class="bi bi-eye"></i>
                        </span>
                    </div>
                </div>

                <button type="submit" name="login" class="btn btn-success w-100">
                    <i class="bi bi-door-open"></i> Login
                </button>

                <div class="text-center mt-3">
                    Don’t have an account? 
                    <a href="register_student.php">Register as Student</a><br>
                    <a href="register_admin.php">Register as Admin</a>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
function togglePassword() {
    const password = document.getElementById("password");
    password.type = password.type === "password" ? "text" : "password";
}
</script>

</body>
</html>
