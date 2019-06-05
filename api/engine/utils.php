<?php

function get_data()
{
    $method = $_SERVER['REQUEST_METHOD'];
    $requestHeaders = getallheaders();
    $requestBodyAsString = file_get_contents('php://input');
    $response = [
        'method' => $method,
        'headers' => $requestHeaders,
        'body' => $requestBodyAsString
    ];

    return $response;
};

?>