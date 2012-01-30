
<!DOCTYPE html>
<html>
  <head>
	<script type="text/javascript">
	
	function successHandler(location) {
		var url = "map_nearby_articles.php?lat=" + location.coords.latitude + "&lng=" + location.coords.longitude;
		window.location = url;
	}

	function errorHandler(error) {
		alert('Attempt to get location failed: ' + error.message);
	}
	
	//get lat/long of client's location
	function start_find(){
		navigator.geolocation.getCurrentPosition(successHandler, errorHandler);
	}
	
	</script>

	</head>
	<body>
		<button type="button" onclick="start_find()">What's Near Me?</button>
	</body>
	
</html>
	