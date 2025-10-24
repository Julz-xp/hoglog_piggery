<?php
require_once __DIR__ . '/../../../config/db.php';
if (!isset($_GET['id'])) die("Invalid request");
$id = $_GET['id'];

$stmt = $pdo->prepare("DELETE FROM batch_sales_record WHERE sale_id=?");
$stmt->execute([$id]);

header("Location: list_sale.php?deleted=1");
exit;
?>
