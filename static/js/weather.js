console.log('weather');
var body = document.body;
var bodyClass = '';
var r = document.querySelector(':root');
var request_weather = new XMLHttpRequest();
request_weather.onreadystatechange = function(){
	if (request_weather.readyState === 4) {
		if (request_weather.status === 200) { 
			try{
                var data= JSON.parse(request_weather.responseText)['current'];
				console.log(data);
				console.log(data['is_day']);
				var isDay = data['is_day'] != 0;
				console.log('isDay = '+isDay);
				bodyClass += isDay ? 'is_day' : ' is_night';
				body.classList = bodyClass;
				// console.log(r);
				if(typeof data['temp_f'] != 'undefined')
				{
					let temp = data['temp_f'];
					let temp_max = 76;
					let temp_min = 38;
					let hue =  (parseInt((temp - temp_min) / (temp_max - temp_min) * 360) + 180) % 360;
					if(isDay){
						var s = '100%';
						var l = '70%';
					}
					else
					{
						
						var s = '100%';
						var l = '10%';
					}
					r.style.setProperty('--background-color', 'hsl('+hue+', '+s+', '+l+')');
				}
				if(typeof data['humidity'] != 'undefined')
				{
					let humidity = data['humidity'];
					let humidity_max = 100;
					let humidity_min = 0;
					let hue =  (parseInt((humidity - humidity_min) / (humidity_max - humidity_min) * 360) + 180) % 360;
					if(isDay){
						var s = '100%';
						var l = '20%';
					}
					else
					{
						
						var s = '100%';
						var l = '80%';
					}
					r.style.setProperty('--text-color', 'hsl('+hue+', '+s+', '+l+')');
				}
				if(typeof data['wind_degree'] != 'undefined')
				{
					let wind_degree = data['wind_degree'];
					let hue =  wind_degree % 360;

					if(isDay){
						var s = '100%';
						var l = '20%';
					}
					else
					{
						
						var s = '100%';
						var l = '80%';
					}
					r.style.setProperty('--highlight-color', 'hsl('+hue+', '+s+', '+l+')');
				}
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
request_weather.open('GET', location.protocol + '//api.weatherapi.com/v1/current.json?key=5262904081d248dc9d6134509221701&q=Mexico City');
request_weather.send();