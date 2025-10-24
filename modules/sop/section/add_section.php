<?php
require_once __DIR__ . '/../../config/db.php';

// Fetch categories for dropdown
$cat_stmt = $pdo->query("SELECT category_id, category_name FROM sop_master_categories ORDER BY category_name ASC");
$categories = $cat_stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_id = $_POST['category_id'];
    $section_name = $_POST['section_name'];
    $description = $_POST['description'];
    $standard_procedure = $_POST['standard_procedure'];
    $responsible_person = $_POST['responsible_person'];
    $frequency = $_POST['frequency'];

    $stmt = $pdo->prepare("INSERT INTO sop_category_sections 
        (category_id, section_name, description, standard_procedure, responsible_person, frequency) 
        VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$category_id, $section_name, $description, $standard_procedure, $responsible_person, $frequency]);

    header("Location: list_section.php");
    exit;
}
?>

<h2>Add SOP Section</h2>
<form method="POST">
  <label>Category:</label><br>
  <select name="category_id" required>
    <option value="">-- Select Category --</option>
    <?php foreach($categories as $cat): ?>
      <option value="<?= $cat['category_id'] ?>"><?= htmlspecialchars($cat['category_name']) ?></option>
    <?php endforeach; ?>
  </select><br><br>

  <label>Section Name:</label><br>
  <input type="text" name="section_name" required><br><br>

  <label>Description:</label><br>
  <textarea name="description"></textarea><br><br>

  <label>Standard Procedure:</label><br>
  <textarea name="standard_procedure"></textarea><br><br>

  <label>Responsible Person:</label><br>
  <input type="text" name="responsible_person"><br><br>

  <label>Frequency:</label><br>
  <select name="frequency">
    <option value="Daily">Daily</option>
    <option value="Weekly">Weekly</option>
    <option value="Monthly">Monthly</option>
    <option value="Quarterly">Quarterly</option>
    <option value="As Needed" selected>As Needed</option>
  </select><br><br>

  <button type="submit">Save</button>
</form>

<a href="list_section.php">Back</a>
