/**
 * voteManager function
 * Purpose: manages votes from users
 *
 * @author Luc Martin -at- Clorox.com
 * @version $ID
 */
var voteManager = (function(){

	var target; //Button that was clicked.

	// Initialize the listeners
	function privateInitListeners(){
		// set initial text in the input field for moments to prevent default text from being submit
		messageText = $('textarea#Form_MomentForm_Moment.textarea').val();

		$('body').on('click','.btnVote', function(e){
			target = e.target;
			// check to see if there is a redirect for this, and execute it on click
			if($(target).attr('data-redirect') != undefined ){
				window.location = $(target).attr('data-redirect');
			}else{
				privateCastVote(e);
			}
		});

		$('body').on('click','#Form_MomentForm_action_ShareMomentAction', function(){
			$('.feedback').remove();
			var minValue = 2,
			    inputText = $('textarea#Form_MomentForm_Moment.textarea').val();
				count = inputText.split(' ').length,
			    match = inputText.match(messageText);
			if(messageText == inputText){
				$('#Form_MomentForm_Moment').focus();
				$('.momentSubmitForm').prepend('<div class="feedback" style="color:darkRed; text-shadow:1px 1px 2px #ddd;  margin-left:20px;">Write something about your best Bleachable moment!</div>')
				return false;
			}else if(count <= minValue){
				var word = (count == 1 || count == 0) ? 'word' : 'words';
				$('#Form_MomentForm_Moment').focus();
				$('.momentSubmitForm').prepend('<div class="feedback" style="color:darkRed; text-shadow:1px 1px 2px #ddd;  margin-left:20px;">Surely your best Bleachable moment is longer than '+count+' '+word+'?</div>');
				return false;
			}

  		});
	}

	/**
	 * function privateHandleSuccess
	 * purpose handle successful vote
	 * will update the buttons states and give feedback to the users
	 *
	 * @author Luc Martin -at- clorox.com
	 * @version $ID
	 */
	function privateHandleSuccess(response, type){

		// the parent div will be dynamically generated
		var parentDiv;
		console.log(target);

		// hard coded responses
		if(type == 'ShowdownVotes'){
			parentDiv = $(target).closest('.showdownMoment');
		} else {
			parentDiv = $(target).closest('.momentComment');
		}
		// the feedback divs
		var thanks = $('.copyThanks',parentDiv);
		var moment = $('.copyMoment',parentDiv);

		var parentThanksDiv = $(target).closest('.showdownMoment');

		$('.'+type, parentDiv).hide();

		$('.'+type+'Success', parentDiv).removeClass('hidden');

		$(thanks).removeClass('hidden');
		$(moment).hide().addClass('hidden');

		$('.'+type).not(target).hide();
		$('.'+type).not(target).siblings('.popularityTomorrow').removeClass('hidden');
	}

	/**
	 * privateCastVote function
	 * Purpose: will send an ajax on user vote
	 *
	 * @author luc Martin -at- Clorox.com
	 * @version $ID
	 */
	function privateCastVote(e){
		var target = e.target;
		var id = $(target).attr('data-id');
		var type = $(target).attr('data-type');
		var url = window.location.href;
		var errorMessage = "Error with the voting";
		var data = {
			'id' : id,
			'type' : type
		}
		$.ajax({
			'url' : url,
			'data':data,
			'errorMessage':errorMessage,
			'success' : function(response){
				privateHandleSuccess(response, type);
			}
		});

	}

	// public
	return {
		initListeners : function(){
			privateInitListeners();
		}
	}
})();

$(document).ready(function(){
	voteManager.initListeners();
});

/**
 * submitBoxManager function
 * Purpose: Clears the submit panel on focus.
 *
 * @author James Billings -at- Clorox.com
 * @version $ID
 */
var submitBoxManager = (function() {
	function privateInitListeners() {

  		$('body').on('focus','#Form_MomentForm_Moment', function(){
	        if (this.value === this.defaultValue) {
	            this.value = '';
	        }
  		});
  	}

  	return {
		initListeners : function (){
			privateInitListeners();
		}
	}
})();

$(document).ready(function() {
	console.info('Submit Box Manager Ready');

    submitBoxManager.initListeners();
});

/**
 * TabModuleMediaManager function
 * Purpose: Initiates slideshow for Tab Module.
 *
 * @author James Billings -at- Clorox.com
 * @version $ID
 */
var TabModuleMediaManager = (function() {

    function privateStartTabModule(className) {
        $(className).cycle({
            'timeout': 0,
            'slides':'> section',
            'autoHeight':'calc'
        });
    }

    function privateStartSlideShow(className) {

        var arrowLeft = $(className).siblings('.slideControls').find('.icon-chevron-left').first();
        var arrowRight = $(className).siblings('.slideControls').find('.icon-chevron-right').first();

        var pager = $(className).siblings('.slideControls').find('.pager').first();

        $(className).cycle({
            'timeout': 0,
            'slides':'> .tabSlide',
            'prev':arrowLeft,
            'next':arrowRight,
            'autoHeight':'calc',
            'pager':pager,
            'pagerTemplate' : '<i class="icon-circle"></i>',
            'pagerActiveClass' : 'active'
        });
    }

    function addListeners(className) {
        $('li.tipsTricks').click(function(){
            $(className).cycle('goto', 0);
        });
        $('li.drLaundry').click(function(){
            $(className).cycle('goto', 1);
        });
    }

    return {
        startTabModule: function(className) {
            privateStartTabModule(className);
        },
        startSlideShow: function(className) {
            privateStartSlideShow(className);
        },
        initListeners: function(className) {
            addListeners(className);
        }
    }
})();


/**
 * characterCountManager function
 * Purpose: Notify user of the quantity of character they have left while writing in a input
 *
 * @author Matt and Luc Martin -at- Clorox.com
 */
var characterCountManager = (function (){

	var maxchars = 450;
	var countFeedbackObj;
	var listenerTarget;

	function privateInitCountDownVariables(args){
		maxchars = args.maxChars;
		countFeedbackObj = args.countFeedbackObj;
		listenerTarget = args.listenerTarget;
	}

	// Initialize the listeners
	function privateInitListeners(args){

		// iterate through the args
		for(var n in args){

			// each Object is a set of key values
			var event = args[n];

			// set the listeners
			$('body').on( event , listenerTarget , function(e){
				privateUpdateCountdown(listenerTarget);
			});
			privateUpdateCountdown(listenerTarget);
		}

	}

	/**
	 * function privateUpdateCountdown
	 * purpose: Let user know how many character they have left
	 *
	 * @author Matt & Luc Martin -at- Clorox.com
	 * @version $ID
	 */
	function privateUpdateCountdown(listenerTarget) {
		var charCount
		maxchars = 450;

		if(typeof listenerTarget !== "undefined") {

			if(typeof $(listenerTarget).val() == 'undefined'){
				charCount = $(listenerTarget).html().length;
			} else {
				charCount = $(listenerTarget).val().length;
			}

			var remaining = maxchars - charCount;
			remaining = Math.max(remaining, 0);

			if (!remaining){
			    if(typeof $(listenerTarget).val() == 'undefined'){
					listenerTarget.html(listenerTarget.html().substr(0, maxchars));
				} else {
					listenerTarget.val(listenerTarget.val().substr(0, maxchars));
				}
			}

	  		$(countFeedbackObj).html('Give us your<br/>best mess.<br />We can take it!<br /><br />'+charCount + '/' + maxchars);

	  	} else {
	  		return;
	  	}
	}
	return {
		initCountDownVariables : function (args){
			privateInitCountDownVariables(args);
		},
		initListeners : function (args){
			privateInitListeners(args);
		}
	}
})();

$(document).ready(function() {
	TabModuleMediaManager.startTabModule('#BLMSlider');
    TabModuleMediaManager.initListeners('#BLMSlider');

    TabModuleMediaManager.startSlideShow('.tipsSlideshow');

    TabModuleMediaManager.startSlideShow('.drLaundrySlideshow');
});

