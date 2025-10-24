<?php 
require_once __DIR__ . '/../../../config/db.php'; // Adjust path to your db.php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ear_tag_no = $_POST['ear_tag_no'];
    $batch_no = $_POST['batch_no'];
    $sex = $_POST['sex'];
    $breed_line = $_POST['breed_line'];
    $birth_date = $_POST['birth_date'];
    $weaning_date = $_POST['weaning_date'];
    $weaning_weight = $_POST['weaning_weight'];
    $status = $_POST['status'];
    $notes = $_POST['notes'];

    $stmt = $pdo->prepare("INSERT INTO fattener_records 
        (ear_tag_no, batch_no, sex, breed_line, birth_date, weaning_date, weaning_weight, status, notes)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$ear_tag_no, $batch_no, $sex, $breed_line, $birth_date, $weaning_date, $weaning_weight, $status, $notes]);

    header("Location: list_profile.php?success=1");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>🐖 Add Fattener Profile</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
    font-family: "Poppins", sans-serif;
    background: linear-gradient(135deg, #e3f2fd, #bbdefb);
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px;
}

.card {
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(15px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.1);
    border-radius: 16px;
    width: 100%;
    max-width: 700px;
    padding: 30px;
    animation: fadeIn 0.5s ease;
}

.card h3 {
    color: #0288d1;
    font-weight: 700;
    margin-bottom: 20px;
    text-align: center;
}

.btn-primary, .btn-secondary {
    border-radius: 10px;
    font-weight: 600;
    transition: 0.3s ease;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #0288d1, #03a9f4);
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(0,0,0,0.2);
}

.btn-secondary:hover {
    background: #b0bec5;
    transform: translateY(-2px);
}

@keyframes fadeIn {
  from {opacity: 0; transform: translateY(20px);}
  to {opacity: 1; transform: translateY(0);}
}

@media (max-width: 576px) {
    .row > .col-md-6, .row > .col-md-4 { margin-bottom: 15px; }
}
</style>
</head>
<body>

<div class="card">
    <h3>🐖 Add Fattener Profile</h3>
    <form method="POST">
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Ear Tag No.</label>
                <input type="text" name="ear_tag_no" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Batch No.</label>
                <input type="text" name="batch_no" class="form-control" required>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <label class="form-label">Sex</label>
                <select name="sex" class="form-select" required>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Breed Line</label>
                <input type="text" name="breed_line" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="Active">Active</option>
                    <option value="Market Ready">Market Ready</option>
                    <option value="Sold">Sold</option>
                    <option value="Dead">Dead</option>
                </select>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Birth Date</label>
                <input type="date" name="birth_date" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Weaning Date</label>
                <input type="date" name="weaning_date" class="form-control" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Weaning Weight (kg)</label>
            <input type="number" step="0.01" name="weaning_weight" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Notes</label>
            <textarea name="notes" class="form-control" rows="3"></textarea>
        </div>

        <div class="d-flex justify-content-between">
            <a href="http://localhost/hoglog_piggery/modules/batches/profile/view_batch.php?id=2" class="btn btn-secondary">← Back to Batch</a>
            <button type="submit" class="btn btn-primary">Add Record</button>
        </div>
    </form>
</div>

</body>
</html>
