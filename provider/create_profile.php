<?php
include("../db.php");

if(session_status() == PHP_SESSION_NONE){
    session_start();
}

$provider_id = $_SESSION['user_id'];

if(isset($_POST['save'])){

    $name = $_POST['name'];
    $service = $_POST['service'];
    $price = $_POST['price'];
    $phone = $_POST['phone'];
    $exp = $_POST['exp'];

    mysqli_query($conn,"
    INSERT INTO provider_details(user_id,name,service,price_per_hour,phone,experience)
    VALUES('$provider_id','$name','$service','$price','$phone','$exp')
    ON DUPLICATE KEY UPDATE
    name='$name',
    service='$service',
    price_per_hour='$price',
    phone='$phone',
    experience='$exp'
    ");

    echo "<script>alert('Profile Saved');</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Create Profile</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Poppins;
}

body{
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background:linear-gradient(135deg,#0f172a,#1e293b,#2563eb);
}

/* CARD */
.card{
    width:420px;
    padding:35px;
    border-radius:20px;
    background:rgba(255,255,255,0.08);
    backdrop-filter:blur(18px);
    box-shadow:0 20px 40px rgba(0,0,0,0.3);
    color:white;
}

/* TITLE */
.title{
    text-align:center;
    margin-bottom:25px;
    font-size:22px;
    font-weight:600;
}

/* INPUT GROUP */
.input-group{
    position:relative;
    margin-bottom:15px;
}

.input-group i{
    position:absolute;
    left:12px;
    top:50%;
    transform:translateY(-50%);
    color:#94a3b8;
}

input, select{
    width:100%;
    padding:12px 12px 12px 40px;
    border:none;
    border-radius:10px;
    outline:none;
    background:rgba(255,255,255,0.15);
    color:white;
}

/* SELECT FIX */
.custom-select{
    appearance:none;
    cursor:pointer;
}

.custom-select option{
    background:#1e293b;
    color:white;
}

/* BUTTON */
button{
    width:100%;
    padding:12px;
    border:none;
    border-radius:10px;
    background:linear-gradient(90deg,#22d3ee,#06b6d4);
    color:black;
    font-weight:600;
    margin-top:15px;
    cursor:pointer;
    transition:0.3s;
}

button:hover{
    transform:scale(1.05);
}

.back{
    display:block;
    text-align:center;
    margin-bottom:15px;
    color:#cbd5e1;
    text-decoration:none;
}
</style>
</head>

<body>

<form method="POST" class="card">

<a href="profile.php" class="back">⬅ Back</a>

<div class="title">
<i class="fa-solid fa-user-gear"></i> Complete Profile
</div>

<!-- NAME -->
<div class="input-group">
<i class="fa-solid fa-user"></i>
<input type="text" name="name" placeholder="Enter Your Name" required>
</div>

<!-- SERVICE -->
<div class="input-group">
<i class="fa-solid fa-briefcase"></i>

<select name="service" required class="custom-select">
<option value="">🔍 Select Service</option>

<option>🔧 Plumber</option>
<option>⚡ Electrician</option>
<option>🪚 Carpenter</option>
<option>🎨 Painter</option>
<option>❄️ AC Repair</option>
<option>🧊 Fridge Repair</option>
<option>🌀 Washing Machine Repair</option>
<option>📺 TV Repair</option>
<option>📱 Mobile Repair</option>
<option>💻 Laptop Repair</option>

<option>💄 Beautician</option>
<option>💇 Hair Stylist</option>
<option>🎭 Makeup Artist</option>
<option>🧹 Home Cleaning</option>
<option>🐜 Pest Control</option>
<option>🌱 Gardener</option>

<option>🚗 Driver</option>
<option>👨‍🍳 Cook</option>
<option>📚 Tutor</option>
<option>📸 Photographer</option>
<option>🏠 Interior Designer</option>

</select>
</div>

<!-- PRICE -->
<div class="input-group">
<i class="fa-solid fa-indian-rupee-sign"></i>
<input type="number" name="price" placeholder="Price Per Hour" required>
</div>

<!-- PHONE -->
<div class="input-group">
<i class="fa-solid fa-phone"></i>
<input type="text" name="phone" placeholder="Phone Number" required>
</div>

<!-- EXPERIENCE -->
<div class="input-group">
<i class="fa-solid fa-briefcase"></i>
<input type="number" name="exp" placeholder="Experience (years)" required>
</div>

<button name="save">
<i class="fa-solid fa-floppy-disk"></i> Save Profile
</button>

</form>

</body>
</html>