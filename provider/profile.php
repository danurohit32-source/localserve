<?php
include("../db.php");

if(session_status() == PHP_SESSION_NONE){
    session_start();
}

if(!isset($_SESSION['user_id'])){
    header("Location: ../auth/login.php");
    exit();
}

$provider_id = $_SESSION['user_id'];

/* FETCH PROFILE */
$data = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT * FROM provider_details WHERE user_id='$provider_id'
"));
?>

<!DOCTYPE html>
<html>
<head>
<title>Profile</title>

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
    display:flex;
    background:#f1f5f9;
}

/* SIDEBAR SAME */
.sidebar{
    width:250px;
    height:100vh;
    background:linear-gradient(180deg,#0f172a,#1e293b);
    padding:20px;
    position:fixed;
    display:flex;
    flex-direction:column;
    justify-content:space-between;
}

.logo{
    font-size:22px;
    font-weight:600;
    color:white;
    margin-bottom:30px;
}

.sidebar a{
    display:flex;
    align-items:center;
    gap:10px;
    padding:12px;
    margin:10px 0;
    border-radius:10px;
    text-decoration:none;
    color:#cbd5e1;
}

.sidebar a:hover,
.active{
    background:linear-gradient(90deg,#2563eb,#3b82f6);
    color:white;
}

/* MAIN */
.container{
    margin-left:270px;
    padding:60px;
    width:100%;
    display:flex;
    justify-content:center;
}

/* ID CARD */
.card{
    width:350px;
    background:white;
    border-radius:20px;
    box-shadow:0 15px 40px rgba(0,0,0,0.15);
    overflow:hidden;
}

/* TOP STRIP */
.card-header{
    background:linear-gradient(90deg,#2563eb,#3b82f6);
    color:white;
    padding:20px;
    text-align:center;
    font-weight:600;
    font-size:18px;
}

/* PROFILE */
.profile{
    padding:25px;
    text-align:center;
}

/* AVATAR */
.avatar{
    width:90px;
    height:90px;
    border-radius:50%;
    background:linear-gradient(135deg,#60a5fa,#2563eb);
    display:flex;
    align-items:center;
    justify-content:center;
    color:white;
    font-size:35px;
    margin:0 auto 15px;
}

/* NAME */
.name{
    font-size:20px;
    font-weight:600;
}

/* DETAILS */
.details{
    margin-top:20px;
    text-align:left;
}

.details p{
    margin:8px 0;
    font-size:14px;
}

/* BADGE */
.badge{
    display:inline-block;
    background:#e0f2fe;
    color:#0369a1;
    padding:4px 10px;
    border-radius:8px;
    font-size:12px;
}

/* BUTTON */
.edit-btn{
    margin-top:20px;
    width:100%;
    padding:10px;
    border:none;
    border-radius:10px;
    background:linear-gradient(90deg,#2563eb,#3b82f6);
    color:white;
    cursor:pointer;
}
</style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">

<div>
<div class="logo">LocalServe ®</div>

<a href="/localserve/provider/dashboard.php">
<i class="fa-solid fa-house"></i> Dashboard
</a>

<a href="/localserve/provider/requests.php">
<i class="fa-solid fa-bell"></i> Requests
</a>

<a href="/localserve/provider/profile.php" class="active">
<i class="fa-solid fa-user"></i> Profile
</a>

<a href="/localserve/provider/earnings.php">
<i class="fa-solid fa-wallet"></i> Earnings
</a>
</div>

<a href="../auth/logout.php">
<i class="fa-solid fa-right-from-bracket"></i> Logout
</a>

</div>

<!-- MAIN -->
<div class="container">

<div class="card">

<div class="card-header">
Provider ID Card
</div>

<div class="profile">

<div class="avatar">
<i class="fa-solid fa-user"></i>
</div>

<div class="name">
<?php echo $data['name'] ?? "Not set"; ?>
</div>

<div class="badge">
<?php echo $data['service']; ?>
</div>

<div class="details">
<p><b>📞 Phone:</b> <?php echo $data['phone']; ?></p>
<p><b>🛠 Experience:</b> <?php echo $data['experience']; ?> years</p>
<p><b>💰 Price:</b> ₹<?php echo $data['price_per_hour']; ?>/hr</p>
</div>

<form action="create_profile.php">
<button class="edit-btn">Edit Profile</button>
</form>

</div>

</div>

</div>

</body>
</html>