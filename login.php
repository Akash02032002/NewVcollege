<?php
session_start();
include "config/database.php";
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_POST['login'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check Student
    $stmt = $conn->prepare("SELECT * FROM students WHERE email = :email");
    $stmt->execute([':email' => $email]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($student && $password === $student['password']) {
        $_SESSION['user'] = $student['name'];
        $_SESSION['user_email'] = $student['email'];
        $_SESSION['role'] = "student";
        header("Location: student/dashboard.php");
        exit();
    }

    // Check Admin
    $stmt = $conn->prepare("SELECT * FROM admins WHERE email = :email");
    $stmt->execute([':email' => $email]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin && $password === $admin['password']) {
        $_SESSION['user'] = $admin['name'];
        $_SESSION['role'] = "admin";
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
        }

        .card {
            border: none;
            border-radius: 15px;
        }

        .card-header {
            background: #1d3557;
            color: white;
            text-align: center;
            border-radius: 15px 15px 0 0;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #1d3557;
        }

        .btn-success {
            background: #1d3557;
            border: none;
        }

        .btn-success:hover {
            background: #16324f;
        }

        .toggle-password {
            cursor: pointer;
        }
    </style>
</head>

<body>

    <div class="col-md-4">
        <div class="card shadow-lg">
            <div class="card-header p-3">
                <h4><i class="bi bi-box-arrow-in-right"></i> Login</h4>
            </div>

            <div class="card-body p-4">

                <?php if (isset($error)): ?>
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
                    <div class="text-center mt-2">
                        <a href="index.php" class="btn btn-outline-secondary btn-sm"><i class="bi bi-house-door"></i> Home</a>
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