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

/* FETCH BOOKINGS */
$data = mysqli_query($conn,"
SELECT b.*, p.service 
FROM bookings b
JOIN provider_details p ON b.provider_id = p.user_id
WHERE b.user_id='$user_id'
ORDER BY b.id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>My Bookings</title>

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

/* GRID */
.grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(300px,1fr));
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

/* SERVICE */
.service{
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

/* STATUS */
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

/* CHAT BUTTON */
.chat{
    background:#3b82f6;
    color:white;
}

.chat:hover{
    background:#2563eb;
}

/* ICON */
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

<a href="/localserve/user/dashboard.php">
<i class="fa-solid fa-house"></i> Dashboard
</a>

<a href="/localserve/user/book.php">
<i class="fa-solid fa-plus"></i> Book Service
</a>

<a href="/localserve/user/bookings.php" class="active">
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
<h2>📋 My Bookings</h2>
</div>

<div class="grid">

<?php while($row=mysqli_fetch_assoc($data)){ ?>

<div class="card">

<i class="fa-solid fa-box icon"></i>

<div class="service"><?php echo $row['service']; ?></div>

<div class="info">
<i class="fa-solid fa-calendar"></i>
<?php echo $row['booking_date']; ?>
</div>

<div class="info">
<i class="fa-solid fa-clock"></i>
<?php echo $row['time_slot']; ?>
</div>

<div class="status <?php echo $row['status']; ?>">
<?php echo ucfirst($row['status']); ?>
</div>

<!-- ACTIONS -->
<div class="actions">

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