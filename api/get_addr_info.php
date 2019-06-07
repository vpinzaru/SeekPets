<?php

include_once 'engine/utils.php';
include_once 'engine/database.php';

header('Content-Type: application/json');

$data = get_data();

if($data['method'] != 'GET')
{
    show_result("error",'Wrong request method.',400);
    exit();
}

$ipKey = '42e0d743fa1aedac0ab95d7eafa96e2f';
$ip = file_get_contents("http://ipecho.net/plain");
$link = "http://api.ipapi.com/".$ip."?access_key=".$ipKey;
echo $link;

$result = generic_request("null","null","GET",$link);

$response = [
'country' => $result['country_name'],
'zip' => $result['zip'],
'city' => $result['city'],
'region'=> $result['region_name']
];

echo json_encode($response);


?>