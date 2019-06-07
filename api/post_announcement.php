<?php

include_once 'engine/utils.php';
include_once 'engine/database.php';

header('Content-Type: application/json');

$data = get_data();

$params = array('name', 'race', 'collar', 'reward', 'description', 'contact', 'latitude', 'longitude', 'status');
/*
    status = 1 - FOUND
             2 - LOST
*/

if($data['method'] != 'POST')
{
    show_result("error",'Wrong request method.',400);
    exit();
}

$conn = get_conn();

$ann = get_payload($data['body'], $params);
if($ann == 'error')
{
    close_conn($conn);
    exit();
}

$id = $data['headers']['Bearer'];

$found = generic_query($id,'id_user','users',$conn);

if($found->num_rows == 0)
{
    show_result("error",'Invalid Bearer ID',400);
    close_conn($conn);
    exit();
}

if($ann['status'] != 1 && $ann['status'] != 2)
{
    show_result("error",'Invalid pet status.',400);
    close_conn($conn);
    exit();
}

$add_sql = "insert into pets (nume, latitude, longitude, contact, species, description, collar, reward, status) values('".$ann['name']."', ".$ann['latitude'].", ".$ann['longitude'].", '".$ann['contact']."', '".$ann['race']."', '".$ann['description']."', '".$ann['collar']."', '".$ann['reward']."', ".$ann['status'].")";

if ($conn->query($add_sql)) {
    show_result('ok','Announcement has been added.',200);
} else {
    show_result('error',$conn->error,400);
}

close_conn($conn);


?>