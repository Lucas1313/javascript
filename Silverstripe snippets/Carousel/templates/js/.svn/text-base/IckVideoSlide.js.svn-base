/**
 * IckVideoPage.js
 * Description:  The js file for the Ick Videos
 * @author Luc Martin
 * @ID
 */
var IckVideoManager = (function(){

	var defaultVideo = $('.playVideo').first().attr('data-videoid');
	console.info('defaultVideo: '+defaultVideo);

	function privatePlayDefaultVideo(){
		// Get the query parameters
		var defaultVideoType = $clx.urlParams["play"];

		// default video
		var matchedVideo = $('.playVideo').first();

		$('.playVideo').each(function(){
			if($(this).attr('data-videoname')){
				var thisVideo = $(this).attr('data-videoname');
				var matched = thisVideo.toLowerCase().split('_');
				if(matched[1] == 'solve'){
					$(this).children('.conclusion').css('display', 'block');
				}
				if(matched[1] == defaultVideoType){
					matchedVideo = this;
				}
			}

		});
		// trigger the click event
		$(matchedVideo).trigger('click');
	}

	function privateInitListeners(){
		var videoWidth = $('.videoWrapper').width();
		var videoHeight = videoWidth/(713/434);

		$('.playVideo').youtubewrapper({
			targetId : 'videoPlayer',
			height : videoHeight,
			width : videoWidth,
			videoId : 'data-videoid',
			modestbranding : 1,
			rel : 0,
			showinfo : 0,
			dataLayerEventName : 'ick-tionary_click-to-play_click',
			events : {
				'onStateChange' : function() {
					onPlayerStateChange();
				}
			},
			afterClick : function(eventTarget, args) {
				console.info('AfterClick functions running');
				$('.playVideo.hidden').removeClass('hidden');
				$(eventTarget).addClass('hidden');
				$('#videoTitle').html($(eventTarget).attr('data-headline'));
				$('#videoDescription').html($(eventTarget).attr('data-description'));
			}
		});
	}
	function onPlayerStateChange(){

	}
	return {
		playDefaultVideo : function(){
			privatePlayDefaultVideo();
		},
		initListeners: function(){
			privateInitListeners();
		}
	}
})();

$(document).ready(function(){
	//console.info('document ready video page');
	IckVideoManager.initListeners();

});
