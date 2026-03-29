<?php
include("../db.php");

$msg = "";

if(isset($_POST['login'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $res = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");

    if(mysqli_num_rows($res) > 0){
        $user = mysqli_fetch_assoc($res);

        if(password_verify($password, $user['password'])){

            // ✅ session start safe
            if(session_status() == PHP_SESSION_NONE){
                session_start();
            }

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];

            // 🔥 REDIRECTION LOGIC
            if($user['role'] == "user"){
                header("Location: ../user/dashboard.php");
            } else {

                // 🔥 provider profile check
                $check = mysqli_query($conn, "SELECT * FROM provider_details WHERE user_id='".$user['id']."'");

                if(mysqli_num_rows($check) == 0){
                    header("Location: ../provider/create_profile.php");
                } else {
                    header("Location: ../provider/dashboard.php");
                }
            }

        } else {
            $msg = "Wrong Password!";
        }
    } else {
        $msg = "User not found!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Login</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>

body{
    margin:0;
    font-family:'Poppins',sans-serif;
    background: linear-gradient(135deg,#0f2027,#203a43,#2c5364);
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}

/* CARD */
.card{
    background: rgba(255,255,255,0.08);
    padding:40px 35px;
    border-radius:20px;
    backdrop-filter: blur(15px);
    width:400px;
    text-align:center;
    color:white;
    box-shadow: 0 10px 40px rgba(0,0,0,0.3);
    border:1px solid rgba(255,255,255,0.1);
}

/* TITLE */
h2{
    margin-bottom:20px;
    font-weight:600;
}

/* MESSAGE */
.msg{
    margin-bottom:15px;
    color:#ffcc00;
    font-size:14px;
}

/* INPUT GROUP */
.input-box{
    position:relative;
    width:100%;
    margin:10px 0;
}

.input-box i{
    position:absolute;
    top:50%;
    left:15px;
    transform:translateY(-50%);
    color:#555;
}

/* INPUT */
.input-box input{
    width:100%;
    height:45px;
    padding:0 15px 0 40px;
    border:none;
    border-radius:12px;
    outline:none;
    font-size:14px;
    box-sizing:border-box;
}

/* FOCUS */
.input-box input:focus{
    box-shadow:0 0 8px #00c6ff;
}

/* BUTTON */
button{
    width:100%;
    height:45px;
    margin-top:15px;
    background:linear-gradient(135deg,#00c6ff,#0072ff);
    border:none;
    border-radius:12px;
    color:white;
    font-weight:500;
    cursor:pointer;
    transition:0.3s;
}

button:hover{
    transform: translateY(-2px);
}

/* LINK */
p{
    margin-top:15px;
}

a{
    color:#00c6ff;
    text-decoration:none;
    font-weight:500;
}

</style>
</head>

<body>

<div class="card">

<h2><i class="fa-solid fa-right-to-bracket"></i> Welcome Back</h2>

<div class="msg"><?php echo $msg; ?></div>

<form method="POST">

<div class="input-box">
<i class="fa-solid fa-envelope"></i>
<input type="email" name="email" placeholder="Email Address" required>
</div>

<div class="input-box">
<i class="fa-solid fa-lock"></i>
<input type="password" name="password" placeholder="Password" required>
</div>

<button name="login">
<i class="fa-solid fa-arrow-right-to-bracket"></i> Login
</button>

</form>

<p>
Don't have account? 
<a href="register.php">Register</a>
</p>

</div>

</body>
</html>