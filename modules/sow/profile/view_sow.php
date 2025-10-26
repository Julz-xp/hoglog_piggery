<?php
require_once __DIR__ . '/../../../config/db.php';

$id = $_GET['id'] ?? 0;

// 🧾 Fetch sow record
$stmt = $pdo->prepare("SELECT * FROM sows WHERE sow_id = ?");
$stmt->execute([$id]);
$sow = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>HogLog | Sow Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<style>
/* 🌈 Background Gradient */
body {
  background: linear-gradient(135deg, #74ebd5, #ACB6E5);
  min-height: 100vh;
  display: flex;
  font-family: 'Poppins', sans-serif;
  overflow-x: hidden;
}

/* 🧭 Sidebar */
.sidebar {
  width: 250px;
  height: 100vh;
  position: fixed;
  top: 0;
  left: 0;
  background: rgba(0, 0, 0, 0.6);
  backdrop-filter: blur(20px);
  color: #fff;
  transition: width 0.3s ease;
  overflow: hidden;
  z-index: 100;
}
.sidebar.collapsed {
  width: 80px;
}
.sidebar .brand {
  font-size: 1.3rem;
  font-weight: 600;
  text-align: center;
  padding: 20px 10px;
  color: #fff;
  border-bottom: 1px solid rgba(255,255,255,0.1);
}
.sidebar ul {
  list-style: none;
  padding: 0;
  margin-top: 20px;
}
.sidebar ul li {
  margin: 10px 0;
}
.sidebar ul li a {
  color: #dcdcdc;
  text-decoration: none;
  padding: 12px 20px;
  display: flex;
  align-items: center;
  transition: all 0.3s;
  border-left: 4px solid transparent;
}
.sidebar ul li a:hover {
  color: #fff;
  background: rgba(255, 255, 255, 0.1);
  border-left: 4px solid #0dcaf0;
}
.sidebar ul li i {
  font-size: 1.3rem;
  margin-right: 15px;
  transition: 0.3s;
}
.sidebar.collapsed ul li i {
  margin-right: 0;
}
.sidebar.collapsed ul li a span {
  display: none;
}

/* 🔄 Sidebar Toggle Button */
#toggle-btn {
  position: absolute;
  top: 20px;
  right: -18px;
  background: #0dcaf0;
  color: #fff;
  border-radius: 50%;
  border: none;
  width: 35px;
  height: 35px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: 0.3s;
  z-index: 200;
}
#toggle-btn:hover {
  background: #17a2b8;
}

/* 🧊 Main Content */
.main {
  margin-left: 250px;
  padding: 40px;
  flex: 1;
  transition: margin-left 0.3s;
}
.sidebar.collapsed ~ .main {
  margin-left: 80px;
}

/* 🐖 Sow Card */
.card.glass {
  background: rgba(255,255,255,0.2);
  border: 1px solid rgba(255,255,255,0.3);
  border-radius: 1rem;
  backdrop-filter: blur(20px);
  box-shadow: 0 4px 20px rgba(0,0,0,0.1);
  transition: all 0.3s ease;
}
.card.glass:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 24px rgba(0,0,0,0.2);
}

/* 🎨 Profile Avatar */
.profile-pic {
  width: 90px;
  height: 90px;
  border-radius: 50%;
  object-fit: cover;
  border: 3px solid #fff;
  box-shadow: 0 2px 8px rgba(0,0,0,0.2);
}

/* 📊 Dashboard Cards */
.metric-card {
  background: rgba(255,255,255,0.25);
  border-radius: 1rem;
  padding: 20px;
  color: #333;
  backdrop-filter: blur(15px);
  transition: all 0.3s;
  text-align: center;
}
.metric-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}
.metric-card h4 {
  font-weight: 600;
  color: #0d6efd;
}
.metric-card i {
  font-size: 2rem;
  margin-bottom: 10px;
  color: #0d6efd;
}

</style>
</head>

<body>
<!-- 🌐 SIDEBAR -->
<div class="sidebar" id="sidebar">
  <button id="toggle-btn"><i class="bi bi-list"></i></button>
  <div class="brand">🐖 HogLog</div>
  <ul>
    <li><a href="list_sow.php"><i class="bi bi-arrow-left-circle"></i><span>Back</span></a></li>
    <li><a href="../ai_attempt/add_ai.php?sow_id=<?= $sow['sow_id'] ?>"><i class="bi bi-droplet-fill"></i><span>Add AI Attempt</span></a></li>
    <li><a href="../ai_attempt/view_ai.php?sow_id=<?= $sow['sow_id'] ?>"><i class="bi bi-journal-text"></i><span>View AI Records</span></a></li>
    <?php if ($sow['status'] === 'Gilt'): ?>
      <li><a href="../gilt/view_roadmap.php?sow_id=<?= $sow['sow_id'] ?>"><i class="bi bi-diagram-3"></i><span>Gilt Roadmap</span></a></li>
    <?php endif; ?>
    <?php if ($sow['status'] === 'Gestating'): ?>
      <li><a href="../gestation/view_roadmap.php?sow_id=<?= $sow['sow_id'] ?>"><i class="bi bi-egg-fried"></i><span>Gestation Roadmap</span></a></li>
    <?php endif; ?>
    <li><a href="../health/add_health.php?sow_id=<?= $sow['sow_id'] ?>"><i class="bi bi-heart-pulse-fill"></i><span>Add Health Record</span></a></li>
    <li><a href="../feed/add_feed.php?sow_id=<?= $sow['sow_id'] ?>"><i class="bi bi-basket-fill"></i><span>Add Feed Record</span></a></li>
  </ul>
</div>

<!-- 🧠 MAIN CONTENT -->
<div class="main">
  <div class="container-fluid">
    <div class="row mb-4 align-items-center">
      <div class="col-md-8">
        <h2 class="fw-bold text-dark">Sow Dashboard</h2>
        <p class="text-muted mb-0">Monitor sow performance and health overview.</p>
      </div>
    </div>

    <!-- 🐷 SOW INFO CARD -->
    <div class="card glass p-4 mb-4">
      <div class="d-flex align-items-center gap-3">
        <img src="<?= $sow['picture'] ? '/hoglog_piggery/uploads/sows/' . $sow['picture'] : '/hoglog_piggery/assets/default_pig.png' ?>" alt="Sow Photo" class="profile-pic">
        <div>
          <h4 class="fw-bold mb-1"><?= htmlspecialchars($sow['ear_tag_no']) ?></h4>
          <p class="mb-0"><strong>Breed:</strong> <?= htmlspecialchars($sow['breed_line']) ?></p>
          <p class="mb-0"><strong>Status:</strong> <?= htmlspecialchars($sow['status']) ?></p>
        </div>
      </div>
    </div>

    <!-- 📊 METRICS PLACEHOLDER -->
    <div class="row g-3 mb-4">
      <div class="col-md-3">
        <div class="metric-card">
          <i class="bi bi-activity"></i>
          <h4>AI Success Rate</h4>
          <p>92% success</p>
        </div>
      </div>
      <div class="col-md-3">
        <div class="metric-card">
          <i class="bi bi-bar-chart-fill"></i>
          <h4>Gestation Progress</h4>
          <p>85% complete</p>
        </div>
      </div>
      <div class="col-md-3">
        <div class="metric-card">
          <i class="bi bi-heart-pulse"></i>
          <h4>Health Treatments</h4>
          <p>12 recorded</p>
        </div>
      </div>
      <div class="col-md-3">
        <div class="metric-card">
          <i class="bi bi-basket3-fill"></i>
          <h4>Feed Efficiency</h4>
          <p>3.5 kg/day avg</p>
        </div>
      </div>
    </div>

    <!-- 📋 SOW DETAILS TABLE -->
    <div class="card glass p-4">
      <h5 class="fw-semibold mb-3 text-dark"><i class="bi bi-card-list me-2"></i>Detailed Information</h5>
      <table class="table table-bordered text-center mb-0">
        <?php foreach ($sow as $key => $value): ?>
        <tr>
          <th><?= htmlspecialchars(ucwords(str_replace('_', ' ', $key))) ?></th>
          <td><?= htmlspecialchars($value) ?></td>
        </tr>
        <?php endforeach; ?>
      </table>
    </div>
  </div>
</div>

<script>
const sidebar = document.getElementById('sidebar');
const toggleBtn = document.getElementById('toggle-btn');
toggleBtn.onclick = () => {
  sidebar.classList.toggle('collapsed');
};
</script>
</body>
</html>
