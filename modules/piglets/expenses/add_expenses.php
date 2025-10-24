<?php
require_once __DIR__ . '/../../../config/db.php';

// ✅ Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $piglet_id = $_POST['piglet_id'];

    // Fetch total feed cost from feed table
    $stmt_feed = $pdo->prepare("SELECT SUM(total_feed_cost) AS total_feed_cost FROM piglet_feed_consumption WHERE piglet_id=?");
    $stmt_feed->execute([$piglet_id]);
    $feed = $stmt_feed->fetch(PDO::FETCH_ASSOC);
    $total_feed_cost = $feed['total_feed_cost'] ?? 0;

    // Fetch total health cost from health table
    $stmt_health = $pdo->prepare("SELECT SUM(cost) AS total_health_cost FROM piglet_health_record WHERE piglet_id=?");
    $stmt_health->execute([$piglet_id]);
    $health = $stmt_health->fetch(PDO::FETCH_ASSOC);
    $total_health_cost = $health['total_health_cost'] ?? 0;

    $stmt = $pdo->prepare("INSERT INTO piglet_expenses (piglet_id, total_feed_cost, total_health_cost)
                           VALUES (?, ?, ?)");
    $stmt->execute([$piglet_id, $total_feed_cost, $total_health_cost]);

    header("Location: list_expenses.php?success=1");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head><title>Add Piglet Expenses</title></head>
<body>
<h2>Add Piglet Expenses Record</h2>
<form method="POST">
    Piglet ID: <input type="number" name="piglet_id" required><br><br>
    <button type="submit">Generate Expenses</button>
</form>
<p><i>💡 This will automatically calculate total feed + health costs for the selected Piglet ID.</i></p>
</body>
</html>
