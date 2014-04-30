var productPageBrain = (function(){
	var useSectionHtml;
	return {
		getUseSectionHtml : function(){
			return	useSectionHtml;
		},
		setUseSectionHtml : function(useSection){
			useSectionHtml = useSection;
		}
	}
})()

/**
 * productpageController object
 *
 * manages all the javascript for the product page
 * @author Luc Martin lmartin -at- clorox.com
 * @version $Id:
 */
var productpageController = {

	base : this,
	codename : null,
	/**
	 * initPage
	 * initialize the page visuals and global stuff
	 */
	initTitle : function(){

		// Title Manipulation
		// Split "Clorox" and "Clorox 2" to a new line in product descriptions

		$("#hero h1").each(function( index ) {
			$(this).html($(this).text().replace('Clorox® ','<div class="brand">Clorox<sup>&reg;</sup></div>').replace('Clorox 2® ','<div class="brand">Clorox 2<sup>&reg;</sup></div>'));
		});

	},
	/**
	 * function  initListeners
	 * initialize the listeners related to the product page
	 * @author Luc Martin
	 * @param void
	 * @return null
	 */
	initListeners : function() {

		/**  **  **  **  **  **  **
	     * build CI shop now dialogs
	     ***************************/
	    $('.btnShopnow').on("click", function(e) {
	        e.preventDefault();
	        // pass the Buy Now launch method the
	        // elements storing scent data
	        buyNowModals.launch(
	            $('.scents li .scentIcon img', '.desktop')
	        );
    	});

    	// delegate the shop now events
    	// on click they will trigger the shopnow window
		$(document).delegate('.shopnow','click', function(){
			$('.btnShopnow').trigger('click');
			return false;
		})
		/**
		 * Click event for the useOn section
		 * @author Luc Martin
		 *
		 * modded: 6/13/2013  by kody smith
		 * seperated into named function to be able to be called by multiple types of objects.
		 * done for mobile version of the site compatibility
		 */
		$('body').on('change', '.UseInLinkSelect', function() {

			// Style selection
			$('.UseSelected').removeClass('UseSelected');

			$(this).parent().addClass('UseSelected');

			// The div to set is defined in the rel="" tag
			var toOpen = '#' + $(this).attr('value');

			// switch the content of the div
			$('.uses.col.right').html($(toOpen).html());

			// set class
			$('.uses.col.right').find('.descriptionDynamic').first().addClass('description');

			// initialize the Click events in the icons
			productpageController.initUseOnIconsListeners();


			// initialize the sweetScroller

			$($('.uses.col.right').find('.selectionIconsWrapper').first()).sweetScroller({
					target : '.selectionIcons',
									duration : 300,
									listenerClass : '.navigationArrow',
									direction : 'vertical',
									easeInOut : 'easeInOutQuint',
									arrowUpImage : null,
									arrowDownImage : null,
									arrowLeftImage : null,
                                    arrowRightImage : null});

		 });

		$('body').on('click', '.UseInLink', function() {
			console.log('useInLink clicked even in mobile');

			// Style selection
			$('.UseSelected').removeClass('UseSelected');

			$(this).parent().addClass('UseSelected');

			// The div to set is defined in the rel="" tag
			var toOpen = '#' + $(this).attr('rel');

			// switch the content of the div
			$('.uses.col.right').html($(toOpen).html());

			// set class
			$('.uses.col.right').find('.descriptionDynamic').first().addClass('description');

			// initialize the Click events in the icons
			productpageController.initUseOnIconsListeners();


			// initialize the sweetScroller

			$($('.uses.col.right').find('.selectionIconsWrapper').first()).sweetScroller({
					target : '.selectionIcons',
									duration : 300,
									listenerClass : '.navigationArrow',
									direction : 'vertical',
									easeInOut : 'easeInOutQuint',
									arrowUpImage : null,
									arrowDownImage : null,
									arrowLeftImage : null,
                                    arrowRightImage : null});

		  });

		 /**
		 * Click events for the FAQ section
		 * @author Luc Martin
		 */
		 $('.faqLink').click(function(){

			// highlight selected
			$('.faqLink').removeClass('active');
			$(this).addClass('active');

			// Grab the new content using the rel="" tag
			var toOpen = '#' + $(this).attr('rel');

			// Fade in out effect
			$('.FAQ.col.right').fadeOut('1000', function(){

				// replace the content
				$('.FAQ.col.right').html($(toOpen).html());
				$('.FAQ.col.right').find('.descriptionDynamic').first().addClass('description');
				$('.FAQ.col.right').fadeIn('1000');
			});

		})



	},



	/**
	 * function initUseOnIconsListeners
	 *
	 * Method for initiating the useOn listeners every time a new useInRoom is being clicked
	 * @author Luc Martin
	 * @param void
	 * @return null
	 */
	initUseOnIconsListeners : function() {
		var test = false;
		// Click Listener
		$('body').on('click', '.useOnListItems', function() {

			// the description div id
			var desc = '#' + $(this).attr('rel');
			var disclaimer = '#' + $(this).attr('rel') + '-disclaimer';

			// Description New content
			var descHtml = $('p',desc).html();

			// The actual description
			var actualDesc = $('.p1','.jspPane').html();

			// Title new content
			var titleHtml = $('h5',desc).html();

			// cleanup the white spaces for compare; in case the content editors have added silly white space
			if(descHtml){
				descHtml = descHtml.replace(' ','');
			}
			if(actualDesc){
				actualDesc = actualDesc.replace(' ','');
			}

			// if there is a difference in between the descriptions, switch them
			if(descHtml != actualDesc || test == false){

				// proceed with content replacement
				$('.description p').fadeOut(200);
				$('.description h5').fadeOut(200, function() {
					$('.description').html($(desc).html());
					$('.disclaimer','.uses.col.right').html($(disclaimer).html());
					$('.description').fadeIn(200);
					$('.description .scrollContainer').jScrollPane();
					test = true;
				});
			}
			else{
				$('.description h5').html(titleHtml);
				$('.disclaimer','.uses.col.right').html($(disclaimer).html());
			}

		});

		$('.uses.col.right').find('.useOnIcon').first().trigger('click');


	},

	/**
	 * Function initSweetScroller
	 * First initialization of the scroller
	 * @autor Luc Martin
	 */
	initSweetScroller : function(){

		$('.faqScrollerWrapper').sweetScroller({target:'.faqScrollerTarget',listenerClass:'.navigationArrow', direction:'vertical'});

		//$($('.uses.col.right').find('.selectionIconsWrapper').first()).sweetScroller({target:'.selectionIcons',listenerClass:'.navigationArrow', direction:'vertical'});

	},
	/**
	 * initScents function
	 * all the functionnality for the scents icons
	 * @author Luc "Varchar" Martin and someone else,
	 * because there is stuff there I surely didn't write (Luc)
	 */
	initScents : function(){

		// associate touch functionality to click event
		//TODO this function should deployed on every site that will be mobile
		var hitEvent = 'ontouchstart' in document.documentElement
		  ? 'touchstart'
		  : 'click';

		var originalImage = $('.productImg').clone();

		// Scent Tooltip
		$(".scents li a img").tooltip({
		    tooltipClass: "scentTooltip scentTooltipRegular",
		    position: { my: " top-55", at: " top" }
		});
		/**
		$(".scents li .lemon").tooltip({
		    tooltipClass: "scentTooltip scentTooltipLemon",
		    position: { my: "right+0 top-55", at: "right top" }
		});
		**/
		// Click the icon will change the ingredient list and also
		$('.scentIcon').bind(hitEvent, function(){
			var clicked = this;
			var scentData = $(this).next('.scentData');
			$('.addedScent').remove();
			$('h1','.prodDescription').html($('img',this).attr('data-title'));

			// Making sure that the title is formatted
			productpageController.initTitle();

			// pass the scent codename to the ingredients
			// ajax method
			productpageController.getIngredientsAjax($('img',this).attr('data-codename'));

			$('span','.ingredientsDataTitle').html($('h1','.prodDescription').html());
			$('.prodImgWrapper img').remove();


			if(!$('img',this).hasClass('original')){

					$('.prodImgWrapper').append('<img class="productImg" src="/assets/Uploads/products/'+$('img',this).attr('data-codename')+'.png" alt="">')

			}else if($(originalImage).attr('src') !== '/assets/Uploads/products/'+$('img',this).attr('data-codename')+'.png'){

				$('.productImg').attr('src',$(originalImage).attr('src'));
				$('.prodImgWrapper').append('<img class="productImg" src="'+$(originalImage).attr('src')+'"  alt="">');

			}
			$('.productImg').load(function(){

				var newWidth = this.naturalWidth;
				$('.prodImgWrapper').css('width',newWidth+'px');
				$('.buttonWrap').css('position','absolute').css('left',Number(newWidth+30)+'px');
			})

			$(".collapsibleList").collapsibleList();


		});

		$('body').on('click','.scentIcon',function(){
			var howTo = $('.howToScent',$(this).parent()).html();
			if($(howTo).length > 0){
				productPageBrain.setUseSectionHtml($('.col.left.desktop').html());
				$('.col.left','#usage').html(howTo);
			}else{
				if(typeof productPageBrain.getUseSectionHtml !== 'undefined'){
					var oldHowTo = productPageBrain.getUseSectionHtml();
					$('.col.left','#usage').html(oldHowTo);
				}
			}
			productpageController.initUseOn();
		})
	},
	/**
	 * initUseOn function
	 * default selection for the UseOn icons
	 * @author Luc Martin
	 */
	initUseOn : function(){
		$('.UseInLink','#usage').first().trigger('click');
		$('.useOnIcon','#usage').first().trigger('click');
	},

	/**
	 * getIngredientsAjax function
	 * makes a request to the ingredients api based on
	 * the code name of the current
	 *
	 */
	getIngredientsAjax : function(codename){

		// we pass the first scent codename to
		// this method on initial load

		if(!codename){
			if( $('.codeNameContainer').length > 0 ){
    			codename = $('.codeNameContainer').attr('data-codename');
		    }else{
		    	codename = $('ul.scents img').attr('data-codename');
		    }
		}


    	$.ajax({
        	url:"//ingredients.thecloroxcompany.com/api/product/en-us/"
        	     +codename+"?callback=?",
        	dataType : "jsonp",
        	timeout : 3000,
        	error: function(x, e) {

                // if there is an error we should proactively do something
                 var hiddenHeight = $('section#ingredients').css('height').replace('px','');

                 $('section#ingredients').hide(function(){
                 });
                 $('nav li#navIngredients').hide(function(){
                 });
                 var hash = window.location.hash;
                 if(hash !== ''){
	                if ($(hash).offset()) {
						$('html, body').animate({
						  	scrollTop: $(hash).offset().top - hiddenHeight-250
						  }, 10);
	             	}
				 }
            },
        	success:function(data){
				if(data.response.ingredients && data.response.ingredients.length > 0 ){


	        	   // resetting possible side effects of previous requests
	        	   $('section#ingredients').show();
	        	   $('nav li#navIngredients').show(function(){
	                 });
	        	   $('dl.ingredientsData').empty();
	            	// on successful return of data append the
	            	// ingredients list to the dom
	            	$.each(data.response.ingredients, function(index, element) {
	                    $('dl.ingredientsData').append('<dt>'+element.name+'</dt>').append('<dd>'+element.description.replace('<a href="fragrances">','').replace('</a>','')+'</dd>');
	                });
	                // fire the accordion
	                $(".collapsibleList").collapsibleList();

	                // make sure tracking is enabled
	                // Track virtual events using google tag manager
	                // and replicate virtual pageview tagging from common libs
	                $('dl.ingredientsData').on("mousedown", "dt", function() {

	                    dataLayer.push({'event': 'product_ingredients_click'});
	                    _gaq.push(['_trackPageview', '/clorox-virtual/product_ingredients_click']);

	                });
	              }else{
	              	$('section#ingredients').hide();
                 	$('nav li#navIngredients').hide();
	              }
            }
        });

	},

	/**
	* Name: function initHashtags
	*
	* Description: initialize the hashtags for the page
	* @ID
	* @author Luc Martin
	**/
	initHashtags : function(){

		$(this).hashnav({
	        'updateMetatags' : true,
				'metatags' : [{'attribute':'og:description', 'value':'testing tags'}],
				'after' : function (){
					//console.info('Callback function ');
					}

		});
	},

	initFAQ : function(){
		//console.info('Init FAQ')
		$('.accordion  h3').click(function(){
			$('.accordion').first().find('.faqLink').first().trigger('click');
		})

		$('.accordion  h3').first().trigger('click');

	},
	showHideProducts : function(){

		$('.notDefaultProduct').hide();
		$('.defaultProduct').show();

	}
}

/**
	* Name: function productImageController
	*
	* Description: calculate height and width of product image in the hero panel; place the green buttons to the right of the image
	* @ID
	* @author Luc Martin
	**/

var productImageController = (function () {
	var oldImage;

	function initListeners (){
		//timer that runs every .5 seconds to see if image has loaded
		var interval = setInterval(function(){

			var imageWrapper = $('.prodImgWrapper', '.productWrapperMain'),
				imageWrapperWidth,
				image = $('.productImg',imageWrapper),
				imageHeight,
				imageWidth,
				buttonWrapper = $('.buttonWrapMain'),
				buttonPosition;

			if(typeof buttonWrapper == 'undefined'){
				return;
			}

			imageWrapperWidth = $(imageWrapper).css('width').replace('px','');
		    buttonPosition = Number(buttonWrapper.css('left').replace('px',''));

			// extract the real width of the image
			if(image.attr('src') || image.attr('src') !== null || image.attr('src') !== ''){
				// Get on screen image
				var screenImage = image;

				// Create new offscreen image to test
				var theImage = new Image();
				theImage.src = screenImage.attr("src");

				// Get accurate measurements from that.
				imageWidth = theImage.width;
				imageHeight = theImage.height;
			}
			if(imageHeight == 0 || !imageHeight){
				imageHeight = 480;
			}

			if ($(oldImage).attr('src') !== $(image).attr('src')){
				resetPosition(image, imageWidth, imageHeight, imageWrapper, buttonWrapper);
			}


		},500);

	}

	function resetPosition (image, imageWidth, imageHeight, imageWrapper, buttonWrapper){
		var minHeight = 310;

		//position from top for the image
		var imageNudgeAmount = 70;

		var containerHeight = $('#hero').css('height').replace('px', '');
		var availableSpace = Number(containerHeight) - Number(imageNudgeAmount) - Number(imageHeight);

		//position the buttons are pushed down from image
		var buttonNudgeAmount = availableSpace * .20;

		if (imageHeight > 250 && imageHeight <= minHeight) {
			buttonNudgeAmount = 30;
			imageNudgeAmount = 30;
		}

		$(image).css('position','absolute');
		$(image).css('visibility','visible');
		$(image).css('bottom','0px');
		$(image).fadeIn(400);
		oldImage = $(image);
		$(imageWrapper).css('width',imageWidth+'px');

		if (imageHeight <= minHeight) {

			var buttonTop = Number(imageNudgeAmount) + Number(imageHeight) + 60;
			$(imageWrapper).css('top', imageNudgeAmount + 'px').css('height', imageHeight + 'px');
			$(imageWrapper).parent().css('height', imageHeight + 'px').css('overflow', 'visible');
			$(buttonWrapper).css('position','absolute').css('top',buttonTop + 'px');
		}
		else {
			$(buttonWrapper).css('position','absolute').css('left',Number(imageWidth) + 40  + 'px');
		}

		console.info(imageHeight);

		$(buttonWrapper).fadeIn(1500);

	}
		return { initListeners : function(){
			initListeners();
		}
	}
})();


// unfortunate ugly hack to get tags firing on
// rate and review
$(document).ready(function() {
    // poll for buttons loaded and add tracking
    var timeout = setTimeout( checkDOMChange, 100 );
    function checkDOMChange(){

        var target = $('#BVRRDisplayContentLinkWriteID');
        // only do a lot of stuff if the elements exist
        // due to relying on listeners
        if( target != -1 ){

            // Track virtual events using google tag manager
            // and replicate virtual pageview tagging from common libs
            $('#BVRRContainer').on("mousedown", target, function() {

                dataLayer.push({'event': 'product_write-review_click'});
                _gaq.push(['_trackPageview', '/clorox-popup/product_write-review_click']);

            });

            // clear the timeout
            window.clearTimeout(timeout);
        }
    }
});

var productDetailMediaManager = (function() {
    function privateStartSlideShow (className) {

        var arrowLeft =  $(className).parent().find('.left').first();
        var arrowRight = $(className).parent().find('.right').first();

        $(className).cycle({
        	'timeout': 0,
            'slides':'> li',
        	'prev':arrowLeft,
            'next':arrowRight,

            before: function(currSlideElement, nextSlideElement, options, forwardFlag) {
               	var playingActive = $('.playingActive', currSlideElement);
               	var videosource = $(playingActive).attr('src');
				$(playingActive).fadeOut(200, function() {
	      			$(playingActive).attr('src', null);
	      			$(playingActive).attr('src', videosource);
	      			$(playingActive).fadeIn('200');
	      			$('.playingActive', currSlideElement).removeClass('.playingActive');
              	});
            },

            // callback fn that creates a thumbnail to use as pager anchor
            pagerAnchorBuilder: function(idx, slide) {
            	return '<a href="#"><div><span>' + $(slide).attr('data-pager') + '</span></div></a>';
            },

            fx: 'scrollHorz'
        });
        console.info(className+' slideshow initiated');
    }

	return {
	    startSlideShow: function(className) {
    	    privateStartSlideShow(className);
     	}
	}
})();

/**
 * Function Document Ready
 * Method to initialize the Javascript in the page
 * will initialize the event listeners, google analytics,
 * the sweetScroller, and an ingredients api call
 */
$(document).ready(function() {
	productpageController.initTitle();
	productpageController.initListeners();
	productpageController.initSweetScroller();
	productpageController.initScents();
	productpageController.initUseOn();
	productpageController.getIngredientsAjax();
	productpageController.initHashtags();
	productpageController.initFAQ();
	productImageController.initListeners();
	productpageController.showHideProducts();
	$('.fullwidthSlides').each(function() {
		productDetailMediaManager.startSlideShow('#' + $(this).attr('id'));
	});
});
