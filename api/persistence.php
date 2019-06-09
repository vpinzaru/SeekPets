<?php

include_once 'engine/database.php';
include_once 'engine/utils.php';

header('Content-Type: application/json');

$data = get_data();

if($data['method'] != 'GET')
{
    show_result("error",'Wrong request method.',400);
    exit();
}

$res = check_persistence();

if($res == 'not ok')
{
    show_result("ok",'not logged',200);
    exit();
}

show_result("ok",$res,200);

?>