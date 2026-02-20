<?php
include "../config/database.php";
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Fetch all colleges
$stmt = $conn->query("SELECT * FROM colleges ORDER BY created_at DESC");
$colleges = $stmt->fetchAll(PDO::FETCH_ASSOC);
$totalColleges = count($colleges);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>College List - Top College</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
</head>
<body class="list-body">

<!-- Top Bar -->
<div class="list-topbar">
    <a href="../index.php" class="brand-link">
        <i class="bi bi-mortarboard-fill"></i>
        <span>Top College</span>
    </a>
    <div class="nav-links">
        <a href="../index.php"><i class="bi bi-house-fill"></i> Home</a>
        <a href="../register_college.php"><i class="bi bi-plus-circle"></i> Register</a>
        <a href="list.php" class="active"><i class="bi bi-list-ul"></i> College List</a>
    </div>
</div>

<!-- Main Content -->
<div class="list-container">

    <!-- Header -->
    <div class="list-header">
        <h2><i class="bi bi-building"></i> Registered Colleges</h2>
        <div class="list-search">
            <i class="bi bi-search"></i>
            <input type="text" id="clgSearch" placeholder="Search college, city, state, course...">
        </div>
    </div>

    <!-- Stat Bar -->
    <div class="stat-bar">
        <div class="stat-pill">
            <div class="stat-icon blue"><i class="bi bi-building"></i></div>
            <div>
                <div class="stat-num"><?= $totalColleges ?></div>
                <div class="stat-label">Total Colleges</div>
            </div>
        </div>
    </div>

    <!-- College Table -->
    <div class="clg-table-card">
        <?php if ($totalColleges > 0): ?>
        <table class="clg-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>College Image</th>
                    <th>College Name</th>
                    <th>Contact</th>
                    <th>State</th>
                    <th>City</th>
                    <th>Courses</th>
                    <th>Added On</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="clgTableBody">
                <?php foreach ($colleges as $i => $clg): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td>
                        <?php if ($clg['college_image']): ?>
                            <img src="../uploads/colleges/<?= htmlspecialchars($clg['college_image']) ?>" class="clg-img-thumb" alt="">
                        <?php else: ?>
                            <div class="clg-img-thumb d-flex align-items-center justify-content-center" style="background:#e3f2fd; color:#1565c0; font-size:1.2rem;">
                                <i class="bi bi-building"></i>
                            </div>
                        <?php endif; ?>
                    </td>
                    <td class="clg-name-cell"><strong><?= htmlspecialchars($clg['college_name']) ?></strong></td>
                    <td><?= htmlspecialchars($clg['contact']) ?></td>
                    <td><?= htmlspecialchars($clg['state']) ?></td>
                    <td><?= htmlspecialchars($clg['city']) ?></td>
                    <td>
                        <div class="course-badges">
                            <?php 
                            $courses = array_map('trim', explode(',', $clg['courses']));
                            foreach ($courses as $course): ?>
                                <span class="course-badge"><?= htmlspecialchars($course) ?></span>
                            <?php endforeach; ?>
                        </div>
                    </td>
                    <td><?= date('d M Y', strtotime($clg['created_at'])) ?></td>
                    <td>
                        <button class="btn-view-clg" onclick="viewCollege(<?= $clg['id'] ?>)">
                            <i class="bi bi-eye"></i> View
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="empty-state-clg">
            <i class="bi bi-building-slash"></i>
            <h5>No Colleges Registered Yet</h5>
            <p>Be the first to <a href="../register_college.php" style="color:#1565c0;">register a college</a>!</p>
        </div>
        <?php endif; ?>
    </div>

</div>

<!-- View College Detail Modal -->
<div class="modal fade" id="viewCollegeModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:14px; border:none;">
            <div class="modal-header" style="background:linear-gradient(135deg,#1a1a2e,#16213e); color:#fff; border-radius:14px 14px 0 0; padding:16px 20px;">
                <h6 class="modal-title" style="font-weight:700; font-size:.95rem;">
                    <i class="bi bi-building"></i> College Details
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="clgDetailContent" style="padding:20px;">
                <!-- Filled by JS -->
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="script.js"></script>
</body>
</html>
