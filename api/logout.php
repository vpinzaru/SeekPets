<?php

include_once 'engine/utils.php';
include_once 'engine/database.php';

header('Content-Type: application/json');

$data = get_data();

$params = array('id_user');

if ($data['method'] != 'POST') {
    show_result("error", 'Wrong request method.', 400);
    exit();
}

$id_user = get_payload($data['body'], $params);
if ($id_user == 'error') {
    exit();
}
$id_user = $id_user['id_user'];

delete_persistence($id_user);

show_result("ok", "Everything seems to be ok.", 200);
