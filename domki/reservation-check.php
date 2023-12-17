<?php
require_once "include/sesconf.php";
session_start();
require_once "include/functions.php";
require_once "include/sql.php";
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] !== 'POST')
    returnError("error");
//werifikacja i ustawienie danych
$db = connect();
$id = (int)$_POST['CottageId'];
$start_data = $_POST['Start_data'];
$end_data = $_POST['End_data'];
$people = validate($_POST['people']);
$uwagi = validate($_POST['uwagi']);
if (!isset($_SESSION['id']))
    returnError("Zaloguj się");
if($id==null||$id<1||$id>5)
    returnError("Nie ma tagiego domku");

if (empty($start_data) || empty($end_data) || empty($people))
    returnError("Wypełnij wszystkie wymagane");

//doby
try {
    $datetime1 = new DateTime($start_data);
    $datetime2 = new DateTime($end_data);
    $roznica = $datetime1->diff($datetime2);
    $doby = $roznica->days;
    if ($doby < 1) returnError("Za krótki okres min 1 doba");
    else if ($doby > 14) returnError("Za długi okres max 2 tyg");

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

//dostępność
try {
    $sql = "SELECT * FROM reservation
                WHERE Cottage_id = :id
                AND ((start_date BETWEEN :start_data AND :end_data)
                OR (end_date BETWEEN :start_data AND :end_data))
                OR (:start_data BETWEEN start_date AND end_date)
                OR (:end_data BETWEEN start_date AND end_date)";

    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':start_data', $start_data);
    $stmt->bindParam(':data_end', $end_data);
    $stmt->execute();
    if ($stmt->rowCount() > 0)
        returnError("Domek jest zajęty w danym terminie");

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

//dane domku
$sql = "SELECT * FROM cottage
         WHERE id = :id";
$stmt = $db->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$price_per_day = $row['price'];
$max_people = $row['number_of_persons'];

//ludzie
if ($people < 1)
    returnError("Minimum 1 osoba");
else if ($max_people > $people)
    returnError("Max $max_people osób");

//cena
$price = $price_per_day * $doby;
//czy stały klient
$sql = "SELECT regular_customer FROM customer
         WHERE User_id = :uid";
$stmt = $db->prepare($sql);
$stmt->bindParam(':uid', $_SESSION['id'], PDO::PARAM_INT);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row['regular_customer'] == 1) {
    $price -= $price * 0.1;
}
$response = array('price' => $price);
echo json_encode($response);
/*to do:
promocje
*/