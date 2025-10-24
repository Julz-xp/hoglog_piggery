<?php
require_once __DIR__ . '/../../config/db.php';

$id = $_GET['id'];
$stmt = $pdo->prepare("DELETE FROM sop_master_categories WHERE category_id=?");
$stmt->execute([$id]);

header("Location: list_categories.php");
exit;
?>
