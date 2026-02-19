<?php
include "config/database.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);

if(isset($_POST['register'])) {

    // Sanitize inputs
    $name  = htmlspecialchars(trim($_POST['name'] ?? ''));
    $mobile = trim($_POST['mobile'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $role = $_POST['role'] ?? 'admin';
    $state = trim($_POST['state'] ?? '');
    $region = trim($_POST['region'] ?? '');
    $district = trim($_POST['district'] ?? '');
    $assigned_student_email = trim($_POST['assigned_student_email'] ?? '');

    // Basic validation
    if(empty($name) || empty($mobile) || empty($email) || empty($password)) {
        $error = "All required fields must be filled.";
    }
    elseif($password !== $confirm_password) {
        $error = "Passwords do not match.";
    }
    elseif(strlen($password) < 8) {
        $error = "Password must be at least 8 characters.";
    }
    elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address.";
    }
    elseif(!preg_match('/^[0-9+\- ]{7,20}$/', $mobile)) {
        $error = "Invalid mobile number.";
    }
    elseif($role === 'gm' && empty($state)) {
        $error = "State is required for GM.";
    }
    elseif($role === 'agm' && (empty($state) || empty($region) || empty($district))) {
        $error = "State, Region and District are required for AGM.";
    }
    elseif($role === 'counselor' && empty($assigned_student_email)) {
        $error = "Assigned student email is required for Counselor.";
    }
    else {

        try {
            // Check duplicate email
            $check = $conn->prepare("SELECT id FROM admins WHERE email = :email");
            $check->execute([':email' => $email]);

            if($check->rowCount() > 0) {
                $error = "Email already registered.";
            } else {

                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                $sql = "INSERT INTO admins 
                        (name, mobile, email, password, role, state, region, district, assigned_student_email)
                        VALUES 
                        (:name, :mobile, :email, :password, :role, :state, :region, :district, :assigned_student_email)";

                $stmt = $conn->prepare($sql);
                $stmt->execute([
                    ':name' => $name,
                    ':mobile' => $mobile,
                    ':email' => $email,
                    ':password' => $hashed_password,
                    ':role' => $role,
                    ':state' => $state ?: null,
                    ':region' => $region ?: null,
                    ':district' => $district ?: null,
                    ':assigned_student_email' => $assigned_student_email ?: null
                ]);

                $success = "Admin registered successfully.";
            }

        } catch(PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Registration</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
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
    border-radius: 15px;
    max-width: 500px;
    width: 100%;
}
.card-header {
    background: #dc3545;
    color: white;
    text-align: center;
}
.btn-primary {
    background: #dc3545;
    border: none;
}
.btn-primary:hover {
    background: #b02a37;
}
.input-group-text {
    background: #dc3545;
    color: white;
    border: none;
}
.toggle-password {
    cursor: pointer;
}
</style>
</head>

<body>

<div class="card shadow-lg">
<div class="card-header p-3">
<h4><i class="bi bi-shield-lock"></i> Admin Registration</h4>
</div>

<div class="card-body p-4">

<?php if(isset($error)): ?>
<div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<?php if(isset($success)): ?>
<div class="alert alert-success"><?php echo $success; ?></div>
<?php endif; ?>

<form method="POST">

<!-- Name -->
<div class="mb-3">
<label>Full Name</label>
<div class="input-group">
<span class="input-group-text"><i class="bi bi-person"></i></span>
<input type="text" name="name" class="form-control" required>
</div>
</div>

<!-- Mobile -->
<div class="mb-3">
<label>Mobile</label>
<div class="input-group">
<span class="input-group-text"><i class="bi bi-telephone"></i></span>
<input type="tel" name="mobile" class="form-control" required>
</div>
</div>

<!-- Email -->
<div class="mb-3">
<label>Email</label>
<div class="input-group">
<span class="input-group-text"><i class="bi bi-envelope"></i></span>
<input type="email" name="email" class="form-control" required>
</div>
</div>

<!-- Role -->
<div class="mb-3">
<label>Role</label>
<select name="role" id="role" class="form-select" onchange="handleRoleChange()">
<option value="admin">Admin</option>
<option value="gm">GM</option>
<option value="agm">AGM</option>
<option value="counselor">Counselor</option>
</select>
</div>

<!-- State -->
<div class="mb-3 d-none" id="stateField">
<label>State</label>
<input type="text" name="state" class="form-control">
</div>

<!-- Region -->
<div class="mb-3 d-none" id="regionField">
<label>Region</label>
<input type="text" name="region" class="form-control">
</div>

<!-- District -->
<div class="mb-3 d-none" id="districtField">
<label>District</label>
<input type="text" name="district" class="form-control">
</div>

<!-- Student Email -->
<div class="mb-3 d-none" id="studentField">
<label>Assigned Student Email</label>
<input type="email" name="assigned_student_email" class="form-control">
</div>

<!-- Password -->
<div class="mb-3">
<label>Password</label>
<div class="input-group">
<span class="input-group-text"><i class="bi bi-lock"></i></span>
<input type="password" name="password" id="password" class="form-control" required>
<span class="input-group-text toggle-password" onclick="togglePassword()">
<i class="bi bi-eye"></i>
</span>
</div>
</div>

<!-- Confirm Password -->
<div class="mb-3">
<label>Confirm Password</label>
<div class="input-group">
<span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
<input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
<span class="input-group-text toggle-password" onclick="toggleConfirmPassword()">
<i class="bi bi-eye"></i>
</span>
</div>
</div>

<button type="submit" name="register" class="btn btn-primary w-100">
<i class="bi bi-person-plus"></i> Register
</button>

</form>
</div>
</div>

<script>
function togglePassword() {
    const p = document.getElementById("password");
    p.type = p.type === "password" ? "text" : "password";
}

function toggleConfirmPassword() {
    const p = document.getElementById("confirm_password");
    p.type = p.type === "password" ? "text" : "password";
}

function handleRoleChange() {
    const role = document.getElementById("role").value;

    const state = document.getElementById("stateField");
    const region = document.getElementById("regionField");
    const district = document.getElementById("districtField");
    const student = document.getElementById("studentField");

    state.classList.add("d-none");
    region.classList.add("d-none");
    district.classList.add("d-none");
    student.classList.add("d-none");

    if(role === "gm") {
        state.classList.remove("d-none");
    }

    if(role === "agm") {
        state.classList.remove("d-none");
        region.classList.remove("d-none");
        district.classList.remove("d-none");
    }

    if(role === "counselor") {
        student.classList.remove("d-none");
    }
}
</script>

</body>
</html>
