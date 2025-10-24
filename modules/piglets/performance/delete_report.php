<?php
require_once __DIR__ . '/../../../config/db.php';
if (!isset($_GET['id'])) die("Invalid request.");
$id = $_GET['id'];

$stmt = $pdo->prepare("DELETE FROM piglet_performance_report WHERE report_id = ?");
$stmt->execute([$id]);

header("Location: list_report.php?deleted=1");
exit;
?>

