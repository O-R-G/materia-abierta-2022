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
video_ratio = player_h / player_w;
function onYouTubeIframeAPIReady() {
player = new YT.Player('player', {
  height: player_h,
  width: player_w,
  videoId: 'MEP5EN7baKc',
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
		console.log('video init');
		nextStage();
	  	played = true;
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
