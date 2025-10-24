<?php
require_once __DIR__ . '/../../../config/db.php';

// ✅ Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $piglet_id = $_POST['piglet_id'];

    // 🐷 Fetch main piglet data
    $stmt = $pdo->prepare("SELECT * FROM piglet_records WHERE piglet_id=?");
    $stmt->execute([$piglet_id]);
    $piglet = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$piglet) die("Piglet record not found.");

    // 📊 Compute survival rate
    $litter_size = $piglet['total_born'];
    $total_weaned = $piglet['total_weaned'];
    $survival_rate = ($litter_size > 0) ? round(($total_weaned / $litter_size) * 100, 2) : 0;

    // ⚖️ Feed & health cost totals
    $stmt_feed = $pdo->prepare("SELECT SUM(total_feed_cost) AS total_feed_cost FROM piglet_feed_consumption WHERE piglet_id=?");
    $stmt_feed->execute([$piglet_id]);
    $feed = $stmt_feed->fetch(PDO::FETCH_ASSOC);
    $total_feed_cost = $feed['total_feed_cost'] ?? 0;

    $stmt_health = $pdo->prepare("SELECT SUM(cost) AS total_health_cost FROM piglet_health_record WHERE piglet_id=?");
    $stmt_health->execute([$piglet_id]);
    $health = $stmt_health->fetch(PDO::FETCH_ASSOC);
    $total_health_cost = $health['total_health_cost'] ?? 0;

    $net_expense = $total_feed_cost + $total_health_cost;

    // 💰 Cost per piglet
    $feed_cost_per_piglet = ($total_weaned > 0) ? round($total_feed_cost / $total_weaned, 2) : 0;
    $health_cost_per_piglet = ($total_weaned > 0) ? round($total_health_cost / $total_weaned, 2) : 0;
    $net_expense_per_piglet = ($total_weaned > 0) ? round($net_expense / $total_weaned, 2) : 0;

    // 🧾 Insert / Update report
    $stmt = $pdo->prepare("
        INSERT INTO piglet_performance_report 
        (piglet_id, litter_size, total_weaned, survival_rate, avg_birth_weight, avg_weaning_weight,
         feed_cost_per_piglet, health_cost_per_piglet, net_expense_per_piglet)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE
         litter_size=VALUES(litter_size),
         total_weaned=VALUES(total_weaned),
         survival_rate=VALUES(survival_rate),
         avg_birth_weight=VALUES(avg_birth_weight),
         avg_weaning_weight=VALUES(avg_weaning_weight),
         feed_cost_per_piglet=VALUES(feed_cost_per_piglet),
         health_cost_per_piglet=VALUES(health_cost_per_piglet),
         net_expense_per_piglet=VALUES(net_expense_per_piglet)
    ");

    $stmt->execute([
        $piglet_id,
        $litter_size,
        $total_weaned,
        $survival_rate,
        $piglet['avg_birth_weight'],
        $piglet['avg_weaning_weight'],
        $feed_cost_per_piglet,
        $health_cost_per_piglet,
        $net_expense_per_piglet
    ]);

    header("Location: list_report.php?success=1");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head><title>Generate Piglet Performance Report</title></head>
<body>
<h2>Generate Piglet Performance Report</h2>
<form method="POST">
    Piglet ID: <input type="number" name="piglet_id" required><br><br>
    <button type="submit">Generate Report</button>
</form>
<p><i>💡 This will analyze feed, health, and growth data for the selected Piglet ID.</i></p>
</body>
</html>
