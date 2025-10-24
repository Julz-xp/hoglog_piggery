<?php
require_once __DIR__ . '/../../../config/db.php';

$id = $_GET['id'];

// Fetch selected expense record
$stmt = $pdo->prepare("SELECT * FROM sow_expenses WHERE expense_id = ?");
$stmt->execute([$id]);
$expense = $stmt->fetch();
if (!$expense) die("Expense record not found!");

// Fetch all sows for dropdown
$sows = $pdo->query("SELECT sow_id, ear_tag_no FROM sows ORDER BY ear_tag_no ASC")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sow_id = $_POST['sow_id'];
    $stage = $_POST['stage'];
    $feed_cost = $_POST['feed_cost'] ?: 0;
    $health_cost = $_POST['health_cost'] ?: 0;
    $ai_cost = $_POST['ai_cost'] ?: 0;
    $total_cost = $feed_cost + $health_cost + $ai_cost;

    $update = $pdo->prepare("
        UPDATE sow_expenses
        SET sow_id=?, stage=?, feed_cost=?, health_cost=?, ai_cost=?, total_cost=?
        WHERE expense_id=?
    ");
    $update->execute([$sow_id, $stage, $feed_cost, $health_cost, $ai_cost, $total_cost, $id]);

    header("Location: list_expense.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Expense Record</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script>
function computeTotal() {
  const feed = parseFloat(document.getElementById('feed_cost').value) || 0;
  const health = parseFloat(document.getElementById('health_cost').value) || 0;
  const ai = parseFloat(document.getElementById('ai_cost').value) || 0;
  document.getElementById('total_cost').value = (feed + health + ai).toFixed(2);
}
</script>
</head>
<body class="p-4">
<div class="container">
  <h2 class="mb-4">✏️ Edit Expense Record</h2>
  <form method="POST">
    <div class="mb-3">
      <label>Sow (Ear Tag)</label>
      <select name="sow_id" class="form-select" required>
        <?php foreach ($sows as $sow): ?>
          <option value="<?= $sow['sow_id'] ?>" <?= ($sow['sow_id']==$expense['sow_id'])?'selected':'' ?>>
            <?= htmlspecialchars($sow['ear_tag_no']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="mb-3">
      <label>Stage</label>
      <select name="stage" class="form-select" required>
        <?php foreach (['Gilt','Gestating','Lactating','Dry'] as $st): ?>
          <option value="<?= $st ?>" <?= ($expense['stage']==$st)?'selected':'' ?>><?= $st ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="row g-3">
      <div class="col-md-4">
        <label>Feed Cost (₱)</label>
        <input type="number" step="0.01" id="feed_cost" name="feed_cost" value="<?= $expense['feed_cost'] ?>" class="form-control" oninput="computeTotal()">
      </div>
      <div class="col-md-4">
        <label>Health Cost (₱)</label>
        <input type="number" step="0.01" id="health_cost" name="health_cost" value="<?= $expense['health_cost'] ?>" class="form-control" oninput="computeTotal()">
      </div>
      <div class="col-md-4">
        <label>AI Cost (₱)</label>
        <input type="number" step="0.01" id="ai_cost" name="ai_cost" value="<?= $expense['ai_cost'] ?>" class="form-control" oninput="computeTotal()">
      </div>
    </div>

    <div class="mt-3 mb-3">
      <label>Total Cost (₱)</label>
      <input type="number" step="0.01" id="total_cost" name="total_cost" value="<?= $expense['total_cost'] ?>" class="form-control" readonly>
    </div>

    <button type="submit" class="btn btn-warning">Update</button>
    <a href="list_expense.php" class="btn btn-secondary">Cancel</a>
  </form>
</div>
</body>
</html>
