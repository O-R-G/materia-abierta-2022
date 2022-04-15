var sIframe = document.querySelector('#live-background iframe');
console.log(sIframe);
if(sIframe)
{
	var msg_liveConsent = {
		'es': '(es)Materia Abierta would like to play a live stream at the background. Please click the play button to continue.',
		'en': 'Materia Abierta would like to play a live stream at the background. Please click the play button to continue.'
	};
	function centerLiveFeed(){
		let video_ratio = sIframe.height / sIframe.width;
		let wW = window.innerWidth;
		let wH = window.innerHeight;
		let screen_ratio = wH / wW;
		if(video_ratio < screen_ratio)
		{
			sIframe.height = wH;
			sIframe.width = wH / video_ratio;
		}
		else
		{
			sIframe.width = wW;
			sIframe.height = wW * video_ratio;
		}
	} 

	window.addEventListener('load', function(){
		sIframe
		let msg_container = document.createElement('DIV');
		msg_container.id = 'msg-container';
		let msg_box = document.createElement('DIV');
		msg_box.id = 'msg-box';
		msg_box.innerText = msg_liveConsent[lang];
		msg_container.appendChild(msg_box);
		document.body.appendChild(msg_container);
		centerLiveFeed();
	});
	window.addEventListener('resize', function(){
		centerLiveFeed();
	});
}

