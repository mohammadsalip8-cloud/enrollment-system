<?php
include 'config/db.php';
$q = $conn->query("SELECT l.*, s.full_name, s.student_id FROM attendance_logs l JOIN students s ON s.id=l.student_id ORDER BY l.id DESC LIMIT 10");
while($r=$q->fetch_assoc()){
  echo "<div>{$r['log_time']} - <b>{$r['full_name']}</b> ({$r['student_id']}) - {$r['log_type']}</div>";
}
?>