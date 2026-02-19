<?php
include __DIR__ . '/../includes/auth.php';
require_role('counselor');
include __DIR__ . '/../config/database.php';

$id = intval($_GET['id'] ?? 0);
if($id <= 0) {
    header('Location: applications.php'); exit();
}

$stmt = $conn->prepare('SELECT * FROM applications WHERE id = :id');
$stmt->execute([':id' => $id]);
$app = $stmt->fetch(PDO::FETCH_ASSOC);
if(!$app) {
    $_SESSION['flash_error'] = 'Application not found.';
    header('Location: applications.php'); exit();
}

// permission check
$me = $_SESSION['admin_id'] ?? 0;
$role = $_SESSION['role'] ?? '';
$canView = false;
if($role === 'admin') $canView = true;
if(!$canView) {
    if(!empty($app['assigned_admin_id']) && intval($app['assigned_admin_id']) === intval($me)) $canView = true;
    if(!$canView && function_exists('can_view_application')) {
        if(can_view_application($app)) $canView = true;
    }
}
if(!$canView) {
    $_SESSION['flash_error'] = 'Not allowed to view this application.';
    header('Location: applications.php'); exit();
}

$flash_success = $_SESSION['flash_success'] ?? null;
$flash_error = $_SESSION['flash_error'] ?? null;
unset($_SESSION['flash_success'], $_SESSION['flash_error']);

$allowed = ['pending','accepted','rejected'];
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>View Application</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="p-4">
<div class="container">
  <a href="applications.php" class="btn btn-sm btn-secondary mb-3">Back</a>
  <?php if($flash_success): ?><div class="alert alert-success"><?php echo htmlspecialchars($flash_success); ?></div><?php endif; ?>
  <?php if($flash_error): ?><div class="alert alert-danger"><?php echo htmlspecialchars($flash_error); ?></div><?php endif; ?>

  <div class="card mb-3">
    <div class="card-body">
      <h5 class="card-title"><?php echo htmlspecialchars($app['name'] ?? ''); ?> <small class="text-muted">#<?php echo $app['id']; ?></small></h5>
      <p><strong>Email:</strong> <?php echo htmlspecialchars($app['email'] ?? ''); ?></p>
      <p><strong>Phone:</strong> <?php echo htmlspecialchars($app['phone'] ?? ''); ?></p>
      <p><strong>State/Region/District:</strong> <?php echo htmlspecialchars(($app['state']??'')." / ".($app['region']??'')." / ".($app['district']??'')); ?></p>
      <p><strong>Course:</strong> <?php echo htmlspecialchars($app['course_interest'] ?? ''); ?></p>
      <p><strong>College:</strong> <?php echo htmlspecialchars($app['college_name'] ?? ''); ?></p>
      <p><strong>Assigned to:</strong> <?php if(!empty($app['assigned_admin_id'])){
          $q = $conn->prepare('SELECT name,role FROM admins WHERE id = :id'); $q->execute([':id'=>$app['assigned_admin_id']]); $r=$q->fetch(PDO::FETCH_ASSOC);
          echo htmlspecialchars($r['name'] ?? '') . ' (' . htmlspecialchars($r['role'] ?? '') . ')';
      } else echo '<em>Not assigned</em>'; ?></p>
      <p><strong>Status:</strong> <span class="badge bg-info text-dark"><?php echo htmlspecialchars($app['status'] ?? 'pending'); ?></span></p>
    </div>
  </div>

  <?php if($role === 'admin' || (!empty($app['assigned_admin_id']) && intval($app['assigned_admin_id'])===intval($me)) || (function_exists('can_view_application') && can_view_application($app))): ?>
    <div class="card">
      <div class="card-body">
        <h6>Update Status</h6>
        <form method="POST" action="update_status.php">
          <input type="hidden" name="application_id" value="<?php echo $app['id']; ?>">
          <div class="mb-2">
            <select name="status" class="form-select form-select-sm">
              <?php foreach($allowed as $s): ?>
                <option value="<?php echo $s; ?>" <?php if(($app['status'] ?? '')===$s) echo 'selected'; ?>><?php echo ucfirst($s); ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <button class="btn btn-primary btn-sm" type="submit">Save</button>
        </form>
      </div>
    </div>
  <?php endif; ?>

</div>
</body>
</html>
