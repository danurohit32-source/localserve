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

/* TOTAL EARNINGS */
$total = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT SUM(p.price_per_hour) as total 
FROM bookings b
JOIN provider_details p ON b.provider_id = p.user_id
WHERE b.provider_id='$provider_id' AND b.status='accepted'
"))['total'];

/* WITHDRAW */
if(isset($_POST['withdraw'])){
    echo "<script>alert('Withdrawal Request Sent!');</script>";
}

/* FETCH */
$data = mysqli_query($conn,"
SELECT b.*, p.price_per_hour 
FROM bookings b
JOIN provider_details p ON b.provider_id = p.user_id
WHERE b.provider_id='$provider_id' AND b.status='accepted'
ORDER BY b.id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Earnings</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:Poppins;}

body{
    display:flex;
    background:#f1f5f9;
}

/* SIDEBAR */
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

.logo{color:white;font-size:22px;margin-bottom:30px;}

.sidebar a{
    display:flex;
    gap:12px;
    align-items:center;
    padding:12px;
    margin:10px 0;
    border-radius:10px;
    text-decoration:none;
    color:#cbd5e1;
}

.sidebar a:hover,.active{
    background:linear-gradient(90deg,#2563eb,#3b82f6);
    color:white;
}

/* MAIN */
.container{
    margin-left:270px;
    padding:50px;
    width:100%;
}

/* HEADER */
.header{
    background:white;
    padding:25px;
    border-radius:15px;
    box-shadow:0 5px 20px rgba(0,0,0,0.08);
    margin-bottom:25px;
}

.header h2{
    font-size:26px;
}

/* TOTAL CARD */
.total-card{
    background:linear-gradient(135deg,#22c55e,#16a34a);
    color:white;
    padding:25px;
    border-radius:18px;
    box-shadow:0 10px 25px rgba(0,0,0,0.15);
    margin-bottom:30px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.total-card h3{
    font-size:22px;
}

/* WITHDRAW BUTTON */
.withdraw{
    padding:10px 18px;
    border:none;
    border-radius:10px;
    background:white;
    color:#16a34a;
    font-weight:600;
    cursor:pointer;
}

/* GRID */
.grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(320px,1fr));
    gap:25px;
}

/* CARD */
.card{
    background:white;
    border-radius:18px;
    padding:20px;
    box-shadow:0 10px 25px rgba(0,0,0,0.1);
    transition:0.3s;
}

.card:hover{
    transform:translateY(-5px);
}

/* INFO */
.info{
    display:flex;
    align-items:center;
    gap:10px;
    margin:8px 0;
    color:#475569;
}

/* AMOUNT */
.amount{
    margin-top:10px;
    font-size:18px;
    font-weight:600;
    color:#16a34a;
}

/* DIVIDER */
.divider{
    height:1px;
    background:#e2e8f0;
    margin:10px 0;
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

<a href="/localserve/provider/profile.php">
<i class="fa-solid fa-user"></i> Profile
</a>

<a href="/localserve/provider/earnings.php" class="active">
<i class="fa-solid fa-wallet"></i> Earnings
</a>
</div>

<a href="../auth/logout.php">
<i class="fa-solid fa-right-from-bracket"></i> Logout
</a>

</div>

<!-- MAIN -->
<div class="container">

<div class="header">
<h2><i class="fa-solid fa-money-bill-trend-up"></i> Earnings Overview</h2>
</div>

<!-- TOTAL -->
<form method="POST">
<div class="total-card">
<h3><i class="fa-solid fa-wallet"></i> ₹<?php echo $total ?? 0; ?></h3>
<button name="withdraw" class="withdraw">Withdraw</button>
</div>
</form>

<div class="grid">

<?php while($row=mysqli_fetch_assoc($data)){ ?>

<div class="card">

<div class="info">
<i class="fa-solid fa-id-badge"></i>
Booking ID: <?php echo $row['id']; ?>
</div>

<div class="info">
<i class="fa-solid fa-calendar-days"></i>
<?php echo $row['booking_date']; ?>
</div>

<div class="info">
<i class="fa-solid fa-clock"></i>
<?php echo $row['time_slot']; ?>
</div>

<div class="divider"></div>

<div class="amount">
<i class="fa-solid fa-indian-rupee-sign"></i>
₹<?php echo $row['price_per_hour']; ?>
</div>

</div>

<?php } ?>

</div>

</div>

</body>
</html>