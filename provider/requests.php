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

/* FETCH REQUESTS */
$requests = mysqli_query($conn,"
SELECT b.*, u.name 
FROM bookings b
JOIN users u ON b.user_id = u.id
WHERE b.provider_id='$provider_id'
ORDER BY b.id DESC
");

/* STATUS UPDATE */
if(isset($_GET['action'])){
    $id = $_GET['id'];
    $action = $_GET['action'];

    mysqli_query($conn,"UPDATE bookings SET status='$action' WHERE id='$id'");
    header("Location: requests.php");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Requests</title>

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
    padding:40px;
    width:100%;
}

/* HEADER */
.header{
    background:white;
    padding:25px;
    border-radius:18px;
    margin-bottom:30px;
    box-shadow:0 10px 30px rgba(0,0,0,0.08);
}

.header h2{
    font-size:26px;
}

.header p{
    color:#64748b;
    margin-top:5px;
}

/* GRID */
.grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(280px,1fr));
    gap:20px;
}

/* CARD */
.card{
    background:white;
    padding:22px;
    border-radius:18px;
    box-shadow:0 10px 25px rgba(0,0,0,0.08);
    transition:0.3s;
    position:relative;
}

.card:hover{
    transform:translateY(-6px);
}

/* USER */
.user{
    font-size:16px;
    font-weight:600;
    margin-bottom:10px;
}

/* INFO */
.info{
    font-size:14px;
    color:#64748b;
    margin:5px 0;
}

/* STATUS BADGE */
.status{
    display:inline-block;
    margin-top:8px;
    padding:6px 12px;
    border-radius:20px;
    font-size:12px;
    font-weight:500;
}

.pending{background:#fef3c7;color:#92400e;}
.accepted{background:#dcfce7;color:#166534;}
.rejected{background:#fee2e2;color:#991b1b;}

/* BUTTONS */
.actions{
    margin-top:15px;
    display:flex;
    gap:10px;
}

.btn{
    flex:1;
    text-align:center;
    padding:10px;
    border-radius:10px;
    text-decoration:none;
    font-size:13px;
    font-weight:500;
}

/* COLORS */
.accept{background:#22c55e;color:white;}
.reject{background:#ef4444;color:white;}
.chat{background:#3b82f6;color:white;}

.accept:hover{background:#16a34a;}
.reject:hover{background:#dc2626;}
.chat:hover{background:#2563eb;}

/* ICON TOP */
.icon{
    position:absolute;
    top:15px;
    right:15px;
    color:#94a3b8;
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

<a href="/localserve/provider/requests.php" class="active">
<i class="fa-solid fa-bell"></i> Requests
</a>

<a href="/localserve/provider/profile.php">
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

<!-- HEADER -->
<div class="header">
<h2>📩 Service Requests</h2>
<p>Manage and respond to customer requests professionally</p>
</div>

<!-- CARDS -->
<div class="grid">

<?php while($row=mysqli_fetch_assoc($requests)){ ?>

<div class="card">

<i class="fa-solid fa-user icon"></i>

<div class="user"><?php echo $row['name']; ?></div>

<div class="info"><i class="fa-solid fa-calendar"></i> <?php echo $row['booking_date']; ?></div>
<div class="info"><i class="fa-solid fa-clock"></i> <?php echo $row['time_slot']; ?></div>

<div class="status <?php echo $row['status']; ?>">
<?php echo ucfirst($row['status']); ?>
</div>

<div class="actions">

<a href="?action=accepted&id=<?php echo $row['id']; ?>" class="btn accept">
<i class="fa-solid fa-check"></i> Accept
</a>

<a href="?action=rejected&id=<?php echo $row['id']; ?>" class="btn reject">
<i class="fa-solid fa-xmark"></i> Reject
</a>

<a href="chat.php?booking_id=<?php echo $row['id']; ?>" class="btn chat">
<i class="fa-solid fa-comment"></i> Chat
</a>

</div>

</div>

<?php } ?>

</div>

</div>

</body>
</html>