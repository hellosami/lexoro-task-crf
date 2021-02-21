<?php
    require 'class.book.php';

        

    if(isset($_POST['btn-book'])) {
        DbQuery::book($_POST['room-name'], $_POST['booking-date'], $_POST['name']);
    }

    
    if(isset($_POST['btn-capacity'])) {
        DbQuery::find_capacity($_POST['set-date']);
    }



?>



<?php
    require '../handler/class.auth.php';
    $t = JWTAuth::Verify();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>

<style>
            @import url('https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400,600&display=swap');
    * {
        margin: 0;
        padding: 0;
        font-family: 'Source Sans Pro', sans-serif;
        font-weight: 300;
        box-sizing: border-box;
    }
    
    html, body {
        background-color: #007deb;
    }

    .form > span {
        font-size: 1.2em;
    }
    .form > span {
        display: block;
        margin: 0 0 4px 0;
        
    }

    .form-container {
        max-width: 320px;
        background-color: #FFF;
        padding: 10px 20px 20px 20px;
        margin: 0 auto;
        margin-top: 25px;
    }
    .btn {
        
        color: #FFF;
        border: 0;
        border-radius: 3px;
        height: 50px;
        cursor: pointer;
        width: 100%;
        font-size: 1.2em;
    }

    .btn { background-color: #0066c8; }

    .form > input[type="text"], input[type="date"], select {
        width: 100%;
        background-color: #eff0f4;
        height: 50px;
        font-size: 1.2em;
        padding: 8px;
        border: 0;
        border-radius: 3px;
    }


    


    .login-heading {
        color: #0066c8;
        font-weight: 600;
    }

    .registration-form {
        display: none;
    }
    



    .form-header {
        display: flex;
        justify-content: space-between;
        border-bottom: 1px solid #eff0f4;
    }

 

    .form-header > span {
        cursor: pointer;
    }
</style>
<div class="form-container">
    <div class="form-header">
        <span CLASS="login-heading"><?php echo $t; ?> » DASHBOARD</span>
    </div>
    <br>

    <!-- Login -->
    <form class="form" method="POST">
        <span>Room: </span>
        <select name="room-name" id="cars">
            <option value="1-1-Office1">Office 1</option>
            <option value="2-1-Office2">Office 2</option>
            <option value="3-2-Office3">Office 3</option>
            <option value="4-3-BigOffice">Big office</option>
            <option value="5-1-BossOffice">Boss office</option>
            <option value="6-2-AdditionalOffice">Additional office</option>
        </select>
        <br><br>
        <span>Date: </span>
        <input type="date" id="date" name="booking-date" required>
        <br><br>
        <span>Your Name: </span>
        <input type="text" name="name" placeholder="John Doe" required>
        <br><br>
        <input class="btn" type="submit" name="btn-book" value="Book">
    </form>

    <br><br>

    <span>Get Capacity</span>
    <form class="form" action="" method="POST">
        <input type="date" id="date" name="set-date" required>
        <br><br>
        <input class="btn" type="submit" name="btn-capacity" value="Get Capacity">
    </form>


</div>





</body>
</html>