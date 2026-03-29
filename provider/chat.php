<?php
include("../db.php");

if(session_status() == PHP_SESSION_NONE){
    session_start();
}

$provider_id = $_SESSION['user_id'];
$booking_id = $_GET['booking_id'];

/* SEND */
if(isset($_POST['send'])){
    $msg = $_POST['msg'];

    $b = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM bookings WHERE id='$booking_id'"));
    $user_id = $b['user_id'];

    mysqli_query($conn,"INSERT INTO messages(booking_id,sender_id,receiver_id,message)
    VALUES('$booking_id','$provider_id','$user_id','$msg')");
}

/* FETCH */
$messages = mysqli_query($conn,"SELECT * FROM messages WHERE booking_id='$booking_id'");
?>

<!DOCTYPE html>
<html>
<head>
<title>Chat</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">

<style>
body{
    margin:0;
    font-family:Poppins;
    background:linear-gradient(135deg,#0f172a,#1e3a8a,#2563eb);
    color:white;
}

/* HEADER */
.header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:15px 25px;
    background:rgba(0,0,0,0.5);
}

.back{
    background:#22d3ee;
    padding:8px 15px;
    border-radius:10px;
    text-decoration:none;
    color:black;
}

/* CHAT CONTAINER */
.chat-container{
    max-width:650px;
    margin:20px auto;
    background:rgba(255,255,255,0.1);
    border-radius:20px;
    backdrop-filter:blur(15px);
    padding:20px;
}

/* CHAT BOX */
.chat-box{
    height:420px;
    overflow-y:auto;
    padding:10px;
    display:flex;
    flex-direction:column;
}

/* MESSAGE */
.msg{
    margin:8px 0;
    padding:12px 15px;
    border-radius:15px;
    max-width:70%;
    font-size:14px;
}

.me{
    background:linear-gradient(135deg,#22d3ee,#06b6d4);
    color:black;
    align-self:flex-end;
}

.other{
    background:linear-gradient(135deg,#6366f1,#8b5cf6);
}

/* INPUT AREA */
form{
    display:flex;
    gap:10px;
    margin-top:15px;
}

input{
    flex:1;
    padding:12px;
    border:none;
    border-radius:12px;
    outline:none;
}

button{
    padding:12px 18px;
    border:none;
    border-radius:12px;
    background:linear-gradient(135deg,#22d3ee,#06b6d4);
    cursor:pointer;
    font-weight:500;
}

/* SCROLLBAR */
.chat-box::-webkit-scrollbar{
    width:5px;
}
.chat-box::-webkit-scrollbar-thumb{
    background:#22d3ee;
    border-radius:10px;
}
</style>
</head>

<body>

<!-- HEADER -->
<div class="header">
    <h3>💬 Chat</h3>
    <a href="requests.php" class="back">⬅ Back</a>
</div>

<!-- CHAT -->
<div class="chat-container">

<div class="chat-box">

<?php while($row=mysqli_fetch_assoc($messages)){ ?>

<div class="msg <?php echo ($row['sender_id']==$provider_id)?'me':'other'; ?>">
<?php echo $row['message']; ?>
</div>

<?php } ?>

</div>

<form method="POST">
<input type="text" name="msg" placeholder="Type your message..." required>
<button name="send">Send</button>
</form>

</div>

</body>
</html>