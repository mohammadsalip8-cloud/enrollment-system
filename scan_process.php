<?php
include 'config/db.php';
$code = $_GET['code'] ?? '';
$q = $conn->query("SELECT * FROM students WHERE student_id='$code'");
if($q->num_rows==0){ echo "❌ Student not found: $code"; exit; }
$s = $q->fetch_assoc();
$sid = $s['id'];

// check last log
$last = $conn->query("SELECT * FROM attendance_logs WHERE student_id=$sid ORDER BY id DESC LIMIT 1")->fetch_assoc();
$type = "IN";
if($last && $last['log_type']=="IN") $type="OUT";

$conn->query("INSERT INTO attendance_logs (student_id, log_type) VALUES ($sid, '$type')");
echo "✅ $type - {$s['full_name']} - {$s['student_id']} - SUCCESS";
?>