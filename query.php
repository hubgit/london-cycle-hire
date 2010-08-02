<? 

$mongo = new Mongo('localhost:27017', array('persist' => TRUE));
$center = array('latitude' => (float) $_GET['latitude'], 'longitude' => (float) $_GET['longitude']);
$radius = 10.0;
$items = $mongo->{'london-cycle-hire'}->{'stations'}->find(array('location' => array('$within' => array('$center' => array($center, $radius)))))->limit(25);
$items = iterator_to_array($items);

if ($_GET['_format'] == 'json'){
  header('Content-Type: application/json; charset=UTF-8');
  print json_encode(array('updated' => $updated, 'stations' => array_values($items)));
  exit();
}