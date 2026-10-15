<?php
include 'config/db.php';
$id=$_GET['id']??'';
$s=null;
if($id!=''){
$q="SELECT s.*,c.course_name,c.course_code FROM students s LEFT JOIN courses c ON c.id=s.course_id WHERE s.student_id='$id' OR s.id='$id' LIMIT 1";
$r=$conn->query($q);
if($r)$s=$r->fetch_assoc();
}
?>
<!DOCTYPE html>
<html>
<head>
<title>YBVC Portal</title>
<meta name="viewport" content="width=device-width,initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<style>
*{font-family:system-ui;}
body{background:#f4f4f5;color:#18181b;padding:20px;margin:0;}
.card{background:#ffffff;border:1px solid #e4e4e7;border-radius:16px;padding:20px;margin-bottom:16px;box-shadow:0 1px 3px rgba(0,0,0,0.1);}
.badge{background:#18181b;color:#fff;padding:6px 12px;border-radius:20px;font-weight:700;font-size:11px;}
.badge-light{background:#f4f4f5;color:#18181b;border:1px solid #e4e4e7;padding:6px 12px;border-radius:20px;font-weight:700;font-size:11px;}
.tab{display:flex;gap:8px;margin-bottom:16px;flex-wrap:wrap;}
.tab a{padding:10px 16px;border-radius:20px;font-size:12px;font-weight:700;border:1px solid #e4e4e7;cursor:pointer;text-decoration:none;}
.tab a.on{background:#18181b;color:#fff;}
.tab a.off{background:#fff;color:#71717a;}
.in{width:100%;background:#fff;border:1px solid #e4e4e7;color:#18181b;border-radius:10px;padding:12px;}
.btn{background:#18181b;color:#fff;border-radius:10px;padding:12px 18px;font-weight:700;border:none;}
.m{color:#71717a;font-size:12px;}
h6{font-weight:700;color:#18181b;}
b{color:#18181b;}
</style>
</head>
<body>
<div style="max-width:900px;margin:auto;">
<div class="card">
<b><i class="bi bi-mortarboard-fill"></i> YBVC Student Portal</b><br>
<span class="m">Enter ID - ex: WCODE-2026-50409</span>
<form method="GET" style="display:flex;gap:8px;margin-top:12px;">
<input name="id" class="in" placeholder="WCODE-2026-50409" value="<?php echo htmlspecialchars($id);?>" required>
<button class="btn">Go <i class="bi bi-arrow-right"></i></button>
</form>
<?php if($id!=''&&!$s){?>
<div style="color:#ef4444;margin-top:10px;font-size:13px; background:#fef2f2; padding:10px; border-radius:8px; border:1px solid #fecaca;">Not found: <?php echo htmlspecialchars($id);?></div>
<?php }?>
</div>
<?php if($s){
$sid=$s['student_id'];
$did=$s['id'];
$pay=$conn->query("SELECT * FROM payments WHERE student_id='$did' OR student_id='$sid' ORDER BY id DESC");
$log=$conn->query("SELECT * FROM attendance_logs WHERE student_id='$did' OR student_id='$sid' ORDER BY log_time DESC LIMIT 20");
$sum=$conn->query("SELECT SUM(downpayment) as s FROM payments WHERE student_id='$did' OR student_id='$sid'")->fetch_assoc()['s'];
if(!$sum)$sum=0;
?>
<div class="card" style="display:flex;justify-content:space-between;align-items:center; background:#18181b; color:#fff;">
<div>
<b style="font-size:18px; color:#fff;">Welcome, <?php echo $s['full_name'];?>!</b><br>
<span style="color:#a1a1aa; font-size:12px;">ID: <?php echo $sid;?> | DB: <?php echo $did;?> | <?php echo $s['status'];?></span>
</div>
<span class="badge-light"><?php echo $s['course_code'];?></span>
</div>
<div class="tab">
<a class="on" onclick="show('profile',this)"><i class="bi bi-person"></i> Profile</a>
<a class="off" onclick="show('contacts',this)"><i class="bi bi-telephone"></i> Contacts</a>
<a class="off" onclick="show('payments',this)"><i class="bi bi-wallet"></i> Payments ₱<?php echo number_format($sum);?></a>
<a class="off" onclick="show('attendance',this)"><i class="bi bi-qr-code"></i> Attendance</a>
</div>
<div id="tab-profile" class="card">
<h6><i class="bi bi-person-circle"></i> Profile Information</h6>
<hr>
<span class="m">Full Name</span><br>
<b><?php echo $s['full_name'];?></b><br><br>
<span class="m">Student ID</span><br>
<b style="font-size:20px; color:#18181b;"><?php echo $sid;?></b><br>
<span class="m">DB ID: <?php echo $did;?></span><br><br>
<span class="m">Course</span><br>
<b><?php echo $s['course_name'];?></b> - <span class="badge"><?php echo $s['course_code'];?></span><br><br>
<span class="m">Status</span><br>
<span class="badge" style="background:#22c55e;"><?php echo $s['status'];?></span><br><br>
<span class="m">Address</span><br>
<b><?php echo $s['address'];?></b>
</div>
<div id="tab-contacts" class="card" style="display:none;">
<h6><i class="bi bi-telephone-fill"></i> Guardian Contacts</h6>
<hr>
<span class="m">Guardian Name</span><br>
<b><?php echo $s['parent_name'];?></b><br><br>
<span class="m">Contact Number</span><br>
<b style="font-size:18px;"><?php echo $s['parent_contact'];?></b>
</div>
<div id="tab-payments" class="card" style="display:none;">
<div style="display:flex;justify-content:space-between; align-items:center;">
<h6><i class="bi bi-currency-dollar"></i> My Payments</h6>
<span class="badge">Total ₱<?php echo number_format($sum);?></span>
</div>
<hr>
<?php if($pay->num_rows==0){?>
<span class="m">No payments yet - Add at admin panel</span>
<?php }else{ while($p=$pay->fetch_assoc()){
$am=$p['downpayment'];
$dt=$p['created_at']??'';
?>
<div style="display:flex;justify-content:space-between;padding:12px 0;border-bottom:1px solid #f4f4f5;">
<div><b style="font-size:16px;">₱<?php echo number_format($am);?></b><br><span class="m"><?php echo $dt;?></span></div>
<span class="badge" style="background:#22c55e;">Paid</span>
</div>
<?php }}?>
</div>
<div id="tab-attendance" class="card" style="display:none;">
<h6><i class="bi bi-qr-code-scan"></i> Attendance Logs</h6>
<hr>
<?php if($log->num_rows==0){?>
<span class="m">No scans yet - Use Gate Scanner</span>
<?php }else{ while($l=$log->fetch_assoc()){?>
<div style="padding:10px 0;border-bottom:1px solid #f4f4f5;font-size:13px; display:flex; gap:10px;">
<i class="bi bi-check-circle-fill" style="color:#22c55e;"></i> <?php echo $l['log_time'];?>
</div>
<?php }}?>
</div>
<?php }?>
</div>
<script>
function show(id,el){
document.querySelectorAll('[id^="tab-"]').forEach(d=>d.style.display='none');
document.getElementById('tab-'+id).style.display='block';
document.querySelectorAll('.tab a').forEach(a=>a.className='off');
el.className='on';
}
</script>
</body>
</html>