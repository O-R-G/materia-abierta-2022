// console.log('weather');
var body = document.body;
var bodyClass = '';
var r = document.querySelector(':root');
var request_weather = XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
// var request_client = XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
// var request_static_weather = XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
var string_weather = '';
// request_static_weather.onreadystatechange = function(){
// 	if (request_static_weather.readyState === 4) {
// 		if (request_static_weather.status === 200) { 
// 			try{
//                 if(request_static_weather.responseText)
//                 	string_weather = request_static_weather.responseText;
// 			}
// 			catch(err){
// 				// typewriter(string_weather, sWeather, 100, nextStage);
// 			}
// 		}
// 	}
// };
// request_static_weather.open('GET', '/static/txt/weather.txt');

request_weather.onreadystatechange = function(){
	if (request_weather.readyState === 4) {
		if (request_weather.status === 200) { 
			try{
                var data= JSON.parse(request_weather.responseText)['current'];
				var isDay = data['is_day'] != 0;
				bodyClass += isDay ? 'is_day' : 'is_night';
				body.classList.add(bodyClass);
				// console.log(r);
				// console.log(data);
				if(typeof data['temp_c'] != 'undefined')
				{
					let temp = data['temp_c'];
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
					string_weather = temp + '° C';
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
				if(typeof data['condition'] != 'undefined' && typeof data['condition']['text'] != 'undefined')
				{
					console.log(data['condition']['text']);
					if(string_weather != '')
						string_weather += '. ';
					string_weather += data['condition']['text'] + '.';
				}
				weather_isReady = true;
				if(liveStream_isReady)
					body.classList.remove('loading');
			}
			catch(err){
				// console.log(err);
				// request_static_weather.send();
				weather_isReady = true;
		        if(liveStream_isReady)
		            body.classList.remove('loading');
			}
		}
		else
		{
			// console.log('not 200');
			// request_static_weather.send();
		}
	}
	else
	{
		// console.log('not 4?');
	}
};
// request_client.onreadystatechange = function(){
// 	if (request_client.readyState === 4) {
// 		if (request_client.status === 200) { 
// 			try{
//                 var data_client = JSON.parse(request_client.responseText)['current'];
// 				console.log('data_client = ');
// 				console.log(data_client);
// 				if(typeof data_client['temp_c'] != 'undefined')
// 				{
// 					let temp = data_client['temp_c'];
// 					string_weather = temp + '° C';
// 				}
// 				if(typeof data_client['condition'] != 'undefined' && typeof data_client['condition']['text'] != 'undefined')
// 				{
// 					if(string_weather != '')
// 						string_weather += '. ';
// 					string_weather += data_client['condition']['text'];
// 				}
// 				weather_isReady = true;
// 				if(liveStream_isReady)
// 					body.classList.remove('loading');
// 			}
// 			catch(err){
// 				// request_static_weather.send();
// 				weather_isReady = true;
// 		        if(liveStream_isReady)
// 		            body.classList.remove('loading');
// 			}
// 		}
// 		else
// 		{
// 			// console.log('not 200');
// 			// request_static_weather.send();
// 			weather_isReady = true;
// 	        if(liveStream_isReady)
// 	            body.classList.remove('loading');
// 		}
// 	}
// };
// let request_url = location.protocol + '//api.weatherapi.com/v1/current.json?key=5262904081d248dc9d6134509221701&q='+latitude+','+longitude+'&lang='+lang;
var request_weather_url = '//api.weatherapi.com/v1/current.json?key=5262904081d248dc9d6134509221701&q='+camera_coordinate+'&lang='+lang;
// var request_weather_url = '//api.weatherapi.com/v1/current.json?key=5262904081d248dc9d6134509221701&q=Mexico City&lang='+lang;

// console.log(request_weather_url);
// var request_client_url = '//api.weatherapi.com/v1/current.json?key=5262904081d248dc9d6134509221701&q='+camera_coordinate+'&lang='+lang;
request_weather.open('GET', request_weather_url);

