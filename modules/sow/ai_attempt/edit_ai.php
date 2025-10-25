<?php
require_once __DIR__ . '/../../../config/db.php';

$ai_id = $_GET['ai_id'] ?? null;
$sow_id = $_GET['sow_id'] ?? null;

if (!$ai_id) {
  die("❌ Missing AI record ID.");
}

// 🧾 Fetch AI record
$stmt = $pdo->prepare("SELECT * FROM ai_attempt WHERE ai_id = ?");
$stmt->execute([$ai_id]);
$ai = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$ai) die("❌ Record not found!");

// 🐖 Fetch sow list
$sows = $pdo->query("SELECT sow_id, ear_tag_no FROM sows ORDER BY ear_tag_no ASC")->fetchAll(PDO::FETCH_ASSOC);

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

    // 📝 Update AI attempt
    $update = $pdo->prepare("
        UPDATE ai_attempt
        SET sow_id=?, heat_detection_date=?, ai_date=?, breeding_type=?, boar_source=?, farm_vet=?, pregnancy_check_date=?, confirmation=?, cost=?, remarks=?
        WHERE ai_id=?
    ");
    $update->execute([
        $sow_id, $heat_detection_date, $ai_date, $breeding_type, $boar_source,
        $farm_vet, $pregnancy_check_date, $confirmation, $cost, $remarks, $ai_id
    ]);

    // ✅ If confirmed Positive → update sow + create gestation + trigger roadmap
    if ($confirmation === 'Positive') {
        // 1️⃣ Update sow status
        $pdo->prepare("UPDATE sows SET status = 'Gestating' WHERE sow_id = ?")->execute([$sow_id]);

        // 2️⃣ Check if gestation record already exists
        $check = $pdo->prepare("SELECT * FROM gestating_stage WHERE ai_id = ?");
        $check->execute([$ai_id]);
        $exists = $check->fetch();

        if (!$exists) {
            $expected_farrowing = date('Y-m-d', strtotime($ai_date . ' +114 days'));

            // 3️⃣ Insert gestation record
            $insert = $pdo->prepare("
                INSERT INTO gestating_stage (sow_id, ai_id, breeding_date, expected_farrowing_date, notes)
                VALUES (?, ?, ?, ?, ?)
            ");
            $insert->execute([$sow_id, $ai_id, $ai_date, $expected_farrowing, 'Auto-created after positive pregnancy confirmation.']);

            // 4️⃣ Generate gestation feed & health roadmap
            require_once __DIR__ . '/../gestation/roadmap_generator.php';
            generateGestationRoadmap($pdo, $sow_id, $ai_date);
        }
    }

    header("Location: view_ai.php?sow_id=$sow_id");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit A.I. Attempt</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<style>
body { background-color: #f8f9fa; }
.card { border: none; border-radius: 1rem; box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
h2 { font-weight: 600; color: #2f3640; }
</style>
</head>
<body class="d-flex align-items-center justify-content-center vh-100">
<div class="container" style="max-width: 720px;">
  <div class="card p-4">
    <div class="text-center mb-3">
      <i class="bi bi-pencil-square fs-1 text-warning"></i>
      <h2 class="mt-2">Edit / Confirm A.I. Attempt</h2>
      <p class="text-muted mb-0">Review, update, or confirm pregnancy details.</p>
    </div>

    <form method="POST">
      <input type="hidden" name="ai_id" value="<?= htmlspecialchars($ai_id) ?>">

      <div class="row g-3">
        <!-- Sow Selection -->
        <div class="col-md-12">
          <label class="form-label">Sow (Ear Tag):</label>
          <select name="sow_id" class="form-select">
            <?php foreach ($sows as $sow): ?>
              <option value="<?= $sow['sow_id'] ?>" <?= ($sow['sow_id'] == $ai['sow_id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($sow['ear_tag_no']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="col-md-6">
          <label class="form-label">Heat Detection Date:</label>
          <input type="date" name="heat_detection_date" value="<?= htmlspecialchars($ai['heat_detection_date']) ?>" class="form-control">
        </div>

        <div class="col-md-6">
          <label class="form-label">A.I. Date:</label>
          <input type="date" name="ai_date" value="<?= htmlspecialchars($ai['ai_date']) ?>" class="form-control">
        </div>

        <div class="col-md-6">
          <label class="form-label">Breeding Type:</label>
          <input type="text" name="breeding_type" value="<?= htmlspecialchars($ai['breeding_type']) ?>" class="form-control" placeholder="e.g. AI, Double AI, Natural">
        </div>

        <div class="col-md-6">
          <label class="form-label">Boar Source:</label>
          <input type="text" name="boar_source" value="<?= htmlspecialchars($ai['boar_source']) ?>" class="form-control">
        </div>

        <div class="col-md-6">
          <label class="form-label">Farm Vet / Technician:</label>
          <input type="text" name="farm_vet" value="<?= htmlspecialchars($ai['farm_vet']) ?>" class="form-control">
        </div>

        <div class="col-md-6">
          <label class="form-label">Pregnancy Check Date:</label>
          <input type="date" name="pregnancy_check_date" value="<?= htmlspecialchars($ai['pregnancy_check_date']) ?>" class="form-control">
          <div class="form-text">💡 Usually 18–25 days after A.I. date.</div>
        </div>

        <div class="col-md-6">
          <label class="form-label">Confirmation:</label>
          <select name="confirmation" class="form-select">
            <option value="Pending" <?= ($ai['confirmation'] == 'Pending') ? 'selected' : '' ?>>Pending</option>
            <option value="Positive" <?= ($ai['confirmation'] == 'Positive') ? 'selected' : '' ?>>Positive</option>
            <option value="Negative" <?= ($ai['confirmation'] == 'Negative') ? 'selected' : '' ?>>Negative</option>
          </select>
        </div>

        <div class="col-md-6">
          <label class="form-label">Cost (₱):</label>
          <input type="number" step="0.01" name="cost" value="<?= htmlspecialchars($ai['cost']) ?>" class="form-control">
        </div>

        <div class="col-12">
          <label class="form-label">Remarks / Estrus Notes:</label>
          <textarea name="remarks" class="form-control" rows="3"><?= htmlspecialchars($ai['remarks']) ?></textarea>
        </div>
      </div>

      <div class="d-flex justify-content-end gap-2 mt-4">
        <a href="view_ai.php?sow_id=<?= htmlspecialchars($sow_id) ?>" class="btn btn-secondary">
          <i class="bi bi-arrow-left-circle"></i> Back
        </a>
        <button type="submit" class="btn btn-warning">
          <i class="bi bi-save2"></i> Update Record
        </button>
      </div>
    </form>
  </div>
</div>
</body>
</html>
