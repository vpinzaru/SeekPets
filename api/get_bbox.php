<?php

include_once 'engine/map.php';

header('Content-Type: application/json');

$data = get_data();

$params = array('latitude', 'longitude');

if ($data['method'] != 'POST') {
    show_result("error", 'Wrong request method.', 400);
    exit();
}

$info = get_payload($data['body'], $params);
if ($info == 'error') {
    close_conn($conn);
    exit();
}

$box = get_box($info['latitude'], $info['longitude']);

$response = [];
$response['primul'] = $box['minlon'];
$response['aldoilea'] = $box['minlat'];
$response['altreilea'] = $box['maxlon'];
$response['alpatrulea'] = $box['maxlat'];

show_result("ok", $response, 200);
