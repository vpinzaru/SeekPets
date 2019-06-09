<?php

include_once 'engine/map.php';
include_once 'engine/database.php';


header('Content-Type: application/json');

$data = get_data();

$params = array('status');

if($data['method'] != 'POST')
{
    show_result("error",'Wrong request method.',400);
    exit();
}

$conn = get_conn();

$info = get_payload($data['body'], $params);
if($info == 'error')
{
    close_conn($conn);
    exit();
}

if($info['status'] != 0)
{
    $sql = "select * from pets where status = ".$info['status']." order by timestamp desc";
}
else
{
    $sql = "select * from pets order by timestamp desc";
}

$result = $conn->query($sql);


if($result -> num_rows == 0)
{
    show_result('ok','No notifications available.',200);
    close_conn($conn);
    exit();
}

$notifications = [];

while($row = $result->fetch_assoc()) {
    $anunt = [];
    $anunt['id_pet'] = $row['id_pet'];
    $anunt['name'] = $row['nume'];
    $anunt['contact'] = $row['contact'];
    $anunt['race'] = $row['species'];
    $anunt['latitude'] = $row['latitude'];
    $anunt['longitude'] = $row['longitude'];
    $anunt['address'] = $row['address'];
    $anunt['collar'] = $row['collar'];
    $anunt['reward'] = $row['reward'];
    $anunt['description'] = $row['description'];
    $anunt['image'] = $row['image'];
    $anunt['status'] = $row['status'];
    array_push($notifications,$anunt);
};

show_result('ok',$notifications,200);
close_conn($conn);

?>