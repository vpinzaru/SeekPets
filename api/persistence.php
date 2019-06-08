<?php

include_once 'engine/database.php';
include_once 'engine/utils.php';

header('Content-Type: application/json');

$data = get_data();

$params = array('id_user');

if($data['method'] != 'POST')
{
    show_result("error",'Wrong request method.',400);
    exit();
}

$pers = get_payload($data['body'], $params);
if($pers == 'error')
{
    exit();
}

$res = check_persistence($pers['id_user']);

if($res == 'ok')
{
    show_result("ok",'logged',200);
    exit();
}

show_result("ok",'not logged',200);

?>