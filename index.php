<?php
    require 'handler/class.User.php';

    if(isset($_POST['LOGIN'])) 
        User::Login($_POST['USERNAME'], $_POST['PASSWORD']);

    else if(isset($_POST['REGISTER']))
        User::UserRegister($_POST['USERNAME'], $_POST['PASSWORD']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lexoro</title>

    <link rel="stylesheet" href="css/form.css">
</head>
<body>


<div class="form-container">
    <div class="form-header">
        <span CLASS="login-heading">CRF 4.0</span>
        <span id="lt" class="btn-active">LOGIN</span>
        <span id="rt">REGISTER</span>
    </div>
    <br>

    <!-- Login -->
    <form id="login-form" class="form" method="POST" autocomplete="off">
        <span>Username</span>
        <input id="lf-user" type="text" name="USERNAME" placeholder="username" autofocus required>
        <br><br>
        <span>Password</span>
        <input id="lf-pass" type="password" name="PASSWORD" placeholder="* * * * * *" required>
        <br><br>
        <input class="btn login-btn" type="submit" name="LOGIN" value="LOGIN">
    </form>

    <!-- Registration -->
    <form id="registration-form" class="form registration-form" method="POST" autocomplete="off"> 
        <span>Username</span>
        <input id="rf-user" type="text" name="USERNAME" placeholder="username" required>
        <br><br>
        <span>Password</span>
        <input id="rf-pass" type="password" name="PASSWORD" placeholder="* * * * * *" required>
        <br><br>
        <input class="btn registration-btn" type="submit" name="REGISTER" value="REGISTER">
    </form>
</div>

<script src="js/form.js"></script>
</body>
</html>