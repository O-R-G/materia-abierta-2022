// 2. This code loads the IFrame Player API code asynchronously.
var tag = document.createElement('script');

tag.src = "https://www.youtube.com/iframe_api";
var firstScriptTag = document.getElementsByTagName('script')[0];
firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

// 3. This function creates an <iframe> (and YouTube player)
//    after the API code downloads.
var player;
function onYouTubeIframeAPIReady() {
player = new YT.Player('player', {
  height: '390',
  width: '640',
  videoId: 'MEP5EN7baKc',
  playerVars: {
    'playsinline': 1
    // 'autoplay': 1
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
function onPlayerStateChange(event) {
	console.log('onPlayerStateChange');
	if (event.data == YT.PlayerState.PLAYING) {
	  typewriter(string_weather, sWeather, 100, nextStage);
	}
}
var msg_liveConsent = {
	'es': '(es)Materia Abierta would like to play a live stream at the background. Please click the play button to continue.',
	'en': 'Materia Abierta would like to play a live stream at the background. Please click the play button to continue.'
};
var sConsent_msg = document.getElementById('consent-msg');
sConsent_msg.innerText = msg_liveConsent[lang];
function centerLiveFeed(){
	let sPlayer = document.getElementById('player');
	let video_ratio = sPlayer.height / sPlayer.width;
	let wW = window.innerWidth;
	let wH = window.innerHeight;
	let screen_ratio = wH / wW;
	if(video_ratio < screen_ratio)
	{
		console.log('screen is thinner');
		sPlayer.height = wH;
		sPlayer.width = wH / video_ratio;
	}
	else
	{
		console.log('screen is wider');
		sPlayer.width = wW;
		sPlayer.height = wW * video_ratio;
	}
} 

window.addEventListener('load', function(){
	
	
	centerLiveFeed();
});
window.addEventListener('resize', function(){
	centerLiveFeed();
});

