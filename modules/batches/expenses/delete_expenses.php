<?php
require_once __DIR__ . '/../../../config/db.php';
if (!isset($_GET['id'])) die("Invalid request");
$id = $_GET['id'];

$stmt = $pdo->prepare("DELETE FROM batch_expenses WHERE expense_id=?");
$stmt->execute([$id]);

header("Location: list_expense.php?deleted=1");
exit;
?>
