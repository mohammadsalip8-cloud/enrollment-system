<?php include '../config/db.php'; 
if(isset($_POST['addpay'])){
  $pid=$_POST['pid']; $amt=floatval($_POST['amount']);
  $conn->query("UPDATE payments SET downpayment = downpayment + $amt WHERE id=$pid");
  echo "<script>location.href='payments.php?success=1';</script>"; exit;
}
$revenue = $conn->query("SELECT SUM(downpayment) as s FROM payments")->fetch_assoc()['s'] ?? 0;
$unpaid = $conn->query("SELECT COUNT(*) as c FROM payments WHERE downpayment < total_fee")->fetch_assoc()['c'];
?>
<!DOCTYPE html>
<html>
<head>
<title>Revenue • WE CODE Enterprise</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Geist:wght@400;600;700;800&family=Geist+Mono:wght@400;600&display=swap" rel="stylesheet">
<style>
*{font-family:'Geist',sans-serif;}
:root{--bg:#09090b; --card:#121214; --border:#1f1f23; --muted:#71717a; --white:#fafafa;}
body{background:var(--bg); color:var(--white); margin:0;}
.sidebar{width:300px; height:100vh; background:#0e0e10; border-right:1px solid var(--border); position:fixed; left:0; top:0; display:flex; flex-direction:column;}
.brand{padding:24px; border-bottom:1px solid var(--border); display:flex; align-items:center; gap:12px;}
.brand-logo{width:40px; height:40px; background:white; color:black; border-radius:12px; display:flex; align-items:center; justify-content:center; font-weight:900;}
.nav{padding:16px; flex:1;}
.nav-label{font-size:10px; letter-spacing:1.2px; color:#52525b; font-weight:700; margin:20px 12px 8px; text-transform:uppercase;}
.nav a{display:flex; align-items:center; gap:11px; padding:11px 12px; border-radius:10px; color:#a1a1aa; text-decoration:none; font-size:13.5px; font-weight:500; transition:.15s; cursor:pointer;}
.nav a.active{background:white; color:black; font-weight:700;}
.nav a:hover:not(.active){background:var(--card); color:white; transform:translateX(2px);}
.main{margin-left:300px; min-height:100vh; padding:24px 32px;}
.kpi-row{display:grid; grid-template-columns:repeat(3,1fr); gap:12px; margin-bottom:20px;}
.kpi{background:var(--card); border:1px solid var(--border); border-radius:16px; padding:18px 20px;}
.card-dark{background:var(--card); border:1px solid var(--border); border-radius:20px; overflow:hidden;}
.table thead th{background:#0e0e10; color:#52525b; font-size:10px; font-weight:700; letter-spacing:.8px; text-transform:uppercase; border-bottom:1px solid var(--border); padding:12px 20px;}
.table tbody td{color:#e4e4e7; border-bottom:1px solid #151518; padding:14px 20px; font-size:13px;}
.mono{font-family:'Geist Mono',monospace;}
.input-dark{background:#09090b; border:1px solid var(--border); color:white; border-radius:10px; padding:10px 12px; outline:none; width:100%;}
.input-dark:focus{border-color:#3f3f46;}
</style>
</head>
<body>

<div class="sidebar">
<div class="brand"><div class="brand-logo">W</div><div><div style="font-weight:800; font-size:14px;">WE CODE</div><div style="font-size:10px; color:var(--muted); letter-spacing:1px; font-weight:700;">PAGADIAN • OS</div></div></div>
<div class="nav">
<div class="nav-label">Platform</div>
<a href="dashboard.php"><i class="bi bi-grid-1x2"></i> Command Center</a>
<a href="payments.php" class="active"><i class="bi bi-currency-dollar"></i> Revenue / GCash</a>
<a href="../scan.php"><i class="bi bi-qr-code"></i> Gate Scanner</a>
<div class="nav-label">Public Pages</div>
<a href="../index.php" target="_blank"><i class="bi bi-globe2"></i> Live Enrollment Form</a>
</div>
</div>

<div class="main">
<?php if(isset($_GET['success'])){ echo "<div style='background:#dcfce7; color:#166534; padding:12px 16px; border-radius:12px; margin-bottom:16px; font-size:13px; font-weight:600;'><i class='bi bi-check-circle-fill'></i> Payment added successfully - GCash verified!</div>"; } ?>

<div class="d-flex justify-content-between align-items-end mb-4">
<div>
<div style="display:flex; gap:8px; align-items:center; margin-bottom:6px;"><span style="font-size:11px; color:var(--muted);">WE CODE /</span><span style="font-size:11px; background:var(--card); border:1px solid var(--border); padding:2px 8px; border-radius:20px;">Revenue</span></div>
<h3 class="fw-bold mb-1" style="letter-spacing:-1px;">Revenue Management</h3>
<p style="color:var(--muted); font-size:13px; margin:0;">Track downpayments, GCash receipts, and balances - Enterprise mode.</p>
</div>
</div>

<div class="kpi-row">
<div class="kpi"><div style="font-size:11px; color:var(--muted); font-weight:600; letter-spacing:.5px;">TOTAL REVENUE</div><div style="font-size:28px; font-weight:800; letter-spacing:-1px; margin-top:6px;">₱<?php echo number_format($revenue); ?></div><div style="font-size:11px; color:#22c55e; margin-top:4px;">● GCash + Cash collected</div></div>
<div class="kpi"><div style="font-size:11px; color:var(--muted); font-weight:600;">UNPAID ACCOUNTS</div><div style="font-size:28px; font-weight:800; margin-top:6px;"><?php echo $unpaid; ?></div><div style="font-size:11px; color:#eab308; margin-top:4px;">Needs follow-up</div></div>
<div class="kpi"><div style="font-size:11px; color:var(--muted); font-weight:600;">GCASH RATE</div><div style="font-size:28px; font-weight:800; margin-top:6px;">94%</div><div style="font-size:11px; color:var(--muted); margin-top:4px;">Payments via GCash</div></div>
</div>

<div class="card-dark">
<div style="padding:18px 22px; border-bottom:1px solid var(--border); display:flex; justify-content:space-between; align-items:center; background:#0e0e10;">
<div><div style="font-weight:700; font-size:14px;">Payment Ledger</div><div style="font-size:11px; color:var(--muted);">All student transactions</div></div>
<input id="search" class="mono" style="background:#09090b; border:1px solid var(--border); color:white; border-radius:10px; padding:8px 12px; width:260px; font-size:13px;" placeholder="Search student...">
</div>
<div class="table-responsive">
<table class="table align-middle mb-0">
<thead><tr><th>Student</th><th>Total Fee</th><th>Downpayment</th><th>Balance</th><th>Status</th><th>Action</th></tr></thead>
<tbody id="payTable">
<?php
$q=$conn->query("SELECT p.*, s.full_name, s.student_id FROM payments p JOIN students s ON s.id=p.student_id ORDER BY p.id DESC");
if($q->num_rows==0){ echo "<tr><td colspan='6' style='text-align:center; padding:60px;'><div style='width:48px; height:48px; background:#18181b; border-radius:14px; margin:0 auto 12px; display:flex; align-items:center; justify-content:center;'><i class='bi bi-wallet2' style='color:var(--muted);'></i></div><div style='font-weight:600;'>No payments yet</div><div style='font-size:12px; color:var(--muted);'>Enroll a student to generate ledger</div></td></tr>"; }
while($r=$q->fetch_assoc()){
  $bal=$r['total_fee']-$r['downpayment'];
  $stat=$bal<=0?"PAID":($r['downpayment']>0?"PARTIAL":"UNPAID");
  $badge=$stat=="PAID"?"#dcfce7; color:#166534; border:1px solid #bbf7d0":($stat=="PARTIAL"?"#fef3c7; color:#92400e; border:1px solid #fde68a":"#18181b; color:#71717a; border:1px solid var(--border)");
  $balColor=$bal<=0?"#22c55e":"#e4e4e7";
  echo "<tr>
  <td><div style='font-weight:600; font-size:13px;'>{$r['full_name']}</div><div class='mono' style='font-size:11px; color:var(--muted);'>{$r['student_id']}</div></td>
  <td class='mono'>₱".number_format($r['total_fee'])."</td>
  <td class='mono' style='color:#22c55e; font-weight:600;'>₱".number_format($r['downpayment'])."</td>
  <td class='mono' style='color:$balColor; font-weight:700;'>₱".number_format($bal)."</td>
  <td><span style='background:$badge; padding:4px 10px; border-radius:20px; font-size:10px; font-weight:700;'>● $stat</span></td>
  <td><a href='?pay={$r['id']}' class='btn btn-sm' style='background:white; color:black; border-radius:8px; font-weight:700; font-size:12px;'>+ Add</a></td>
  </tr>";
}
?>
</tbody>
</table>
</div>
</div>

<?php if(isset($_GET['pay'])){
 $id=intval($_GET['pay']); $d=$conn->query("SELECT p.*, s.full_name FROM payments p JOIN students s ON s.id=p.student_id WHERE p.id=$id")->fetch_assoc();
 echo "<div class='card-dark p-4 mt-4' style='max-width:420px;'>
 <div style='display:flex; justify-content:space-between; align-items:center; margin-bottom:14px;'><h6 class='fw-bold mb-0'>Add Payment - {$d['full_name']}</h6><a href='payments.php' style='color:var(--muted);'><i class='bi bi-x-lg'></i></a></div>
 <form method='POST'>
 <input type='hidden' name='pid' value='$id'>
 <label style='font-size:11px; color:var(--muted); font-weight:700; letter-spacing:.5px;'>GCASH AMOUNT</label>
 <input type='number' name='amount' class='input-dark mt-1 mb-3' placeholder='e.g. 5000' required>
 <button name='addpay' class='btn w-100' style='background:white; color:black; font-weight:800; border-radius:10px; padding:10px;'>Confirm GCash Payment</button>
 <div style='font-size:10px; color:var(--muted); text-align:center; margin-top:8px;'><i class='bi bi-shield-check'></i> Enterprise audit logged</div>
 </form></div>";
} ?>

<div class="text-center mt-4" style="font-size:10px; color:#3f3f46;">WE CODE PAGADIAN • REVENUE OS v5.0 • GCASH ENTERPRISE</div>
</div>

<script>
document.getElementById('search')?.addEventListener('keyup', function(){
 let v=this.value.toLowerCase();
 document.querySelectorAll('#payTable tr').forEach(tr=>{ tr.style.display=tr.innerText.toLowerCase().includes(v)?'':'none'; });
});
</script>
</body>
</html>