var carouselLoader = (function(){

	function loadScript(){

		console.info('Loading the loader');

		if(typeof theCarouselBrain !== 'object'){

			console.info('Loading the AJAX '+typeof jQuery().cycle);

			if(typeof jQuery().cycle == 'undefined'){

				$.getScript("Carousel/js/jquery.cycle2.min.js");

				$.getScript("Carousel/js/jquery.cycle2.carousel.min.js")
					.done(function(){
						loadCarouselScript();
					}
				);

			} else {
				loadCarouselScript();
			}
		}

		function loadCarouselScript(){

			$.getScript( "/Carousel/js/carousel.js" )
				.done(function( script, textStatus ) {
					console.info( 'success' );
				})
				.fail(function( jqxhr, settings, exception ) {
					console.info( 'fail '+exception );
				}
			);
		}
	}

	return {
		loadScript : function(){
			loadScript();
		}
	}
})();

$(document).ready(function(){
	setTimeout(function(){
		carouselLoader.loadScript();
	}, 500);
});
