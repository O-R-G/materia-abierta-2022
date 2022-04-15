var sIframe = document.querySelector('#live-background iframe');
console.log(sIframe);
if(sIframe)
{
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
		centerLiveFeed();
	});
	window.addEventListener('resize', function(){
		centerLiveFeed();
	});
}

