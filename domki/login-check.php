<?php
if (!isset($_POST['email']) && !isset($_POST['password'])) {
    header("Location: login.php");
    exit();
}
require_once("include/sesconf.php");
session_start();
require_once("include/sql.php");
$db = connect();
$email = validate($_POST['email'], $db);
$pass = validate($_POST['password'], $db);

if (empty($email)) {
    header("Location: index.php?error=Email is required");
    exit();
}
if (empty($pass)) {
    header("Location: index.php?error=Password is required");
    exit();
}
$stmt = $db->prepare("SELECT * FROM user where email = ?");
$stmt->bindParam(1, $email);
$stmt->execute();
if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    if (password_verify($pass, $row['password'])) {
        $_SESSION['admin']['login'] = $email;
        header("Location: login.php");
        exit();
    } else {
        header("Location: index.php?error=Incorect User name or password");
        exit();
    }
} else {
    header("Location: index.php?error=Incorect User name or password");
    exit();
}
