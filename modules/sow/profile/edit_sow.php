<?php
require_once __DIR__ . '/../../../config/db.php';

$id = $_GET['id'];

// Fetch current sow record
$stmt = $pdo->prepare("SELECT * FROM sows WHERE sow_id = ?");
$stmt->execute([$id]);
$sow = $stmt->fetch();

if (!$sow) {
    die("❌ Sow record not found!");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ear_tag_no = $_POST['ear_tag_no'];
    $breed_line = $_POST['breed_line'];
    $date_of_birth = $_POST['date_of_birth'];
    $selection_date = $_POST['selection_date'];
    $weight_at_selection = $_POST['weight_at_selection'];
    $source = $_POST['source'];
    $boar_source = $_POST['boar_source'];
    $sow_source = $_POST['sow_source'];

    // Keep existing picture by default
    $picture = $sow['picture'];

    // 📸 If new picture uploaded, replace it
    if (!empty($_FILES['picture']['name'])) {
        $uploadDir = __DIR__ . '/../../../uploads/sows/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = time() . '_' . basename($_FILES['picture']['name']);
        $targetPath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['picture']['tmp_name'], $targetPath)) {
            // Delete old image (optional)
            if (!empty($sow['picture']) && file_exists($uploadDir . $sow['picture'])) {
                unlink($uploadDir . $sow['picture']);
            }
            $picture = $fileName;
        }
    }

    // 🧾 Update sow info
    $update = $pdo->prepare("UPDATE sows 
        SET ear_tag_no=?, breed_line=?, date_of_birth=?, selection_date=?, 
            weight_at_selection=?, source=?, boar_source=?, sow_source=?, picture=? 
        WHERE sow_id=?");
    $update->execute([$ear_tag_no, $breed_line, $date_of_birth, $selection_date, 
                      $weight_at_selection, $source, $boar_source, $sow_source, $picture, $id]);

    header("Location: list_sow.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Sow Profile</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<div class="container">
  <h2 class="mb-4">✏️ Edit Sow Profile</h2>
  <form method="POST" enctype="multipart/form-data">
    <div class="mb-3">
      <label>Ear Tag No. or Name.:</label>
      <input type="text" name="ear_tag_no" value="<?= htmlspecialchars($sow['ear_tag_no']) ?>" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Breed / Line:</label>
      <input type="text" name="breed_line" value="<?= htmlspecialchars($sow['breed_line']) ?>" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Date of Birth:</label>
      <input type="date" name="date_of_birth" value="<?= htmlspecialchars($sow['date_of_birth']) ?>" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Selection Date:</label>
      <input type="date" name="selection_date" value="<?= htmlspecialchars($sow['selection_date']) ?>" class="form-control">
    </div>
    <div class="mb-3">
      <label>Weight at Selection (kg):</label>
      <input type="number" step="0.01" name="weight_at_selection" value="<?= htmlspecialchars($sow['weight_at_selection']) ?>" class="form-control">
    </div>
    <div class="mb-3">
      <label>Source:</label>
      <select name="source" class="form-select">
        <option value="Farm-bred" <?= ($sow['source'] == 'Farm-bred') ? 'selected' : '' ?>>Farm-bred</option>
        <option value="Purchased" <?= ($sow['source'] == 'Purchased') ? 'selected' : '' ?>>Purchased</option>
      </select>
    </div>
    <div class="mb-3">
      <label>Boar Source:</label>
      <input type="text" name="boar_source" va_
