<?php
require_once __DIR__ . '/../../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("
        INSERT INTO batch_records
        (batch_no, building_position, num_pigs_total, num_male, num_female, breed, birth_date,
         avg_birth_weight, source_sow, source_boar, weaning_date, avg_weaning_weight,
         expected_market_date, status, remarks)
        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
    ");

    $stmt->execute([
        $_POST['batch_no'],
        $_POST['building_position'] ?: null,
        $_POST['num_pigs_total'] ?: null,
        $_POST['num_male'] ?: null,
        $_POST['num_female'] ?: null,
        $_POST['breed'] ?: null,
        $_POST['birth_date'] ?: null,
        $_POST['avg_birth_weight'] ?: null,
        $_POST['source_sow'] ?: null,
        $_POST['source_boar'] ?: null,
        $_POST['weaning_date'] ?: null,
        $_POST['avg_weaning_weight'] ?: null,
        $_POST['expected_market_date'] ?: null,
        $_POST['status'] ?: 'Active',
        $_POST['remarks'] ?: null
    ]);

    header("Location: list_batches.php?success=1");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Batch | HogLog</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
    
    body { background: #f8f9fa; font-family: 'Poppins', sans-serif; }
    .sidebar { height: 100vh; width: 240px; background: #212529; position: fixed; top: 0; left: 0; color: #fff; padding-top: 25px; overflow-y:auto; }
    .sidebar a { display: block; padding: 10px 20px; color: #adb5bd; text-decoration: none; transition: .2s; font-size: 14px; }
    .sidebar a:hover, .sidebar a.active { background: #0d6efd; color: #fff; }
    .sidebar .submenu a { padding-left: 45px; font-size: 13px; }
    .topbar { height: 60px; background: white; border-bottom: 1px solid #dee2e6; margin-left: 240px; display:flex; align-items:center; padding:0 25px; }
    .content { margin-left: 260px; padding: 25px; }
    .card { border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
    
</style>

</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <h4 class="text-center mb-4">🐖 HogLog</h4>

    <a href="../dashboard/batch_fattener.php"><i class="fa fa-chart-bar me-2"></i> Dashboard</a>

    <a href="add_batch.php" class="active"><i class="fa fa-plus-circle me-2"></i> Add Batch</a>
    
    <a href="list_batches.php"><i class="fa fa-list me-2"></i> Batch List</a>
    <a href="/hoglog_piggery/modules/batches/feed/list_feed.php"><i class="fa-solid fa-wheat-awn"></i> Feed Records</a>
    <a href="../growth/growth_summary.php"><i class="fa fa-chart-line me-2"></i> Growth Summary</a>
    <a href="../mortality/mortality.php"><i class="fa fa-skull-crossbones me-2"></i> Mortality</a>
    <a href="../sales/sales.php"><i class="fa fa-peso-sign me-2"></i> Sales</a>
</div>

<!-- TOPBAR -->
<div class="topbar">
    <h5 class="m-0">Add New Batch</h5>
</div>

<!-- CONTENT -->
<div class="content">
    <div class="card p-4">

        <form method="POST" class="row g-3">

            <div class="col-md-4">
                <label class="form-label">Batch No</label>
                <input type="text" name="batch_no" class="form-control" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Building / Pen</label>
                <input type="text" name="building_position" class="form-control">
            </div>

            <div class="col-md-4">
                <label class="form-label">Breed</label>
                <input type="text" name="breed" class="form-control">
            </div>

            <div class="col-md-4">
                <label class="form-label">Total Pigs</label>
                <input type="number" name="num_pigs_total" min="0" class="form-control">
            </div>

            <div class="col-md-4">
                <label class="form-label">Male</label>
                <input type="number" name="num_male" min="0" class="form-control">
            </div>

            <div class="col-md-4">
                <label class="form-label">Female</label>
                <input type="number" name="num_female" min="0" class="form-control">
            </div>

            <div class="col-md-4">
                <label class="form-label">Birth Date</label>
                <input type="date" name="birth_date" class="form-control">
            </div>

            <div class="col-md-4">
                <label class="form-label">Avg Birth Weight (kg)</label>
                <input type="number" step="0.01" name="avg_birth_weight" class="form-control">
            </div>

            <div class="col-md-4">
                <label class="form-label">Weaning Date</label>
                <input type="date" name="weaning_date" class="form-control">
            </div>

            <div class="col-md-4">
                <label class="form-label">Avg Weaning Weight (kg)</label>
                <input type="number" step="0.01" name="avg_weaning_weight" class="form-control">
            </div>

            <div class="col-md-4">
                <label class="form-label">Expected Market Date</label>
                <input type="date" name="expected_market_date" class="form-control">
            </div>

            <div class="col-md-4">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="Active">Active</option>
                    <option value="Sold">Sold</option>
                    <option value="Closed">Closed</option>
                </select>
            </div>

            <div class="col-12">
                <label class="form-label">Remarks</label>
                <textarea name="remarks" rows="3" class="form-control"></textarea>
            </div>

            <div class="col-12 mt-3 text-end">
                <a href="list_batches.php" class="btn btn-secondary">Cancel</a>
                <button class="btn btn-primary">Save Batch</button>
            </div>

        </form>
    </div>
</div>

</body>
</html>
