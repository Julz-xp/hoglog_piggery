<?php
require_once __DIR__ . '/../../../config/db.php';

$id = $_GET['id'];

// Fetch record
$stmt = $pdo->prepare("SELECT * FROM sow_feed_consumption WHERE feed_id = ?");
$stmt->execute([$id]);
$feed = $stmt->fetch();
if (!$feed) die("Feed record not found!");

// Fetch sows
$sows = $pdo->query("SELECT sow_id, ear_tag_no FROM sows ORDER BY ear_tag_no ASC")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sow_id = $_POST['sow_id'];
    $stage = $_POST['stage'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $feed_type = $_POST['feed_type'];
    $daily_intake = $_POST['daily_intake'];
    $total_days = $_POST['total_days'];
    $total_feed = $_POST['total_feed'];

    $update = $pdo->prepare("
        UPDATE sow_feed_consumption 
        SET sow_id=?, stage=?, start_date=?, end_date=?, feed_type=?, daily_intake=?, total_days=?, total_feed=?
        WHERE feed_id=?
    ");
    $update->execute([$sow_id, $stage, $start_date, $end_date, $feed_type, $daily_intake, $total_days, $total_feed, $id]);

    header("Location: list_feed.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Feed Record</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script>
function computeFeed() {
  const daily = parseFloat(document.getElementById('daily_intake').value) || 0;
  const days = parseInt(document.getElementById('total_days').value) || 0;
  document.getElementById('total_feed').value = (daily * days).toFixed(2);
}
</script>
</head>
<body class="p-4">
<div class="container">
  <h2 class="mb-4">✏️ Edit Feed Consumption Record</h2>
  <form method="POST">
    <div class="mb-3">
      <label>Sow (Ear Tag)</label>
      <select name="sow_id" class="form-select" required>
        <?php foreach ($sows as $s): ?>
          <option value="<?= $s['sow_id'] ?>" <?= ($s['sow_id']==$feed['sow_id'])?'selected':'' ?>>
            <?= htmlspecialchars($s['ear_tag_no']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="row g-3">
      <div class="col-md-4">
        <label>Stage</label>
        <select name="stage" class="form-select" required>
          <?php foreach (['Gilt','Gestating','Lactating','Dry'] as $stage): ?>
            <option value="<?= $stage ?>" <?= ($feed['stage']==$stage)?'selected':'' ?>><?= $stage ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-4"><label>Start Date</label><input type="date" name="start_date" value="<?= $feed['start_date'] ?>" class="form-control" required></div>
      <div class="col-md-4"><label>End Date</label><input type="date" name="end_date" value="<?= $feed['end_date'] ?>" class="form-control" required></div>
    </div>

    <div class="row g-3 mt-3">
      <div class="col-md-4"><label>Feed Type</label><input type="text" name="feed_type" value="<?= htmlspecialchars($feed['feed_type']) ?>" class="form-control" required></div>
      <div class="col-md-4"><label>Daily Intake (kg)</label><input type="number" step="0.01" id="daily_intake" name="daily_intake" value="<?= $feed['daily_intake'] ?>" class="form-control" oninput="computeFeed()"></div>
      <div class="col-md-4"><label>Total Days</label><input type="number" id="total_days" name="total_days" value="<?= $feed['total_days'] ?>" class="form-control" oninput="computeFeed()"></div>
    </div>

    <div class="mt-3 mb-3">
      <label>Total Feed (kg)</label>
      <input type="number" step="0.01" id="total_feed" name="total_feed" value="<?= $feed['total_feed'] ?>" class="form-control" readonly>
    </div>

    <button type="submit" class="btn btn-warning">Update</button>
    <a href="list_feed.php" class="btn btn-secondary">Cancel</a>
  </form>
</div>
</body>
</html>
