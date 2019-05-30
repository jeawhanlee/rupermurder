<?php session_start() ?>
<!DOCTYPE HTML>
<html lang="en">
    <head>
        <title>Rupermurder - Signin</title>
    </head>
    
    <body>
        <?php
        if(isset($_SESSION['haptic_feed'])){
            print $_SESSION['haptic_feed'];
            unset($_SESSION['haptic_feed']);
        }
        ?>
        <form method="POST" action="processes.php">
            <label>E-mail</label>
            <input type="email" name="email" placeholder="E-mail" />
            <br /><br />
            <label>Password</label>
            <input type="password" name="pass" placeholder="Password"  />
            <br /><br />
            <button type="submit" name="signin">Validate</button>
        </form>
    </body>
</html>