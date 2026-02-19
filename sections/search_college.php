<?php
include "../config/database.php";  // your existing DB connection


// Filters
$course = $_GET['course'] ?? '';
$state  = $_GET['state'] ?? '';
$viewId = $_GET['view'] ?? '';

// Pagination
$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = ($page < 1) ? 1 : $page;
$start = ($page - 1) * $limit;

// Base Query
$query = "SELECT * FROM colleges_list WHERE 1=1";
$countQuery = "SELECT COUNT(*) FROM colleges_list WHERE 1=1";
$params = [];

if($course != ''){
    $query .= " AND courses LIKE :course";
    $countQuery .= " AND courses LIKE :course";
    $params[':course'] = "%$course%";
}

if($state != ''){
    $query .= " AND state = :state";
    $countQuery .= " AND state = :state";
    $params[':state'] = $state;
}

$query .= " ORDER BY id ASC LIMIT $start, $limit";

$stmt = $conn->prepare($query);
$stmt->execute($params);
$colleges = $stmt->fetchAll(PDO::FETCH_ASSOC);

$countStmt = $conn->prepare($countQuery);
$countStmt->execute($params);
$totalRecords = $countStmt->fetchColumn();
$totalPages = ceil($totalRecords / $limit);
?>

<!DOCTYPE html>
<html>
<head>
<title>College List</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
    background-color:#f8f9fa;
}
</style>

</head>



<div class="container mt-4">
<h2 class="mb-4">College List</h2>

<!-- Search Form -->
<form method="GET" class="row g-3 mb-4">

<div class="col-md-4">
<select name="course" class="form-select">
<option value="">Select Course</option>
<option value="B.Ed" <?= ($course=='B.Ed')?'selected':'' ?>>B.Ed</option>
<option value="D.El.Ed" <?= ($course=='D.El.Ed')?'selected':'' ?>>D.El.Ed</option>
<option value="M.Ed" <?= ($course=='M.Ed')?'selected':'' ?>>M.Ed</option>
<option value="D.Pharmacy" <?= ($course=='D.Pharmacy')?'selected':'' ?>>D.Pharmacy</option>
</select>
</div>

<div class="col-md-4">
<select name="state" class="form-select">
<option value="">Select State</option>
<option value="Haryana" <?= ($state=='Haryana')?'selected':'' ?>>Haryana</option>
<option value="Delhi" <?= ($state=='Delhi')?'selected':'' ?>>Delhi</option>
<option value="Madhya Pradesh" <?= ($state=='Madhya Pradesh')?'selected':'' ?>>Madhya Pradesh</option>
</select>
</div>

<div class="col-md-2">
<button type="submit" class="btn btn-primary w-100">Search</button>
</div>

<div class="col-md-2">
<a href="?" class="btn btn-secondary w-100">Reset</a>
</div>

</form>

<!-- View Details -->
<?php if($viewId): 
    $viewStmt = $conn->prepare("SELECT * FROM colleges WHERE id = ?");
    $viewStmt->execute([$viewId]);
    $college = $viewStmt->fetch(PDO::FETCH_ASSOC);
    if($college):
?>
<div class="card mb-4">
<div class="card-header bg-info text-white">
College Details
</div>
<div class="card-body row">
<div class="col-md-4">
<img src="college_images/<?= $college['college_image'] ?>" 
class="img-fluid rounded">
</div>
<div class="col-md-8">
<h4><?= $college['college_name'] ?></h4>
<p><strong>Contact:</strong> <?= $college['contact'] ?></p>
<p><strong>State:</strong> <?= $college['state'] ?></p>
<p><strong>City:</strong> <?= $college['city'] ?></p>
<p><strong>Courses:</strong> <?= $college['courses'] ?></p>
<p><strong>Added On:</strong> 
<?= date("d M Y", strtotime($college['created_at'])) ?></p>
</div>
</div>
</div>
<?php endif; endif; ?>

<!-- Table -->
<div class="table-responsive">
<table class="table table-bordered table-striped text-center align-middle">
<thead class="table-dark">
<tr>
<th>ID</th>
<th>Image</th>
<th>Name</th>
<th>Contact</th>
<th>State</th>
<th>City</th>
<th>Courses</th>
<th>Added On</th>
<th>Action</th>
</tr>
</thead>
<tbody>

<?php if(count($colleges) > 0): ?>
<?php foreach($colleges as $row): ?>
<tr>
<td><?= $row['id'] ?></td>
<td>
<img src="college_images/<?= $row['college_image'] ?>" 
width="80" height="60" class="rounded">
</td>
<td><?= $row['college_name'] ?></td>
<td><?= $row['contact'] ?></td>
<td><?= $row['state'] ?></td>
<td><?= $row['city'] ?></td>
<td><?= $row['courses'] ?></td>
<td><?= date("d M Y", strtotime($row['created_at'])) ?></td>
<td>
<a href="?view=<?= $row['id'] ?>&course=<?= $course ?>&state=<?= $state ?>&page=<?= $page ?>" 
class="btn btn-info btn-sm">View</a>
</td>
</tr>
<?php endforeach; ?>
<?php else: ?>
<tr><td colspan="9">No Records Found</td></tr>
<?php endif; ?>

</tbody>
</table>
</div>

<!-- Pagination -->
<!-- Pagination -->
<nav aria-label="Page navigation">
  <ul class="pagination justify-content-center">

    <!-- Previous Page -->
    <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
      <a class="page-link" href="?page=<?= ($page-1) ?>&course=<?= $course ?>&state=<?= $state ?>" aria-label="Previous">
        <span aria-hidden="true">&laquo;</span>
      </a>
    </li>

    <!-- Page Numbers -->
    <?php 
    // Optional: show only 5 pages around current
    $startPage = max(1, $page - 2);
    $endPage = min($totalPages, $page + 2);
    for($i = $startPage; $i <= $endPage; $i++): 
    ?>
      <li class="page-item <?= ($i==$page)?'active':'' ?>">
        <a class="page-link" href="?page=<?= $i ?>&course=<?= $course ?>&state=<?= $state ?>">
          <?= $i ?>
        </a>
      </li>
    <?php endfor; ?>

    <!-- Next Page -->
    <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : '' ?>">
      <a class="page-link" href="?page=<?= ($page+1) ?>&course=<?= $course ?>&state=<?= $state ?>" aria-label="Next">
        <span aria-hidden="true">&raquo;</span>
      </a>
    </li>

  </ul>
</nav>


</div>

<body>