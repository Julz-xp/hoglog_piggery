<?php
session_start();
require_once '../../config/db.php';

// (Optional) Ensure farm session if your app requires a farm to be logged in to edit users
if (!isset($_SESSION['farm_id'])) {
    echo "<p style='color:red;'>⚠️ Please log in as a farm first.</p>";
    exit;
}

// ---------- Load current user ----------
if (!isset($_GET['id'])) { die("User ID missing."); }
$id = (int)$_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$user) { die("User not found."); }

$success = null;
$error = null;

// ---------- Handle Update ----------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lock non-editable fields in backend
    $full_name              = trim($_POST['full_name'] ?? '');
    $date_of_birth          = $_POST['date_of_birth'] ?? null;
    $gender                 = $_POST['gender'] ?? '';
    $civil_status           = $_POST['civil_status'] ?? '';
    $nationality            = $_POST['nationality'] ?? '';
    $contact_number         = $_POST['contact_number'] ?? '';
    $email                  = $_POST['email'] ?? '';
    $home_address           = $_POST['home_address'] ?? '';
    $tin_number             = $_POST['tin_number'] ?? null;
    $sss_number             = $_POST['sss_number'] ?? null;
    $philhealth_number      = $_POST['philhealth_number'] ?? null;
    $pagibig_number         = $_POST['pagibig_number'] ?? null;
    $emergency_contact_name = $_POST['emergency_contact_name'] ?? '';
    $emergency_contact_number = $_POST['emergency_contact_number'] ?? '';
    $emergency_address      = $_POST['emergency_address'] ?? '';
    $medical_conditions     = $_POST['medical_conditions'] ?? null;
    $position               = $_POST['position'] ?? '';
    // username, password, user_id are not editable

    // ----- Handle Profile Picture Upload (optional) -----
    $newProfilePath = null;  // relative path to store in DB, e.g. 'uploads/users/xxx.jpg'

    if (!empty($_FILES['profile_picture']['name'])) {
        try {
            // Create upload directory if missing
            $uploadRootFs = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'users' . DIRECTORY_SEPARATOR;
            if (!is_dir($uploadRootFs)) {
                if (!mkdir($uploadRootFs, 0777, true)) {
                    throw new RuntimeException('Failed to create upload directory.');
                }
            }

            // Validate file
            if (!isset($_FILES['profile_picture']['error']) || is_array($_FILES['profile_picture']['error'])) {
                throw new RuntimeException('Invalid file upload parameters.');
            }

            switch ($_FILES['profile_picture']['error']) {
                case UPLOAD_ERR_OK: break;
                case UPLOAD_ERR_NO_FILE: throw new RuntimeException('No file sent.');
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE: throw new RuntimeException('Exceeded file size limit (max 2MB).');
                default: throw new RuntimeException('Unknown upload error.');
            }

            // Size check (<= 2MB)
            if ($_FILES['profile_picture']['size'] > 2 * 1024 * 1024) {
                throw new RuntimeException('Exceeded file size limit (max 2MB).');
            }

            // MIME validation + extension
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mime  = $finfo->file($_FILES['profile_picture']['tmp_name']);
            $ext   = null;
            if ($mime === 'image/jpeg') $ext = 'jpg';
            elseif ($mime === 'image/png') $ext = 'png';
            else throw new RuntimeException('Only JPG or PNG images are allowed.');

            // Generate unique filename: userID_timestamp.ext
            $filename = sprintf('user_%d_%s.%s', $id, date('YmdHis'), $ext);
            $destFs   = $uploadRootFs . $filename;

            if (!move_uploaded_file($_FILES['profile_picture']['tmp_name'], $destFs)) {
                throw new RuntimeException('Failed to move uploaded file.');
            }

            // Relative path saved to DB
            $newProfilePath = 'uploads/users/' . $filename;

            // (Optional) delete old file if exists and different
            if (!empty($user['profile_picture'])) {
                $oldFs = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . $user['profile_picture'];
                if (is_file($oldFs) && is_writable($oldFs)) {
                    @unlink($oldFs);
                }
            }
        } catch (Throwable $e) {
            $error = 'Image upload error: ' . htmlspecialchars($e->getMessage());
        }
    }

    if (!$error) {
        try {
            // Build UPDATE query dynamically depending on profile picture
            if ($newProfilePath) {
                $sql = "UPDATE users SET
                    full_name=?, date_of_birth=?, gender=?, civil_status=?, nationality=?,
                    contact_number=?, email=?, home_address=?,
                    tin_number=?, sss_number=?, philhealth_number=?, pagibig_number=?,
                    emergency_contact_name=?, emergency_contact_number=?, emergency_address=?,
                    medical_conditions=?, position=?, profile_picture=?
                    WHERE user_id=?";
                $params = [
                    $full_name, $date_of_birth, $gender, $civil_status, $nationality,
                    $contact_number, $email, $home_address,
                    $tin_number, $sss_number, $philhealth_number, $pagibig_number,
                    $emergency_contact_name, $emergency_contact_number, $emergency_address,
                    $medical_conditions, $position, $newProfilePath,
                    $id
                ];
            } else {
                $sql = "UPDATE users SET
                    full_name=?, date_of_birth=?, gender=?, civil_status=?, nationality=?,
                    contact_number=?, email=?, home_address=?,
                    tin_number=?, sss_number=?, philhealth_number=?, pagibig_number=?,
                    emergency_contact_name=?, emergency_contact_number=?, emergency_address=?,
                    medical_conditions=?, position=?
                    WHERE user_id=?";
                $params = [
                    $full_name, $date_of_birth, $gender, $civil_status, $nationality,
                    $contact_number, $email, $home_address,
                    $tin_number, $sss_number, $philhealth_number, $pagibig_number,
                    $emergency_contact_name, $emergency_contact_number, $emergency_address,
                    $medical_conditions, $position,
                    $id
                ];
            }

            $u = $pdo->prepare($sql);
            $u->execute($params);

            // Reload fresh user data for display (and preview)
            $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
            $stmt->execute([$id]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            $success = "✅ User updated successfully.";
        } catch (PDOException $e) {
            $error = "❌ Error updating user: " . htmlspecialchars($e->getMessage());
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>HogLog | Edit User</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<style>
:root{
  --blue1:#4fc3f7; --blue2:#29b6f6; --blue3:#0277bd;
  --bg:#f5f7fb; --white:#fff; --border:#e3f2fd; --text:#333;
}
*{box-sizing:border-box}
body{
  font-family:"Poppins",sans-serif;
  margin:0; background:linear-gradient(135deg,#e3f2fd,#bbdefb);
  padding:30px;
}
.wrapper{
  max-width:1100px; margin:0 auto;
}
.header-bar{
  display:flex; justify-content:space-between; align-items:center;
  background:linear-gradient(90deg,var(--blue1),var(--blue2));
  color:#fff; padding:16px 24px; border-radius:14px;
  box-shadow:0 8px 20px rgba(0,0,0,.12); margin-bottom:20px;
}
.header-bar h1{margin:0; font-size:20px; font-weight:700; letter-spacing:.3px}
.back-btn{
  background:#fff; color:var(--blue2); text-decoration:none;
  padding:8px 14px; border-radius:10px; font-weight:600; display:inline-flex; align-items:center; gap:8px;
  transition:.25s;
}
.back-btn:hover{background:var(--blue2); color:#fff; box-shadow:0 6px 14px rgba(0,0,0,.15)}

.card{
  background:var(--white); border-radius:16px; padding:24px;
  box-shadow:0 8px 24px rgba(0,0,0,.08); margin-bottom:18px;
}

.top-flex{
  display:flex; gap:24px; align-items:center; flex-wrap:wrap;
}
.avatar{
  width:110px; height:110px; border-radius:50%; overflow:hidden; flex:0 0 auto;
  border:4px solid #fff; box-shadow:0 6px 18px rgba(0,0,0,.15);
  background:#f0f7ff; display:flex; align-items:center; justify-content:center;
}
.avatar img{width:100%;height:100%;object-fit:cover}
.avatar .placeholder{font-size:42px; color:#90caf9}

.id-block{
  background:#f7fbff; border:1px solid var(--border); border-radius:12px;
  padding:12px 16px; color:#0d47a1; font-weight:700;
}

.notice{
  padding:12px 16px; border-radius:10px; margin-bottom:16px; font-weight:600;
}
.notice.ok{background:#e8f5e9; color:#1b5e20; border:1px solid #a5d6a7;}
.notice.err{background:#ffebee; color:#b71c1c; border:1px solid #ef9a9a;}

.form-section{
  background:#f6fbff; border-left:6px solid var(--blue2);
  border-radius:12px; padding:20px; margin-bottom:18px;
}
.form-section h3{
  margin:0 0 12px; color:var(--blue3); font-size:16px; display:flex; align-items:center; gap:8px;
}

.grid{display:grid; grid-template-columns:1fr 1fr; gap:18px 24px}
.grid .full{grid-column:1 / -1}

.group{position:relative}
.group input, .group select, .group textarea{
  width:100%; padding:12px 12px; border:1px solid #cfd8dc; border-radius:10px; background:#fff; outline:none; font-size:.95em; transition:.2s;
}
.group textarea{min-height:90px; resize:vertical}
.group input:focus, .group select:focus, .group textarea:focus{
  border-color:#42a5f5; box-shadow:0 0 0 3px rgba(66,165,245,.18)
}
.group label{
  position:absolute; left:12px; top:12px; color:#607d8b; font-size:.9em; pointer-events:none; transition:.18s;
  background:#fff; padding:0 6px;
}
.group input:not(:placeholder-shown) + label,
.group input:focus + label,
.group textarea:not(:placeholder-shown) + label,
.group textarea:focus + label,
.group select:focus + label{
  top:-10px; font-size:.75em; color:var(--blue3)
}

.locked{
  background:#f5f7fb; color:#78909c; cursor:not-allowed
}

.file-note{font-size:.85em; color:#607d8b; margin-top:6px}

.actions{
  display:flex; gap:12px; margin-top:8px; flex-wrap:wrap;
}
.btn{
  border:none; padding:12px 18px; border-radius:10px; font-weight:700; cursor:pointer; transition:.25s;
}
.btn.save{background:linear-gradient(135deg,var(--blue1),var(--blue2)); color:#fff}
.btn.save:hover{filter:brightness(.95); transform:translateY(-1px)}
.btn.cancel{background:#eceff1;color:#455a64}
.btn.cancel:hover{background:#e0e0e0}

@media (max-width: 820px){
  .grid{grid-template-columns:1fr}
  .top-flex{flex-direction:column; align-items:flex-start}
}
</style>
</head>
<body>
<div class="wrapper">

  <div class="header-bar">
    <h1>✏️ Edit User</h1>
    <a href="list_users.php" class="back-btn"><i class="bi bi-arrow-left-circle"></i> Back to List</a>
  </div>

  <?php if ($success): ?>
    <div class="notice ok"><?= $success ?></div>
  <?php endif; ?>
  <?php if ($error): ?>
    <div class="notice err"><?= $error ?></div>
  <?php endif; ?>

  <!-- Top Card: Avatar + ID & Locked fields -->
  <div class="card">
    <div class="top-flex">
      <div class="avatar">
        <?php if (!empty($user['profile_picture'])): ?>
          <img id="avatarPreview" src="../../<?= htmlspecialchars($user['profile_picture']) ?>" alt="Profile">
        <?php else: ?>
          <div id="avatarFallback" class="placeholder"><i class="bi bi-person-fill"></i></div>
          <img id="avatarPreview" src="" alt="" style="display:none;">
        <?php endif; ?>
      </div>

      <div class="id-block">
        <div>🆔 User ID: <strong><?= htmlspecialchars($user['user_id']) ?></strong></div>
        <div>👤 Username: <strong><?= htmlspecialchars($user['username']) ?></strong></div>
        <div>🔒 Password: <strong>********</strong></div>
      </div>
    </div>
  </div>

  <form method="POST" enctype="multipart/form-data">
    <!-- Personal Information -->
    <div class="form-section">
      <h3><i class="bi bi-person-lines-fill"></i> Personal Information</h3>
      <div class="grid">
        <div class="group">
          <input type="text" name="full_name" value="<?= htmlspecialchars($user['full_name']) ?>" placeholder=" " required>
          <label>Full Name</label>
        </div>
        <div class="group">
          <input type="date" name="date_of_birth" value="<?= htmlspecialchars($user['date_of_birth']) ?>" placeholder=" ">
          <label>Date of Birth</label>
        </div>
        <div class="group">
          <select name="gender">
            <option value="" <?= $user['gender']==''?'selected':''; ?>>Select Gender</option>
            <option <?= $user['gender']=='Male'?'selected':''; ?>>Male</option>
            <option <?= $user['gender']=='Female'?'selected':''; ?>>Female</option>
            <option <?= $user['gender']=='Prefer not to say'?'selected':''; ?>>Prefer not to say</option>
          </select>
          <label>Gender</label>
        </div>
        <div class="group">
          <input type="text" name="civil_status" value="<?= htmlspecialchars($user['civil_status']) ?>" placeholder=" ">
          <label>Civil Status</label>
        </div>
        <div class="group">
          <input type="text" name="nationality" value="<?= htmlspecialchars($user['nationality']) ?>" placeholder=" ">
          <label>Nationality</label>
        </div>
        <div class="group">
          <input type="text" name="contact_number" value="<?= htmlspecialchars($user['contact_number']) ?>" placeholder=" ">
          <label>Contact Number</label>
        </div>
        <div class="group">
          <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" placeholder=" ">
          <label>Email</label>
        </div>
        <div class="group full">
          <textarea name="home_address" placeholder=" "><?= htmlspecialchars($user['home_address']) ?></textarea>
          <label>Home Address</label>
        </div>
      </div>
    </div>

    <!-- Government IDs -->
    <div class="form-section">
      <h3><i class="bi bi-file-earmark-text"></i> Government IDs (Optional)</h3>
      <div class="grid">
        <div class="group">
          <input type="text" name="tin_number" value="<?= htmlspecialchars($user['tin_number']) ?>" placeholder=" ">
          <label>TIN</label>
        </div>
        <div class="group">
          <input type="text" name="sss_number" value="<?= htmlspecialchars($user['sss_number']) ?>" placeholder=" ">
          <label>SSS</label>
        </div>
        <div class="group">
          <input type="text" name="philhealth_number" value="<?= htmlspecialchars($user['philhealth_number']) ?>" placeholder=" ">
          <label>PhilHealth</label>
        </div>
        <div class="group">
          <input type="text" name="pagibig_number" value="<?= htmlspecialchars($user['pagibig_number']) ?>" placeholder=" ">
          <label>Pag-IBIG</label>
        </div>
      </div>
    </div>

    <!-- Emergency -->
    <div class="form-section">
      <h3><i class="bi bi-heart-pulse-fill"></i> Emergency Details</h3>
      <div class="grid">
        <div class="group">
          <input type="text" name="emergency_contact_name" value="<?= htmlspecialchars($user['emergency_contact_name']) ?>" placeholder=" ">
          <label>Emergency Contact Name</label>
        </div>
        <div class="group">
          <input type="text" name="emergency_contact_number" value="<?= htmlspecialchars($user['emergency_contact_number']) ?>" placeholder=" ">
          <label>Emergency Number</label>
        </div>
        <div class="group full">
          <textarea name="emergency_address" placeholder=" "><?= htmlspecialchars($user['emergency_address']) ?></textarea>
          <label>Emergency Address</label>
        </div>
      </div>
    </div>

    <!-- Medical + Position -->
    <div class="form-section">
      <h3><i class="bi bi-capsule-pill"></i> Medical & Role</h3>
      <div class="grid">
        <div class="group full">
          <input type="text" name="medical_conditions" value="<?= htmlspecialchars($user['medical_conditions']) ?>" placeholder=" ">
          <label>Medical Conditions (if any)</label>
        </div>
        <div class="group">
          <select name="position" required>
            <option value="">Select Position</option>
            <option <?= $user['position']=='Farm Owner'?'selected':''; ?>>Farm Owner</option>
            <option <?= $user['position']=='Farm Vet'?'selected':''; ?>>Farm Vet</option>
            <option <?= $user['position']=='Caretaker'?'selected':''; ?>>Caretaker</option>
          </select>
          <label>Position</label>
        </div>
        <div class="group">
          <input type="text" value="<?= htmlspecialchars($user['username']) ?>" class="locked" disabled placeholder=" ">
          <label>Username (locked)</label>
        </div>
      </div>
    </div>

    <!-- Profile Picture Upload -->
    <div class="form-section">
      <h3><i class="bi bi-camera-fill"></i> Profile Picture</h3>
      <div class="grid">
        <div class="group full">
          <input type="file" name="profile_picture" id="profile_picture" accept="image/png, image/jpeg" onchange="previewAvatar(this)">
          <label>Upload new photo (JPG/PNG, max 2MB)</label>
          <div class="file-note">Tip: Use a square image for best circular fit.</div>
        </div>
      </div>
    </div>

    <div class="actions">
      <button type="submit" class="btn save">💾 Save Changes</button>
      <a class="btn cancel" href="list_users.php">Cancel</a>
    </div>
  </form>
</div>

<script>
// Live preview for avatar
function previewAvatar(input){
  const file = input.files && input.files[0];
  const img = document.getElementById('avatarPreview');
  const fallback = document.getElementById('avatarFallback');

  if (file){
    const reader = new FileReader();
    reader.onload = function(e){
      img.src = e.target.result;
      img.style.display = 'block';
      if (fallback) fallback.style.display = 'none';
    };
    reader.readAsDataURL(file);
  }
}
</script>
</body>
</html>
