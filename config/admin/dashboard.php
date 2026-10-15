<?php include '../config/db.php';
$total = $conn->query("SELECT COUNT(*) as c FROM students")->fetch_assoc()['c'];
$paid = $conn->query("SELECT SUM(downpayment) as s FROM payments")->fetch_assoc()['s'] ?? 0;
$pending = $conn->query("SELECT COUNT(*) as c FROM students WHERE status='Pending'")->fetch_assoc()['c'];
$today = $conn->query("SELECT COUNT(*) as c FROM attendance_logs WHERE DATE(log_time)=CURDATE()")->fetch_assoc()['c'];
?>
<!DOCTYPE html>
<html>
<head>
<title>WE CODE • Pagadian Enterprise</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<style>
*{font-family:system-ui,sans-serif;}
:root{--bg:#09090b; --card:#121214; --border:#1f1f23; --muted:#71717a;}
body{background:var(--bg); color:white; margin:0;}
.sidebar{width:280px; height:100vh; background:#0e0e10; border-right:1px solid var(--border); position:fixed; left:0; top:0; padding:20px;}
.nav a{display:flex; gap:10px; padding:10px 12px; border-radius:10px; color:#a1a1aa; text-decoration:none; margin-bottom:4px;}
.nav a.active{background:white; color:black; font-weight:700;}
.nav a:hover{background:var(--card); color:white;}
.main{margin-left:280px; padding:24px;}
.data-card{background:var(--card); border:1px solid var(--border); border-radius:16px; overflow:hidden;}
.btn-portal{background:white; color:black; border-radius:8px; font-size:11px; font-weight:700; padding:7px 12px; text-decoration:none;}
.btn-portal:hover{background:#e4e4e7;}
</style>
</head>
<body>
<div class="sidebar">
<b>WE CODE</b><br><small style="color:var(--muted);">PAGADIAN • OS</small>
<div class="nav" style="margin-top:20px;">
<a href="dashboard.php" class="active"><i class="bi bi-grid"></i> Command Center</a>
<a href="payments.php"><i class="bi bi-currency-dollar"></i> Revenue / GCash</a>
<a href="../scan.php"><i class="bi bi-qr-code"></i> Gate Scanner</a>
<hr style="border-color:var(--border); margin:16px 0;">
<a href="../index.php" target="_blank"><i class="bi bi-plus-circle"></i> Generate Enrollment</a>
<a href="../student_portal.php" target="_blank"><i class="bi bi-person-badge"></i> YBVC Student Portal</a>
</div>
</div>

<div class="main">
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
<div>
<h4>Good evening, Admin</h4>
<small style="color:var(--muted);">Total <?php echo $total; ?> • Revenue ₱<?php echo number_format($paid); ?> • Today <?php echo $today; ?> scans</small>
</div>
<a href="../index.php" target="_blank" style="background:white; color:black; padding:10px 16px; border-radius:10px; font-weight:700; text-decoration:none;">+ Generate Enrollment</a>
</div>

<div class="data-card">
<div style="padding:16px; border-bottom:1px solid var(--border); display:flex; justify-content:space-between;">
<b>Student Registry</b>
<input id="search" placeholder="Search ID, name..." style="background:#09090b; border:1px solid var(--border); color:white; border-radius:8px; padding:6px 10px; font-size:12px;">
</div>

<table class="table" style="color:white; margin:0;">
<thead>
<tr style="font-size:10px; color:var(--muted);">
<th>ID</th><th>Profile</th><th>Course</th><th>Guardian</th><th>Status</th><th>QR</th><th>ACTION</th>
</tr>
</thead>
<tbody id="studentTable">
<?php
$q=$conn->query("SELECT s.*, c.course_code FROM students s LEFT JOIN courses c ON c.id=s.course_id ORDER BY s.id DESC");

if($q->num_rows==0){
    echo "<tr><td colspan='7' style='text-align:center; padding:60px;'><b>No students yet</b><br><small style='color:#71717a;'>Click top right 'Generate Enrollment' to add first student</small></td></tr>";
} else {
    while($r=$q->fetch_assoc()){
        $sid=$r['student_id'];
        $name=$r['full_name'];
        $course=$r['course_code'];
        $contact=$r['parent_contact'];
        $pname=$r['parent_name'];
        $status=$r['status'];
        $id=$r['id'];
        // FIX: if student_id empty, use id
        $finalId = !empty($sid) ? $sid : $id;
?>
<tr>
<td style="font-size:11px;"><?php echo $sid; ?></td>
<td><b><?php echo $name; ?></b></td>
<td><span style="background:white; color:black; padding:3px 8px; border-radius:20px; font-size:11px; font-weight:700;"><?php echo $course; ?></span></td>
<td style="font-size:11px;"><?php echo $contact; ?><br><small style="color:var(--muted);"><?php echo $pname; ?></small></td>
<td><small><?php echo $status; ?></small></td>
<td><div id="qr<?php echo $id; ?>" style="background:white; padding:4px; border-radius:8px; display:inline-block;"></div></td>
<td><a href="../student_portal.php?id=<?php echo $finalId; ?>" target="_blank" class="btn-portal">Portal <i class="bi bi-box-arrow-up-right"></i></a></td>
</tr>
<script>new QRCode(document.getElementById('qr<?php echo $id; ?>'), {text:'<?php echo $finalId; ?>', width:36, height:36});</script>
<?php 
    } 
} 
?>
</tbody>
</table>
</div>
</div>

<script>
document.getElementById('search').addEventListener('keyup', function(){
let v=this.value.toLowerCase();
document.querySelectorAll('#studentTable tr').forEach(function(tr){
tr.style.display=tr.innerText.toLowerCase().includes(v)?'':'none';
});
});
</script>
</body>
</html>