<?php
include "include/sql.php";
$conn = connect();
try {
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Pobierz dane z formularza
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Rozpocznij transakcję
    $conn->beginTransaction();

    // Dodaj nowego użytkownika do tabeli User
    $sqlUser = "INSERT INTO User (email, password, UserType_id) VALUES (:email, :password, 1)";
    $stmtUser = $conn->prepare($sqlUser);
    $stmtUser->bindParam(':email', $email);
    $stmtUser->bindParam(':password', $password);
    $stmtUser->execute();

    // Pobierz ID nowo dodanego użytkownika
    $userId = $conn->lastInsertId();

    // Dodaj informacje o kliencie do tabeli Customer
    $sqlCustomer = "INSERT INTO Customer (name, surname, phone_number, User_id) VALUES (:firstname, :lastname, :phone, :userId)";
    $stmtCustomer = $conn->prepare($sqlCustomer);
    $stmtCustomer->bindParam(':firstname', $firstname);
    $stmtCustomer->bindParam(':lastname', $lastname);
    $stmtCustomer->bindParam(':phone', $phone);
    $stmtCustomer->bindParam(':userId', $userId);
    $stmtCustomer->execute();

    // Zakończ transakcję
    $conn->commit();

    echo "<h1>Rejestracja udana!</h1>";
} catch (PDOException $e) {
    // W przypadku błędu, cofnij transakcję
    $conn->rollBack();
    echo "Błąd podczas rejestracji: " . $e->getMessage();
} finally {
    // Zamknij połączenie z bazą danych
    $conn = null;
}
?>
