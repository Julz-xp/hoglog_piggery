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
<title>Add Fattener Profile</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
  <div class="card shadow p-4">
    <h3 class="mb-3 text-center">🐖 Add Fattener Profile</h3>
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

      <button type="submit" class="btn btn-primary w-100">Add Record</button>
    </form>
  </div>
</div>
</body>
</html>
