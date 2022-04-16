// console.log('weather');
var body = document.body;
var bodyClass = '';
var r = document.querySelector(':root');
var request_weather = new XMLHttpRequest();
var request_static_weather = new XMLHttpRequest();
var string_weather = '';
request_static_weather.onreadystatechange = function(){
	if (request_static_weather.readyState === 4) {
		if (request_static_weather.status === 200) { 
			try{
                if(request_static_weather.responseText)
                	string_weather = request_static_weather.responseText;
    //             if(typeof typewriter === 'function'){
    //             	if(page === 'home')
				// 		typewriter(string_weather, sWeather, 100, nextStage);
				// 	else
				// 		typewriter(page, sWeather, 100, nextStage);
    //             }
				// else
				// {
				// 	window.addEventListener('load', function(){
				// 		if(typeof typewriter === 'function')
				// 			typewriter(string_weather, sWeather, 100, nextStage);
				// 		else
				// 			console.log('no typewriter');
				// 	});
				// }
			}
			catch(err){
				// typewriter(string_weather, sWeather, 100, nextStage);
			}
		}
	}
};
request_static_weather.open('GET', '/static/txt/weather.txt');

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
					string_weather = 'Currently ' + temp + '° C';
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
					if(string_weather != '')
						string_weather += '. ';
					string_weather += data['condition']['text'];
				}

				// if(typeof typewriter === 'function'){
				// 	typewriter(string_weather, sWeather, 100, nextStage);
				// }
				// else
				// {
				// 	window.addEventListener('load', function(){
				// 		if(typeof typewriter === 'function'){
				// 			if(page === 'home')
				// 				typewriter(string_weather, sWeather, 100, nextStage);
				// 			else
				// 				typewriter(page, sWeather, 100, nextStage);
				// 		}
				// 	});
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
request_weather.open('GET', location.protocol + '//api.weatherapi.com/v1/current.json?key=5262904081d248dc9d6134509221701&q=Milpa Alta');
