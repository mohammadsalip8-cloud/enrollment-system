<?php
$conn = new mysqli("localhost", "root", "", "enrollment_db");
if ($conn->connect_error) { die("DB Error: ".$conn->connect_error); }
?>