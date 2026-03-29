<?php
include("../db.php");

if(session_status() == PHP_SESSION_NONE){
    session_start();
}

$user_id = $_SESSION['user_id'];
$booking_id = $_GET['booking_id'];

/* SEND MESSAGE */
if(isset($_POST['send'])){
    $msg = $_POST['msg'];

    // get provider id
    $b = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM bookings WHERE id='$booking_id'"));
    $provider_id = $b['provider_id'];

    mysqli_query($conn,"INSERT INTO messages(booking_id,sender_id,receiver_id,message)
    VALUES('$booking_id','$user_id','$provider_id','$msg')");
}

/* FETCH */
$messages = mysqli_query($conn,"SELECT * FROM messages WHERE booking_id='$booking_id'");
?>

<!DOCTYPE html>
<html>
<head>
<title>Chat</title>

<style>
body{font-family:Poppins;background:#0f172a;color:white;}

.chat-box{
    height:400px;
    overflow-y:auto;
    background:rgba(255,255,255,0.1);
    padding:15px;
}

.msg{
    margin:10px 0;
    padding:10px;
    border-radius:10px;
}

.me{background:#22d3ee;color:black;}
.other{background:#6366f1;}

form{margin-top:10px;}
input{padding:10px;width:70%;}
button{padding:10px;}
</style>
</head>

<body>

<h2>Chat</h2>

<div class="chat-box">

<?php while($row=mysqli_fetch_assoc($messages)){ ?>

<div class="msg <?php echo ($row['sender_id']==$user_id)?'me':'other'; ?>">
<?php echo $row['message']; ?>
</div>

<?php } ?>

</div>

<form method="POST">
<input type="text" name="msg" placeholder="Type message" required>
<button name="send">Send</button>
</form>

</body>
</html>