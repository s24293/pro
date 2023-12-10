<?php
if (!isset($_POST['sub'])) {
    header("Location: reservation-check.php");
    exit();
}
require_once("include/sesconf.php");
session_start();
require_once("include/sql.php");

$db = connect();
$id = (int)$_POST['id'];
$email = validate($_POST['email'], $db);
$phone = validate($_POST['phone'], $db);
$name = validate($_POST['name'], $db);
$lastname = validate($_POST['lastname'], $db);
$start_data = validate($_POST['Start_data'], $db);
$end_data = validate($_POST['End_data'], $db);
$people = validate($_POST['people'], $db);
$uwagi = validate($_POST['uwagi'], $db);
function sprawdzDostepnoscDomku($id, $data_start, $data_end, $conn){
    try {
        $sql = "SELECT * FROM reservation
                WHERE id = :id
                AND ((start_date BETWEEN :data_start AND :data_end)
                OR (end_date BETWEEN :data_start AND :data_end))
                OR (:data_start BETWEEN start_date AND end_date)
                OR (:data_end BETWEEN start_date AND end_date)";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':data_start', $data_start);
        $stmt->bindParam(':data_end', $data_end);

        $stmt->execute();

        if ($stmt->rowCount() > 0) return true; else return false;

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
if(sprawdzDostepnoscDomku($id,$start_data,$end_data,$db)){
    header("Location: index.php?error=Domek jest zajęty w danym terminie");
    exit();
}

if (empty($email) || empty($phone) || empty($name) || empty($lastname) || empty($start_data) || empty($end_data) || empty($people)) {
    header("Location: index.php?error=Wypełnij wszystkie wymagane");
    exit();
}
echo 123;
/*to do:
oblicz cene

czy danie nie zakrótka
czy liość osób nie za duża
1 sprawdz i wyświetl cene
2 potem przycisk z opcją rezerwacji osobny plik dodaj do bazy danych ---js?



*/

