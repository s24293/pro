<?php
require_once "include/sesconf.php";
session_start();
require_once "include/functions.php";
require_once "include/sql.php" ;

if (!isset($_POST['email']) && !isset($_POST['password']))
    redirectWithError("", "login.php");

$db = connect();
$email = validate($_POST['email']);
$pass = validate($_POST['password']);

if (empty($email))
    redirectWithError("Email is required", "login.php");

if (empty($pass))
    redirectWithError("Password is required", "login.php");

$stmt = $db->prepare("SELECT * FROM user where email = ?");
$stmt->bindParam(1, $email);
$stmt->execute();
if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    if (password_verify($pass, $row['password'])) {
        $_SESSION['id'] = $row['id'];
        if($row['UserType_id']!=2) {
            $stmt = $db->prepare("SELECT * FROM customer where User_id = ?");
            $stmt->bindParam(1, $row['id']);
            $stmt->execute();
            $_SESSION['udata'] = $stmt->fetch(PDO::FETCH_ASSOC);
            $_SESSION['udata']['email'] = $row['email'];
        }
        redirectWithError("", "login.php");
    } else
        redirectWithError("Incorect Email or passwordp", "login.php");
} else
    redirectWithError("Incorect Email or password", "login.php");


