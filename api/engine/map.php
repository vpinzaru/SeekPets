<?php

$apiKey = eb2924c332e34d06af31306e39ff092b;

//https://api.ipgeolocation.io/ipgeo?apiKey=API_KEY&ip=1.1.1.1

function get_addr($lat,$long)
{
      
    $url  = "http://maps.googleapis.com/maps/api/geocode/json?latlng=".$lat.",".$long."&sensor=false";
    $json = @file_get_contents($url);
    $data = json_decode($json);
    $status = $data->status;
    if($status == "OK")
    {
      return $data->results[0]->formatted_address;
    }
    else
    {
      return 'error';
    }
};



?>