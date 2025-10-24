<?php
require_once __DIR__ . '/../../../config/db.php';
$stmt = $pdo->query("
  SELECT e.*, s.ear_tag_no, s.breed_line
  FROM sow_expenses e
  LEFT JOIN sows s ON e.sow_id = s.sow_id
  ORDER BY e.expense_id DESC
");
$expenses = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Sow Expenses</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<div class="container">
  <h2 class="mb-4">💰 Sow Expenses Record</h2>
  <a href="add_expense.php" class="btn btn-success mb-3">➕ Add Expense</a>

  <table class="table table-bordered text-center align-middle">
    <thead class="table-dark">
      <tr>
        <th>ID</th>
        <th>Sow</th>
        <th>Stage</th>
        <th>Feed Cost</th>
        <th>Health Cost</th>
        <th>AI Cost</th>
        <th>Total Cost</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($expenses): foreach ($expenses as $e): ?>
      <tr>
        <td><?= $e['expense_id'] ?></td>
        <td><?= htmlspecialchars($e['ear_tag_no']) ?></td>
        <td><?= htmlspecialchars($e['stage']) ?></td>
        <td>₱<?= number_format($e['feed_cost'], 2) ?></td>
        <td>₱<?= number_format($e['health_cost'], 2) ?></td>
        <td>₱<?= number_format($e['ai_cost'], 2) ?></td>
        <td><strong>₱<?= number_format($e['total_cost'], 2) ?></strong></td>
        <td>
          <a href="view_expense.php?id=<?= $e['expense_id'] ?>" class="btn btn-info btn-sm">View</a>
          <a href="edit_expense.php?id=<?= $e['expense_id'] ?>" class="btn btn-warning btn-sm">Edit</a>
          <a href="delete_expense.php?id=<?= $e['expense_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this expense record?')">Delete</a>
        </td>
      </tr>
      <?php endforeach; else: ?>
        <tr><td colspan="8">No expense records found.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
</body>
</html>

