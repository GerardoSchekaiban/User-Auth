<?php

require 'config/db.php';

$errors = array();

//If user submit form
if(isset($_POST['sign-up'])){
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $passwordConf = $_POST['passwordConf'];

    // Validations
    if(empty($username)){
        $errors['username'] = "Username required";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $errors['invalidEmail'] = "Invalid email";
    }
    if(empty($email)){
        $errors['email'] = "email required";
    }
    if(empty($password)){
        $errors['password'] = "Password required";
    }
    if(empty($passwordConf)){
        $errors['passwordConf'] = "Password Confirmation required";
    }
    if($password !== $passwordConf){
        $errors['username'] = "Passwords do not match";
    }
    // Query
    $emailQuery = "SELECT * FROM users WHERE email=? LIMIT 1";

    // Prepared statements
    $stmt = $connection ->prepare($emailQuery);
    $stmt->bind_param('s',$email);
    $stmt->execute();
    $result = $stmt->get_result();
    $userCount = $result->num_rows;

    // Check if email already exists
    if($userCount > 0){
        $errors['emailExists'] = "Email already exists";
    }

    if(count($errors === 0)){
        $password = password_hash($password, PASSWORD_DEFAULT);

        // Insert Query
        $sql = "INSERT into users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $connection ->prepare($sql);
        $stmt->bind_param('sss',$username, $email, $password);

        // Execute statement
        if($stmt->execute()){

        }else{
            $errors['db_error'] = "Database error: failed to register";
        };
    }
}