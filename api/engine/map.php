<?php

include_once 'utils.php';
include_once 'database.php';

/*
<osm>
  <changeset>
    <tag k="created_by" v="JOSM 1.61"/>
    <tag k="comment" v="Just adding some streetnames"/>
    ...
  </changeset>
  ...
</osm>
*/

$basic = 'Authorization:Basic dnBpbnphcnUxOEBnbWFpbC5jb206U3VudFZlbm9tOTk=';
$header = array($basic);

function create_new_changeset($description)
{
  // PUT /api/0.6/changeset/create
  $link = 'https://master.apis.dev.openstreetmap.org/api/0.6/changeset/create';

  $root = new SimpleXMLElement('<osm></osm>');
  $changeset = $root->addChild('changeset');
  $tag = $changeset->addChild('tag');
  $tag->addAttribute('k','created_by');
  $tag->addAttribute('v','vpinzaru for Pawtaie');

  $tag = $changeset->addChild('tag');
  $tag->addAttribute('k','comment');
  $tag->addAttribute('v',$description);

  return more_generic_request("PUT",$link,$root->asXML(),array('Authorization: Basic dnBpbnphcnUxOEBnbWFpbC5jb206U3VudFZlbm9tOTk'));
}


function get_box($lat, $long)
{
  $link = 'https://master.apis.dev.openstreetmap.org/api/0.6/map?bbox=';
  $left = $long - 0.002;
  $bottom = $lat - 0.002;
  $right = $long + 0.002;
  $top = $lat + 0.002;
  $link = $link.$left.",".$bottom.",".$right.",".$top;

  $call = more_generic_request("GET",$link,false,array('Authorization: Basic dnBpbnphcnUxOEBnbWFpbC5jb206U3VudFZlbm9tOTk'));
  switch($call['code'])
  {
    case '200':
      break;
    case '0':
      break;
    default:
      show_result('error',$call['result'],400);
      exit();
  };

  $xml=simplexml_load_string($call['result']) or die("Error: Cannot create object");
  foreach($xml->children() as $child)
  {
    if($child->getName() == "bounds")
    {
      $response = [];
      foreach($child->attributes() as $key => $value)
      {
        $response[$key] = $value;
      }
      return $response;
    }
  }

  return 'error';
  

}




?>