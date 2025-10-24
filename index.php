<?php
session_start();
if (isset($_SESSION['farm_id'])) {
    header("Location: modules/farm/farm_dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>HogLog | Smart Piggery Monitoring</title>

<!-- ✅ Modern Google Font -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
:root {
    --blue:#0277bd;
    --light-blue:#4fc3f7;
    --white:#ffffff;
    --shadow:rgba(0,0,0,0.15);
}

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:"Inter",sans-serif;
}

body{
    height:100vh;
    display:flex;
    align-items:center;
    justify-content:center;
    background:linear-gradient(135deg,#81d4fa,#0288d1);
    background-attachment: fixed;
}

/* === CARD STRUCTURE === */
.container{perspective:1000px;}
.card{
    width:850px;
    height:500px;
    position:relative;
    overflow:hidden;
    border-radius:20px;
    box-shadow:0 10px 25px var(--shadow);
    transition:all 0.8s ease-in-out;
    background:var(--white);
}

/* SIDES */
.side{
    position:absolute;
    top:0;
    left:0;
    width:100%;
    height:100%;
    border-radius:20px;
    display:flex;
    backface-visibility:hidden;
    transition:all 0.8s ease-in-out;
    opacity:0;
    transform:translate(60px,60px) scale(0.95);
}

.back .form-section {
    overflow-y:auto;
    scroll-behavior:smooth;
}
.back .form-section::-webkit-scrollbar{width:8px;}
.back .form-section::-webkit-scrollbar-thumb{
    background:var(--blue);
    border-radius:10px;
}
.back .form-section::-webkit-scrollbar-track{background:#e1f5fe;}

.front {
    background:var(--white);
    opacity:1;
    transform:translate(0,0) scale(1);
    z-index:2;
    display:flex;
    flex-direction:row;
    align-items:stretch;
}

.front .form-section,.back .form-section{
    width:50%;
    padding:60px 40px;
    display:flex;
    flex-direction:column;
    justify-content:center;
    animation:fadeIn 0.8s ease;
}
.front .form-section h2,.back .form-section h2{
    color:var(--blue);
    margin-bottom:10px;
}
.front .form-section p,.back .form-section p{
    font-size:0.95em;
    margin-bottom:30px;
    color:#444;
}
.front .form-section input,
.back .form-section input,
.back select,
.back textarea{
    margin-bottom:15px;
    padding:12px;
    width:100%;
    border:1px solid #bbb;
    border-radius:8px;
    font-family:"Inter",sans-serif;
}
.front .form-section button,.back .form-section button{
    padding:12px;
    border:none;
    background:var(--blue);
    color:var(--white);
    border-radius:8px;
    cursor:pointer;
    font-weight:600;
    transition:0.3s;
}
.front .form-section button:hover,.back .form-section button:hover{
    background:var(--light-blue);
}

/* WELCOME SECTIONS */
.front .welcome,.back .welcome{
    width:50%;
    color:var(--white);
    display:flex;
    flex-direction:column;
    justify-content:center;
    align-items:center;
    text-align:center;
    padding:40px;
}

.front .welcome{
    background:linear-gradient(180deg,#4fc3f7,#0288d1);
}
.back .welcome{
    background:linear-gradient(135deg,#0288d1,#4fc3f7);
}

.welcome h2{font-size:2em;margin-bottom:10px;}
.welcome p{font-size:1em;margin-bottom:25px;opacity:0.9;}
.welcome button{
    background:var(--white);
    color:var(--blue);
    padding:10px 25px;
    border-radius:25px;
    border:none;
    cursor:pointer;
    font-weight:600;
    transition:0.3s;
}
.welcome button:hover{background:#e1f5fe;}

.card.swipe .front{
    transform:translate(-60px,-60px) scale(0.95);
    opacity:0;
}
.card.swipe .back{
    transform:translate(0,0) scale(1);
    opacity:1;
    z-index:3;
}

@media(max-width:900px){
    .card{width:90%;height:auto;}
    .side{flex-direction:column;}
    .front .form-section,.front .welcome,
    .back .form-section,.back .welcome{width:100%;}
}
</style>
</head>
<body>

<div class="container">
  <div class="card" id="card">

    <!-- FRONT SIDE (LOGIN) -->
    <div class="side front">
      <div class="form-section">
        <h2>Welcome Back to HogLog</h2>
        <p>Smart Farming, Simplified.</p>
        <!-- ✅ Corrected login path -->
        <form method="POST" action="modules/processs/farm_login.php">
          <input type="text" name="username" placeholder="Username" required>
          <input type="password" name="password" placeholder="Password" required>
          <button type="submit">Log In</button>
        </form>
      </div>
      <div class="welcome">
        <h2>New Here?</h2>
        <p>Join HogLog today and simplify your farm management.</p>
        <button id="showRegister">Sign Up</button>
      </div>
    </div>

    <!-- BACK SIDE (REGISTER) -->
    <div class="side back">
      <div class="welcome">
        <h2>Welcome to HogLog</h2>
        <p>Empowering farmers through smart digital management.</p>
        <button id="showLogin">Log In</button>
      </div>
      <div class="form-section">
        <h2>Create Your Farm Account</h2>
        <p>Start managing your piggery with ease.</p>

        <!-- ✅ Corrected registration path -->
        <form method="POST" action="modules/farm/add_farm.php">
          <input type="text" name="farm_name" placeholder="Farm Name" required>
          <input type="text" name="owner_name" placeholder="Owner Name" required>
          <input type="text" name="contact_number" placeholder="Contact Number" required>
          <input type="email" name="email" placeholder="Email Address" required>
          <textarea name="farm_address" placeholder="Farm Address" required></textarea>

          <select name="farm_size" required>
            <option value="">Select Farm Size</option>
            <option value="Small">Small (1–50 pigs)</option>
            <option value="Medium">Medium (51–200 pigs)</option>
            <option value="Large">Large (200+ pigs)</option>
          </select>

          <select name="farm_type" required>
            <option value="">Select Farm Type</option>
            <option value="Backyard">Backyard</option>
            <option value="Commercial">Commercial</option>
            <option value="Cooperative">Cooperative</option>
          </select>

          <input type="text" name="username" placeholder="Username" required>
          <input type="password" name="password" placeholder="Password" required>

          <button type="submit">Register Farm</button>
        </form>
      </div>
    </div>

  </div>
</div>

<script>
const card=document.getElementById('card');
document.getElementById('showRegister').onclick=()=>card.classList.add('swipe');
document.getElementById('showLogin').onclick=()=>card.classList.remove('swipe');
</script>

</body>
</html>
