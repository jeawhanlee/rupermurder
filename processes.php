<?php
session_start();

require_once 'db_config.php';

if(isset($_POST['val'])){
    // create new user
    $name = $_POST['name'];
    $email = $_POST['email'];
    $pass = $_POST['pass'];
    $cpass = $_POST['cpass'];
    
    
    if(empty($name) || empty($email) || empty($pass) || empty($cpass)){
        $_SESSION['haptic_feed'] = 'Empty fields detected';
        header("Location:index.php");
    }
    
    // check email
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $_SESSION['haptic_feed'] = 'Invalid email';
        header("Location:index.php");
    }
    
    // check passwords
    $pass_length = strlen($pass);
    if($pass_length < 16){
        $_SESSION['haptic_feed'] = 'Password needs to be at least 16 characters';
        header("Location:index.php");
        exit;
    }
    else if($pass_length > 16){
        $_SESSION['haptic_feed'] = 'Password must have at most 16 characters';
        header("Location:index.php");
        exit;
    }
    
    // convert password to array
    $pass_array = str_split($pass);
    
    // check for 3 numeric characters and 2 special characters
    $num_count = 0;
    $spec_count = 0;
    $lower_count = 0;
    
    for($i=0;$i<=count($pass_array)-1;$i++){
        // check for 3 numeric characters
        if(is_numeric($pass_array[$i])){
            $num_count  += 1;
        }
        // check for 2 special characters
        if(preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $pass_array[$i])){
            $spec_count += 1;
        }
        if(preg_match("/^[a-z]*$/",$pass_array[$i])){
            $lower_count += 1;
        }
    }
    
    //print $num_count.'<br />';
    //print $spec_count.'<br />';
    //exit;
    
    if($num_count < 3){
        $_SESSION['haptic_feed'] = 'Password must have 3 numeric characters'.$num_count;
        header("Location:index.php");
        exit;
    }
    
    if($spec_count < 3){
        $_SESSION['haptic_feed'] = 'Password must have 3 special characters'.$spec_count;
        header("Location:index.php");
        exit;
    }
    
    if($lower_count < 3){
        $_SESSION['haptic_feed'] = 'Password must contain lower case'.$lower_count;
        header("Location:index.php");
        exit;
    }
    
    
    // check if first 2 characters are uppercase
    if(!ctype_upper($pass_array[0]) && !ctype_upper($pass_array[1])){
        $_SESSION['haptic_feed'] = 'First 2 characters of password are not uppercase';
        header("Location:index.php");
        exit;
    }
    
    // check if passwords match
    if($pass != $cpass){
        $_SESSION['haptic_feed'] = 'Passwords do not match';
        header("Location:index.php");
        exit;
    }
    
    // encrypt password low level
    $pass = sha1($pass);
    
    // check if email is unique
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $select = mysqli_query($con,$sql);
    $count = mysqli_num_rows($select);
    
    if($count > 0){
        $_SESSION['haptic_feed'] = 'A user already exists with the specified email';
        header("Location:index.php");
        exit;
    }
    
    // after successful validation store user in db
    $sql = "INSERT INTO users (fullname,email,password) VALUES('$name','$email','$pass')";
    if(mysqli_query($con, $sql)){
        $_SESSION['haptic_feed'] = 'Registration was successful';
        header("Location:index.php");
        exit;
    }
    
}

if(isset($_POST['signin'])){
    
    // validate user login input
    $email = $_POST['email'];
    $pass = $_POST['pass'];
    
    if(!isset($_SESSION['error_counter'])){
        $_SESSION['error_counter'] = 0;   
    }
    
    // check for empty fields
    if(empty($email) || empty($pass)){
        $_SESSION['haptic_feed'] = 'Empty fields detected';
        header("Location:signin.php");
        exit;
    }
    
    // check email
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $select = mysqli_query($con,$sql);
    $row_email_count = mysqli_num_rows($select);
    
    if($row_email_count > 0){
        // check password
        // check email
        $sql = "SELECT * FROM users WHERE email = '$email' AND password = '$pass'";
        $select = mysqli_query($con,$sql);
        $row_pass = mysqli_num_rows($select);
        
        if($row_pass == 0){
            $_SESSION['error_counter'] += 1;
            $_SESSION['haptic_feed'] = 'Invalid password';
            
            if($_SESSION['error_counter'] >= 3){
                // get user ip
                $user_ip = $_SERVER['REMOTE_ADDR'];
                $sql = "UPDATE users SET ip = '$user_ip' WHERE email = '$email'";
                mysqli_query($con,$sql);

                $_SESSION['haptic_feed'] = 'You have typed the wrong password thrice, try again in 5 minutes';
                header("Location:signin.php");
                exit;
            } 
            header("Location:signin.php");
            exit;
        }
    }
    else{
        $_SESSION['haptic_feed'] = 'Invalid login credentials';
        header("Location:signin.php");
        exit;
    }
    
}


?>