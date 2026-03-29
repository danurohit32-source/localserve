<?php
$conn = mysqli_connect("localhost", "root", "", "localserve_db");

if(!$conn){
    die("Database Connection Failed: " . mysqli_connect_error());
}

session_start();
?>