<?php
function connect() {
    $dbuser = "root";
    $dbpass = "";
    $dbname = "domki";
    $charset = "utf8mb4";

    try {
        $db = new PDO("mysql:host=localhost;dbname=$dbname;charset=$charset", $dbuser, $dbpass);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // Dodatkowe konfiguracje PDO, jeśli są potrzebne
    } catch (PDOException $error) {
        die("Connection failed: " . $error->getMessage());
    }

    return $db;
}
function validate($data,$db): string
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return mysqli_real_escape_string($db, $data);
}
?>