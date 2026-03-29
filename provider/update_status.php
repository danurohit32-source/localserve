<?php
include("../db.php");

$id = $_POST['id'];
$status = $_POST['status'];

mysqli_query($conn,"UPDATE bookings SET status='$status' WHERE id='$id'");
?>