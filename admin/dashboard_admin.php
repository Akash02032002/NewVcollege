<?php
// Admin dashboard (restored full page)
include __DIR__ . '/../includes/auth.php';
include __DIR__ . '/../config/database.php';
require_role('admin');

// AJAX API: handle status update / delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['action'])) {
	header('Content-Type: application/json');
	$action = $_POST['action'];
	if ($action === 'update_status') {
		$id = intval($_POST['id'] ?? 0);
		$status = trim($_POST['status'] ?? '');
		$allowed = ['pending','reviewed','accepted','rejected'];
		if ($id > 0 && in_array($status, $allowed)) {
			$stmt = $conn->prepare('UPDATE applications SET status = :status WHERE id = :id');
			$stmt->execute([':status'=>$status, ':id'=>$id]);
			echo json_encode(['success'=>true]);
		} else {
			echo json_encode(['success'=>false,'message'=>'Invalid data']);
		}
		exit;
	}
	if ($action === 'delete') {
		$id = intval($_POST['id'] ?? 0);
		if ($id > 0) {
			$stmt = $conn->prepare('DELETE FROM applications WHERE id = :id');
			$stmt->execute([':id'=>$id]);
			echo json_encode(['success'=>true]);
		} else {
			echo json_encode(['success'=>false,'message'=>'Invalid id']);
		}
		exit;
	}
	echo json_encode(['success'=>false,'message'=>'Unknown action']);
	exit;
}

// Fetch all applications
$stmt = $conn->query('SELECT * FROM applications ORDER BY created_at DESC');
$applications = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalApps    = count($applications);
$pendingApps  = count(array_filter($applications, fn($a)=>(($a['status'] ?? '')==='pending')));
$acceptedApps = count(array_filter($applications, fn($a)=>(($a['status'] ?? '')==='accepted')));
$rejectedApps = count(array_filter($applications, fn($a)=>(($a['status'] ?? '')==='rejected')));

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Admin Dashboard - Top Colleges India</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
	<style>
		/* minimal styles for admin dashboard */
		*{box-sizing:border-box} body{font-family:Segoe UI, Tahoma, Geneva, Verdana, sans-serif;background:#f0f2f5;margin:0}
		.sidebar{position:fixed;top:0;left:0;width:250px;height:100vh;background:linear-gradient(180deg,#1a1a2e,#16213e);color:#fff;padding:20px;overflow:auto}
		.main-content{margin-left:250px;padding:20px}
		.top-bar{display:flex;justify-content:space-between;align-items:center;background:#fff;padding:15px;border-radius:10px;box-shadow:0 2px 8px rgba(0,0,0,0.06);margin-bottom:25px}
		.stat-card{background:#fff;border-radius:12px;padding:22px;box-shadow:0 2px 8px rgba(0,0,0,0.06)}
		.table-card{background:#fff;border-radius:12px;padding:25px;box-shadow:0 2px 8px rgba(0,0,0,0.06)}
		.search-box{position:relative;width:280px}
		.empty-state{text-align:center;padding:60px 20px;color:#90a4ae}
		@media(max-width:768px){.sidebar{width:0}.main-content{margin-left:0;padding:15px}.search-box{width:100%}}
	</style>
</head>
<body>

	<div class="sidebar">
		<div class="brand"><h3><i class="bi bi-mortarboard-fill"></i> Top Colleges</h3><small>Admin Panel</small></div>
		<nav class="mt-3">
			<a href="dashboard.php" class="nav-link active"><i class="bi bi-grid-1x2-fill"></i> Dashboard</a>
			<a href="manage_admins.php" class="nav-link"><i class="bi bi-people-fill"></i> Manage Admins</a>
			<a href="applications.php" class="nav-link"><i class="bi bi-file-earmark-text-fill"></i> Applications</a>
			<a href="../index.php" class="nav-link"><i class="bi bi-house-fill"></i> View Website</a>
			<a href="../logout.php" class="nav-link"><i class="bi bi-box-arrow-left"></i> Logout</a>
		</nav>
	</div>

	<div class="main-content">
		<div class="top-bar">
			<h4><i class="bi bi-grid-1x2-fill me-2"></i>Dashboard</h4>
			<div class="admin-info"><span class="text-muted" style="font-size:14px">Welcome,</span> <strong><?php echo htmlspecialchars($_SESSION['user']); ?></strong></div>
		</div>

		<div class="row g-4 mb-4">
			<div class="col-xl-3 col-md-6"><div class="stat-card"><div class="icon-box bg-primary-soft"><i class="bi bi-file-earmark-text-fill"></i></div><h2><?php echo $totalApps; ?></h2><p>Total Applications</p></div></div>
			<div class="col-xl-3 col-md-6"><div class="stat-card"><div class="icon-box bg-warning-soft"><i class="bi bi-clock-fill"></i></div><h2><?php echo $pendingApps; ?></h2><p>Pending</p></div></div>
			<div class="col-xl-3 col-md-6"><div class="stat-card"><div class="icon-box bg-success-soft"><i class="bi bi-check-circle-fill"></i></div><h2><?php echo $acceptedApps; ?></h2><p>Accepted</p></div></div>
			<div class="col-xl-3 col-md-6"><div class="stat-card"><div class="icon-box bg-danger-soft"><i class="bi bi-x-circle-fill"></i></div><h2><?php echo $rejectedApps; ?></h2><p>Rejected</p></div></div>
		</div>

		<div class="table-card">
			<div class="card-header-custom d-flex justify-content-between align-items-center mb-3">
				<h5><i class="bi bi-people-fill me-2"></i>Admission Applications</h5>
				<div class="search-box"><i class="bi bi-search"></i><input type="text" id="searchInput" placeholder="Search by name, email, phone..."></div>
			</div>

			<?php if($totalApps>0): ?>
			<div class="table-responsive"><table class="table table-sm" id="appTable"><thead><tr><th>#</th><th>Name</th><th>Email</th><th>Phone</th><th>State</th><th>Course</th><th>College</th><th>Status</th><th>Applied On</th><th>Actions</th></tr></thead><tbody>
			<?php foreach($applications as $i=>$app): ?>
				<tr id="row-<?php echo $app['id']; ?>">
					<td><?php echo $i+1; ?></td>
					<td><?php echo htmlspecialchars($app['name']); ?></td>
					<td><?php echo htmlspecialchars($app['email']); ?></td>
					<td><?php echo htmlspecialchars($app['phone']); ?></td>
					<td><?php echo htmlspecialchars($app['state']?:'—'); ?></td>
					<td><?php echo htmlspecialchars($app['course_interest']?:'—'); ?></td>
					<td><?php echo htmlspecialchars($app['college_name']?:'—'); ?></td>
					<td>
						<select class="status-select form-select form-select-sm" data-id="<?php echo $app['id']; ?>" onchange="updateStatus(this)">
							<option value="pending" <?php echo (($app['status']??'')==='pending')?'selected':''; ?>>Pending</option>
							<option value="reviewed" <?php echo (($app['status']??'')==='reviewed')?'selected':''; ?>>Reviewed</option>
							<option value="accepted" <?php echo (($app['status']??'')==='accepted')?'selected':''; ?>>Accepted</option>
							<option value="rejected" <?php echo (($app['status']??'')==='rejected')?'selected':''; ?>>Rejected</option>
						</select>
					</td>
					<td><?php echo date('d M Y, h:i A', strtotime($app['created_at'])); ?></td>
					<td>
						<button class="btn btn-sm btn-outline-primary" onclick='viewDetails(<?php echo json_encode($app); ?>)'><i class="bi bi-eye-fill"></i></button>
						<button class="btn btn-sm btn-outline-danger" onclick="deleteApp(<?php echo $app['id']; ?>)"><i class="bi bi-trash-fill"></i></button>
					</td>
				</tr>
			<?php endforeach; ?></tbody></table></div>
			<?php else: ?><div class="empty-state text-center py-5"><i class="bi bi-inbox" style="font-size:36px"></i><h5 class="mt-3">No Applications Yet</h5><p>Applications submitted via the website will appear here.</p></div><?php endif; ?>
		</div>
	</div>

	<!-- Detail Modal -->
	<div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true"><div class="modal-dialog modal-dialog-centered"><div class="modal-content"><div class="modal-header bg-dark text-white"><h5 class="modal-title">Application Details</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div><div class="modal-body" id="detailBody"></div></div></div></div>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
	<script>
	// search
	document.getElementById('searchInput').addEventListener('input', function(){const q=this.value.toLowerCase();document.querySelectorAll('#appTable tbody tr').forEach(r=>r.style.display=r.textContent.toLowerCase().includes(q)?'':'none');});
	function updateStatus(el){const id=el.dataset.id,status=el.value;fetch(location.pathname,{method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:'action=update_status&id='+id+'&status='+encodeURIComponent(status)}).then(r=>r.json()).then(d=>{if(d.success)showToast('Status updated','success');else showToast(d.message||'Error','danger')});}
	function deleteApp(id){if(!confirm('Are you sure?'))return;fetch(location.pathname,{method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:'action=delete&id='+id}).then(r=>r.json()).then(d=>{if(d.success){const row=document.getElementById('row-'+id);if(row)row.remove();showToast('Deleted','success')}else showToast(d.message||'Error','danger')});}
	function viewDetails(app){const html=`<div class="detail-row"><div class="detail-label">Full Name</div><div class="detail-value">${escHtml(app.name)}</div></div><div class="detail-row"><div class="detail-label">Email</div><div class="detail-value">${escHtml(app.email)}</div></div><div class="detail-row"><div class="detail-label">Phone</div><div class="detail-value">${escHtml(app.phone)}</div></div><div class="detail-row"><div class="detail-label">State</div><div class="detail-value">${escHtml(app.state||'—')}</div></div><div class="detail-row"><div class="detail-label">Course</div><div class="detail-value">${escHtml(app.course_interest||'—')}</div></div><div class="detail-row"><div class="detail-label">College</div><div class="detail-value">${escHtml(app.college_name||'—')}</div></div><div class="detail-row"><div class="detail-label">Status</div><div class="detail-value">${escHtml(app.status||'—')}</div></div><div class="detail-row"><div class="detail-label">Applied On</div><div class="detail-value">${app.created_at}</div></div>`;document.getElementById('detailBody').innerHTML=html;new bootstrap.Modal(document.getElementById('detailModal')).show();}
	function escHtml(s){const d=document.createElement('div');d.textContent=s;return d.innerHTML}
	function showToast(msg,type){const t=document.createElement('div');t.style.cssText='position:fixed;top:20px;right:20px;z-index:9999;padding:12px 24px;border-radius:8px;color:#fff;font-weight:600';t.style.background=type==='success'?'#2e7d32':'#c62828';t.textContent=msg;document.body.appendChild(t);setTimeout(()=>{t.style.opacity='0';setTimeout(()=>t.remove(),500)},2000);}
	</script>
</body>
</html>

