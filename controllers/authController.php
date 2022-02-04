<?php

session_start();

//Database connection
require 'config/db.php';

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

        // Insert Query
        $sql = "INSERT into users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $connection ->prepare($sql);
        $stmt->bind_param('sss',$username, $email, $password);

        // Execute statement
        if($stmt->execute()){
            //login user automatically
            $user_id = $connection->insert_id;
            $_SESSION['id'] = $user_id;
            $_SESSION['username'] = $username;
            $_SESSION['message'] = "You are now logged in!";
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

