<?php

include_once 'engine/utils.php';
include_once 'engine/database.php';

header('Content-Type: application/json');

$data = get_data();

$params = array('email','password');

if($data['method'] != 'POST')
{
    show_result("error",'Wrong request method.',400);
    exit();
}

$conn = get_conn();

$login = get_payload($data['body'], $params);
if($login == 'error')
{
    close_conn($conn);
    exit();
}

$pass = hash('sha256',$login['password']);

$sql = "select * from users where email = '".$login['email']."' and parola = '".$pass."'";

$found = $conn->query($sql);

if($found->num_rows == 0)
{
    show_result("error",'Wrong login credentials.',401);
    exit();
}

show_result("ok",'Login data is correct.',200);
?>