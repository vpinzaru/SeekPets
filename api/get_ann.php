<?php

include_once 'engine/map.php';
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

$id = get_payload($data['body'], $params);
if($id == 'error')
{
    close_conn($conn);
    exit();
}
$id = $id['id_user'];

$user = generic_query($id,'id_user','users',$conn);

if($user->num_rows === 0)
{
    show_result('error','Invalid user id.',400);
    close_conn($conn);
    exit();
}

$row = $user->fetch_assoc();
$lat = $row['latitude'];
$long = $row['longitude'];

$box = get_box($lat,$long);
if($box == 'error')
{
    show_result('error','Failed to get box borders',400);
    close_conn($conn);
    exit();
}

$results = [];

echo 'minlat: '.$box['minlat'];
echo 'maxlat: '.$box['maxlat'];
echo 'minlong: '.$box['minlong'];
echo 'maxlong: '.$box['maxlong'];


$sql = 'select * from pets where latitude > '.$box['minlat'].' and latitude < '.$box['maxlat'].' and longitude > '.$box['minlong'].' and longitude < '.$box['maxlong'].' order by timestamp desc';

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
    $anunt['name'] = $row['nume'];
    $anunt['contact'] = $row['contact'];
    $anunt['race'] = $row['species'];
    $anunt['latitude'] = $row['latitude'];
    $anunt['longitude'] = $row['longitude'];
    $anunt['address'] = $row['address'];
    $anunt['collar'] = $row['collar'];
    $anunt['reward'] = $row['reward'];
    $anunt['description'] = $row['description'];
    array_push($notifications,$anunt);
};

show_result('ok',$notifications,200);
close_conn($conn);

?>