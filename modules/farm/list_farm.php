<?php
require_once "../../config/db.php";

try {
    $stmt = $pdo->query("SELECT * FROM farms ORDER BY created_at DESC");
    $farms = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Farm List | HogLog</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<style>
body {
  font-family: 'Poppins', sans-serif;
  background: #e1f5fe;
  margin: 0;
  padding: 30px;
}
.container {
  background: #fff;
  padding: 20px;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}
h2 {
  color: #0277bd;
  margin-bottom: 20px;
}
table {
  width: 100%;
  border-collapse: collapse;
}
th, td {
  padding: 10px 12px;
  border-bottom: 1px solid #ccc;
  text-align: left;
}
th {
  background: #4fc3f7;
  color: #fff;
}
a.btn {
  text-decoration: none;
  padding: 6px 10px;
  border-radius: 5px;
  color: #fff;
  font-size: 14px;
}
.view { background: #26a69a; }
.edit { background: #42a5f5; }
.delete { background: #ef5350; }
a.btn:hover { opacity: 0.85; }
</style>
</head>
<body>
<div class="container">
<h2>Registered Farms</h2>
<table>
<tr>
  <th>ID</th>
  <th>Farm Name</th>
  <th>Owner</th>
  <th>Type</th>
  <th>Contact</th>
  <th>Actions</th>
</tr>
<?php foreach ($farms as $farm): ?>
<tr>
  <td><?= $farm["farm_id"]; ?></td>
  <td><?= htmlspecialchars($farm["farm_name"]); ?></td>
  <td><?= htmlspecialchars($farm["owner_name"]); ?></td>
  <td><?= htmlspecialchars($farm["farm_type"]); ?></td>
  <td><?= htmlspecialchars($farm["contact_number"]); ?></td>
  <td>
    <a href="view_farm.php?farm_id=<?= $farm["farm_id"]; ?>" class="btn view">View</a>
    <a href="edit_farm.php?farm_id=<?= $farm["farm_id"]; ?>" class="btn edit">Edit</a>
    <a href="delete_farm.php?farm_id=<?= $farm["farm_id"]; ?>" class="btn delete" onclick="return confirm('Are you sure you want to delete this farm?');">Delete</a>
  </td>
</tr>
<?php endforeach; ?>
</table>
</div>
</body>
</html>
