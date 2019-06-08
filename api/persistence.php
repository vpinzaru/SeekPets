<?php

include_once 'engine/database.php';

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
    close_conn($conn);
    exit();
}

$res = check_persistence($pers['id_user']);

if($res == 'ok')
{
    show_result("ok",'logged',200);
    close_conn($conn);
    exit();
}

show_result("ok",'not logged',200);
close_conn($conn);

?>