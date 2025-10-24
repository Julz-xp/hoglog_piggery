<?php
require_once __DIR__ . '/../../../config/db.php';

if (!isset($_GET['id'])) die("Invalid request");
$id = $_GET['id'];

$stmt = $pdo->prepare("DELETE FROM fattener_sales_record WHERE sale_id = ?");
$stmt->execute([$id]);

header("Location: list_sales.php?deleted=1");
exit;
?>
