<?php
session_start();
require_once '../../config/db.php';

// ✅ Ensure farm is logged in
if (!isset($_SESSION['farm_id'])) {
  echo "<p style='color:red;'>⚠️ Please log in as a farm first.</p>";
  exit;
}

$farm_id = $_SESSION['farm_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  try {
      // ✅ Handle profile picture upload
      $profile_picture = null;
      if (!empty($_FILES['profile_picture']['name'])) {
          $targetDir = "../../uploads/users/";
          if (!file_exists($targetDir)) mkdir($targetDir, 0777, true);

          $fileName = time() . "_" . basename($_FILES["profile_picture"]["name"]);
          $targetFilePath = $targetDir . $fileName;

          $allowedTypes = ['jpg','jpeg','png','gif'];
          $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

          if (in_array($fileType, $allowedTypes)) {
              if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $targetFilePath)) {
                  $profile_picture = $fileName;
              }
          }
      }

      $passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);

      $stmt = $pdo->prepare("
          INSERT INTO users (
              farm_id, full_name, date_of_birth, gender, civil_status, nationality, contact_number, email,
              home_address, tin_number, sss_number, philhealth_number, pagibig_number,
              emergency_contact_name, emergency_contact_number, emergency_address,
              medical_conditions, username, password, position, profile_picture
          ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
      ");

      $stmt->execute([
          $farm_id,
          $_POST['full_name'],
          $_POST['date_of_birth'],
          $_POST['gender'],
          $_POST['civil_status'],
          $_POST['nationality'],
          $_POST['contact_number'],
          $_POST['email'],
          $_POST['home_address'],
          $_POST['tin_number'] ?? null,
          $_POST['sss_number'] ?? null,
          $_POST['philhealth_number'] ?? null,
          $_POST['pagibig_number'] ?? null,
          $_POST['emergency_contact_name'],
          $_POST['emergency_contact_number'],
          $_POST['emergency_address'],
          $_POST['medical_conditions'] ?? null,
          $_POST['username'],
          $passwordHash,
          $_POST['position'],
          $profile_picture
      ]);

      header("Location: ../users/user_dashboard.php");
      exit;

  } catch (PDOException $e) {
      echo "<p style='color:red;'>❌ Database Error: " . htmlspecialchars($e->getMessage()) . "</p>";
  }
}
?>

<div class="page-header">
  <h1><i class="bi bi-person-plus-fill"></i> Register New User</h1>
  <a href="../farm/farm_dashboard.php" class="back-btn"><i class="bi bi-arrow-left-circle"></i> Back to Dashboard</a>
</div>

<div class="modern-container">
  <form method="POST" action="../users/add_users.php" enctype="multipart/form-data" class="modern-form">

    <!-- PROFILE UPLOAD -->
    <div class="profile-section">
      <div class="image-wrapper">
        <img id="preview" src="../../assets/default-user.png" alt="Profile Preview">
        <label for="profile_picture" class="upload-btn"><i class="bi bi-camera-fill"></i></label>
        <input type="file" name="profile_picture" id="profile_picture" accept="image/*" onchange="previewImage(event)">
      </div>
      <p>Upload user’s profile picture</p>
    </div>

    <!-- PERSONAL INFO -->
    <div class="form-section">
      <h3><i class="bi bi-person-lines-fill"></i> Personal Information</h3>
      <div class="form-grid">
        <div class="form-group"><input type="text" name="full_name" required><label>Full Name</label></div>
        <div class="form-group"><input type="date" name="date_of_birth"><label>Date of Birth</label></div>
        <div class="form-group">
          <select name="gender" required>
            <option value="">Select Gender</option>
            <option>Male</option>
            <option>Female</option>
            <option>Prefer not to say</option>
          </select><label>Gender</label>
        </div>
        <div class="form-group"><input type="text" name="civil_status"><label>Civil Status</label></div>
        <div class="form-group"><input type="text" name="nationality"><label>Nationality</label></div>
        <div class="form-group"><input type="text" name="contact_number"><label>Contact Number</label></div>
        <div class="form-group"><input type="email" name="email"><label>Email Address</label></div>
        <div class="form-group full"><textarea name="home_address"></textarea><label>Home Address</label></div>
      </div>
    </div>

    <!-- EMERGENCY -->
    <div class="form-section">
      <h3><i class="bi bi-heart-pulse-fill"></i> Emergency & Health Details</h3>
      <div class="form-grid">
        <div class="form-group"><input type="text" name="emergency_contact_name" required><label>Emergency Contact Name</label></div>
        <div class="form-group"><input type="text" name="emergency_contact_number" required><label>Emergency Number</label></div>
        <div class="form-group full"><textarea name="emergency_address" required></textarea><label>Emergency Address</label></div>
        <div class="form-group full"><input type="text" name="medical_conditions"><label>Medical Conditions (if any)</label></div>
      </div>
    </div>

    <!-- LOGIN -->
    <div class="form-section">
      <h3><i class="bi bi-shield-lock-fill"></i> Login Information</h3>
      <div class="form-grid">
        <div class="form-group"><input type="text" name="username" required><label>Username</label></div>
        <div class="form-group"><input type="password" name="password" required><label>Password</label></div>
        <div class="form-group full">
          <select name="position" required>
            <option value="">Select Position</option>
            <option>Farm Owner</option>
            <option>Farm Vet</option>
            <option>Caretaker</option>
          </select>
      
        </div>
      </div>
    </div>

    <button type="submit" class="modern-btn">💾 Register User</button>
  </form>
</div>

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<style>
body {
  font-family: "Poppins", sans-serif;
  background: linear-gradient(135deg, #0d47a1, #42a5f5);
  margin: 0;
  padding: 40px 0;
}

/* HEADER */
.page-header {
  width: 85%;
  margin: 0 auto 20px auto;
  background: rgba(255,255,255,0.15);
  color: white;
  padding: 15px 25px;
  border-radius: 15px;
  backdrop-filter: blur(10px);
  display: flex;
  justify-content: space-between;
  align-items: center;
  box-shadow: 0 4px 20px rgba(0,0,0,0.2);
}
.page-header h1 {
  font-weight: 700;
  font-size: 22px;
  letter-spacing: 0.5px;
}
.back-btn {
  text-decoration: none;
  background: white;
  color: #1976d2;
  padding: 8px 15px;
  border-radius: 8px;
  transition: 0.3s;
}
.back-btn:hover { background: #1976d2; color: white; }

/* CONTAINER */
.modern-container {
  width: 85%;
  margin: 0 auto;
  background: rgba(255,255,255,0.95);
  border-radius: 20px;
  padding: 40px 60px;
  box-shadow: 0 15px 30px rgba(0,0,0,0.15);
  animation: fadeIn 0.6s ease;
}

/* PROFILE UPLOAD */
.profile-section {
  text-align: center;
  margin-bottom: 35px;
}
.image-wrapper {
  position: relative;
  display: inline-block;
}
.image-wrapper img {
  width: 140px;
  height: 140px;
  border-radius: 50%;
  border: 4px solid #2196f3;
  object-fit: cover;
  box-shadow: 0 8px 15px rgba(0,0,0,0.15);
}
.upload-btn {
  position: absolute;
  bottom: 8px;
  right: 8px;
  background: #2196f3;
  color: white;
  border-radius: 50%;
  padding: 10px;
  cursor: pointer;
  transition: 0.3s;
}
.upload-btn:hover { background: #1976d2; }
#profile_picture { display: none; }
.profile-section p { color: #607d8b; margin-top: 10px; font-size: 0.9em; }

/* FORM */
.form-section {
  background: #f4f9ff;
  border-left: 5px solid #2196f3;
  border-radius: 12px;
  padding: 25px;
  margin-bottom: 30px;
}
.form-section h3 {
  color: #1565c0;
  font-size: 1.1em;
  margin-bottom: 15px;
  display: flex;
  align-items: center;
  gap: 10px;
}
.form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px 30px; }
.form-grid .full { grid-column: span 2; }

.form-group { position: relative; }
.form-group input, .form-group select, .form-group textarea {
  width: 100%;
  padding: 12px;
  border: 1px solid #cfd8dc;
  border-radius: 10px;
  background: white;
  font-size: 0.95em;
  transition: all 0.3s ease;
}
.form-group input:focus, .form-group select:focus, .form-group textarea:focus {
  border-color: #42a5f5;
  box-shadow: 0 0 8px rgba(33,150,243,0.4);
}
.form-group label {
  position: absolute;
  top: 12px; left: 15px;
  color: #607d8b;
  transition: 0.2s;
  background: white;
  padding: 0 4px;
}
.form-group input:focus + label,
.form-group input:not(:placeholder-shown) + label,
.form-group textarea:focus + label,
.form-group textarea:not(:placeholder-shown) + label,
.form-group select:focus + label,
.form-group select:valid + label {
  top: -8px;
  left: 10px;
  font-size: 0.75em;
  color: #1565c0;
}

/* BUTTON */
.modern-btn {
  width: 100%;
  background: linear-gradient(135deg, #2196f3, #64b5f6);
  color: white;
  border: none;
  padding: 15px;
  font-size: 1em;
  border-radius: 12px;
  cursor: pointer;
  font-weight: 600;
  transition: 0.3s;
}
.modern-btn:hover {
  background: linear-gradient(135deg, #1976d2, #42a5f5);
  transform: translateY(-3px);
}

/* ANIMATION */
@keyframes fadeIn {
  from {opacity: 0; transform: translateY(15px);}
  to {opacity: 1; transform: translateY(0);}
}
</style>

<script>
function previewImage(event) {
  const reader = new FileReader();
  reader.onload = function(){
    document.getElementById('preview').src = reader.result;
  }
  reader.readAsDataURL(event.target.files[0]);
}
</script>
