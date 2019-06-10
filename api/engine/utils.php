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
    foreach ($body as $key => $value) {
        if (strlen($value) == 0) {
            return $key;
        }
    };

    return 'ok';
}


function check_params($params, $payload)
{
    foreach ($params as $param) {
        if (array_key_exists($param, $payload) == FALSE) {
            show_result("error", $param . ' is missing from the request body', 400);
            return 'error';
        }
    }
    return 'ok';
}

function get_payload($body, $params)
{
    $payload = json_decode($body, true);

    if ($payload == NULL) {
        show_result("error", 'Wrong request body.', 400);
        return 'error';
    }

    $check = check_request_body($payload);

    if ($check != "ok") {
        show_result("error", $check . " should not be empty.", 406);
        return 'error';
    }

    $check = check_params($params, $payload);
    if ($check != "ok") {
        return 'error';
    }

    return $payload;
}

function generic_request($headers, $body, $method, $link)
{
    $req = curl_init($link);

    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    if ($headers != "null") {
        curl_setopt($req, CURLOPT_HTTPHEADER, $headers);
    }
    if ($body != "null") {
        curl_setopt($req, CURLOPT_POSTFIELDS, json_encode($body));
    }
    curl_setopt($req, CURLOPT_RETURNTRANSFER, 1);

    $respAsJson = json_decode(curl_exec($req), true);

    curl_close($req);
    return $respAsJson;
}

function more_generic_request($method, $url, $data = false, $headers = false)
{

    $curl = curl_init();
    if ($headers) {
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    }
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $result = curl_exec($curl);
    if (curl_errno($curl)) {
        echo 'Curl error: ' . curl_error($curl);
    }
    curl_close($curl);
    return ['result' => $result, 'code' => $http_status];
}
