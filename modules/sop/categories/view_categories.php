<?php
require_once __DIR__ . '/../../config/db.php';

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM sop_master_categories WHERE category_id=?");
$stmt->execute([$id]);
$row = $stmt->fetch();
?>

<h2>View Category</h2>
<p><b>ID:</b> <?= $row['category_id'] ?></p>
<p><b>Name:</b> <?= htmlspecialchars($row['category_name']) ?></p>
<p><b>Description:</b> <?= nl2br(htmlspecialchars($row['description'])) ?></p>
<p><b>Group:</b> <?= htmlspecialchars($row['category_group']) ?></p>
<p><b>Created:</b> <?= $row['created_at'] ?></p>

<a href="edit_categories.php?id=<?= $row['category_id'] ?>">Edit</a> |
<a href="list_categories.php">Back</a>
