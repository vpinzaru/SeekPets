<?php

include_once 'engine/utils.php';
include_once 'engine/database.php';

header('Content-Type: application/json');

$data = get_data();

$params = array('id_user');

if($data['method'] != 'POST')
{
    show_result("error",'Wrong request method.',400);
    exit();
}

$conn = get_conn();

$user = get_payload($data['body'], $params);
if($user == 'error')
{
    close_conn($conn);
    exit();
}

$result = generic_query($user['id_user'],'id_user','users',$conn);

if($result -> num_rows == 0 )
{
    show_result("error",'Invalid ID',400);
    close_conn($conn);
    exit();
}

$result = $result->fetch_assoc();

$list = [];

$list['lastname'] = $result['nume'];
$list['firstname'] = $result['prenume'];
$list['email'] = $result['email'];
$list['address'] = $result['adresa'];
$list['latitude'] = $result['latitude'];
$list['longitude'] = $result['longitude'];

show_result("ok",$list,200);
close_conn($conn);

?>