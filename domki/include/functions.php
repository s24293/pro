<?php

use JetBrains\PhpStorm\NoReturn;

function validate($data): string
{
    $data = trim($data);
    $data = stripslashes($data);
    return htmlspecialchars($data);
}
#[NoReturn] function redirectWithError($error, $location): void
{
    if(!empty($error)) $url = $location . "?error=" . urlencode($error);
    else $url = $location;

    header("Location: " . $url);
    exit();
}

#[NoReturn] function returnError($error): void
{
    $errorResponse = array('error' => $error);
    echo json_encode($errorResponse);
    exit();
}
