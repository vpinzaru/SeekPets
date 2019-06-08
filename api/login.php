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
    close_conn($conn);
    exit();
}

$row = $found->fetch_assoc();
update_persistence($row['id_user']);
$response = [
    'status' => 'ok',
    'data' => 'Login credentials are correct.',
    'id_user' => $row['id_user']
];
close_conn($conn);
http_response_code(200);
echo json_encode($response);
?>