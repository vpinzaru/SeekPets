<?php

include_once 'pdf/pdf.php';
include_once 'database.php';


function make_pdf_stats($lines)
{
    $pdf = new PDF();
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->SetFont('Times', '', 12);
    for ($i = 0; $i < count($lines); $i++)
        $pdf->Cell(0, 10, $lines[$i], 0, 1);
    $pdf->Output();
}

function make_html_stats($lines)
{

    $buffer = '<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>PawTaie Statistics</title>
    </head>
    <body>';
    for ($i = 0; $i < count($lines); $i++) {
        $buffer = $buffer . '<p> ' . $lines[$i] . ' </p>';
    }
    $buffer = $buffer . "</body>
    </html>";

    header('Content-Type: application/x-download');
    header('Content-Disposition: attachment; filename=doc.html');
    header('Cache-Control: private, max-age=0, must-revalidate');
    header('Pragma: public');
    echo $buffer;
    exit();
}

function make_csv_stats($lines)
{
    $columns = array('Name', 'Value');
    header('Content-Type: application/x-download');
    header('Content-Disposition: attachment; filename=doc.csv');
    header('Cache-Control: private, max-age=0, must-revalidate');
    header('Pragma: public');
    $file = fopen('php://output', 'w');

    fputcsv($file, $columns);
    for ($i = 0; $i < count($lines); $i++) {
        $pieces = explode(":", $lines[$i]);
        fputcsv($file, array($pieces[0], $pieces[1]));
    }
    exit();
}
