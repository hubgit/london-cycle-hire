<?php

// caching the TfL data - TODO: warn if this file isn't writable
$file = realpath(__DIR__ . '/cache');
$updated = (int) filemtime($file);
if (time() - $updated < 60) // cache for 60 seconds
  return TRUE;

touch($file);

$mongo = new Mongo('localhost:27017', array('persist' => TRUE));
$collection = $mongo->{'london-cycle-hire'}->{'stations-tmp'};
//$collection->drop();

$collection->ensureIndex(array('location' => '2d'));
$collection->ensureIndex(array('installed' => TRUE));
//$collection->ensureIndex(array('nbBikes' => TRUE));
//$collection->ensureIndex(array('nbEmptyDocks' => TRUE));

$dom = DOMDocument::loadHTMLFile('https://web.barclayscyclehire.tfl.gov.uk/maps');
$xpath = new DOMXPath($dom);

$data = NULL;
foreach ($xpath->query('//script') as $node){
  if (preg_match('/function genateScript/', $node->textContent)){
    preg_match_all('/station=(\{.+?\})/', $node->textContent, $matches);
    foreach ($matches[1] as $match){
      $data = json_decode(preg_replace('/(\w+):"/', '"$1":"', $match));
      
      if ($data->id)
        $collection->insert(array(
          '_id' => $data->id,
          'name' => $data->name,
          'location' =>  array('latitude' => (float) $data->lat, 'longitude' => (float) $data->long),
          'nbBikes' => (int) $data->nbBikes,
          'nbEmptyDocks' => (int) $data->nbEmptyDocks,
          'installed' => $data->installed == 'true',
          'locked' => $data->locked == 'true',
          'temporary' => $data->temporary == 'true',
          ), array('safe' => TRUE));
    }
  }
}

if (is_object($data)){ // only update the db if it found new data
  $mongo->{'london-cycle-hire'}->{'stations'}->drop();

  $mongo->admin->command(array(
    'renameCollection' => 'london-cycle-hire.stations-tmp',
    'to' => 'london-cycle-hire.stations',
  ));

  $collection->drop();

  $updated = time();
}