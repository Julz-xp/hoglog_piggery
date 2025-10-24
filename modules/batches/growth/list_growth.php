<?php
require_once __DIR__ . '/../../../config/db.php';

// Fetch growth summary data
$records = $pdo->query("
    SELECT g.*, b.batch_no
    FROM batch_growth_summary g
    JOIN batch_records b ON g.batch_id = b.batch_id
    ORDER BY g.growth_id DESC
")->fetchAll(PDO::FETCH_ASSOC);

// Fetch recent activities for the calendar (format date)
$activities = $pdo->query("
    SELECT CONCAT('Growth update for batch ', b.batch_no) AS title, DATE(g.date_recorded) AS start
    FROM batch_growth_summary g
    JOIN batch_records b ON g.batch_id = b.batch_id
    ORDER BY g.growth_id DESC
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Growth Summary | HogLog</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css' rel='stylesheet' />
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.js'></script>
<style>
body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; }
.sidebar { height: 100vh; width: 240px; position: fixed; top: 0; left: 0; background-color: #212529; color: #fff; padding-top: 25px; box-shadow: 2px 0 8px rgba(0,0,0,0.1); }
.sidebar h4 { text-align: center; font-weight: 600; margin-bottom: 25px; }
.sidebar a { display: block; color: #adb5bd; padding: 12px 20px; text-decoration: none; transition: all 0.2s; }
.sidebar a:hover, .sidebar a.active { background-color: #0d6efd; color: white; }
.topbar { margin-left: 240px; height: 60px; background: #fff; border-bottom: 1px solid #dee2e6; display: flex; align-items: center; justify-content: space-between; padding: 0 25px; position: sticky; top: 0; z-index: 10; }
.topbar h5 { margin: 0; font-weight: 600; }
.content { margin-left: 250px; padding: 25px; }
.card { border: none; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); transition: transform 0.3s; }
.card:hover { transform: translateY(-5px); }
.calendar-container, .activity-container { background: white; border-radius: 12px; padding: 20px; margin-top: 30px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
th { background-color: #0d6efd; color: white; }
</style>
</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <h4>🐖 HogLog</h4>
    <a href="../dashboard/batch_fattener.php"><i class="fa-solid fa-chart-line"></i> Dashboard</a>
    <a href="../profile/add_batch.php"><i class="fa-solid fa-folder-plus"></i> Add Batch</a>
    <a href="../profile/list_batch.php"><i class="fa-solid fa-list"></i> Batch List</a>
    <a href="../feed_records/feed_records.php"><i class="fa-solid fa-wheat-awn"></i> Feed Records</a>
    <a href="#" class="active"><i class="fa-solid fa-chart-simple"></i> Growth Summary</a>
    <a href="../mortality/mortality.php"><i class="fa-solid fa-skull-crossbones"></i> Mortality</a>
    <a href="../sales/sales.php"><i class="fa-solid fa-hand-holding-dollar"></i> Sales</a>
</div>

<!-- TOPBAR -->
<div class="topbar">
    <h5><i class="fa-solid fa-chart-simple"></i> Growth Summary</h5>
    <span class="text-secondary">HogLog Smart Piggery System</span>
</div>

<!-- CONTENT -->
<div class="content">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold">Batch Growth Records</h4>
        <a href="add_growth.php" class="btn btn-primary btn-sm"><i class="fa-solid fa-plus"></i> Add Growth Record</a>
    </div>

    <!-- Alerts -->
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success"><i class="fa-solid fa-check"></i> Record added successfully.</div>
    <?php endif; ?>
    <?php if (isset($_GET['updated'])): ?>
        <div class="alert alert-info"><i class="fa-solid fa-pen-to-square"></i> Record updated successfully.</div>
    <?php endif; ?>
    <?php if (isset($_GET['deleted'])): ?>
        <div class="alert alert-danger"><i class="fa-solid fa-trash"></i> Record deleted.</div>
    <?php endif; ?>

    <!-- Calendar & Recent Activities -->
    <div class="row g-4">
        <div class="col-md-6">
            <div class="calendar-container">
                <h5 class="mb-3"><i class="fa-solid fa-calendar"></i> Calendar</h5>
                <div id="calendar"></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="activity-container">
                <h5 class="mb-3"><i class="fa-solid fa-list-check"></i> Recent Activities</h5>
                <ul class="list-group">
                    <?php if ($activities): ?>
                        <?php foreach($activities as $act): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <?= htmlspecialchars($act['title']) ?>
                                <span class="badge bg-primary rounded-pill"><?= $act['start'] ?></span>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li class="list-group-item text-center">No recent activities</li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>

    <!-- Growth Records Table -->
    <div class="card p-3 shadow-sm mt-4">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Batch</th>
                    <th>Stage</th>
                    <th>Initial Wt (kg)</th>
                    <th>Final Wt (kg)</th>
                    <th>ADG</th>
                    <th>FCR</th>
                    <th width="150">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($records): foreach ($records as $r): ?>
                    <tr>
                        <td><?= $r['growth_id'] ?></td>
                        <td><?= htmlspecialchars($r['batch_no']) ?></td>
                        <td><?= htmlspecialchars($r['stage']) ?></td>
                        <td><?= htmlspecialchars($r['avg_initial_weight']) ?></td>
                        <td><?= htmlspecialchars($r['avg_final_weight']) ?></td>
                        <td><?= htmlspecialchars($r['avg_adg']) ?></td>
                        <td><?= htmlspecialchars($r['avg_fcr']) ?></td>
                        <td>
                            <a href="view_growth.php?id=<?= $r['growth_id'] ?>" class="btn btn-sm btn-info text-white"><i class="fa-solid fa-eye"></i></a>
                            <a href="edit_growth.php?id=<?= $r['growth_id'] ?>" class="btn btn-sm btn-warning text-white"><i class="fa-solid fa-pen"></i></a>
                            <a href="delete_growth.php?id=<?= $r['growth_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this record?')"><i class="fa-solid fa-trash"></i></a>
                        </td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr><td colspan="8" class="text-center">No growth records found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- JS Calendar -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: 400,
        events: [
            <?php foreach($activities as $act): ?>
                { title: '<?= addslashes($act['title']) ?>', start: '<?= $act['start'] ?>' },
            <?php endforeach; ?>
        ]
    });
    calendar.render();
});
</script>

</body>
</html>
