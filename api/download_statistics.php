<?php
include_once 'engine/utils.php';
include_once 'engine/files.php';

header('Content-Type: application/json');

$data = get_data();

$params = array('nr_lost','nr_found','vuln_address','file_type', 'fp_address');

if($data['method'] != 'POST')
{
    show_result("error",'Wrong request method.',400);
    exit();
}

$down = get_payload($data['body'], $params);
if($down == 'error')
{
    exit();
}

$lost = "Numar animale pierdute: ".$down['nr_lost'];
$found = "Numar animale gasite: ".$down['nr_found'];
$address = "Zona unde au fost pierdute cele mai multe animale: ".$down['vuln_address'];
$address_fp = "Zona unde au fost gasite cele mai multe animale: ".$down['fp_address'];

$stats = array($lost,$found,$address,$address_fp);

switch($down['file_type'])
{
    case 'csv':
        make_csv_stats($stats);
        break;
    case 'pdf':
        make_pdf_stats($stats);
        break;
    case 'html':
        make_html_stats($stats);
        break;
    default:
        show_result("error","Invalid file type.",400);
}
