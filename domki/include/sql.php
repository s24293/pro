<?php
function connect(){
    $dbuser="root";
    $dbpass="";
    try {
        $db = new PDO("mysql:host=localhost;dbname=domki", $dbuser,$dbpass);
    } catch (Exception $error){
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