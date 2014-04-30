var theCarouselBrain = {};

/**
 * carouselManager function
 * Purpose: Setup and manages the slideshows in a page
 *
 * @author Luc Martin at clorox.com
 * @version $ID
 */
var carouselManager = (function(){
	var pagerWrapperWidth;
	var allPagers = {};
	var i = 0;

	function privateDetectSlideShows(){

		$('.carousel').each(function() {
			var id = (typeof $(this).attr('id') == 'undefined') ? 'carousel_'+i : $(this).attr('id');
			//console.info('running slideshow '+id)
			privateInitSlideShow('#' + id);
		});

	}

	/**
	 * @method measurePager
	 *
	 * purpose: will measure the Pager and count the elements in relation to the initial width of the pager
	 * will then define of the scrolling rules for that pager system
	 *
	 * @requirement Pager needs to have a set width, from the css system
	 * elements need also to have a set width from the css system
	 *
	 * @param {Object} pager
	 *
	 * @author Luc Martin at clorox.com
	 * @version $ID
	 */
	function measurePager(pager){
		// the wrapper div around the pager
		var pagerWrapper = $(pager).parent();

		// boolean defining if the elements within the pager are flush or spilling out of the pager
		var extraItemIsFlush = true;

		// extracts the width of the wrapper, set the value in the closure
		pagerWrapperWidth = $(pagerWrapper).css('width').replace('px','');

		//console.info('pager wrapper width '+$(pagerWrapper).css('width'));

		// the width of the single pager element
		var elementWidth = 0;

		// Initialize the main object with that specific pager information
		allPagers[pager] = {'itemsWidth':[], 'totalExtraItems' : 0, 'firstJump' : 0};

		// Iterate through all the items of the specific pager
		$('.pagerItem',pager).each(function(){

			//TODO replace the value 2 with the total of margin left and right for the pager element
			elementWidth += Number($(this).css('width').replace('px','')) + 2;

			//TODO WHY ???
			pagerWrapperWidth = Number(pagerWrapperWidth) + 2;

			// Saves all the width in an array for later use
			allPagers[pager].itemsWidth.push($(this).width());

			// adds an attribute to the HTML (just because I like to do it!)
			$(pagerWrapper).attr('data-position','0').attr('data-length',allPagers[pager].itemsWidth);

			// Now we need to define if there is more elements than there is space in the pager
			if(elementWidth > pagerWrapperWidth){

				// count all the extra items
				allPagers[pager].totalExtraItems += 1;

				// there is at least one extra item
				if(allPagers[pager].totalExtraItems == 1){

					// we need to jump the first item if it is half showing
					allPagers[pager].firstJump = elementWidth - pagerWrapperWidth;

					// Test if the element is flush with
					if(allPagers[pager].firstJump < elementWidth ){
					// It's not flush, so we add the missing pixels to the first jump
						allPagers[pager].itemsWidth[1] += allPagers[pager].firstJump;

						// set the item is flush to false
						extraItemIsFlush = false;
					}
				}
			}

		});

		// if the item is NOT flush, we need to remove one item from the extra items
		// this because we are jumping over that half showing not flush item
		allPagers[pager].totalExtraItems = (extraItemIsFlush == false) ? allPagers[pager].totalExtraItems - 1 : allPagers[pager].totalExtraItems;

		// set the pager to the correct width
		$(pager).css('width',elementWidth+'px');
	}

	/**
	 * @method privateInitSlideShow
	 *
	 * Purpose: This is the standard cycle initialization for a horizontal slide show
	 * @param {Object} className
	 *
	 * @author Luc Martin at clorox.com
	 * @version $ID
	 */
	function privateInitSlideShow(className){

		// the slideshow controls
		var arrowLeft = $(className).parent().find('.left').first();
		var arrowRight = $(className).parent().find('.right').first();

		// the pager controls
		var pager = '#'+$(className).attr('data-cycle-pager');
		var arrowPagerLeft = $(pager).parent().find('.left').first();
		var arrowPagerRight = $(pager).parent().find('.right').first();

		// Setup the pager
		if( isMobile !== true){
			measurePager(pager);
		}

		// Init the slideshow
		$(className).cycle({
			'timeout': 0,
			'slides':'> .slide',
			'prev':arrowLeft,
			'next':arrowRight,
			'fx':'scrollHorz',
			'cleartypeNoBg':true,
			'pager':pager,
			'pagerActiveClass':'activeSlide',
			'autoHeight':'calc',

		});

		// call back after a slide is being shown
		$(className).on('cycle-before', function(currSlideElement, nextSlideElement, options, forwardFlag){
            //console.log(currSlideElement)
            //console.log(nextSlideElement)
            //console.log(options)
            console.log(forwardFlag)
            var eventName = className.replace('#','').replace('.','');
			// propagate the event to the all page
			$('body').trigger('cycle-after_'+eventName, forwardFlag);

			// local function for the call back
			onAfter();
		});

		$(pager).find('img').last().load(function(){
			measurePager(pager);
		});

		// init the listeners for the pager
		privateInitListeners(pager);

		return;

	}

	/**
	 * @method privateInitListeners
	 *
	 * Purpose: initialize the listeners for the slide show
	 * @param {Object} pager
	 * @author Luc Martin at clorox.com
	 * @version $ID
	 */
	function privateInitListeners(pager){

		// the slideshow pager
		//var pager = pager;

		// initialize the position of the pager
		position = 0;

		// make sure the pager has the correct positioning
		$(pager).css('position','relative');

		// the wrapper
		var pagerTopWrapper = $(pager).parent().parent();

		// Click the left arrow on the pager area
		$('.left',pagerTopWrapper).on('click', function(){

			// if position is 0, don't move
			if(position == 0 ) {
				return false;
			}

			// Initialize the left position in the element to left=0
			var left = Number(($(pager).css('left') == 'auto') ? 0 : $(pager).css('left').replace('px',''));

			// the position we need the pager to go to, we use the closure object, set in the measurePager() function
			var newLeft = left + allPagers[pager].itemsWidth[position];

			// the animation
			$(pager).animate({'left' : newLeft+'px'});

			// new position ( if it's not 0')
			position = --position;

			// kill the event
			return false;

		});

		// the right arrow for the pager
		$('.right',pagerTopWrapper).on('click', function(){

			var left;
			var newLeft;

			// stop the movements if we are done moving up (that is all the extra items as set in the measurePager() function)
			if(position == allPagers[pager].totalExtraItems){
				// kill the event
				return false;
			}

			// go to next position
			position = ++position;

			// initialize the left position if we are at position 0
			left = Number(($(pager).css('left') == 'auto') ? 0 : $(pager).css('left').replace('px',''));

			// the new position, that is the "spilling item + the next item" if it's in position 0
			// If not, just move to the next
			newLeft = left - allPagers[pager].itemsWidth[position];

			// do it!
			$(pager).animate({'left':newLeft+'px'});

			// kill the event
			return false;
		});
	}

	/**
	 * @ method onAfter
	 * Purpose call back for the carousel slide move
	 *
	 * @author luc Martin at Clorox.com
	 * @version $ID
	 */
	function onAfter(){
	}

	/**
	 * Public methods for the object
	 */
	return {
		initSlideShow : function(className) {
			privateInitSlideShow(className);
		},
		detectSlideShows : function(){
			privateDetectSlideShows();
		},
		initListeners : function(){
			privateInitListeners();
		}
	}
})();

$(document).ready(function() {
	carouselManager.detectSlideShows();
});

//This operates a second slideshow and treats it like a pager for the slideshow above.
var pagerManager = (function(){
	function privateInitPager(pager){
		$(pager).cycle({
            'timeout':0,
            'fx':'carousel',
            'slides':'> .js-pager-item',
            'prev': '.js-pager-arrow-left',
            'next': '.js-pager-arrow-right',
            'carouselVisible':3,
            'carouselFluid':true
		});

		$('.js-pager-item').each(function() {

			var pos = $(this).attr('data-name').replace('pager-item-','');

			if(pos == 1) {
				$(this).addClass('active');
			}
		});
	}

	function privateInitListeners(classname,pager){
		$('body').on('click','.js-pager-item',function(e){

			if($(this).hasClass('active')==true){

				return false;

			} else {

				var name = $(e.currentTarget).attr('data-name');

				$('.js-pager-item').each(function() {
					$(this).removeClass('active');

					if( $(this).attr('data-name') == name ){
						$(this).addClass('active');
					}
				});

				var target = $(e.currentTarget).attr('data-target');
				var pos = $(e.currentTarget).attr('data-target').replace('carousel-slide-','');
				pos = pos - 1;

				$(classname).cycle('goto', pos);

				if(pos == 0) {
					$(pager).cycle('goto', pos);
				} else {
					pos = pos - 1;
					$(pager).cycle('goto', pos);
				}
			}

			return false;
		});
	}

	return {
		initPager : function(pager) {
			privateInitPager(pager);
		},
		initListeners : function(classname,pager) {
			privateInitListeners(classname,pager);
		}
	}
})();

$(document).ready(function() {

	pagerManager.initPager('.js-pager-slider');
	pagerManager.initListeners('.carousel','.js-pager-slider');
});
