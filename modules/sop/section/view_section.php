<?php
require_once __DIR__ . '/../../config/db.php';

$id = $_GET['id'];

$sql = "SELECT s.*, c.category_name 
        FROM sop_category_sections s 
        LEFT JOIN sop_master_categories c ON s.category_id = c.category_id
        WHERE s.section_id=?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$row = $stmt->fetch();
?>

<h2>View SOP Section</h2>
<p><b>ID:</b> <?= $row['section_id'] ?></p>
<p><b>Category:</b> <?= htmlspecialchars($row['category_name']) ?></p>
<p><b>Section Name:</b> <?= htmlspecialchars($row['section_name']) ?></p>
<p><b>Description:</b><br><?= nl2br(htmlspecialchars($row['description'])) ?></p>
<p><b>Standard Procedure:</b><br><?= nl2br(htmlspecialchars($row['standard_procedure'])) ?></p>
<p><b>Responsible Person:</b> <?= htmlspecialchars($row['responsible_person']) ?></p>
<p><b>Frequency:</b> <?= htmlspecialchars($row['frequency']) ?></p>
<p><b>Created:</b> <?= $row['created_at'] ?></p>

<a href="edit_section.php?id=<?= $row['section_id'] ?>">Edit</a> |
<a href="list_section.php">Back</a>
