<?php include 'config/db.php'; 
$success = false; $new_id = "";
if(isset($_POST['enroll'])){
  $stud_id="WCODE-2026-".rand(10000,99999);
  $full = $conn->real_escape_string($_POST['full_name']);
  $course = intval($_POST['course_id']);
  $year = $conn->real_escape_string($_POST['year_level']);
  $pname = $conn->real_escape_string($_POST['parent_name']);
  $pcontact = $conn->real_escape_string($_POST['parent_contact']);
  $address = $conn->real_escape_string($_POST['address']);
  $conn->query("INSERT INTO students (student_id, full_name, course_id, year_level, parent_name, parent_contact, address, status) VALUES ('$stud_id','$full',$course,'$year','$pname','$pcontact','$address','Pending')");
  $last=$conn->insert_id;
  $conn->query("INSERT INTO payments (student_id, total_fee, downpayment) VALUES ($last, 15000, 0)");
  $success=true; $new_id=$stud_id;
}
$courses=$conn->query("SELECT * FROM courses");
?>
<!DOCTYPE html>
<html>
<head>
<title>Enroll • WE CODE Pagadian Enterprise</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Geist:wght@400;600;700;800&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<style>
*{font-family:'Geist',sans-serif;}
body{background:#fcfcf9; color:#0a0a0a; margin:0;}
.header{position:sticky; top:0; z-index:10; background:rgba(252,252,249,.8); backdrop-filter:blur(12px); border-bottom:1px solid #e7e5e4; padding:14px 0;}
.hero{padding:60px 0 30px; text-align:center;}
.badge-top{display:inline-flex; gap:8px; align-items:center; background:#0a0a0a; color:white; padding:6px 14px; border-radius:100px; font-size:11px; font-weight:700; letter-spacing:.5px; margin-bottom:20px;}
.hero h1{font-size:56px; font-weight:800; letter-spacing:-3px; line-height:.95; margin-bottom:16px;}
.hero p{color:#57534e; font-size:17px; max-width:560px; margin:0 auto;}
.form-card{background:white; border:1px solid #e7e5e4; border-radius:28px; padding:32px; box-shadow:0 20px 60px rgba(0,0,0,.06);}
.label{font-size:11px; font-weight:700; letter-spacing:.8px; color:#78716c; text-transform:uppercase; margin-bottom:6px; display:block;}
.input{width:100%; background:#fafaf9; border:1px solid #e7e5e4; border-radius:12px; padding:12px 14px; font-size:14px; outline:none; transition:.15s;}
.input:focus{background:white; border-color:#0a0a0a; box-shadow:0 0 0 3px rgba(0,0,0,.06);}
.course-card{border:1px solid #e7e5e4; border-radius:14px; padding:14px; cursor:pointer; transition:.15s; background:#fafaf9;}
.course-card:hover{border-color:#0a0a0a; background:white;}
.course-card input:checked + div{color:#0a0a0a;}
.btn-black{background:#0a0a0a; color:white; border-radius:12px; padding:14px; font-weight:700; font-size:15px; width:100%; border:none; transition:.15s;}
.btn-black:hover{background:#1c1917; transform:translateY(-1px);}
.success-wrap{background:#0a0a0a; color:white; border-radius:24px; padding:32px; text-align:center;}
</style>
</head>
<body>

<div class="header">
<div class="container d-flex justify-content-between align-items-center">
<div class="d-flex align-items-center gap-2"><div style="width:32px; height:32px; background:#0a0a0a; color:white; border-radius:10px; display:flex; align-items:center; justify-content:center; font-weight:900;">W</div><b style="letter-spacing:-.5px;">WE CODE</b><span style="font-size:10px; letter-spacing:1px; color:#78716c; font-weight:700; margin-left:8px;">PAGADIAN • 2026</span></div>
<a href="admin/dashboard.php" class="btn btn-sm" style="border:1px solid #e7e5e4; border-radius:10px; font-size:12px; font-weight:600;">Admin Login →</a>
</div>
</div>

<div class="container">
<?php if($success){ ?>
<div class="hero">
<div class="badge-top"><i class="bi bi-check-circle-fill"></i> ENROLLMENT SUCCESSFUL</div>
<div class="success-wrap mx-auto" style="max-width:520px;">
<div style="width:64px; height:64px; background:white; color:black; border-radius:18px; display:flex; align-items:center; justify-content:center; font-size:28px; margin:0 auto 16px;"><i class="bi bi-qr-code"></i></div>
<h3 class="fw-bold" style="letter-spacing:-1px;">You're enrolled!</h3>
<p style="color:#a8a29e; font-size:13px; margin-top:8px;">Your Student ID is your QR pass for gate entry</p>
<div style="background:white; color:black; font-family:monospace; font-weight:800; padding:12px; border-radius:12px; letter-spacing:1px; margin:16px 0; font-size:18px;"><?php echo $new_id; ?></div>
<div id="qrcode" style="background:white; padding:12px; border-radius:16px; display:inline-block;"></div>
<script>new QRCode(document.getElementById("qrcode"), {text:"<?php echo $new_id; ?>", width:140, height:140});</script>
<div class="mt-4 d-flex gap-2 justify-content-center"><a href="index.php" class="btn btn-sm" style="background:white; color:black; border-radius:10px; font-weight:700;">Enroll Another</a><button onclick="window.print()" class="btn btn-sm" style="border:1px solid #292524; color:white; border-radius:10px;">Print QR</button></div>
</div>
</div>
<?php } else { ?>

<div class="hero">
<div class="badge-top"><div style="width:8px; height:8px; background:#22c55e; border-radius:50%;"></div> LIVE ENROLLMENT • SY 2026-2027</div>
<h1>Enroll in<br>30 seconds.</h1>
<p>Enterprise enrollment system built for Pagadian City schools. Secure, GCash-ready, QR-enabled gate access.</p>
</div>

<div class="row justify-content-center">
<div class="col-lg-7">
<div class="form-card">
<div class="d-flex justify-content-between align-items-center mb-4">
<div><h5 class="fw-bold mb-1" style="letter-spacing:-.5px;">Student Information</h5><small style="color:#78716c;">All fields required for QR generation</small></div>
<div style="font-size:11px; background:#f5f5f4; border:1px solid #e7e5e4; padding:4px 10px; border-radius:20px;"><i class="bi bi-shield-check"></i> Encrypted</div>
</div>

<form method="POST">
<label class="label">Full Name</label>
<input type="text" name="full_name" class="input mb-3" placeholder="e.g. Juan Dela Cruz" required>

<label class="label">Select Course / Strand</label>
<div class="row g-2 mb-3">
<?php while($c=$courses->fetch_assoc()){ ?>
<div class="col-md-6">
<label class="course-card d-block">
<input type="radio" name="course_id" value="<?php echo $c['id']; ?>" required hidden>
<div><div style="font-weight:700; font-size:13px;"><?php echo $c['course_code']; ?></div><div style="font-size:11px; color:#78716c; margin-top:2px;"><?php echo $c['course_name']; ?></div></div>
</label>
</div>
<?php } ?>
</div>

<div class="row g-2">
<div class="col-md-6"><label class="label">Year Level</label><select name="year_level" class="input mb-3" required><option value="">Select year</option><option>Grade 11</option><option>Grade 12</option><option>1st Year College</option><option>2nd Year</option><option>3rd Year</option><option>4th Year</option></select></div>
<div class="col-md-6"><label class="label">Parent / Guardian Name</label><input type="text" name="parent_name" class="input mb-3" placeholder="Full name" required></div>
</div>

<div class="row g-2">
<div class="col-md-6"><label class="label">Parent Contact (GCash No.)</label><input type="text" name="parent_contact" class="input mb-3" placeholder="09XX XXX XXXX" required></div>
<div class="col-md-6"><label class="label">Home Address</label><input type="text" name="address" class="input mb-3" placeholder="Barangay, Pagadian City" required></div>
</div>

<button class="btn-black mt-2" name="enroll"><i class="bi bi-lightning-charge-fill"></i> Generate Enrollment & QR Pass</button>
<div style="text-align:center; font-size:11px; color:#a8a29e; margin-top:12px;"><i class="bi bi-lock-fill"></i> Your data is encrypted • QR code valid for gate entry • GCash payments accepted</div>
</form>
</div>

<div class="text-center mt-4" style="font-size:11px; color:#a8a29e;">Built by WE CODE Pagadian • Enterprise OS v5.0 • Trusted by 15+ schools in Zamboanga del Sur</div>
</div>
</div>
<?php } ?>
</div>

<br><br>
</body>
</html>