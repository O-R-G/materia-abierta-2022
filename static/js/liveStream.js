// 2. This code loads the IFrame Player API code asynchronously.
var tag = document.createElement('script');

tag.src = "https://www.youtube.com/iframe_api";
var firstScriptTag = document.getElementsByTagName('script')[0];
firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

// 3. This function creates an <iframe> (and YouTube player)
//    after the API code downloads.
var wW = window.innerWidth;
var wH = window.innerHeight;
var player_sky, player_server;
var player_sky_w = 640;
var player_sky_h = 360;
var ratio_dev = 1.25;

video_ratio = player_sky_h / player_sky_w;

var play_server_isReady = false;

function onYouTubeIframeAPIReady() {
	player_sky = new YT.Player('player-sky', {
	  height: player_sky_h,
	  width: player_sky_w,
	  videoId: 'phMtEWlGw_k',
	  playerVars: {
	    'playsinline': 1,
	    'muted': 1,
	    'showinfo': 0,
	    'controls': 0,
	    'modestbranding': 1
	  },
	  events: {
	  	'onStateChange': onPlayerStateChange,
	  	// 'onReady': onPlayerReady
	    
	  }
	});

	// player_server = new YT.Player('player-server', {
	//   height: player_server_h,
	//   width: player_server_w,
	//   videoId: '14rWLXOvpWo',
	//   playerVars: {
	//     'playsinline': 1,
	//     'muted': 1
	//   },
	//   events: {
	//   	'onReady': onPlayerServerReady,
	//     'onStateChange': onPlayerServerStateChange
	//   }
	// });
}

// 4. The API will call this function when the video player is ready.
function onPlayerServerReady(event) {
	console.log("onPlayerServerReady");
	body.classList.remove('loading-player-server');
}
var played = false;
function onPlayerStateChange(event) {
	if (event.data == YT.PlayerState.PLAYING && !played) {
		player_sky.mute();
		// player_server.mute();
		if(!isTest)
			nextStage();
		else{
			// homepage speedrun
			document.body.setAttribute('loadingstage', '3');
			sContent_container.classList.remove('transition');
			typewriter(string_weather, sWeather, 10);
			typewriter(string_geolocation, sGeolocation, 10);
		}
	  played = true;
	}
}
function onPlayerServerStateChange(event) {
	console.log("player server state changes");
	body.classList.add('playing-player-server');
}
function centerLiveFeed(){
	let sPlayer_sky = document.getElementById('player-sky');
	let sLive_background = document.getElementById('live-background');
	wW = window.innerWidth;
	wH = window.innerHeight;
	if(sLive_background.offsetHeight > wH)
		wH = sLive_background.offsetHeight;
	let screen_ratio = wH / wW;
	if(video_ratio < screen_ratio)
	{
		// console.log('screen is thinner');
		sPlayer_sky.height = wH * ratio_dev;
		sPlayer_sky.width = wH * ratio_dev / video_ratio;
	}
	else
	{
		// console.log('screen is wider');
		sPlayer_sky.width = wW * ratio_dev;
		sPlayer_sky.height = wW * ratio_dev * video_ratio;
	}
} 

window.addEventListener('load', function(){
	liveStream_isReady = true;
	if(clientWeather_isReady)
		body.classList.remove('loading');
	centerLiveFeed();
});
window.addEventListener('resize', function(){
	centerLiveFeed();
});

