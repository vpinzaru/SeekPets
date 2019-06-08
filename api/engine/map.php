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

function update_ids($content)
{
    file_put_contents('changeset_ids.json', json_encode($content));
}

function get_ids()
{
  $fileContent = file_get_contents('changeset_ids.json');
  return json_decode($fileContent);
}

function get_new_changeset($type)
{
  return True;
}

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
  $result = more_generic_request("PUT",$link,$root->asXML(),array('Authorization: Basic dnBpbnphcnUxOEBnbWFpbC5jb206U3VudFZlbm9tOTk'));
  echo $result;
}



?>