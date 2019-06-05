<?php

include_once 'engine/utils.php';
include_once 'engine/database.php';

header('Content-Type: application/json');

$data = get_data();

$conn = get_conn();
echo var_dump($conn);
close_conn($conn);
?>