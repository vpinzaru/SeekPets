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

function show_result($status, $payload, $status_code)
{
    $response = [
        'status' => $status,
        'data' => $payload
    ];
    http_response_code($status_code);
    echo json_encode($response);
};


function check_request_body($body)
{
    foreach($body as $key=>$value)
    {
        if(strlen($value) == 0)
        {
            return $key;
        }
    };
    return 'ok';
}

?>