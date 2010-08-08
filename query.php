<?

$mongo = new Mongo('localhost:27017', array('persist' => TRUE));
$center = array('latitude' => (float) $_GET['latitude'], 'longitude' => (float) $_GET['longitude']);

//$items = $mongo->{'london-cycle-hire'}->{'stations'}->find(array('location' => array('$near' => $center)))->limit(25);
//$items = iterator_to_array($items);

$result = $mongo->{'london-cycle-hire'}->command(array('geoNear' => 'stations', 'near' => $center, 'num' => 25));
$items = $result['results'];
array_walk($items, 'fix_values');

if ($_GET['_format'] == 'json'){
  header('Content-Type: application/json; charset=UTF-8');
  print json_encode(array('updated' => $updated, 'stations' => $items));
  exit();
}

function fix_values(&$item){
  $item['obj']['distance'] = ceil($item['dis'] * 111111);
  $item = $item['obj'];
}

