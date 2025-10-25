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
    $status = $_POST['status'];

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
        (ear_tag_no, breed_line, date_of_birth, selection_date, weight_at_selection, source, boar_source, sow_source, picture, status)
        VALUES (?,?,?,?,?,?,?,?,?,?)");
    $stmt->execute([
        $ear_tag_no, 
        $breed_line, 
        $date_of_birth, 
        $selection_date, 
        $weight_at_selection, 
        $source, 
        $boar_source, 
        $sow_source, 
        $picture, 
        $status
    ]);

    // 🧠 Get the ID of the newly added sow
    $sow_id = $pdo->lastInsertId();

    // 🩷 Run automation only if this sow is a Gilt
    if ($status === 'Gilt') {
        require_once __DIR__ . '/../gilt/roadmap_generator.php';
        generateGiltRoadmaps($pdo, $sow_id, $date_of_birth);

        // ✅ Optional: confirmation log message
        $_SESSION['flash_message'] = "✅ Gilt Roadmap successfully generated for Sow #{$ear_tag_no}";
    }

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
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<style>
  body {
    background-color: #f8f9fa;
  }
  .card {
    border: none;
    border-radius: 1rem;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
  }
  .btn-success {
    background-color: #28a745;
    border: none;
  }
  .btn-success:hover {
    background-color: #218838;
  }
  h2 {
    font-weight: 600;
    color: #2f3640;
  }
</style>
</head>
<body class="d-flex align-items-center justify-content-center vh-100">
<div class="container" style="max-width: 700px;">
  <div class="card p-4">
    <div class="text-center mb-4">
      <i class="bi bi-piggy-bank-fill fs-1 text-success"></i>
      <h2 class="mt-2">Add New Sow Profile</h2>
      <p class="text-muted mb-0">Enter details to register a new sow.</p>
    </div>

    <form method="POST" enctype="multipart/form-data">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Ear Tag No:</label>
          <input type="text" name="ear_tag_no" class="form-control" placeholder="e.g. 2025-001" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Breed / Line:</label>
          <input type="text" name="breed_line" class="form-control" placeholder="e.g. Large White" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Date of Birth:</label>
          <input type="date" name="date_of_birth" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Selection Date:</label>
          <input type="date" name="selection_date" class="form-control">
        </div>
        <div class="col-md-6">
          <label class="form-label">Weight at Selection (kg):</label>
          <input type="number" step="0.01" name="weight_at_selection" class="form-control" placeholder="e.g. 75.5">
        </div>
        <div class="col-md-6">
          <label class="form-label">Source:</label>
          <select name="source" class="form-select">
            <option value="Farm-bred">Farm-bred</option>
            <option value="Purchased">Purchased</option>
          </select>
        </div>
        <div class="col-md-6">
          <label class="form-label">Boar Source:</label>
          <input type="text" name="boar_source" class="form-control" placeholder="e.g. Farm A">
        </div>
        <div class="col-md-6">
          <label class="form-label">Sow Source:</label>
          <input type="text" name="sow_source" class="form-control" placeholder="e.g. Farm B">
        </div>

        <!-- 🧩 New: Status Selector -->
        <div class="col-md-6">
          <label class="form-label">Status:</label>
          <select name="status" class="form-select" required>
            <option value="Gilt">Gilt</option>
            <option value="Gestating">Gestating</option>
            <option value="Lactating">Lactating</option>
            <option value="Dry">Dry</option>
            <option value="Culled">Culled</option>
          </select>
        </div>

        <div class="col-12">
          <label class="form-label">Upload Picture:</label>
          <input type="file" name="picture" class="form-control" accept="image/*">
        </div>
      </div>

      <div class="d-flex justify-content-end gap-2 mt-4">
        <a href="list_sow.php" class="btn btn-secondary">
          <i class="bi bi-arrow-left-circle"></i> Cancel
        </a>
        <button type="submit" class="btn btn-success">
          <i class="bi bi-save2"></i> Save Profile
        </button>
      </div>
    </form>
  </div>
</div>
</body>
</html>
