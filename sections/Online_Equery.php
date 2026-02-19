<?php
include "config/database.php";

if(isset($_POST['submit_contact'])){

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $message = trim($_POST['message']);

    if(empty($name) || empty($email) || empty($phone)){
        $error = "Please fill all required fields!";
    } else {

        $stmt = $conn->prepare("INSERT INTO contact_messages (name,email,phone,message) 
                                VALUES (:name,:email,:phone,:message)");
        $stmt->execute([
            ':name'=>$name,
            ':email'=>$email,
            ':phone'=>$phone,
            ':message'=>$message
        ]);

        $success = "Your message submitted successfully!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Contact Us</title>

<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>

body{
    background: #f4f6f9;
    font-family: 'Segoe UI', sans-serif;
}

.page-header{
    background: linear-gradient(90deg,#8b0000,#c40000);
    color: white;
    padding: 60px 0;
    text-align: center;
}

.contact-section{
    padding: 70px 0;
}

.contact-card{
    background: white;
    border-radius: 12px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    padding: 40px;
}

.contact-card h2{
    margin-bottom: 25px;
    font-weight: 600;
    color: #8b0000;
}

.form-control{
    border-radius: 8px;
    padding: 12px;
}

.form-control:focus{
    box-shadow: none;
    border-color: #c40000;
}

.btn-custom{
    background: #8b0000;
    color: white;
    border-radius: 8px;
    padding: 12px;
    font-weight: 500;
    border: none;
}

.btn-custom:hover{
    background: #c40000;
}

.info-box{
    background: #fff;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.08);
}

.info-item{
    display: flex;
    align-items: center;
    margin-bottom: 20px;
}

.info-icon{
    background: #8b0000;
    color: white;
    width: 45px;
    height: 45px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
}

footer{
    background: #111;
    color: #aaa;
    padding: 20px;
    text-align: center;
}

</style>
</head>

<body>

<!-- Header -->
<div class="page-header">
    <h1>Enquiry Us</h1>
    <p>We are here to help you</p>
</div>

<!-- Contact Section -->
<div class="container contact-section">
<div class="row g-4">

<!-- Contact Form -->
<div class="col-md-6">
<div class="contact-card">

<h2>Send Message</h2>

<?php if(isset($error)){ ?>
<div class="alert alert-danger"><?php echo $error; ?></div>
<?php } ?>

<?php if(isset($success)){ ?>
<div class="alert alert-success"><?php echo $success; ?></div>
<?php } ?>

<form method="POST">

<div class="mb-3">
<input type="text" name="name" class="form-control" placeholder="Full Name" required>
</div>

<div class="mb-3">
<input type="email" name="email" class="form-control" placeholder="Email Address" required>
</div>

<div class="mb-3">
<input type="text" name="phone" class="form-control" placeholder="Phone Number" required>
</div>

<div class="mb-3">
<textarea name="message" rows="4" class="form-control" placeholder="Your Message"></textarea>
</div>

<button type="submit" name="submit_contact" class="btn btn-custom w-100">
<i class="bi bi-send"></i> Send Now
</button>

</form>

</div>
</div>

<!-- Contact Info -->
<div class="col-md-6">
<div class="info-box">

<h2 class="mb-4 text-danger">Contact for Online Admission </h2>

<div class="info-item">
<div class="info-icon">
<i class="bi bi-geo-alt"></i>
</div>
<div>
<strong>Address</strong><br>
D-11/156 Sec-8, Rohini East
</div>
</div>

<div class="info-item">
<div class="info-icon">
<i class="bi bi-envelope"></i>
</div>
<div>
<strong>Email</strong><br>
info@yourcollege.com
</div>
</div>

<div class="info-item">
<div class="info-icon">
<i class="bi bi-telephone"></i>
</div>
<div>
<strong>Phone</strong><br>
+91 9911753333
</div>
</div>

</div>
</div>

<!-- Google Map -->
<div class="col-12 mt-4">
<iframe 
src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d55993.31067397694!2d77.05195292167969!3d28.702150500000013!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x253b15e91fca8c77%3A0xb8632df40ff1d5cc!2sTop%20Colleges%20India!5e0!3m2!1sen!2sin"
width="100%" height="350" style="border-radius:12px; border:0;" allowfullscreen></iframe>
</div>

</div>
</div>

<footer>
© <?php echo date('Y'); ?> Your College. All Rights Reserved.
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
