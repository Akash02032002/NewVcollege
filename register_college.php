<?php
include "config/database.php";
error_reporting(E_ALL);
ini_set('display_errors', 1);

$success = '';
$error = '';

if (isset($_POST['register'])) {
    $college_name = trim($_POST['college_name']);
    $email        = trim($_POST['email']);
    $password     = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $contact      = trim($_POST['contact']);
    $state        = trim($_POST['state']);
    $city         = trim($_POST['city']);
    $courses      = trim($_POST['courses']);

    // Validate required fields
    if (empty($college_name) || empty($email) || empty($password) || empty($contact) || empty($state) || empty($city) || empty($courses)) {
        $error = "All fields are required!";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } else {
        // Handle image upload
        $imageName = '';
        if (isset($_FILES['college_image']) && $_FILES['college_image']['error'] === UPLOAD_ERR_OK) {
            $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
            $ext = strtolower(pathinfo($_FILES['college_image']['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, $allowed)) {
                $error = "Only JPG, PNG, WEBP, GIF images are allowed!";
            } elseif ($_FILES['college_image']['size'] > 5 * 1024 * 1024) {
                $error = "Image size must be less than 5MB!";
            } else {
                $imageName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $_FILES['college_image']['name']);
                $uploadDir = 'uploads/colleges/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
                if (!move_uploaded_file($_FILES['college_image']['tmp_name'], $uploadDir . $imageName)) {
                    $error = "Failed to upload image!";
                    $imageName = '';
                }
            }
        }

        if (empty($error)) {
            // Check duplicate email
            $checkEmail = $conn->prepare("SELECT id FROM colleges WHERE email = :email");
            $checkEmail->execute([':email' => $email]);
            if ($checkEmail->rowCount() > 0) {
                $error = "This email is already registered!";
            } else {
                $sql = "INSERT INTO colleges (college_image, college_name, email, password, contact, state, city, courses) 
                        VALUES (:image, :name, :email, :password, :contact, :state, :city, :courses)";
                $stmt = $conn->prepare($sql);
                $stmt->execute([
                    ':image'    => $imageName,
                    ':name'     => $college_name,
                    ':email'    => $email,
                    ':password' => $password,
                    ':contact'  => $contact,
                    ':state'    => $state,
                    ':city'     => $city,
                    ':courses'  => $courses
                ]);
                $success = "College Registered Successfully!";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>College Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="college/style.css">
</head>

<body class="reg-body">

    <div class="reg-card">
        <div class="reg-header">
            <h4><i class="bi bi-building-add"></i> College Registration</h4>
            <small>Register your college to get listed</small>
        </div>
        <div class="reg-body-inner">
            <?php if ($success): ?>
                <div class="alert alert-success py-2 px-3 mb-3" style="font-size:.82rem; border-radius:8px;">
                    <i class="bi bi-check-circle-fill"></i> <?= $success ?>
                </div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div class="alert alert-danger py-2 px-3 mb-3" style="font-size:.82rem; border-radius:8px;">
                    <i class="bi bi-exclamation-circle-fill"></i> <?= $error ?>
                </div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data" autocomplete="off">
                <!-- Image Preview -->
                <div class="img-preview-wrap">
                    <img id="imgPreview" class="img-preview" src="" alt="Preview">
                </div>

                <!-- College Image -->
                <div class="mb-compact">
                    <label class="form-label"><i class="bi bi-image"></i> College Image</label>
                    <input type="file" class="form-control" name="college_image" accept="image/*" onchange="previewImage(this)">
                </div>

                <!-- College Name -->
                <div class="mb-compact">
                    <label class="form-label"><i class="bi bi-building"></i> College Name</label>
                    <input type="text" class="form-control" name="college_name" placeholder="Enter college name" value="<?= htmlspecialchars($_POST['college_name'] ?? '') ?>" required>
                </div>

                <!-- Email -->
                <div class="mb-compact">
                    <label class="form-label"><i class="bi bi-envelope"></i> Email ID</label>
                    <input type="email" class="form-control" name="email" placeholder="Enter email address" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                </div>

                <!-- Password & Confirm Password side by side -->
                <div class="row">
                    <div class="col-6 mb-compact">
                        <label class="form-label"><i class="bi bi-lock"></i> Password</label>
                        <input type="password" class="form-control" name="password" placeholder="Password" required>
                    </div>
                    <div class="col-6 mb-compact">
                        <label class="form-label"><i class="bi bi-lock-fill"></i> Confirm Password</label>
                        <input type="password" class="form-control" name="confirm_password" placeholder="Confirm" required>
                    </div>
                </div>

                <!-- Contact -->
                <div class="mb-compact">
                    <label class="form-label"><i class="bi bi-telephone"></i> Contact Number</label>
                    <input type="text" class="form-control" name="contact" placeholder="Enter contact number" value="<?= htmlspecialchars($_POST['contact'] ?? '') ?>" required maxlength="15">
                </div>

                <!-- State & City side by side -->
                <div class="row">
                    <div class="col-6 mb-compact">
                        <label class="form-label"><i class="bi bi-geo-alt"></i> State</label>
                        <input type="text" class="form-control" name="state" placeholder="State" value="<?= htmlspecialchars($_POST['state'] ?? '') ?>" required>
                    </div>
                    <div class="col-6 mb-compact">
                        <label class="form-label"><i class="bi bi-pin-map"></i> City</label>
                        <input type="text" class="form-control" name="city" placeholder="City" value="<?= htmlspecialchars($_POST['city'] ?? '') ?>" required>
                    </div>
                </div>

                <!-- Courses -->
                <div class="mb-compact">
                    <label class="form-label"><i class="bi bi-book"></i> Courses Offered</label>
                    <input type="text" class="form-control" name="courses" placeholder="e.g. B.Tech, MBA, BCA (comma-separated)" value="<?= htmlspecialchars($_POST['courses'] ?? '') ?>" required>
                </div>

                <button type="submit" name="register" class="btn btn-register mt-2">
                    <i class="bi bi-check-circle"></i> Register College
                </button>
            </form>

            <div class="text-center mt-3">
                <a href="college/list.php" style="font-size:.82rem; color:#1565c0; text-decoration:none; font-weight:600;">
                    <i class="bi bi-list-ul"></i> View College List
                </a>
                <span style="margin:0 8px; color:#ccc;">|</span>
                <a href="index.php" style="font-size:.82rem; color:#78909c; text-decoration:none;">
                    <i class="bi bi-house"></i> Home
                </a>
            </div>
        </div>
    </div>

    <script src="college/script.js"></script>
</body>

</html>