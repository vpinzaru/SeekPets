<?php

include_once 'engine/map.php';
include_once 'engine/database.php';


header('Content-Type: application/json');

$data = get_data();

$params = array('id_pet');

if($data['method'] != 'POST')
{
    show_result("error",'Wrong request method.',400);
    exit();
}

$conn = get_conn();

$id = get_payload($data['body'], $params);
if($id == 'error')
{
    close_conn($conn);
    exit();
}
$id = $id['id_pet'];

$sql = "delete from pets where id_pet = ".$id;
if($conn->query($sql))
{
    show_result("ok","The announcement was deleted.",200);
}
else
{
    show_result("error","There was an sql error",400);
}

close_conn($conn);

?>