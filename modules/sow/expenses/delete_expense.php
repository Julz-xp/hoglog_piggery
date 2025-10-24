<?php
require_once __DIR__ . '/../../../config/db.php';
$id = $_GET['id'];

$stmt = $pdo->prepare("DELETE FROM sow_expenses WHERE expense_id = ?");
$stmt->execute([$id]);

header("Location: list_expense.php");
exit;
?>

