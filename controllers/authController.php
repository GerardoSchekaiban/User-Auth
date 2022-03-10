<?php

session_start();

//Database connection
require 'config/db.php';
require_once 'emailController.php';

$errors = array();
$username = "";
$email = "";
$password = "";
$passwordConf = "";

//If user submit sign-up form
if(isset($_POST['signup'])){
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $passwordConf = $_POST['passwordConf'];

    // Validations
    if(empty($username)){
        $errors['username'] = "Username required";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || empty($email)){
        $errors['invalidEmail'] = "Invalid email";
    }
    if(empty($password)){
        $errors['password'] = "Password required";
    }
    if(empty($passwordConf)){
        $errors['passwordConf'] = "Password Confirmation required";
    }
    if($password !== $passwordConf){
        $errors['passwordMatch'] = "Passwords do not match";
    }

    // Read query
    $emailQuery = "SELECT * FROM users WHERE email=? LIMIT 1";

    // Prepared statements
    $stmt = $connection -> prepare($emailQuery);
    $stmt->bind_param('s',$email);
    $stmt->execute();
    $result = $stmt->get_result();
    $userCount = $result->num_rows;

    // Check if email already exists
    if($userCount > 0){
        $errors['emailExists'] = "Email already exists";
    }
    // If no errors
    if(count($errors) === 0){
        //Password
        $password = password_hash($password, PASSWORD_DEFAULT);
        $token = bin2hex(random_bytes(50));
        $verified = false;

        // Insert Query
        $sql = "INSERT into users (username, email, password, verified, token) VALUES (?, ?, ?, ?, ?)";
        $stmt = $connection ->prepare($sql);
        $stmt->bind_param('sssbs',$username, $email, $password, $verified, $token);

        // Execute statement
        if($stmt->execute()){
            //login user automatically
            $user_id = $connection->insert_id;
            $_SESSION['id'] = $user_id;
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;
            $_SESSION['verified'] = $verified;

            sendVerificationEmail($email, $token);

            //flash message
            $_SESSION['message'] = "You are now logged in!";
            //redirect
            header('location: index.php');
            exit();
        }else{
            $errors['db_error'] = "Database error: failed to register";
        };
    }
}

//If user submit log-in form
if(isset($_POST['login'])){
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validations
    if(empty($username)){
        $errors['username'] = "Email or username required";
    }
    if(empty($password)){
        $errors['password'] = "Password required";
    }

    //Read email or username submit
    $sql = "SELECT * FROM users WHERE email=? OR username=? LIMIT 1";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param('ss', $username, $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $userCount = $result->num_rows;
    $user = $result->fetch_assoc();

    // If no errors
    if(count($errors) === 0){
        //Check if email or username exists
        if($userCount > 0){
            //Verify if password is correct
            if(password_verify($password, $user['password'])){
                $_SESSION['id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['verified'] = $user['verified'];
                $_SESSION['message'] = "You are now logged in!";
                header('location: index.php');
                exit();
            }else{
                $errors['login_fail'] = "Wrong credentials";
            }
        }else{
            $errors['login_fail'] = "Wrong credentials";
        }       
    }
}

//Log out user
if(isset($_GET['logout'])){
    session_destroy();
    unset($_SESSION['id']);
    unset($_SESSION['username']);
    header('Location: login.php');
    exit();
}

//Verify user by token
function verifyUser($token){

    global $connection;
    $query = "SELECT * FROM users WHERE token = '$token' LIMIT 1 ";
    $result = mysqli_query($connection, $query);

    if(mysqli_num_rows($result) > 0){
        $user = mysqli_fetch_assoc($result);
        $update_query = "UPDATE users SET verified = 1 WHERE token = '$token'";

        if(mysqli_query($connection, $update_query)){
            $_SESSION['id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['verified'] = 1;
            $_SESSION['message'] = "Your email addesss was successfully verified";
            header('location: index.php');
            exit();
        }
    }else{
        echo 'User not found';
    }
}
