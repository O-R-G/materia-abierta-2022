// console.log('weather');
var body = document.body;
var bodyClass = '';
var r = document.querySelector(':root');
var request_milpa_alta = XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
var request_client = XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
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

request_milpa_alta.onreadystatechange = function(){
	if (request_milpa_alta.readyState === 4) {
		if (request_milpa_alta.status === 200) { 
			try{
                var data_milpa_alta= JSON.parse(request_milpa_alta.responseText)['current'];
				var isDay = data_milpa_alta['is_day'] != 0;
				bodyClass += isDay ? 'is_day' : 'is_night';
				body.classList.add(bodyClass);
				// console.log(r);
				// console.log(data);
				if(typeof data_milpa_alta['temp_c'] != 'undefined')
				{
					let temp = data_milpa_alta['temp_c'];
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
					// string_weather = 'Currently ' + temp + '° C';
				}
				if(typeof data_milpa_alta['humidity'] != 'undefined')
				{
					let humidity = data_milpa_alta['humidity'];
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
				if(typeof data_milpa_alta['wind_degree'] != 'undefined')
				{
					let wind_degree = data_milpa_alta['wind_degree'];
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
				// if(typeof data['condition'] != 'undefined' && typeof data['condition']['text'] != 'undefined')
				// {
				// 	if(string_weather != '')
				// 		string_weather += '. ';
				// 	string_weather += data['condition']['text'];
				// }
			}
			catch(err){
				request_static_weather.send();
			}
		}
		else
		{
			// console.log('not 200');
			request_static_weather.send();
		}
	}
};
request_client.onreadystatechange = function(){
	if (request_client.readyState === 4) {
		if (request_client.status === 200) { 
			try{
                var data_client = JSON.parse(request_client.responseText)['current'];
				console.log('data_client = ');
				console.log(data_client);
				if(typeof data_client['temp_c'] != 'undefined')
				{
					let temp = data_client['temp_c'];
					string_weather = temp + '° C';
				}
				if(typeof data_client['condition'] != 'undefined' && typeof data_client['condition']['text'] != 'undefined')
				{
					if(string_weather != '')
						string_weather += '. ';
					string_weather += data_client['condition']['text'];
				}
				clientWeather_isReady = true;
				if(liveStream_isReady)
					body.classList.remove('loading');
			}
			catch(err){
				// request_static_weather.send();
			}
		}
		else
		{
			// console.log('not 200');
			// request_static_weather.send();
		}
	}
};
// let request_url = location.protocol + '//api.weatherapi.com/v1/current.json?key=5262904081d248dc9d6134509221701&q='+latitude+','+longitude+'&lang='+lang;
var request_milpa_alta_url = '//api.weatherapi.com/v1/current.json?key=5262904081d248dc9d6134509221701&q=Milpa Alta&lang='+lang;
var request_client_url = '//api.weatherapi.com/v1/current.json?key=5262904081d248dc9d6134509221701&q='+latitude+','+longitude+'&lang='+lang;
request_milpa_alta.open('GET', request_milpa_alta_url);
request_client.open('GET', request_client_url);
