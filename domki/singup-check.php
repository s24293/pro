<?php
require_once "include/sesconf.php";
session_start();
require_once "include/sql.php";
require_once "include/functions.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST')
    redirectWithError("", "signup.php");

$conn = connect();
$firstname = validate($_POST['firstname']);
$lastname = validate($_POST['lastname']);
$pass = validate($_POST['password']);
$re_pass = validate($_POST['re_password']);
$email = validate($_POST['email']);
$phone = validate($_POST['phone']);

if (empty($firstname) || empty($lastname) || empty($email))
    redirectWithError("Uzupełnij wszystkie dane", "signup.php");
if (empty($pass) || empty($re_pass))
    redirectWithError("Uzupełnij hasło", "signup.php");
if (!filter_var($email, FILTER_VALIDATE_EMAIL))
    redirectWithError("Email jest niepoprawny", "signup.php");
if ($pass !== $re_pass)
    redirectWithError("Hasła muszą być takie same", "signup.php");

$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bindParam(1, $email_from_form);
$stmt->execute();
if ($stmt->fetch(PDO::FETCH_ASSOC))
    redirectWithError("E-mail jest zajęty", "signup.php");

function bindExecuteArray($stmt, $params): void
{
    foreach ($params as $key => &$value)
        $stmt->bindParam($key, $value);
    $stmt->execute();
}

try {
    $conn->beginTransaction();

    $pass = password_hash($pass, PASSWORD_DEFAULT);

    $sqlUser = "INSERT INTO User (email, password, UserType_id) VALUES (:email, :password, 1)";
    $stmtUser = $conn->prepare($sqlUser);
    bindExecuteArray($stmtUser, [':email' => $email, ':password' => $pass]);

    $userId = $conn->lastInsertId();

    $sqlCustomer = "INSERT INTO Customer (name, surname, phone_number, User_id) VALUES (:firstname, :lastname, :phone, :userId)";
    $stmtCustomer = $conn->prepare($sqlCustomer);
    bindExecuteArray($stmtCustomer, [':firstname' => $firstname, ':lastname' => $lastname, ':phone' => $phone, ':userId' => $userId]);

    $conn->commit();

    header("Location: signup.php?success=Your account has been created successfully");
    exit();
} catch (PDOException $e) {
    $conn->rollBack();
    redirectWithError("Błąd podczas rejestracji" . $e->getMessage(), "signup.php");
}

