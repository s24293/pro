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
?>