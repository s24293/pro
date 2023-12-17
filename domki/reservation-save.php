<?php
require_once "include/functions.php";
require_once "include/sql.php";
if (!isset($_POST['sub']))
    redirectWithError("", "reservation.php");
function bindExecuteArray($stmt, $params): void
{
    foreach ($params as $key => &$value)
        $stmt->bindParam($key, $value);
    $stmt->execute();
}

//werifikacja i ustawienie danych
$db = connect();
$id = (int)$_POST['id'];
$start_data = $_POST['Start_data'];
$end_data = $_POST['End_data'];
$people = $_POST['people'];
$uwagi = $_POST['uwagi'];
$cena = $_POST['cena'];
$Cottage_id=$_POST['Cottage_id'];
$Customer_id=$_POST['Customer_id'];
$db = connect();
$sqlCustomer = "INSERT INTO reserwation (start_date, end_date, price, number_of_persons, Customer_id, Cottage_id, comments) VALUES (:start_date, :end_date, :price,:number_of_persons, :Customer_id, :Cottage_id, :coments)";
$stmtCustomer = $db->prepare($sqlCustomer);
bindExecuteArray($stmtCustomer, [':firstname' => $firstname, ':lastname' => $lastname, ':phone' => $phone, ':userId' => $userId]);

