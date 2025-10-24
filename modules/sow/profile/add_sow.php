<?php
require_once __DIR__ . '/../../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ear_tag_no = $_POST['ear_tag_no'];
    $breed_line = $_POST['breed_line'];
    $date_of_birth = $_POST['date_of_birth'];
    $selection_date = $_POST['selection_date'];
    $weight_at_selection = $_POST['weight_at_selection'];
    $source = $_POST['source'];
    $boar_source = $_POST['boar_source'];
    $sow_source = $_POST['sow_source'];

    // 📸 Handle picture upload
    $picture = null;
    if (!empty($_FILES['picture']['name'])) {
        $uploadDir = __DIR__ . '/../../../uploads/sows/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = time() . '_' . basename($_FILES['picture']['name']);
        $targetPath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['picture']['tmp_name'], $targetPath)) {
            $picture = $fileName;
        }
    }

    // 🧾 Insert new sow
    $stmt = $pdo->prepare("INSERT INTO sows 
        (ear_tag_no, breed_line, date_of_birth, selection_date, weight_at_selection, source, boar_source, sow_source, picture)
        VALUES (?,?,?,?,?,?,?,?,?)");
    $stmt->execute([$ear_tag_no, $breed_line, $date_of_birth, $selection_date, $weight_at_selection, $source, $boar_source, $sow_source, $picture]);

    header("Location: list_sow.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Sow Profile</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<div class="container">
  <h2 class="mb-4">➕ Add New Sow</h2>
  <form method="POST" enctype="multipart/form-data">
    <div class="mb-3">
      <label>Ear Tag No:</label>
      <input type="text" name="ear_tag_no" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Breed / Line:</label>
      <input type="text" name="breed_line" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Date of Birth:</label>
      <input type="date" name="date_of_birth" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Selection Date:</label>
      <input type="date" name="selection_date" class="form-control">
    </div>
    <div class="mb-3">
      <label>Weight at Selection (kg):</label>
      <input type="number" step="0.01" name="weight_at_selection" class="form-control">
    </div>
    <div class="mb-3">
      <label>Source:</label>
      <select name="source" class="form-select">
        <option value="Farm-bred">Farm-bred</option>
        <option value="Purchased">Purchased</option>
      </select>
    </div>
    <div class="mb-3">
      <label>Boar Source:</label>
      <input type="text" name="boar_source" class="form-control">
    </div>
    <div class="mb-3">
      <label>Sow Source:</label>
      <input type="text" name="sow_source" class="form-control">
    </div>
    <div class="mb-3">
      <label>Upload Picture:</label>
      <input type="file" name="picture" class="form-control" accept="image/*">
    </div>
    <button type="submit" class="btn btn-success">Save</button>
    <a href="list_sow.php" class="btn btn-secondary">Cancel</a>
  </form>
</div>
</body>
</html>
