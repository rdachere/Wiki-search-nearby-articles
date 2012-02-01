
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
	
	function start_find(){
		navigator.geolocation.getCurrentPosition(successHandler, errorHandler);
	}
	
	//<button type="button"  style="height: 50px; width: 300px" onclick="start_find()">What's Near Me?</button>
	
	</script>

	</head>
	<body>
		
		<br>
		<center> <input type="image" src="img/find_nearby.jpg" onclick="start_find()" alt="find_nearby" /></center>
	</body>
	
</html>
	