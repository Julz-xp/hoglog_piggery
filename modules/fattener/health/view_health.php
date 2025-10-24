<?php
require_once __DIR__ . '/../../../config/db.php';

if (!isset($_GET['id'])) die("Invalid request");
$id = $_GET['id'];

$stmt = $pdo->prepare("SELECT h.*, f.ear_tag_no 
                       FROM fattener_health_record h
                       JOIN fattener_records f ON h.fattener_id = f.fattener_id
                       WHERE h.health_id = ?");
$stmt->execute([$id]);
$r = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$r) die("Record not found.");
?>

<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>View Health Record</title></head>
<body>
<h2>View Health Record</h2>

<p><b>Fattener:</b> <?= htmlspecialchars($r['ear_tag_no']) ?></p>
<p><b>Record Type:</b> <?= htmlspecialchars($r['record_type']) ?></p>
<p><b>Date:</b> <?= htmlspecialchars($r['record_date']) ?></p>
<p><b>Stage:</b> <?= htmlspecialchars($r['stage']) ?></p>
<p><b>Description:</b> <?= nl2br(htmlspecialchars($r['description'])) ?></p>
<p><b>Findings:</b> <?= nl2br(htmlspecialchars($r['findings'])) ?></p>
<p><b>Treatment:</b> <?= nl2br(htmlspecialchars($r['treatment'])) ?></p>
<p><b>Farm Vet:</b> <?= htmlspecialchars($r['farm_vet']) ?></p>
<p><b>Cost (₱):</b> <?= htmlspecialchars($r['cost']) ?></p>
<p><b>Remarks:</b> <?= nl2br(htmlspecialchars($r['remarks'])) ?></p>

<br>
<a href="edit_health.php?id=<?= $r['health_id'] ?>">✏️ Edit</a> |
<a href="list_health.php">⬅️ Back to List</a>
</body>
</html>
