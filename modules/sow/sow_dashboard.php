<?php
session_start();
require_once __DIR__ . '/../../config/db.php';

if (!isset($_SESSION['farm_id'])) {
  header("Location: /hoglog_piggery/farm_login.php");
  exit;
}

$farm_id = (int)$_SESSION['farm_id'];

/* ---- Data helpers ---- */
function sowCount($pdo, $farm_id, $status = null) {
  if ($status === null) {
    $q = $pdo->prepare("SELECT COUNT(*) FROM sows WHERE farm_id=?");
    $q->execute([$farm_id]);
  } else {
    $q = $pdo->prepare("SELECT COUNT(*) FROM sows WHERE farm_id=? AND status=?");
    $q->execute([$farm_id, $status]);
  }
  return (int)$q->fetchColumn();
}

$total     = sowCount($pdo, $farm_id, null);
$gilt      = sowCount($pdo, $farm_id, 'Gilt');
$gestation = sowCount($pdo, $farm_id, 'Gestating');
$lactation = sowCount($pdo, $farm_id, 'Lactating');
$dry       = sowCount($pdo, $farm_id, 'Dry');

/* Optional: parse one big number from your PHP summary pages (fallback 0) */
function captureNumber($file) {
  if (!file_exists($file)) return 0;
  ob_start(); include $file; $html = trim(ob_get_clean());
  if (preg_match_all('/\d{1,3}(?:,\d{3})*(?:\.\d+)?|\d+\.\d+/', strip_tags($html), $m)) {
    $nums = array_map(fn($x)=>(float)str_replace(',', '', $x), $m[0]);
    rsort($nums);
    return $nums[0] ?? 0;
  }
  return 0;
}
$totalExpenses  = captureNumber(__DIR__ . '/sow_total_expenses.php');
$totalFeedKg    = captureNumber(__DIR__ . '/feed_consume.php');

/* ---- THEME: set to 'blue' | 'teal' | 'beige' ---- */
$theme = 'beige'; // change here to 'blue' or 'teal'
$themeClass = 'theme-'.$theme;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>HogLog Smart Piggery Management</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;800&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
*{box-sizing:border-box;font-family:"Poppins",sans-serif}

:root{
  /* base (overridden by theme classes) */
  --bg:#f2f4f7;
  --panel:#e9eef2;
  --card:#e9eef2;
  --ink:#23323b;
  --muted:#6b7a86;
  --accent:#1565c0;
  --chip:#f0e5cc;
  --shadow:0 10px 20px rgba(0,0,0,.08);
  --border:#cfd9e0;
}

/* ===== THEMES ===== */

/* A) Farm Blue */
.theme-blue{
  --bg:#eef3f9;
  --panel:#e6eef7;
  --card:#eaf1f8;
  --ink:#1b2a35;
  --muted:#5e6f7a;
  --accent:#1565c0;
  --chip:#e9f1ff;
  --border:#c9d7e3;
}

/* B) Green-Teal */
.theme-teal{
  --bg:#eef7f4;
  --panel:#e2f0ec;
  --card:#e6f3ef;
  --ink:#14322e;
  --muted:#4f6b64;
  --accent:#0d8b7f;
  --chip:#e4f5ef;
  --border:#c3e0d9;
}

/* C) Pastel Beige (mockup) */
.theme-beige{
  --bg:#efeff3;
  --panel:#dfe8ed;
  --card:#e7eef2;
  --ink:#23323b;
  --muted:#607080;
  --accent:#2760d1;
  --chip:#ebdfc9;
  --border:#c9d4da;
}

/* ===== Layout ===== */
html,body{margin:0;background:var(--bg);color:var(--ink)}

.header{
  background:#d6d2d2;
  padding:10px 16px;
  border-bottom:2px solid #c8c4c4;
}
.header h1{
  margin:0;font-size:20px;font-weight:800;
  display:inline-block;padding:6px 12px;background:var(--chip);
  border-radius:12px;
}

.shell{display:grid;grid-template-columns:70px 1fr;gap:12px;padding:14px}
@media (max-width:1080px){ .shell{grid-template-columns:1fr} }

/* Sidebar */
.toolbar{
  background:#d8d8db;border-radius:12px;padding:10px 8px;
  display:flex;flex-direction:column;align-items:center;gap:10px;
  box-shadow:var(--shadow);
}
.tbtn{
  width:44px;height:44px;border-radius:50%; border:none;cursor:pointer;
  display:grid;place-items:center; background:#f0f0ff;color:var(--accent);font-size:18px;
  box-shadow:var(--shadow); transition:.2s;
}
.tbtn:hover{ transform:translateY(-2px); background:#e8efff }

/* Grid (exact 4x2) */
.board{
  display:grid; gap:12px;
  grid-template-columns: repeat(4, 1fr);
  grid-template-areas:
    "pop gilt lact dry"
    "gest feed exp cal";
}
@media (max-width:1080px){
  .board{ grid-template-columns: 1fr 1fr; grid-template-areas:
    "pop pop"
    "gilt lact"
    "gest dry"
    "feed feed"
    "exp cal"; }
}
@media (max-width:680px){
  .board{ grid-template-columns: 1fr; grid-template-areas:
    "pop" "gilt" "lact" "dry" "gest" "feed" "exp" "cal"; }
}

.tile{
  grid-area:auto; position:relative; background:var(--panel);
  border:2px solid var(--border); border-radius:12px; padding:10px;
  box-shadow:var(--shadow); overflow:hidden; min-height:210px;
  transition: transform .15s, box-shadow .15s, border-color .2s;
}
.tile:hover{ transform:translateY(-2px); box-shadow:0 14px 26px rgba(0,0,0,.12); border-color:#b6c7d2; }
.tile-title{
  position:absolute; left:10px; bottom:10px;
  background:var(--chip); color:#000; font-weight:800; font-size:13px;
  padding:6px 12px; border-radius:12px; letter-spacing:.3px; box-shadow:var(--shadow);
}

/* assign areas */
#tile-pop{ grid-area:pop }
#tile-gilt{ grid-area:gilt }
#tile-lact{ grid-area:lact }
#tile-dry{ grid-area:dry }
#tile-gest{ grid-area:gest }
#tile-feed{ grid-area:feed }
#tile-exp{ grid-area:exp }
#tile-cal{ grid-area:cal }

/* mini labels for donuts */
.mini-label{
  position:absolute; left:10px; top:10px; background:var(--chip);
  padding:4px 10px; border-radius:10px; font-size:12px; font-weight:800;
}

/* inline expand */
.expand{
  position:absolute; inset:auto 0 0 0; background:rgba(255,255,255,.98);
  max-height:0; opacity:0; overflow:hidden; border-top:1px dashed #ccd9e1;
  transition:max-height .35s ease, opacity .25s ease; padding:0 12px;
}
.tile.open .expand{ max-height:520px; opacity:1; padding:12px; }
.expand .close{
  position:absolute; right:12px; top:12px;
  background:#fff;border:1px solid #cfd9e0;border-radius:10px;padding:6px 10px;cursor:pointer;
}

/* calendar */
.cal-head{display:flex;justify-content:space-between;align-items:center;margin-bottom:8px}
.cbtn{border:1px solid #dbe7fb;background:#fff;color:var(--accent);padding:6px 10px;border-radius:8px;cursor:pointer}
.cgrid{display:grid;gap:6px;grid-template-columns:repeat(7,1fr)}
.cell{background:#fff;border:1px solid #e6eef5;border-radius:10px;min-height:64px;padding:6px;font-size:12px;position:relative}
.dow{font-weight:800;text-align:center;background:#f1f6ff;border:none}
.today{outline:2px solid var(--accent)}

/* small table */
.table{width:100%;border-collapse:separate;border-spacing:0}
.table th,.table td{padding:8px 10px;border-bottom:1px solid #e6eef5;font-size:13px}
.table th{background:#f6fbff;color:#445}
</style>
</head>
<body class="<?php echo $themeClass; ?>">

<div class="header">
  <h1>hoglog smart piggery management</h1>
</div>

<div class="shell">
  <!-- Sidebar -->
  <div class="toolbar">
    <button class="tbtn" title="Add Sow" onclick="location.href='profile/add_sow.php'">＋</button>
    <button class="tbtn" title="SOP">⚙️</button>
    <button class="tbtn" title="Notes" onclick="toggleTile('notes')">📌</button>
    <button class="tbtn" title="Sow History">🔄</button>
    <button class="tbtn" title="Back to User Dashboard" onclick="location.href='/hoglog_piggery/modules/users/user_dashboard.php'">⬅️</button>
  </div>

  <!-- Board -->
  <div class="board">

    <!-- SOW POPULATION (bar) -->
    <div class="tile" id="tile-pop" onclick="toggleTile('pop')">
      <canvas id="chartPop"></canvas>
      <div class="tile-title">SOW POPULATION</div>
      <div class="expand" id="exp-pop">
        <button class="close" onclick="toggleTile('pop');event.stopPropagation()">Close</button>
        <h3 style="margin:6px 0 8px 0">Summary</h3>
        <table class="table">
          <thead><tr><th>Stage</th><th>Count</th></tr></thead>
          <tbody>
            <tr><td>Gilt</td><td><?= number_format($gilt) ?></td></tr>
            <tr><td>Gestation</td><td><?= number_format($gestation) ?></td></tr>
            <tr><td>Lactation</td><td><?= number_format($lactation) ?></td></tr>
            <tr><td>Dry</td><td><?= number_format($dry) ?></td></tr>
            <tr><th>Total</th><th><?= number_format($total) ?></th></tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- GILT donut -->
    <div class="tile" id="tile-gilt" onclick="toggleTile('gilt')">
      <span class="mini-label">GILT</span>
      <canvas id="donutGilt" style="margin-top:22px;height:160px"></canvas>
      <div class="expand" id="exp-gilt">
        <button class="close" onclick="toggleTile('gilt');event.stopPropagation()">Close</button>
        <p>Gilt population details coming soon.</p>
      </div>
    </div>

    <!-- LACTATION donut -->
    <div class="tile" id="tile-lact" onclick="toggleTile('lact')">
      <span class="mini-label">LACTATION</span>
      <canvas id="donutLact" style="margin-top:22px;height:160px"></canvas>
      <div class="expand" id="exp-lact">
        <button class="close" onclick="toggleTile('lact');event.stopPropagation()">Close</button>
        <p>Lactation population details coming soon.</p>
      </div>
    </div>

    <!-- DRY donut -->
    <div class="tile" id="tile-dry" onclick="toggleTile('dry')">
      <span class="mini-label">DRY</span>
      <canvas id="donutDry" style="margin-top:22px;height:160px"></canvas>
      <div class="expand" id="exp-dry">
        <button class="close" onclick="toggleTile('dry');event.stopPropagation()">Close</button>
        <p>Dry population details coming soon.</p>
      </div>
    </div>

    <!-- GESTATION donut -->
    <div class="tile" id="tile-gest" onclick="toggleTile('gest')">
      <span class="mini-label">GESTATION</span>
      <canvas id="donutGest" style="margin-top:22px;height:160px"></canvas>
      <div class="expand" id="exp-gest">
        <button class="close" onclick="toggleTile('gest');event.stopPropagation()">Close</button>
        <p>Gestation population details coming soon.</p>
      </div>
    </div>

    <!-- FEED CONSUMPTION (bar) -->
    <div class="tile" id="tile-feed" onclick="toggleTile('feed')">
      <canvas id="chartFeed"></canvas>
      <div class="tile-title">FEED CONSUMPTION</div>
      <div class="expand" id="exp-feed">
        <button class="close" onclick="toggleTile('feed');event.stopPropagation()">Close</button>
        <p>Total parsed feed: <strong><?= number_format($totalFeedKg,2) ?></strong> kg</p>
        <p><em>Replace the JS array <code>feedPerStage</code> with your real per-stage values when ready.</em></p>
      </div>
    </div>

    <!-- EXPENSE (pie) -->
    <div class="tile" id="tile-exp" onclick="toggleTile('exp')">
      <canvas id="chartExp"></canvas>
      <div class="tile-title">EXPENSE</div>
      <div class="expand" id="exp-exp">
        <button class="close" onclick="toggleTile('exp');event.stopPropagation()">Close</button>
        <p>Total parsed expenses: <strong>₱ <?= number_format($totalExpenses,2) ?></strong></p>
        <p><em>Replace the JS array <code>expensePerStage</code> with your real per-stage values when ready.</em></p>
      </div>
    </div>

    <!-- CALENDAR -->
    <div class="tile" id="tile-cal" onclick="toggleTile('cal')">
      <div style="display:grid;place-items:center;height:100%;opacity:.9">
        <div>
          <div id="miniMonth" style="text-align:center;font-weight:800;margin-bottom:6px">OCTOBER 2025</div>
          <div style="font-size:12px;color:var(--muted);text-align:center">Click to open calendar</div>
        </div>
      </div>
      <div class="tile-title">CALENDAR</div>
      <div class="expand" id="exp-cal">
        <button class="close" onclick="toggleTile('cal');event.stopPropagation()">Close</button>
        <div class="cal-head">
          <h3 id="calTitle" style="margin:6px 0">Calendar</h3>
          <div>
            <button class="cbtn" id="prevCal">‹ Prev</button>
            <button class="cbtn" id="todayCal">Today</button>
            <button class="cbtn" id="nextCal">Next ›</button>
          </div>
        </div>
        <div class="cgrid" id="calGrid"></div>
        <div style="font-size:12px;color:var(--muted);margin-top:6px">Click a date to pin a tiny note (saved locally).</div>
      </div>
    </div>

    <!-- Hidden NOTES tile opened from sidebar -->
    <div class="tile" id="tile-notes" style="display:none">
      <div class="tile-title">NOTES</div>
      <div class="expand" style="max-height:520px;opacity:1;padding:12px">
        <h3 style="margin:6px 0">Notes</h3>
        <textarea id="noteArea" style="width:100%;min-height:220px;border:1px solid var(--border);border-radius:10px;padding:10px"></textarea>
        <div style="margin-top:8px;display:flex;gap:8px">
          <button class="cbtn" id="saveNote">Save</button>
          <button class="cbtn" id="clearNote">Clear</button>
        </div>
        <div style="font-size:12px;color:var(--muted);margin-top:6px">Saved in this browser (localStorage).</div>
      </div>
    </div>

  </div>
</div>

<script>
/* toggle tiles */
function toggleTile(key){
  const tile=document.getElementById('tile-'+key);
  const exp=document.getElementById('exp-'+key);
  if(!tile || !exp) return;
  tile.classList.toggle('open');
}

/* data from PHP */
const counts = {
  total: <?= $total ?>,
  gilt: <?= $gilt ?>,
  gestation: <?= $gestation ?>,
  lactation: <?= $lactation ?>,
  dry: <?= $dry ?>
};

/* Chart.js global animation */
Chart.defaults.animation.duration = 800;

/* Donut center text plugin */
const centerText = {
  id: 'centerText',
  afterDraw(chart, args, opts) {
    const {ctx, chartArea:{left,right,top,bottom}, _metasets} = chart;
    if(!_metasets || !_metasets[0]) return;
    const total = _metasets[0].total || _metasets[0].totalValue || 0;
    const val = chart.data.datasets[0].data[0] || 0;
    const pct = total > 0 ? Math.round(val/total*100) : 0;
    ctx.save();
    ctx.font = '700 16px Poppins';
    ctx.fillStyle = '#555';
    ctx.textAlign = 'center';
    ctx.textBaseline = 'middle';
    ctx.fillText(pct + '%', (left+right)/2, (top+bottom)/2);
    ctx.restore();
  }
};

/* SOW POPULATION bar */
new Chart(document.getElementById('chartPop'),{
  type:'bar',
  data:{
    labels:['Gilt','Gestation','Lactation','Dry'],
    datasets:[{ data:[counts.gilt, counts.gestation, counts.lactation, counts.dry], borderWidth:1 }]
  },
  options:{ plugins:{legend:{display:false}}, scales:{y:{beginAtZero:true}}, maintainAspectRatio:false }
});

/* Donuts (% of total) */
function donut(el, val){
  const other = Math.max(counts.total - val,0);
  new Chart(document.getElementById(el),{
    type:'doughnut',
    data:{ labels:['Stage','Other'], datasets:[{ data:[val, other], borderWidth:0 }] },
    options:{ cutout:'65%', plugins:{legend:{display:false}}, maintainAspectRatio:false },
    plugins:[centerText]
  });
}
donut('donutGilt', counts.gilt);
donut('donutGest', counts.gestation);
donut('donutLact', counts.lactation);
donut('donutDry', counts.dry);

/* FEED per stage (placeholder proportional) */
const totalFeed = <?= json_encode($totalFeedKg) ?> || 0;
const sumStages = Math.max(counts.gilt+counts.gestation+counts.lactation+counts.dry, 1);
const feedPerStage = [
  totalFeed * (counts.gilt/sumStages),
  totalFeed * (counts.gestation/sumStages),
  totalFeed * (counts.lactation/sumStages),
  totalFeed * (counts.dry/sumStages),
];
new Chart(document.getElementById('chartFeed'),{
  type:'bar',
  data:{ labels:['Gilt','Gestation','Lactation','Dry'], datasets:[{ data:feedPerStage, borderWidth:1 }] },
  options:{ plugins:{legend:{display:false}}, scales:{y:{beginAtZero:true}}, maintainAspectRatio:false }
});

/* EXPENSE per stage (placeholder proportional) */
const totalExp = <?= json_encode($totalExpenses) ?> || 0;
const expensePerStage = [
  totalExp * (counts.gilt/sumStages),
  totalExp * (counts.gestation/sumStages),
  totalExp * (counts.lactation/sumStages),
  totalExp * (counts.dry/sumStages),
];
new Chart(document.getElementById('chartExp'),{
  type:'pie',
  data:{ labels:['Gilt','Gestation','Lactation','Dry'], datasets:[{ data:expensePerStage, borderWidth:0 }] },
  options:{ plugins:{legend:{display:false}}, maintainAspectRatio:false }
});

/* Calendar (init on open) */
let calInit=false;
function initCalendar(){
  if (calInit) return; calInit=true;
  const grid=document.getElementById('calGrid');
  const title=document.getElementById('calTitle');
  if(!grid||!title) return;

  let view = new Date(); view.setDate(1);
  const DOW=['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];

  function render(){
    grid.innerHTML='';
    const y=view.getFullYear(), m=view.getMonth();
    title.textContent = view.toLocaleString('default',{month:'long'})+' '+y;
    DOW.forEach(d=>{ const c=document.createElement('div'); c.className='cell dow'; c.textContent=d; grid.appendChild(c); });
    const first=new Date(y,m,1).getDay(), days=new Date(y,m+1,0).getDate();
    const now=new Date(); const thisMonth=(now.getFullYear()==y && now.getMonth()==m);
    for(let i=0;i<first;i++){ const b=document.createElement('div'); b.className='cell'; b.style.visibility='hidden'; grid.appendChild(b); }
    for(let d=1; d<=days; d++){
      const c=document.createElement('div'); c.className='cell';
      const ds=`${y}-${String(m+1).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
      const n=document.createElement('div'); n.style.fontWeight='800'; n.textContent=d; c.appendChild(n);
      if(thisMonth && d===now.getDate()) c.classList.add('today');
      const key='hoglog_cal_'+ds, saved=localStorage.getItem(key);
      if(saved){
        const b=document.createElement('div');
        b.textContent=saved; b.style.position='absolute'; b.style.right='6px'; b.style.bottom='6px';
        b.style.fontSize='11px'; b.style.padding='2px 6px'; b.style.borderRadius='999px';
        b.style.background='#e3f2fd'; b.style.color:'#1565c0';
        c.appendChild(b);
      }
      c.onclick=()=>{ const t=prompt('Note for '+ds+':', saved||''); if(t===null) return; if((t||'').trim()===''){ localStorage.removeItem(key); } else { localStorage.setItem(key, t.trim()); } render(); };
      grid.appendChild(c);
    }
  }
  document.getElementById('prevCal').onclick=()=>{ view.setMonth(view.getMonth()-1); render(); };
  document.getElementById('nextCal').onclick=()=>{ view.setMonth(view.getMonth()+1); render(); };
  document.getElementById('todayCal').onclick=()=>{ const t=new Date(); view=new Date(t.getFullYear(), t.getMonth(), 1); render(); };
  render();
}
document.getElementById('tile-cal').addEventListener('click', ()=> setTimeout(initCalendar, 120));

/* Notes from toolbar */
const noteKey='hoglog_sow_note';
const noteArea=document.getElementById('noteArea');
const saveNote=document.getElementById('saveNote');
const clearNote=document.getElementById('clearNote');
if (noteArea && saveNote && clearNote){
  const prev=localStorage.getItem(noteKey); if(prev) noteArea.value=prev;
  saveNote.onclick=()=>{ localStorage.setItem(noteKey, noteArea.value||''); alert('Note saved ✅'); };
  clearNote.onclick=()=>{ if(confirm('Clear note?')){ noteArea.value=''; localStorage.removeItem(noteKey); } };
}
</script>
</body>
</html>
