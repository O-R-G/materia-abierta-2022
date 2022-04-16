// 2. This code loads the IFrame Player API code asynchronously.
var tag = document.createElement('script');

tag.src = "https://www.youtube.com/iframe_api";
var firstScriptTag = document.getElementsByTagName('script')[0];
firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

// 3. This function creates an <iframe> (and YouTube player)
//    after the API code downloads.
var player;
var player_w = 640;
var player_h = 360;
var wW = window.innerWidth;
var wH = window.innerHeight;
video_ratio = player_h / player_w;
function onYouTubeIframeAPIReady() {
player = new YT.Player('player', {
  height: player_h,
  width: player_w,
  videoId: 'phMtEWlGw_k',
  playerVars: {
    'playsinline': 1,
    'muted': 1
  },
  events: {
    'onStateChange': onPlayerStateChange
  }
});
}

// 4. The API will call this function when the video player is ready.
function onPlayerReady(event) {
	event.target.playVideo();
}
var played = false;
function onPlayerStateChange(event) {
	if (event.data == YT.PlayerState.PLAYING && !played) {
		player.mute();
		if(!isTest)
			nextStage();
		else{
			// homepage speedrun
			document.body.setAttribute('loadingstage', '3');
			typewriter(string_weather, sWeather, 10);
			typewriter(string_geolocation, sGeolocation, 10);
		}
	  played = true;
	}
}
function centerLiveFeed(){
	let sPlayer = document.getElementById('player');
	let sLive_background = document.getElementById('live-background');
	wW = window.innerWidth;
	wH = window.innerHeight;
	if(sLive_background.offsetHeight > wH)
		wH = sLive_background.offsetHeight;
	let screen_ratio = wH / wW;
	if(video_ratio < screen_ratio)
	{
		// console.log('screen is thinner');
		sPlayer.height = wH;
		sPlayer.width = wH / video_ratio;
	}
	else
	{
		// console.log('screen is wider');
		sPlayer.width = wW;
		sPlayer.height = wW * video_ratio;
	}
} 

window.addEventListener('load', function(){
	body.classList.remove('loading');
	centerLiveFeed();
});
window.addEventListener('resize', function(){
	centerLiveFeed();
});

