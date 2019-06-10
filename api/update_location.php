<?php


include_once 'engine/utils.php';
include_once 'engine/database.php';

header('Content-Type: application/json');

$data = get_data();

$params = array('id_pet', 'latitude', 'longitude');

if ($data['method'] != 'POST') {
    show_result("error", 'Wrong request method.', 400);
    exit();
}

$conn = get_conn();

$up = get_payload($data['body'], $params);
if ($up == 'error') {
    close_conn($conn);
    exit();
}

$sql = "update pets set latitude = " . $up['latitude'] . ", longitude = " . $up['longitude'] . " where id_pet = " . $up['id_pet'];

if ($conn->query($sql)) {
    show_result("ok", "Location updated.", 200);
} else {
    show_result("error", "SQL error", 400);
}

close_conn($conn);
