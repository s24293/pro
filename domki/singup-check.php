<?php
session_start();
include "include/sql.php";

if (isset($_POST['firstname']) && isset($_POST['password'])
    && isset($_POST['lastname']) && isset($_POST['re_password'])
    && isset($_POST['email']) && isset($_POST['phone'])) {

    function validate($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $firstname = validate($_POST['name']);
    $lastname = validate($_POST['lastname']);
    $pass = validate($_POST['password']);
    $re_pass = validate($_POST['re_password']);
    $email = validate($_POST['email']);
    $phone = validate($_POST['phone']);


    if (empty($firstname)) {
        header("Location: signup.php?error=User Name is required");
        exit();
    } else if (empty($lastname)) {
        header("Location: signup.php?error=Password is required");
        exit();
    } else if (empty($pass)) {
        header("Location: signup.php?error=Password is required");
        exit();
    } else if (empty($re_pass)) {
        header("Location: signup.php?error=Re Password is required");
        exit();
    } else if (empty($email)) {
        header("Location: signup.php?error=Name is required");
        exit();
    } else if ($pass !== $re_pass) {
        header("Location: signup.php?error=The confirmation password does not match");
        exit();
    } else {
        $conn = connect();
        // hashing the password
        $pass = md5($pass);

        $sql = "SELECT * FROM users WHERE user_name='$uname' ";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            header("Location: signup.php?error=The username is taken try another&$user_data");
            exit();
        } else {
            $sql2 = "INSERT INTO users(user_name, password, name) VALUES('$uname', '$pass', '$name')";
            $result2 = mysqli_query($conn, $sql2);
            if ($result2) {
                header("Location: signup.php?success=Your account has been created successfully");
                exit();
            } else {
                header("Location: signup.php?error=unknown error occurred&$user_data");
                exit();
            }
        }
    }

} else {
    header("Location: signup.php");
    exit();
}