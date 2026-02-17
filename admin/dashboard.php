
<?php
include "../includes/auth.php";
// include "../includes/header.php";

if($_SESSION['role'] != "admin") {
    header("Location: ../login.php");
}
?>

<h2>Admin Dashboard</h2>
<p>Welcome <?php echo $_SESSION['user']; ?></p>
<a href="../logout.php" class="btn btn-danger">Logout</a>

