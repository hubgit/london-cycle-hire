<? require '/opt/libapi/main.php'; ?>
<? Config::set('DEBUG', 'FIRE'); ?>
<?
if (isset($_GET['latitude']) && isset($_GET['longitude'])) {
  require __DIR__ . '/update.php';
  require __DIR__ . '/query.php';
}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>London Cycle Hire Stations</title>
    <link id="shorturl" rev="canonical" type="text/html" href="http://bit.ly/bikesplz">

    <meta name="application-name" content="Cycle Hire">
    <meta name="application-url" content="http://alf.hubmed.org/2010/07/london-cycle-hire/">
    <link rel="icon" href="icon.png" sizes="60x60">
    <meta name="viewport" content="width=device-width; height=device-height; initial-scale=1.0; maximum-scale=1.0; user-scalable=no">

    <meta name="apple-mobile-web-app-capable" content="yes">

    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-touch-fullscreen" content="yes">
    <link rel="apple-touch-icon" href="icon.png">

    <style>
    body { font-family: sans-serif; }
    th, td { text-align: left; vertical-align: top; padding: 2px 10px 2px 0; }
    div { margin: 0; padding: 0; }
    </style>
  </head>

  <body>

    <form method="GET">
      <label>Latitude: <input type="text" name="latitude" id="latitude" value="<? h(isset($_GET['latitude']) ? (float) $_GET['latitude'] : '51.53404294'); ?>"></label><br>
      <label>Longitude: <input type="text" name="longitude" id="longitude" value="<? h(isset($_GET['longitude']) ? (float) $_GET['longitude'] : '-0.086379717'); ?>"></label><br>
      <label>Format: <select name="_format">
        <option value="html">HTML</option>
        <option value="json">JSON</option>
      </select></label><br>
      <input type="submit" value="Find docking stations near this location">
    </form>

<? if (empty($items)): ?>
<? if (isset($_GET['latitude']) && isset($_GET['longitude'])): ?>
  <p class="error">No data is available at the moment.</p>
<? endif; ?>
<? else: ?>
<p>Data fetched at <?= date('g:ia \o\n F jS Y', $updated); ?></p>

<table>
  <thead>
    <tr>
      <th></th>
      <th>Dist</th>
      <th>In</th>
      <th>Out</th>
      <th>Name</th>
    </tr>
  </thead>
  <tbody>
<? foreach ($items as $item): ?>
    <tr>
      <td><div style="width:50px; height: 1em; background:red;"><div style="width:<? h(ceil(($item['nbBikes']/($item['nbBikes'] + $item['nbEmptyDocks'])) * 50)); ?>px; height:1em; background:green;"></div></div></td>
      <td><? h($item['distance']); ?></td>
      <td><? h($item['nbBikes']); ?></td>
      <td><? h($item['nbEmptyDocks']); ?></td>
      <td><a href="<? h(url('http://maps.google.com/maps', array('z' => 18, 'q' => $item['name'] . '@' . $item['location']['latitude'] . ',' . $item['location']['longitude']))); ?>"><? h($item['name']); ?></a></td>
    </tr>
<? endforeach; ?>
  </tbody>
</table>
<? endif; ?>

    <script>
    if (typeof(navigator.geolocation) != "undefined") {
      navigator.geolocation.watchPosition(

      function (position) {
        document.getElementById("latitude").setAttribute("value", position.coords.latitude);
        document.getElementById("longitude").setAttribute("value", position.coords.longitude);
      }, function (error) {
        var messages = ["", " (permission denied)", " (unavailable)", " (timeout)"];
        //alert("Can't get location" + messages[error.code]);
      }, {
        enableHighAccuracy: true,
        maximumAge: 600000
      });
    }
    </script>
  </body>
</html>

