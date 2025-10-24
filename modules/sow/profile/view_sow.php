<?php
require_once __DIR__ . '/../../../config/db.php';
$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM sows WHERE sow_id = ?");
$stmt->execute([$id]);
$sow = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>View Sow</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<div class="container">
  <h2 class="mb-4">👁 Sow Profile Details</h2>
  <table class="table table-bordered">
    <?php foreach ($sow as $key => $value): ?>
    <tr>
      <th><?= htmlspecialchars(ucwords(str_replace('_', ' ', $key))) ?></th>
      <td><?= htmlspecialchars($value) ?></td>
    </tr>
    <?php endforeach; ?>
  </table>
  <a href="list_sow.php" class="btn btn-secondary">Back</a>
</div>
</body>
</html>
