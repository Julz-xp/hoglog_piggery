<?php
require_once __DIR__ . '/../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['category_name'];
    $desc = $_POST['description'];
    $group = $_POST['category_group'];

    $stmt = $pdo->prepare("INSERT INTO sop_master_categories (category_name, description, category_group) VALUES (?, ?, ?)");
    $stmt->execute([$name, $desc, $group]);

    header("Location: list_categories.php");
    exit;
}
?>

<h2>Add Category</h2>
<form method="POST">
  <label>Name:</label><br>
  <input type="text" name="category_name" required><br><br>

  <label>Description:</label><br>
  <textarea name="description"></textarea><br><br>

  <label>Group:</label><br>
  <select name="category_group">
    <option value="Operations">Operations</option>
    <option value="Health & Safety">Health & Safety</option>
    <option value="Administrative">Administrative</option>
    <option value="Support">Support</option>
  </select><br><br>

  <button type="submit">Save</button>
</form>

<a href="list_categories.php">Back</a>
