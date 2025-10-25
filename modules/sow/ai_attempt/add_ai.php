<?php
require_once __DIR__ . '/../../../config/db.php';

$sow_id = $_GET['sow_id'] ?? null;

// 🐷 Fetch sow info for display
$sow = null;
if ($sow_id) {
  $stmt = $pdo->prepare("SELECT sow_id, ear_tag_no, breed_line, status FROM sows WHERE sow_id = ?");
  $stmt->execute([$sow_id]);
  $sow = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sow_id = $_POST['sow_id'];
    $heat_detection_date = $_POST['heat_detection_date'];
    $ai_date = $_POST['ai_date'];
    $breeding_type = $_POST['breeding_type'];
    $boar_source = $_POST['boar_source'];
    $farm_vet = $_POST['farm_vet'];
    $pregnancy_check_date = $_POST['pregnancy_check_date'];
    $confirmation = $_POST['confirmation'];
    $cost = $_POST['cost'];
    $remarks = $_POST['remarks'];

    // 🧾 Insert A.I. attempt record
    $stmt = $pdo->prepare("
        INSERT INTO ai_attempt
        (sow_id, heat_detection_date, ai_date, breeding_type, boar_source, farm_vet, pregnancy_check_date, confirmation, cost, remarks)
        VALUES (?,?,?,?,?,?,?,?,?,?)
    ");
    $stmt->execute([
        $sow_id, $heat_detection_date, $ai_date, $breeding_type,
        $boar_source, $farm_vet, $pregnancy_check_date, $confirmation,
        $cost, $remarks
    ]);

    // 🩷 If pregnancy confirmed → validate and trigger automation
    if ($confirmation === 'Positive') {
        $today = new DateTime();
        $aiDateObj = new DateTime($ai_date);
        $diff = $today->diff($aiDateObj)->days;

        // 🚫 Block early confirmation (before 18 days)
        if ($diff < 18) {
            echo "<script>
              alert('⚠️ Pregnancy can only be confirmed between 18 to 25 days after A.I. date.');
              window.history.back();
            </script>";
            exit;
        }

        // ✅ Proceed with automation if valid
        $ai_id = $pdo->lastInsertId();
        $expected_farrowing = date('Y-m-d', strtotime($ai_date . ' +114 days'));

        // 1️⃣ Update sow status to Gestating
        $pdo->prepare("UPDATE sows SET status = 'Gestating' WHERE sow_id = ?")->execute([$sow_id]);

        // 2️⃣ Add gestation record
        $insertGest = $pdo->prepare("
            INSERT INTO gestating_stage (sow_id, ai_id, breeding_date, expected_farrowing_date, notes)
            VALUES (?, ?, ?, ?, ?)
        ");
        $insertGest->execute([$sow_id, $ai_id, $ai_date, $expected_farrowing, 'Auto-generated after confirmed pregnancy.']);

        // 3️⃣ Run gestation roadmap automation
        require_once __DIR__ . '/../gestation/roadmap_generator.php';
        generateGestationRoadmap($pdo, $sow_id, $ai_date);
    }

    header("Location: ../profile/view_sow.php?id=$sow_id");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add A.I. Attempt</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<style>
body { background-color: #f8f9fa; }
.card { border: none; border-radius: 1rem; box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
h2 { font-weight: 600; color: #2f3640; }
</style>
</head>
<body class="d-flex align-items-center justify-content-center vh-100">
<div class="container" style="max-width: 700px;">
  <div class="card p-4">
    <div class="text-center mb-3">
      <i class="bi bi-droplet-fill fs-1 text-primary"></i>
      <h2 class="mt-2">Add A.I. Attempt</h2>
      <p class="text-muted">Record new insemination details</p>
    </div>

    <form method="POST">
      <input type="hidden" name="sow_id" value="<?= htmlspecialchars($sow_id) ?>">

      <?php if ($sow): ?>
        <div class="alert alert-secondary py-2">
          <strong>🐖 Sow:</strong> <?= htmlspecialchars($sow['ear_tag_no']) ?> |
          <strong>Breed:</strong> <?= htmlspecialchars($sow['breed_line']) ?> |
          <strong>Status:</strong> <?= htmlspecialchars($sow['status']) ?>
        </div>
      <?php endif; ?>

      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Heat Detection Date:</label>
          <input type="date" name="heat_detection_date" class="form-control">
        </div>
        <div class="col-md-6">
          <label class="form-label">A.I. Date:</label>
          <input type="date" name="ai_date" class="form-control" id="ai_date" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Breeding Type:</label>
          <input type="text" name="breeding_type" class="form-control" placeholder="e.g., AI, Double AI, Natural">
        </div>
        <div class="col-md-6">
          <label class="form-label">Boar Source:</label>
          <input type="text" name="boar_source" class="form-control">
        </div>
        <div class="col-md-6">
          <label class="form-label">Farm Vet / Technician:</label>
          <input type="text" name="farm_vet" class="form-control">
        </div>
        <div class="col-md-6">
          <label class="form-label">Pregnancy Check Date:</label>
          <input type="date" name="pregnancy_check_date" class="form-control" id="pregnancy_check_date">
        </div>
        <div class="col-md-6">
          <label class="form-label">Confirmation:</label>
          <select name="confirmation" class="form-select">
            <option value="Pending">Pending</option>
            <option value="Positive">Positive</option>
            <option value="Negative">Negative</option>
          </select>
        </div>
        <div class="col-md-6">
          <label class="form-label">Cost (₱):</label>
          <input type="number" step="0.01" name="cost" class="form-control">
        </div>
        <div class="col-12">
          <label class="form-label">Remarks / Estrus Notes:</label>
          <textarea name="remarks" class="form-control" rows="3"></textarea>
        </div>
      </div>

      <div class="d-flex justify-content-end gap-2 mt-4">
        <a href="../profile/view_sow.php?id=<?= $sow_id ?>" class="btn btn-secondary">
          <i class="bi bi-arrow-left-circle"></i> Cancel
        </a>
        <button type="submit" class="btn btn-success">
          <i class="bi bi-save2"></i> Save A.I. Attempt
        </button>
      </div>
    </form>
  </div>
</div>

<!-- 🧮 Auto-calculate pregnancy check date -->
<script>
document.addEventListener("DOMContentLoaded", function() {
  const aiDateInput = document.getElementById('ai_date');
  const checkDateInput = document.getElementById('pregnancy_check_date');

  aiDateInput.addEventListener('change', function() {
    if (aiDateInput.value) {
      const aiDate = new Date(aiDateInput.value);
      const checkDate = new Date(aiDate);
      checkDate.setDate(aiDate.getDate() + 21); // midpoint of 18–24 days
      checkDateInput.value = checkDate.toISOString().split('T')[0];
    }
  });
});
</script>

</body>
</html>
