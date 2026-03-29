<?php
include("../db.php");

if(session_status() == PHP_SESSION_NONE){
    session_start();
}

if(!isset($_SESSION['user_id'])){
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/* BOOKING */
if(isset($_POST['book'])){
    $provider_id = $_POST['provider_id'];
    $date = $_POST['date'];
    $time = $_POST['time'];

    mysqli_query($conn,"INSERT INTO bookings(user_id,provider_id,status,booking_date,time_slot) 
    VALUES('$user_id','$provider_id','pending','$date','$time')");

    echo "<script>alert('Booking Sent');</script>";
}

/* FETCH PROVIDERS */
$providers = mysqli_query($conn,"
SELECT p.*, 
(SELECT ROUND(AVG(rating),1) FROM bookings b WHERE b.provider_id=p.user_id) as rating
FROM provider_details p
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Book Service</title>

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
    padding:50px;
    width:100%;
}

/* HEADER */
.header{
    background:white;
    padding:25px;
    border-radius:15px;
    box-shadow:0 5px 20px rgba(0,0,0,0.08);
    margin-bottom:40px;
}

.header h2{
    margin-bottom:5px;
}

/* GRID */
.grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(320px,1fr));
    gap:30px;
}

/* CARD */
.card{
    background:white;
    padding:25px;
    border-radius:18px;
    box-shadow:0 8px 25px rgba(0,0,0,0.08);
    transition:0.3s;
}

.card:hover{
    transform:translateY(-6px);
}

.card h3{
    margin-bottom:5px;
}

/* TEXT */
.price{
    color:#2563eb;
    font-weight:600;
}

.rating{
    color:#facc15;
    font-size:14px;
}

/* INPUT */
input, select{
    width:100%;
    padding:10px;
    margin-top:10px;
    border-radius:10px;
    border:1px solid #e2e8f0;
}

/* BUTTON GROUP */
.btn-group{
    display:flex;
    justify-content:space-between;
    margin-top:15px;
}

/* BUTTONS */
.btn{
    padding:10px 18px;
    border:none;
    border-radius:10px;
    cursor:pointer;
    font-weight:500;
}

.book{
    background:linear-gradient(90deg,#2563eb,#3b82f6);
    color:white;
}

.view{
    background:#e2e8f0;
}

/* MODAL */
.modal{
    display:none;
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    backdrop-filter:blur(6px);
    justify-content:center;
    align-items:center;
}

.modal-content{
    background:white;
    padding:25px;
    border-radius:15px;
    width:300px;
    text-align:center;
}

.close-btn{
    margin-top:10px;
    padding:8px 15px;
    border:none;
    border-radius:8px;
    background:#2563eb;
    color:white;
}
</style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">

<div>
<div class="logo">LocalServe ®</div>

<a href="/localserve/user/dashboard.php">
<i class="fa-solid fa-house"></i> Dashboard
</a>

<a href="/localserve/user/book.php" class="active">
<i class="fa-solid fa-plus"></i> Book Service
</a>

<a href="/localserve/user/bookings.php">
<i class="fa-solid fa-list-check"></i> My Bookings
</a>
</div>

<a href="../auth/logout.php">
<i class="fa-solid fa-right-from-bracket"></i> Logout
</a>

</div>

<!-- MAIN -->
<div class="container">

<div class="header">
<h2>🛠 Book Service</h2>
<p>Select provider and book easily</p>
</div>

<div class="grid">

<?php while($row=mysqli_fetch_assoc($providers)){ ?>

<div class="card">

<h3><?php echo $row['service']; ?></h3>
<p class="price">₹<?php echo $row['price_per_hour']; ?>/hr</p>
<p class="rating">⭐ <?php echo $row['rating'] ?? "No rating"; ?></p>

<form method="POST">

<input type="hidden" name="provider_id" value="<?php echo $row['user_id']; ?>">

<input type="date" name="date" required>

<select name="time" required>
<option value="">Select Time</option>
<option>9 AM - 11 AM</option>
<option>11 AM - 1 PM</option>
<option>1 PM - 3 PM</option>
<option>3 PM - 5 PM</option>
<option>5 PM - 7 PM</option>
</select>

<div class="btn-group">
<button class="btn book" name="book">Book Now</button>
<button type="button" class="btn view"
data-service="<?php echo htmlspecialchars($row['service']); ?>"
data-phone="<?php echo htmlspecialchars($row['phone']); ?>"
data-exp="<?php echo htmlspecialchars($row['experience']); ?>"
onclick="openProfile(this)">
View Profile
</button>
</div>

</form>

</div>

<?php } ?>

</div>

</div>

<!-- MODAL -->
<div class="modal" id="modal">
<div class="modal-content">

<h3 id="m_service"></h3>
<p id="m_phone"></p>
<p id="m_exp"></p>

<button class="close-btn" onclick="closeModal()">Close</button>

</div>
</div>

<script>
function openProfile(btn){
    document.getElementById("modal").style.display="flex";
    document.getElementById("m_service").innerText = "Service: " + btn.dataset.service;
    document.getElementById("m_phone").innerText = "Phone: " + btn.dataset.phone;
    document.getElementById("m_exp").innerText = "Experience: " + btn.dataset.exp + " yrs";
}

function closeModal(){
    document.getElementById("modal").style.display="none";
}
</script>

</body>
</html>