
<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" Content-type: "image/png" />
	<meta charset="utf-8" />
	
    <style type="text/css">
      html { height: 100% }
      body { height: 100%; margin: 0; padding: 0 }
      #map_canvas { height: 100% }
    </style>
	
    <script type="text/javascript"
      src="http://maps.googleapis.com/maps/api/js?key=AIzaSyA7avNTDzZo_nsGYZ2BwMQgxAzqrKUHwrs&sensor=false">
    </script>
    
	<script type="text/javascript">
      function initialize(mylat, mylong, myarray) {
		//alert(myarray.geonames[0].title);
		
		var myLatlng = new google.maps.LatLng(mylat, mylong);
		
        var myOptions = {
          center: myLatlng,
          zoom: 14,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        };
		
		//create map canvas
        var map = new google.maps.Map(document.getElementById("map_canvas"),
            myOptions);
		
		//marker for the client
		var marker = new google.maps.Marker({
		position: myLatlng,
		map: map,
		title:"I am here"
		});
		
		//create custom markers for wikipedia articles
		var wiki_image = new google.maps.MarkerImage('img/wikipedia_marker.png',
			// This marker is 20 pixels wide by 32 pixels tall.
			new google.maps.Size(20, 32),
			// The origin for this image is 0,0.
			new google.maps.Point(0,0),
			// The anchor for this image is the base of the flagpole at 0,32.
			new google.maps.Point(0, 32));
			
		var wiki_shadow = new google.maps.MarkerImage('img/wikipedia_marker_shadow.png',
			  // The shadow image is larger in the horizontal dimension
			  // while the position and offset are the same as for the main image.
			  new google.maps.Size(37, 32),
			  new google.maps.Point(0,0),
			  new google.maps.Point(0, 32));
		
		// marker array with information for each article in the given radius
		var markers = new Array();
		for (var i = 0; i < myarray.geonames.length; i++) {
			var articleLatLng = new google.maps.LatLng(myarray.geonames[i].lat, myarray.geonames[i].lng);
			 markers[i] = new google.maps.Marker({
				position: articleLatLng,
				map: map,
				icon: wiki_image,
				shadow: wiki_shadow,
				url: decodeURIComponent(myarray.geonames[i].wikipediaUrl),
				title: myarray.geonames[i].title
			});
			
			//setup information window when marker is clicked. Populate with Title, url and summary
			var info_window_content = '<a href="http://'+ markers[i].url +'">' + markers[i].title + '</a><br>' 
			+ myarray.geonames[i].summary;
			//alert("url "+i+"is "+markers[i].url);
			
			//click handler for each article marker
			add_url_handler(map, markers[i], info_window_content);
		}
		
	}
	
	//attach the article info to the infowindow
	function add_url_handler(map, marker, info_window_content){
			var infowindow = new google.maps.InfoWindow({
				content: info_window_content
			});
			
			//alert("window content is" + info_window_content);
			
			google.maps.event.addListener(marker, 'click', function() { 
				infowindow.open(map,marker);
			});
	}
    </script>
  </head>
  
  <body>
  
<?php  

// main - check for lat/long validity
// call javascript initialize function to set up google maps and
// plot the wikipedia articles with custom markers

if (isset($_GET['lat'])){
	$myLat = $_GET['lat'];
} else {
	alert("Could not get latitude/longitude. Sorry, better luck next time!");
	die;
}

if (isset($_GET['lng'])){
	$myLong = $_GET['lng'];
} else {
	alert("Could not get latitude/longitude. Sorry, better luck next time!");
	die;
}

// calling the geonames api to get the nearby wikipedia articles - using curl
$geonamesAPI_URL = "http://api.geonames.org/findNearbyWikipediaJSON?lat=".$_GET['lat']."&lng=".$_GET['lng']."&radius=10&username=rupa";

$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, "User-Agent: Mozilla");
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30); // 30 second timeout

curl_setopt($ch, CURLOPT_URL, $geonamesAPI_URL);

$response = curl_exec($ch);

curl_close($ch);

?>

<div id="map_canvas" style="width:100%; height:75%"></div>
<script type="text/javascript">
  initialize(<?php echo $myLat ?>, <?php echo $myLong ?>, <?php echo $response ?>);
</script>

  </body>
</html>