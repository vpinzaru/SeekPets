<?php

include_once 'engine/utils.php';
include_once 'engine/database.php';

header('Content-Type: application/json');

$data = get_data();


if ($data['method'] != 'GET') {
    show_result("error", 'Wrong request method.', 400);
    exit();
}

$conn = get_conn();

$statistics = [];

$lost = 0;
$found = 0;

$sql = "select * from pets";

$result = $conn->query($sql);

$address_ap = [];

while ($row = $result->fetch_assoc()) {
    $street = explode(",", $row['address'])[0];
    if ($row['status'] == 2) // lost
    {
        if (isset($address_ap[$street])) {
            $address_ap[$street] += 1;
        } else {
            $address_ap[$street] = 1;
        }
    }

    switch ($row['status']) {
        case 1:
            $found += 1;
            break;
        case 2:
            $lost += 1;
    }
}
close_conn($conn);

$max = 0;
$street = "";

foreach ($address_ap as $key => $value) {
    if ($value > $max) {
        $max = $value;
        $street = $key;
    }
}

$statistics['nr_found'] = $found;
$statistics['nr_lost'] = $lost;
$statistics['vuln_address'] = $street;

show_result("ok", $statistics, 200);
