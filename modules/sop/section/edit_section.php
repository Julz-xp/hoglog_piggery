<?php
require_once __DIR__ . '/../../config/db.php';

$id = $_GET['id'];

// Fetch section
$stmt = $pdo->prepare("SELECT * FROM sop_category_sections WHERE section_id=?");
$stmt->execute([$id]);
$section = $stmt->fetch();

// Fetch categories
$cat_stmt = $pdo->query("SELECT category_id, category_name FROM sop_master_categories ORDER BY category_name ASC");
$categories = $cat_stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_id = $_POST['category_id'];
    $section_name = $_POST['section_name'];
    $description = $_POST['description'];
    $standard_procedure = $_POST['standard_procedure'];
    $responsible_person = $_POST['responsible_person'];
    $frequency = $_POST['frequency'];

    $update = $pdo->prepare("UPDATE sop_category_sections 
        SET category_id=?, section_name=?, description=?, standard_procedure=?, responsible_person=?, frequency=? 
        WHERE section_id=?");
    $update->execute([$category_id, $section_name, $description, $standard_procedure, $responsible_person, $frequency, $id]);

    header("Location: list_section.php");
    exit;
}
?>

<h2>Edit SOP Section</h2>
<form method="POST">
  <label>Category:</label><br>
  <select name="category_id" required>
    <?php foreach($categories as $cat): ?>
      <option value="<?= $cat['category_id'] ?>" <?= $cat['category_id']==$section['category_id']?'selected':'' ?>>
        <?= htmlspecialchars($cat['category_name']) ?>
      </option>
    <?php endforeach; ?>
  </select><br><br>

  <label>Section Name:</label><br>
  <input type="text" name="section_name" value="<?= htmlspecialchars($section['section_name']) ?>" required><br><br>

  <label>Description:</label><br>
  <textarea name="description"><?= htmlspecialchars($section['description']) ?></textarea><br><br>

  <label>Standard Procedure:</label><br>
  <textarea name="standard_procedure"><?= htmlspecialchars($section['standard_procedure']) ?></textarea><br><br>

  <label>Responsible Person:</label><br>
  <input type="text" name="responsible_person" value="<?= htmlspecialchars($section['responsible_person']) ?>"><br><br>

  <label>Frequency:</label><br>
  <select name="frequency">
    <?php
      $freqs = ['Daily','Weekly','Monthly','Quarterly','As Needed'];
      foreach($freqs as $f) {
        $sel = ($f == $section['frequency']) ? 'selected' : '';
        echo "<option value='$f' $sel>$f</option>";
      }
    ?>
  </select><br><br>

  <button type="submit">Update</button>
</form>

<a href="list_section.php">Back</a>
