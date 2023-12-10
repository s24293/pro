<?php
session_start();
include "include/sql.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: signup.php");
    exit();
}
$conn = connect();
$firstname = validate($_POST['firstname'],$conn);
$lastname = validate($_POST['lastname'],$conn);
$pass = validate($_POST['password'],$conn);
$re_pass = validate($_POST['re_password'],$conn);
$email = validate($_POST['email'],$conn);
$phone = validate($_POST['phone'],$conn);

if (empty($firstname) || empty($lastname) || empty($email)) {
    header("Location: signup.php?error= Uzupełnij wszystkie dane");
    exit();
}
if (empty($pass) || empty($re_pass)) {
    header("Location: signup.php?error=Uzupełnij hasło");
    exit();
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: signup.php?error=Email jest niepoprawny");
    exit();
}
if ($pass !== $re_pass) {
    header("Location: signup.php?error=Hasła muszą być takie same");
    exit();
}
$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bindParam(1, $email_from_form);
$stmt->execute();

if ($stmt->fetch(PDO::FETCH_ASSOC)) {
    header("Location: signup.php?error=E-mail istnieje w bazie danych.");
    exit();
}

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
    header("Location: signup.php?error=Błąd podczas rejestracji" . $e->getMessage());
    exit();
} finally {
    $conn = null;
}

