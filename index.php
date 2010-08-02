<? require '/opt/libapi/main.php'; ?>
<? require __DIR__ . '/update.php'; ?>
<? require __DIR__ . '/query.php'; ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>London Cycle Hire Stations</title>
	  
    <!--<link rel="stylesheet" href="style.css">-->
  </head>
	
  <body>

    <form method="GET">
      <label>Latitude: <input type="text" name="latitude" id="latitude" value="<? h(isset($_GET['latitude']) ? (float) $_GET['latitude'] : '51.53404294'); ?>"></label><br>
      <label>Longitude: <input type="text" name="longitude" id="longitude" value="<? h(isset($_GET['longitude']) ? (float) $_GET['longitude'] : '-0.086379717'); ?>"></label><br>
      <label>Format: <select name="_format">
        <option value="html">HTML</option>
        <option value="json">JSON</option>
      </select></label><br>
      <input type="submit" value="Find nearby docking stations">
    </form>

<? if (empty($items)): ?>
  <p class="error">No data is available at the moment.</p>
<? else: ?>
<p>Data fetched at <?= date('g:ia \o\n F jS Y', $updated); ?></p>
  
<table>
  <thead>
    <tr>
      <th>Name</th>
      <th>Available</th>
      <th>Empty</th>
    </tr>
  </thead>
  <tbody>
<? foreach ($items as $item): ?>
    <tr>
      <td><? h($item['name']); ?></td>
      <td><? h($item['nbBikes']); ?></td>
      <td><? h($item['nbEmptyDocks']); ?></td>
    </tr>
<? endforeach; ?>
  </tbody>
</table>
<? endif; ?>

    <script>
    // geolocation API code from http://owlsnearyou.com/
    if (typeof(navigator.geolocation) != 'undefined' && !location.search) {
  		navigator.geolocation.watchPosition(
  		  function (position) {
      	  document.getElementById('latitude') = position.coords.latitude;
      	  document.getElementById('longitude') = position.coords.longitude;
      	}, 
      	function (error) {
      	  var messages = ["", " (permission denied)", " (unavailabe)", " (timeout)"];
      		alert("Can't get location" + messages[error.code]);
      	}, 
      	{ enableHighAccuracy: true,	maximumAge: 600000 }
  		);
  	}
  	</script>
  </body>
</html>
