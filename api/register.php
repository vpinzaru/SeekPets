<?php

$method = $_SERVER['REQUEST_METHOD'];
$requestHeaders = getallheaders();
$requestBodyAsString = file_get_contents('php://input');

$test = [
    'status' => 'ok'
];

echo json_encode($test)

?>