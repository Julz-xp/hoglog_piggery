<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Database connection
require_once __DIR__ . '/../../config/db.php';

$farm_id = $_SESSION['farm_id'] ?? 1;
$farm_name = $_SESSION['farm_name'] ?? 'HogLog Farm';

// Fetch Piglet Data
$total_piglets = $healthy_piglets = $weak_piglets = 0;

try {
    $stmt = $pdo->prepare("SELECT 
        COUNT(*) AS total,
        SUM(CASE WHEN status='Healthy' THEN 1 ELSE 0 END) AS healthy,
        SUM(CASE WHEN status='Weak' THEN 1 ELSE 0 END) AS weak
        FROM piglets WHERE farm_id = ?");
    $stmt->execute([$farm_id]);
    $stats = $stmt->fetch(PDO::FETCH_ASSOC);

    $total_piglets = $stats['total'] ?? 0;
    $healthy_piglets = $stats['healthy'] ?? 0;
    $weak_piglets = $stats['weak'] ?? 0;
} catch (Exception $e) {
    echo "<p style='color:red'>DB Error: ".$e->getMessage()."</p>";
}

$survival_rate = $total_piglets ? round(($healthy_piglets / $total_piglets) * 100, 1) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Piglets Dashboard | HogLog</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
:root{
  --blue:#1565c0;--blue-100:#e3f2fd;--bg:#f5f7fb;--card:#fff;
  --text:#1f2937;--muted:#6b7280;--border:#eef2f7;
  --pig-orange:#f59e0b;--danger:#ef4444;--success:#16a34a;
}
body{margin:0;background:var(--bg);font-family:Poppins,sans-serif;color:var(--text)}
.topbar{height:64px;background:var(--card);border-bottom:1px solid var(--border);
  display:flex;align-items:center;justify-content:space-between;padding:0 16px;}
.brand{display:flex;align-items:center;gap:10px;color:var(--blue);font-weight:700}
.layout{display:flex;min-height:calc(100vh - 64px)}
.sidebar{width:260px;background:var(--card);border-right:1px solid var(--border);
  padding:12px 10px;position:sticky;top:64px;height:calc(100vh - 64px);}
.nav{display:flex;flex-direction:column;height:100%}
.nav-item{display:flex;align-items:center;gap:12px;padding:10px 12px;margin:3px 4px;
  border-radius:12px;color:#111827;text-decoration:none;}
.nav-item:hover{background:var(--blue-100)}
.nav-item.active{background:var(--blue);color:#fff}
.nav-item.danger{color:var(--danger)}
.content{flex:1;padding:22px}
.page-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:20px}
.page-head h1{margin:0;font-size:22px}
.btns{display:flex;gap:10px}
.btn{background:var(--blue);color:white;padding:8px 14px;border:none;border-radius:10px;
  cursor:pointer;font-size:14px;text-decoration:none;display:flex;align-items:center;gap:6px;}
.btn:hover{background:#0d47a1}
.btn-outline{background:transparent;border:1px solid var(--blue);color:var(--blue);}
.btn-outline:hover{background:var(--blue);color:#fff}
.reports-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:16px}
.report-card{border:1px solid var(--border);border-radius:16px;padding:16px;background:#fff;box-shadow:0 2px 4px rgba(0,0,0,0.03)}
.report-head{display:flex;align-items:center;justify-content:space-between}
.report-title{font-weight:700;display:flex;align-items:center;gap:8px}
.icon{width:34px;height:34px;border-radius:10px;background:var(--blue-100);color:var(--blue);
  display:flex;align-items:center;justify-content:center;}
.report-kpi{font-size:28px;font-weight:700;margin-top:6px}
.report-sub{color:var(--muted);font-size:13px}
.spark svg{width:100%;height:80px}
</style>
</head>
<body>

<header class="topbar">
  <div class="brand"><i class="bi bi-heart-pulse"></i><span>HogLog | Piglets Dashboard</span></div>
  <div><a href="/hoglog_piggery/modules/users/user_logout.php" class="nav-item danger"><i class="bi bi-box-arrow-right"></i> Logout</a></div>
</header>

<div class="layout">
  <aside class="sidebar">
    <nav class="nav">
      <a href="/hoglog_piggery/modules/users/user_dashboard.php" class="nav-item"><i class="bi bi-speedometer2"></i><span>Dashboard</span></a>
      <a href="/hoglog_piggery/modules/sow/sow_dashboard.php" class="nav-item"><i class="bi bi-piggy-bank"></i><span>Sow Dashboard</span></a>
      <a href="/hoglog_piggery/modules/batch/batch_fattener.php" class="nav-item"><i class="bi bi-boxes"></i><span>Fatteners Dashboard</span></a>
      <a href="#" class="nav-item active"><i class="bi bi-heart-pulse"></i><span>Piglets Dashboard</span></a>
      <a href="/hoglog_piggery/modules/farmwide_expenses/expenses_dashboard.php" class="nav-item"><i class="bi bi-cash-stack"></i><span>Expenses</span></a>
      <a href="/hoglog_piggery/modules/farm_wide_feed/feed_dashboard.php" class="nav-item"><i class="bi bi-bag-check"></i><span>Feed Dashboard</span></a>
    </nav>
  </aside>

  <main class="content">
    <div class="page-head">
      <h1>Piglets Overview</h1>
      <div class="btns">
        <!-- ✅ Add Profile button -->
        <a href="/hoglog_piggery/modules/piglets/profile/add_profile.php" class="btn"><i class="bi bi-plus-circle"></i> Add Piglet Profile</a>
        <!-- ✅ List Profile button -->
        <a href="/hoglog_piggery/modules/piglets/list_profile.php" class="btn btn-outline"><i class="bi bi-list-ul"></i> View Piglet List</a>
      </div>
    </div>

    <section class="reports-grid">
      <div class="report-card">
        <div class="report-head">
          <div class="report-title"><span class="icon"><i class="bi bi-piggy-bank"></i></span> Total Piglets</div>
        </div>
        <div class="report-kpi"><?= $total_piglets ?></div>
        <div class="report-sub"><?= htmlspecialchars($farm_name) ?></div>
      </div>

      <div class="report-card">
        <div class="report-head">
          <div class="report-title"><span class="icon"><i class="bi bi-heart-fill"></i></span> Healthy Piglets</div>
        </div>
        <div class="report-kpi" style="color:var(--success)"><?= $healthy_piglets ?></div>
        <div class="report-sub">Out of <?= $total_piglets ?></div>
      </div>

      <div class="report-card">
        <div class="report-head">
          <div class="report-title"><span class="icon"><i class="bi bi-exclamation-triangle"></i></span> Weak Piglets</div>
        </div>
        <div class="report-kpi" style="color:var(--danger)"><?= $weak_piglets ?></div>
        <div class="report-sub"><?= $total_piglets ? round(($weak_piglets/$total_piglets)*100,1) : 0 ?>% of total</div>
      </div>

      <div class="report-card">
        <div class="report-head">
          <div class="report-title"><span class="icon"><i class="bi bi-heart-pulse"></i></span> Survival Rate</div>
        </div>
        <div class="report-kpi"><?= $survival_rate ?>%</div>
        <div class="spark">
          <svg viewBox="0 0 100 30" id="pigSpark">
            <circle cx="20" cy="15" r="2" fill="var(--pig-orange)"></circle>
            <circle cx="45" cy="15" r="2" fill="var(--pig-orange)"></circle>
            <circle cx="70" cy="15" r="2" fill="var(--pig-orange)"></circle>
          </svg>
        </div>
      </div>
    </section>
  </main>
</div>

<script>
(() => {
  const dots = document.querySelectorAll('#pigSpark circle');
  let t=0;
  function loop(){
    t+=0.08;
    dots.forEach((c,i)=>{
      const r=2+Math.abs(Math.sin(t+i*0.8))*2;
      c.setAttribute('r',r.toFixed(2));
    });
    requestAnimationFrame(loop);
  }
  requestAnimationFrame(loop);
})();
</script>

</body>
</html>
