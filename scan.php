<?php include 'config/db.php'; 
$today = $conn->query("SELECT COUNT(*) as c FROM attendance_logs WHERE DATE(log_time)=CURDATE()")->fetch_assoc()['c'];
?>
<!DOCTYPE html>
<html>
<head>
<title>Gate Scanner • WE CODE Enterprise</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Geist:wght@400;600;700;800&family=Geist+Mono:wght@400;600&display=swap" rel="stylesheet">
<script src="https://unpkg.com/html5-qrcode"></script>
<style>
*{font-family:'Geist',sans-serif;}
:root{--bg:#09090b; --card:#121214; --border:#1f1f23; --muted:#71717a;}
body{background:var(--bg); color:white; margin:0; min-height:100vh;}
.sidebar{width:300px; height:100vh; background:#0e0e10; border-right:1px solid var(--border); position:fixed; left:0; top:0; display:flex; flex-direction:column;}
.brand{padding:24px; border-bottom:1px solid var(--border); display:flex; gap:12px; align-items:center;}
.brand-logo{width:40px; height:40px; background:white; color:black; border-radius:12px; display:flex; align-items:center; justify-content:center; font-weight:900;}
.nav{padding:16px;}
.nav a{display:flex; gap:11px; padding:11px 12px; border-radius:10px; color:#a1a1aa; text-decoration:none; font-size:13.5px; margin:2px 0;}
.nav a.active{background:white; color:black; font-weight:700;}
.nav a:hover:not(.active){background:var(--card); color:white;}
.main{margin-left:300px; padding:24px 32px; display:grid; grid-template-columns:420px 1fr; gap:20px; min-height:100vh;}
.scanner-card{background:var(--card); border:1px solid var(--border); border-radius:24px; padding:20px; height:fit-content;}
#reader{width:100%; border-radius:16px; overflow:hidden; background:#09090b; border:1px solid var(--border);}
#reader video{border-radius:16px;}
.log-card{background:var(--card); border:1px solid var(--border); border-radius:20px; overflow:hidden;}
.status-dot{width:8px; height:8px; background:#22c55e; border-radius:50%; box-shadow:0 0 10px #22c55e; animation:pulse 2s infinite;}
@keyframes pulse{0%,100%{opacity:1;} 50%{opacity:.5;}}
.result{border-radius:16px; padding:16px; margin-top:16px; display:none; animation:slide .3s;}
@keyframes slide{from{transform:translateY(10px); opacity:0;} to{transform:translateY(0); opacity:1;}}
.mono{font-family:'Geist Mono',monospace;}
</style>
</head>
<body>

<div class="sidebar">
<div class="brand"><div class="brand-logo">W</div><div><div style="font-weight:800; font-size:14px;">WE CODE</div><div style="font-size:10px; color:var(--muted); letter-spacing:1px; font-weight:700;">PAGADIAN • GATE OS</div></div></div>
<div class="nav">
<a href="admin/dashboard.php"><i class="bi bi-grid-1x2"></i> Command Center</a>
<a href="admin/payments.php"><i class="bi bi-currency-dollar"></i> Revenue / GCash</a>
<a href="scan.php" class="active"><i class="bi bi-qr-code"></i> Gate Scanner</a>
<a href="index.php" target="_blank"><i class="bi bi-globe2"></i> Live Enrollment</a>
</div>
<div style="padding:16px; margin-top:auto;">
<div style="background:var(--card); border:1px solid var(--border); border-radius:12px; padding:12px; display:flex; gap:10px; align-items:center;">
<div class="status-dot"></div><div><div style="font-size:11px; font-weight:700;">GATE SYSTEM ONLINE</div><div style="font-size:10px; color:var(--muted);">Camera • QR • Logs active</div></div>
</div>
</div>
</div>

<div class="main">
<div>
<div class="d-flex justify-content-between align-items-center mb-3">
<div><h5 class="fw-bold mb-0" style="letter-spacing:-.5px;">Gate Scanner</h5><small style="color:var(--muted); font-size:11px;"><?php echo date('l, M d Y • h:i A'); ?></small></div>
<span style="background:#18181b; border:1px solid var(--border); padding:4px 10px; border-radius:20px; font-size:10px; font-weight:700;"><span style="color:#22c55e;">●</span> <?php echo $today; ?> scans today</span>
</div>

<div class="scanner-card">
<div id="reader"></div>
<div id="result" class="result"></div>
<div style="margin-top:14px; display:flex; gap:8px;">
<button class="btn w-50" style="background:#18181b; border:1px solid var(--border); color:white; border-radius:10px; font-size:12px; font-weight:600;"><i class="bi bi-lightbulb"></i> Torch</button>
<button class="btn w-50" style="background:white; color:black; border-radius:10px; font-size:12px; font-weight:700;" onclick="location.reload()"><i class="bi bi-arrow-clockwise"></i> Restart Cam</button>
</div>
</div>

<div class="scanner-card mt-3" style="padding:16px;">
<div style="font-size:11px; font-weight:700; letter-spacing:.8px; color:var(--muted); margin-bottom:10px;">HOW IT WORKS</div>
<div style="font-size:12px; color:var(--muted); line-height:1.6;">
1. Student shows QR from enrollment<br>
2. Point camera to QR<br>
3. Auto logs attendance<br>
4. Shows name + status instantly
</div>
</div>
</div>

<div>
<div class="log-card">
<div style="padding:18px 22px; border-bottom:1px solid var(--border); display:flex; justify-content:space-between; align-items:center; background:#0e0e10;">
<div><div style="font-weight:700; font-size:14px;">Live Attendance Feed</div><div style="font-size:11px; color:var(--muted);">Real-time gate logs</div></div>
<div style="display:flex; align-items:center; gap:8px;"><div class="status-dot"></div><span style="font-size:10px; color:var(--muted); font-weight:700; letter-spacing:.5px;">LIVE</span></div>
</div>
<div id="logs" style="max-height:75vh; overflow-y:auto; padding:8px;">Loading logs...</div>
</div>
</div>
</div>

<script>
function onScanSuccess(decodedText) {
  let res = document.getElementById('result');
  res.style.display='block';
  res.style.background='#18181b';
  res.style.border='1px solid #27272a';
  res.innerHTML='<div style="display:flex; align-items:center; gap:10px;"><div class="spinner-border spinner-border-sm" style="width:16px; height:16px;"></div><span style="font-size:13px;">Verifying <span class="mono" style="background:#09090b; padding:2px 6px; border-radius:6px;">'+decodedText+'</span>...</span></div>';
  
  fetch('scan_process.php?code='+decodedText)
    .then(r=>r.text())
    .then(data=>{
      let isOk = data.toLowerCase().includes('success') || data.toLowerCase().includes('welcome') || data.toLowerCase().includes('logged');
      res.style.background = isOk ? '#052e16' : '#450a0a';
      res.style.borderColor = isOk ? '#166534' : '#7f1d1d';
      res.style.color = isOk ? '#bbf7d0' : '#fecaca';
      res.innerHTML = data;
      loadLogs();
      setTimeout(()=>{ res.style.display='none'; },4000);
    });
}
let html5QrcodeScanner = new Html5QrcodeScanner("reader", { fps: 15, qrbox: {width:260, height:260}, aspectRatio:1 }, false);
html5QrcodeScanner.render(onScanSuccess);

function loadLogs(){
  fetch('load_logs.php').then(r=>r.text()).then(d=>{
    let el=document.getElementById('logs');
    if(d.trim()=="") el.innerHTML="<div style='text-align:center; padding:40px; color:var(--muted); font-size:12px;'>No scans yet today<br>Waiting for first QR...</div>";
    else el.innerHTML=d;
  });
}
loadLogs();
setInterval(loadLogs,3000);
</script>
</body>
</html>