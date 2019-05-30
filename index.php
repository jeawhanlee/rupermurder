<?php session_start() ?>

<!DOCTYPE HTML>
<html lang="en">
    <head>
        <title>Rupermurder - Register</title>
    </head>
    
    <body>
        <?php
        if(isset($_SESSION['haptic_feed'])){
            print $_SESSION['haptic_feed'];
            unset($_SESSION['haptic_feed']);
        }
        ?>
        <form method="POST" action="processes.php">
            <label>full name</label>
            <input type="text" name="name" placeholder="full name" required />
            <br /> <br />
            <label>E-mail</label>
            <input type="email" name="email" placeholder="E-mail" required />
            <br /> <br />
            <label>Password</label>
            <input type="password" name="pass" placeholder="Password" required />
            <br /> <br />
            <label>Confirm password</label>
            <input type="password" name="cpass" placeholder="Re-type password" required />
            <br /> <br />
            <button type="submit" name="val">Validate</button>
        </form>
    </body>
</html>