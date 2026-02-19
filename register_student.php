<?php
include "config/database.php";
error_reporting(E_ALL);
ini_set('display_errors', 1);

if(isset($_POST['register'])) {

    $name = trim($_POST['name']);
    $mobile = trim($_POST['mobile']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if($password !== $confirm_password){
        $error = "Passwords do not match!";
    } else {
        // Optional: check email already exists
        $check = $conn->prepare("SELECT id FROM students WHERE email = :email");
        $check->execute([':email'=>$email]);
        if($check->rowCount() > 0){
            $error = "Email already registered!";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO students (name, mobile, email, password) VALUES (:name, :mobile, :email, :password)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':name'=>$name,
                ':mobile'=>$mobile,
                ':email'=>$email,
                ':password'=>$hashed_password
            ]);
            $success = "Student Registered Successfully!";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #1d3557, #457b9d);
            height: 100vh;
            display:flex;
            justify-content:center;
            align-items:center;
            padding: 20px;
        }
        .card {
            border:none;
            border-radius:15px;
            width: 100%;
            max-width: 500px;
        }
        .card-header {
            background: #dc3545; /* red header */
            color: white;
            text-align:center;
            border-radius:15px 15px 0 0;
        }
        .form-control:focus {
            box-shadow:none;
            border-color:#dc3545;
        }
        .btn-primary {
            background:#dc3545; /* red button */
            border:none;
        }
        .btn-primary:hover {
            background:#b02a37;
        }
        .input-group-text {
            background:#dc3545; /* red icon background */
            color:white;
            border:none;
        }
        .toggle-password {cursor:pointer;}
        @media (max-width:576px){
            .card-body{padding:20px;}
        }
    </style>
</head>
<body>
<div class="col-md-6">
<div class="card shadow-lg">
    <div class="card-header p-3">
        <h4><i class="bi bi-shield-lock"></i> Student Registration</h4>
    </div>
    <div class="card-body p-4">

    <?php if(isset($error)): ?><div class="alert alert-danger"><?php echo $error;?></div><?php endif; ?>
    <?php if(isset($success)): ?><div class="alert alert-success"><?php echo $success;?></div><?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Full Name</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-person"></i></span>
                <input type="text" name="name" class="form-control" placeholder="Enter full name" required>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Mobile Number</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                <input type="text" name="mobile" class="form-control" placeholder="Enter mobile number" required>
            </div>
        </div>
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
                <span class="input-group-text toggle-password" onclick="togglePassword()"><i class="bi bi-eye"></i></span>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Confirm Password</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirm password" required>
                <span class="input-group-text toggle-password" onclick="toggleConfirmPassword()"><i class="bi bi-eye"></i></span>
            </div>
        </div>

        <button type="submit" name="register" class="btn btn-primary w-100"><i class="bi bi-person-plus"></i> Register</button>

        <div class="text-center mt-3">
            Already have an account? <a href="login.php">Login</a>
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
function toggleConfirmPassword() {
    const confirmPassword = document.getElementById("confirm_password");
    confirmPassword.type = confirmPassword.type === "password" ? "text" : "password";
}
</script>
</body>
</html>
