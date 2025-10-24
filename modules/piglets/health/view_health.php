<?php
require_once __DIR__ . '/../../../config/db.php';
if (!isset($_GET['id'])) die("Invalid request.");

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT h.*, p.farrowing_date 
                       FROM piglet_health_record h 
                       JOIN piglet_records p ON h.piglet_id = p.piglet_id 
                       WHERE h.health_id = ?");
$stmt->execute([$id]);
$h = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$h) die("Record not found.");
?>

<!DOCTYPE html>
<html>
<head><title>View Piglet Health Record</title></head>
<body>
<h2>View Piglet Health Record</h2>
<p><b>Piglet ID:</b> <?= $h['piglet_id'] ?></p>
<p><b>Type:</b> <?= $h['record_type'] ?></p>
<p><b>Stage:</b> <?= $h['stage'] ?></p>
<p><b>Date:</b> <?= $h['record_date'] ?></p>
<p><b>Farm Vet:</b> <?= htmlspecialchars($h['farm_vet']) ?></p>
<p><b>Cost:</b> ₱<?= number_format($h['cost'], 2) ?></p>

<?php if ($h['record_type']=='Disease'): ?>
<h4>Disease Details</h4>
<p><b>Symptoms:</b> <?= nl2br(htmlspecialchars($h['symptoms'])) ?></p>
<p><b>Findings:</b> <?= nl2br(htmlspecialchars($h['findings'])) ?></p>
<p><b>Treatment:</b> <?= nl2br(htmlspecialchars($h['treatment'])) ?></p>
<?php elseif ($h['record_type']=='Vaccination'): ?>
<h4>Vaccination Details</h4>
<p><b>Vaccine:</b> <?= htmlspecialchars($h['type_of_vaccine']) ?></p>
<?php elseif ($h['record_type']=='Deworming'): ?>
<h4>Deworming Details</h4>
<p><b>Product:</b> <?= htmlspecialchars($h['product_description']) ?></p>
<?php elseif ($h['record_type']=='Vitamins'): ?>
<h4>Vitamin Details</h4>
<p><b>Type:</b> <?= htmlspecialchars($h['type_of_vitamins']) ?></p>
<?php endif; ?>

<p><b>Remarks:</b> <?= nl2br(htmlspecialchars($h['remarks'])) ?></p>
<br>
<a href="edit_health.php?id=<?= $h['health_id'] ?>">Edit</a> |
<a href="list_health.php">Back to List</a>
</body>
</html>
