<?php
use JetBrains\PhpStorm\NoReturn;

#[NoReturn] function returnError($error): void
{
    $errorResponse = array('error' => $error);
    echo json_encode($errorResponse);
    exit();
}

    $data = $_POST['people'];
header('Content-Type: application/json');
// Your existing PHP logic to calculate price
$price = $data;
// Assume $price is the calculated price
$response = array('price' => $price);

echo json_encode($response);

