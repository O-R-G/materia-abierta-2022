<?
    // relies on existing #weather-block div in dom
	if (isset($_SERVER['HTTPS']) &&
	    ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) ||
	    isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
	    $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
	  $protocol = 'https://';
	}
	else {
	  $protocol = 'http://';
	}
?>
<script>
	var api_url = '<?= $protocol; ?>api.weatherapi.com/v1/current.json?key=5262904081d248dc9d6134509221701&q=Venice';
	var sWeather_block = document.getElementById('weather-block');
	var httpRequest = new XMLHttpRequest();
	httpRequest.onreadystatechange = function(){
		if (httpRequest.readyState === 4) {
			if (httpRequest.status === 200) { 
				try{
                    // console.log(data);
                    // wind_mph
                    // wind_dir
					var data = JSON.parse(httpRequest.responseText);
					let temp = data['current']['temp_f'];
					console.log(temp);
					sWeather_block.innerHTML = temp + '°<br>Venice';
				}
				catch(err)
				{
					console.log('Error!');
					console.log(err);
					return false;
				}
			}
		}
	};
	httpRequest.open('GET', api_url);
	httpRequest.send();
</script>
