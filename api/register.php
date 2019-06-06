<?php

include_once 'engine/utils.php';
include_once 'engine/database.php';

header('Content-Type: application/json');

$data = get_data();

$params = array('lastname','firstname','password','email','address','confirm_password');

if($data['method'] != 'POST')
{
    show_result("error",'Wrong request method.',400);
    exit();
}

$conn = get_conn();

$new_user = get_payload($data['body'], $params);
if($new_user == 'error')
{
    close_conn($conn);
    exit();
}


if($new_user['password'] != $new_user['confirm_password'])
{
    show_result("error","Password and Confirm Password are not the same",406);
    close_conn($conn);
    exit();
}

$found = generic_query($new_user['email'],'email','users',$conn);

if ($found->num_rows > 0) 
{
    show_result("error","The email has already been used",409);
    close_conn($conn);
    exit();
}

$pass = hash('sha256',new_user['password']);

$add_sql = "insert into users (nume, prenume, email, parola, adresa) values ('".$new_user['lastname']."', '".$new_user['firstname']."', '".$new_user['email']."', '".$pass."', '".$new_user['address']."')";

if ($conn->query($add_sql)) {
    show_result('ok','User has been added.',200);
} else {
    show_result('error',$conn->error,304);
}

close_conn($conn);
?>