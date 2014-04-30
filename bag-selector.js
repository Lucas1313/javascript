/**
 * module productSelectorManager
 * purpose: manages operations for the Product Selector
 * @author Luc Martin -at- Clorox
 * @version $ID
 */
var productSelectorManager = (function() {

	// closures
	var currentPhase = 'intro'; // the current phase of questions/answers
	var theBrain = []; // The central intelligence
	var totalPhases; // the total number of phases
	var pos;
	var moreQuestions = true;
    var showingProducts = '';
	/**
	 * function privateInitListeners
	 * purpose: will initialize the listeners for the product selector
	 *
	 * @author Luc Martin -at- Clorox
	 * @version $ID
	 */
	function privateInitListeners(){

	    $('body').on('final-question', function(e, data){
	        console.log(data)
	    })
		/**
		 * intro action click
		 * Listener
		 * Purpose: Initialize the product selector
		 * @author Luc Martin
		 */
		$('body').on('click','.intro-action-js', function(){
			hashnavBrain.hash = $(this).attr('href');
			window.location.hash = $(this).attr('href');
			// Show the first screen
			$('.phase-wrapper').removeClass('hidden');

			// scroll to the top
			$('body, html').animate({'scrollTop': 0}, 1000, function(){

				// reset the view to phase 0
				showHideDivs(0);

				// initialize all questions to phase 0
				activatePhase1();
				toogleProductColor('color');
			})


			return false;
		});

		/**
		 * Listeners for the top menu (Temporary)
		 */
		var timeoutSubnav;
		$('body').on('mouseenter','.hasSub', function(){
			clearTimeout(timeoutSubnav);
			$('.subNavSecondLevel',this).fadeIn(500);
		});

		$('body').on('mouseleave','.hasSub', function(){
			timeoutSubnav = setTimeout(function(){
			$('.subNavSecondLevel',this).fadeOut(500);
			},500);
		});


		/**
		 * Listeners to turn the gray product to color
		 *
		 * @author Luc
		 */
		$('body').on('mouseenter','.productWrapper', function(e){

			if(currentPhase == 'intro'){
				$('img.product',this).attr('src',$('img.product',this).attr('src').replace('gray/','glamour/'));
				$(this).removeClass('grayed-out');
			}
		});
		$('body').on('mouseleave','.productWrapper', function(e){

			if(currentPhase == 'intro'){
				$('img.product',this).attr('src',$('img.product',this).attr('src').replace('glamour/','gray/'));
				$(this).addClass('grayed-out');
			}

		});
		$('body').on('click','.count', function(){
			$($('body, html')).animate({'scrollTop': 300}, 1000);
		})

		/**
		 * startOver click
		 * Listener
		 * Purpose Resets the selector by pushing the start over button
		 *
		 * @author Luc martin
		 * @version $ID
		 */
		$('body').on('click','.start-over', function(){
			hashnavBrain.hash = $(this).attr('href');
			window.location.hash = $(this).attr('href');

			for(var n = 1 ; n <= totalPhases+1 ; n++){
				$('.phase'+n+'-wrapper').fadeOut(500);
			}
			$('.intro-wrapper').removeClass('hidden').fadeIn();
			$('.product-list').attr('style','');
			$('.showing').addClass('hidden').removeClass('showing');
			$('.phase-wrapper.hidden').css('display','none');
			activatePhase1('intro')
			return false;

		});
		$('body').on('click','.intro-wrapper', function(){
			$('.intro-action-js').trigger('click');
		});
		/**
		 * window scroll
		 * Listener
		 * Purpose detects the scroll and shows the keep narrowing wrapper,
		 * also reset the keep narrowing if user get to the top
		 */
		$(window).on('scroll', function(){

			// the scroll position
			pos = $(this).scrollTop();
			//console.log('phase '+currentPhase+ ' position '+ pos+ 'currentPhase '+currentPhase+' totalPhases '+totalPhases+' moreQuestions '+moreQuestions)
			// 340 is the height where the keep narrowing needs to start showing
			if(pos > 340 && currentPhase >= 0 && moreQuestions == true){

				$('.keep-narrowing-wrapper').not('.fixed').addClass('fixed');

			}else{

				$('.keep-narrowing-wrapper').removeClass('fixed');

			}

			// animates the fix position while scrolling
			if(pos >= 398 && (currentPhase == 'intro')){

				$('.intro-wrapper').css('position','fixed');
				$('.intro-wrapper').css('top','-221px');

			}else{

				// clean up the style attribute so the top resets
				$('.intro-wrapper').attr('style','');
			}

		})

		/**
		 * keep-narrowing-over click
		 * Listener
		 * Purpose scroll to top on keep narrowing click
		 * @author Luc martin
		 * @version $ID
		 */
		$('body').on('click', '.keep-narrowing-over', function(){

			// simple animation
			$($('body, html')).animate({'scrollTop': 0}, 1000);
			return false;
		})

		$('body').on('mouseenter', '.selectAnAnswer', function(){
			$('img',this).attr('src', $('img',this).attr('data-hover'));
		});


		$('body').on('mouseleave', '.selectAnAnswer', function(){
			$('img',this).attr('src', $('img',this).attr('data-color'));
		})

		/**
		 * selectAnAnswer click
		 * Listener
		 * Purpose:  This is the main action for the product selector
		 * Clicking an answer will
		 * a) select the next question
		 * b) eliminate products
		 */
		$('body').on('click','.selectAnAnswer', function(e){

			//console.log('ANSWER CLICKED ');
			//console.log(e);

			hashnavBrain.hash = $(this).attr('href');
			window.location.hash = $(this).attr('href');

			// removes the hidden class to all phases
			$('.phase-wrapper').removeClass('hidden');

			var urlTitle = $(this).attr('data-urltitle'),	// The urlTitle is the equivalent of Codename for all E-e objects
				phaseNumber = $(this).attr('data-phase'),	// The phase this answer belongs to
				phase = 'phase'+phaseNumber,				// readeable version of the phase
				relatedTags = getTagsForAnswer(urlTitle);	// Grab the tags related with that answer
			phase = (phase == 'phaseundefined') ? 'phase0' : phase;

			currentPhase = phaseNumber;						// closure for further use
			cleanupClasses(phaseNumber);

			theBrain[phase]['master_tags'] = relatedTags.masterTags; // The brain will hold all data, here we hold the master tags
			theBrain[phase]['tags'] = relatedTags.tags;				 // the tags related to the actual answer
			theBrain[phase]['answer'] = urlTitle;					 // the answer codename

			// if we are in phase 1 saves the tags in the brain
			if(phaseNumber == 1){
				theBrain[phase]['matchedAnswerTag'] = relatedTags.tags;
			}

			// clone the answer data
			var clone = $(this).clone();

			// removes the image data
			$('img',clone).remove();

			// saves the answer data in the Brain phase
			theBrain[phase]['answerContent'] = $(clone).html();

			// select tagged products
			selectProducts(phase);

			// Generate the next questions will be false or true if there is no more questions
			moreQuestions = generateQuestion(phaseNumber);

			// animate the show hide for selected products
			showHideProducts(phaseNumber);

			// Generate breadcrumbs so user can track back
			generateBreadCrumbs(phaseNumber);

			// if there is no more questions fitting the tags, we jump to the last phase
			phaseNumber = (moreQuestions == false) ? totalPhases : phaseNumber;

			// test if it's last phase
			if(phaseNumber == totalPhases){

				if(pos > 0){
					$($('body, html')).animate({'scrollTop': 0}, 1000);
				}

				// if it is show the also like
				addClr();
				showAlsoLike();
				showDescription();
                $('body').trigger('final-question',$('.product-list').attr('data-selectedproducts'));
			}

			// manages the show and hide of the top divs
			showHideDivs(phaseNumber);

			// no hash tag
			return false;

		});


	}

	function addClr(){
		var iterator = 1;
		$('.SHOW').each(function(){
			var crl = '<li class="clr"><div class="clr"></div></li>';
			if(iterator < $('.SHOW').length){
				$(this).after(crl);
			}
			iterator = ++iterator;
		})
	}
	/**
	 * @method toogleProductColor
	 * purpose: switch products from gray to colored
	 *
	 * @author Luc Martin
	 * @version $ID
	 */
	function toogleProductColor(saturation){
		$('.gridImage').each(function(){
			if(saturation == 0 && typeof $('img.product',this).attr('src') !== 'undefined'){
				$('body').append('<img class="hidden" src="'+$('img.product',this).attr('src').replace('glamour/','gray/')+'">')
				$('img.product',this).attr('data-color',$('img.product',this).attr('src'));
				$('img.product',this).attr('data-gray',$('img.product',this).attr('src').replace('glamour/','gray/'));
				$(this).closest('li').addClass('grayed-out');
			}else if(saturation == 'color'){
				$('img.product',this).attr('src',$('img.product',this).attr('data-color'));
				$('.grayed-out').removeClass('grayed-out');
			}else if(saturation == 'gray'){
				$('img.product',this).attr('src',$('img.product',this).attr('data-gray'));
				$(this).closest('li').addClass('grayed-out');
			}
		});

	}

	/**
	 * function showAlsoLike()
	 * Purpose: Will show the also like at the end of the selection process
	 * using the last product selected
	 *
	 * @author Luc Martin
	 * @version $ID
	 */
	function showAlsoLike(){

		// grabs the first of the remaining products
		var showingProduct = $('.SHOW')[0],

		// each product has a hidden div with the also like we use the first of them
		alsoLike = $('.alsoLike',showingProduct).html();

		// update the also like div
		$('.product','.you-may-also-like-wrapper').html(alsoLike);
	}

	/**
	 * function showDescription()
	 * Purpose: Will show the Product Description at the end of the selection process
	 * using the last product selected
	 *
	 * @author Luc Martin
	 * @version $ID
	 */
	function showDescription(){

		// grabs the first of the remaining products
		var  description = $('.descriptionWrapper','.SHOW');
		setTimeout(function(){
			// update the also like div
			$(description).fadeIn(500).removeClass('hidden').addClass('descriptionShowing');
			//console.log(addThisBrain[$(description).attr('data-relatedproducttitle')])
			$('.addthisWrapper', description).html(addThisBrain[$(description).attr('data-relatedproducttitle')].data)
			initAddThis();
		},1500);
	}


	/**
	 * function showHideDivs
	 * purpose: shows and hides the divs in relation with the phase number being processed
	 *
	 * @param phaseNumber Int The actual phase
	 * @author Luc Martin -at- clorox
	 * @version $ID
	 */
	function showHideDivs(phaseNumber){

		// this is the next phase
		var temp = 1*(phaseNumber)+1;

		// this is making sure the next questions are hidden (used on breadcrumbs back tracking)
		var toHide = temp+1;

		// resets the old showing divs to their original class
		$('.showing').addClass('phase-wrapper-js').removeClass('showing');

		// now set the next phase to showing
		$('.phase'+temp+'-wrapper').removeClass('phase-wrapper-js').addClass('showing').fadeIn(1000);

		// animate the fadeOut
		while($('.phase'+toHide+'-wrapper').length > 0){
			$('.phase'+toHide+'-wrapper').fadeOut();
			toHide = ++toHide;
		}

		// test if we are into the last phase
		if(phaseNumber == totalPhases){

			// if yes, we change the height of the product grid, because the last top div is smaller
			$('.product-list').css('marginTop','182px');

			// hides all past and future divs
			$('.phase-wrapper').each(function(){
				if(!$(this).hasClass('showing')){
					$($(this).addClass('hidden'));
				}
			});

			// show the also like
			$('.you-may-also-like-wrapper').fadeIn(1000);

		}else{
			// removes the JS style in the product div if we are not in the last phase
			$('.product-list').attr('style','');

			// hides the also like
			$('.you-may-also-like-wrapper').fadeOut(1000);
		}
	}

	/**
	 * @method generateAddThis
	 * purpose: generates a addthis dic on demand
	 *
	 * @author Luc Martin at clorox.com
	 * @version $ID
	 */
	function initAddThis(){
		setTimeout(function(){
			var addthis_config = addthis_config||{};
		 	addthis_config.pubid = 'ra-504f7d8670939a04';
		 	//addthis.init();
		 	addthis.toolbox('.addthis_toolbox',addthis_config);
		},1000);

	}

	/**
	 * function generateBreadCrumbs(phaseNumber)
	 * Purpose: Will insert a breadCrumb link to the document, the breadcrumb should let the user
	 * backtracking the choices he/she makes
	 *
	 * @param phaseNumber Integer The phase where the user is at
	 * @author Luc Martin
	 * @version $ID
	 */
	function generateBreadCrumbs(phaseNumber){

		// init the breadcrumb div content
		var ret = '<a href="#intro" data-phase="1" data-hashnav="#intro" class="breadcrumb selectAnAnswer intro-action-js">You\'ve Selected</a> > ';

		// the next phase number
		var newPhaseNumber = ++ phaseNumber;

		// iterate through all phases in the brain
		for (var n = 1; n < phaseNumber; ++n ){

			// grab each answer from the brain
			var answer = theBrain['phase'+n].answer;

			// grab the readeable content in the brain
			var answerContent  = theBrain['phase'+n].answerContent;

			// add breadcrumb
			ret += '<a class="breadcrumb-js selectAnAnswer" data-hashnav="#'+answer+'" href="#'+answer + '" data-phase="'+n+'" data-urltitle="' + answer+ '">' + answerContent + '</a>';

			// close the breadcrumb div
			if(n < phaseNumber-1){
				ret+=' > '
			}
		}

		// set content in the visible div
		$('.breadcrumbs','.phase'+newPhaseNumber+'-wrapper').html(ret);

		// this is for the last phase
		var lastPhase = totalPhases+1;
		$('.breadcrumbs','.lastPhase-wrapper').html(ret);
	}

	/**
	 * cleanupClasses function
	 * Purpose: Will remove all selections in the products that are selected by next phases (used by breadcrumbs while back tracking)
	 *
	 * @author Luc Martin
	 * @version $ID
	 */
	function cleanupClasses(phaseNumber){
		$('.descriptionShowing').addClass('hidden');
		for(var n = phaseNumber; n<=4; ++n){

			$('.selectedByphase'+n).removeClass('selectedByphase'+n);

		}

	}

	/**
	 * showHideProducts function
	 * Purpose: Will hide all products not selected by the current phase
	 *
	 * @author Luc Martin
	 * @version $ID
	 */
	function showHideProducts(phaseNumber){
        showingProducts = '';
		if(phaseNumber == 0){
			toogleProductColor('color');
		}
		// reset classes
		$('.SHOW').removeClass('SHOW');
		$('.HIDE').removeClass('HIDE');
		$('li.clr').remove();

		// Iterate through all products
		$('.productWrapper').each(function(){
			var show = false;

			// iterate through all phases stored in the brain, up to the requested phase (used by breadcrumbs)
			for(var n =1 ; n <= phaseNumber; n++)
			{
				//test if that product has been selected
				if($(this).hasClass('selectedByphase'+n)){
					// if the product has not been marked to be hidden, mark it to be shown
					if(!$(this).hasClass('HIDE')){
						$(this).addClass('SHOW');
					}

				}else{
					// this product doesn't belong to any tag in the actual question
					$(this).removeClass('SHOW');
					$(this).addClass('HIDE')

				}
			}

			// show hide
			if($(this).hasClass('SHOW')){

				$(this).fadeIn(1000);
				showingProducts += $('.productTitle',this).html()+', ';
			}
			else{
				$(this).fadeOut(1000);
			}

		});

		// count how many products we are showing
		var count = $('.SHOW').length;

		// update the count for user to keep track
		$('.countNumber').html(count);
		$('.product-list').attr('data-selectedproducts',showingProducts);

	}

	/**
	 * function generateQuestion
	 * Purpose: will generate a question and a set of possible answers
	 * will use the level of question to select the question
	 * and a set of master slave tags to get the right answers
	 *
	 * <!! Narrative for Spencer: !!>
	 * Once a pound a time was in the great Western Land of California, a Marketing Giant agency that was selling lots of different trash bags.
	 * During these time of darkness, in a far, far eastern country was a group of customers that didn't know what bag to get to dispose of their trash.
	 *
	 * The great East was getting more and more filled by trash, the subjects were dying from terrible pandemics such as Great Black Bag Plague.
	 *
	 * The King of the East asked the Help from the Great Gentle Trash Giant Spenceritrus, known for his wise kindness.  The King offered
	 * to Spenceritrus a Boat Full Of Gold and also his 21 Years old Daughter, Annata of the East, to marry, if the Giant could help with all the trash.
	 *
	 * The Gentle Trash Giant summoned was unable to help but knew a powerfull magician, a master of Javascriptures.
	 * The Great Magician was called Lucus Martinus, a member of the UID Magic cast.
	 *
	 * Spenceritrus offered to The Great Magician, a little box of gold, and a old dog to marry if he could cast a magic set of Questions/Answers
	 * that would help The King Of The East to get rid of the Terrible Trash Plague.
	 *
	 * These Questions Answers shall be helping the eastern Customers ro find their way into the Trash Bags Selection Maze.
	 *
	 * Lucus worked and worked for hours after hours, days after days, months and years trying to cast the perfect magic sort.
	 *
	 * The maze was of great complexity, Questions generating more questions answers repeating them self, Confusion leading to
	 * more confusion.
	 *
	 * After 1001 days of work, Lucus the Great Magician had to summon the Great Wizard, his mentor, from the Lost Desert of
	 * Burning Man, the Powerfull Michaelus Barbarinus.
	 *
	 * !!! Think TAGS, think LEVELS, think DIFFERENT!!!  Was the answer.
	 *
	 * Each question shall belong to a level, hold into themself a set of answers.  Each Answer shall be associated
	 * with a group of Magic Tags.
	 *
	 * The first Questions and Answers selected by the Lost Customer shall cast a single magic word a "< MASTER TAG ">
	 * The "MASTER TAG" shall be the first KEY in the quest for the Perfect Trash Bag.
	 *
	 * A < Question > and an < Answer > shall have Parent Tags!
	 *
	 * < Parent Tags > shall define of the next question-answers as follow:
	 *
	 * < MASTER TAG > ->  ->  < Parent tags >< LEVEL 1-4 >< NEW QUESTION >< Related Answers> -> -> <ANSWER TAGS> ->-> <Parent tags> < Question > and so on.
	 *
	 * The whole process is supervised by a central intelligence called < The Brain >
	 * That is a top level Object that is constantly updated following progress of the quest for the Perfect Trash Bag.
	 *
	 * Shall the Customer answer all available Questions, the magic Cast Terminate into a level called <Last Phase> that
	 * will display The Perfect Trash Bag, and a set of other magic formulas such as "Also like", "Buy Now" and so on.
	 *
	 * Spenceritrus got a boat full of gold, a new Wife, Lucus got a little box of gold and a old dog, but got adopted by
	 * The Great Giant, they all got happy and generate lots of Parent -> Children Tags
	 */
	function generateQuestion(phaseNumber){
		// the question
		var selectedQuestion;
		// get the masterTags from the brain, join them so we don't have to loop'
		var masterTags = (typeof theBrain['phase1'].master_tags == 'object') ? theBrain['phase1'].master_tags.join() : 'empty';

		if (masterTags == 'empty'){
			return true;
		}
		// increase the level to next level
		phaseNumber = ++ phaseNumber;

		// the new level
		var level = 'Level '+phaseNumber;

		// grab info from the brain
		theBrain['phase'+phaseNumber] = {'matchedAnswerTag':[],'question':''};

		// questions from the json Object
		var questions = productSelectorData.questions;

		// iterate through all the questions
		for(var n in questions){

		    // the question we are looping in
			var question = questions[n];

			// the level of the question we are looping in
			var questionLevel = question.level;

			// the tags associated to the question
			var questionTags = question.master_tags;


			// test if that question is relevant to our level
			if(questionLevel == level){

				//console.table('The actual level of the question is: ' + question.level.replace('Level ',''));

				var matchedPastLevels =  false;

				for (var n = 1 ; n < phaseNumber; ++n){

					//we need to match with the tags in the last answer.
					var matchLastAnswer = false;
					var matchLastAnswerMatchedTags = false;

					// join the arrays for eash regex match
					var lastQuestionMatchedTags = (typeof theBrain['phase'+n].matchedAnswerTag == 'object') ? theBrain['phase'+n].matchedAnswerTag.join() : 'empty';
					var lastQuestionTags =  (typeof theBrain['phase'+n].tags == 'object') ? theBrain['phase'+n].tags.join() : 'empty';

					// iterate through all tags associated with the actual question (upper loop)
					for(var m in questionTags){

						// the tag we are iterating through
						var tag = questionTags[m];

						// built regex
						var regex = new RegExp(tag);

						// test if we are matching that tag with any of the questions tags
						var matchTemp = lastQuestionTags.match(regex);
						var matchTemp2 = lastQuestionMatchedTags.match(regex);

						// that tag matches last question
						if(matchTemp){

							// ok to use that tag
							matchLastAnswer = true;

							// initialize the brain to receive information
							if(typeof theBrain['phase'+phaseNumber].matchedAnswerTag == 'undefined'){
								theBrain['phase'+phaseNumber].matchedAnswerTag = array();
							}

							// push the tags for later use
							theBrain['phase'+phaseNumber].matchedAnswerTag.push(tag);
						}

						// test if that tag matches the last question already matched tags
						if(matchTemp2){

							// match is ok
							matchLastAnswerMatchedTags = true;
						}
					}

					// true requires both last question and actual question tags match
					matchedPastLevels = (matchLastAnswer == true && matchLastAnswerMatchedTags == true ) ? true : false;

				}

				// iterate through all the question Master tags
				for(var m in questionTags){

					// the tag we are iterating through
					var tag = questionTags[m];

					// match the tag
					var regex = new RegExp(tag);
					var match = masterTags.match(regex);

					// if true that question is selected but only if it matches the past question / answer tag
					if(match && matchedPastLevels == true){

						// we get our new question
						var content = question.question_content;

						// we get our associated answers
						var associated_answers = question.associated_answers;

						theBrain['phase'+phaseNumber].question = question.urlTitle;
						//console.log(content)

						// count the length of the question for UI fontsize
						// this is because some questions are too long for the space we have
						var length = content.length;

						// set the question for UI
						$('.question','.phase'+phaseNumber+'-wrapper').html(content);

						// set the font size if the question is too long
						if(length > 39){
							$('.question','.phase'+phaseNumber+'-wrapper').addClass('smallerFont');
						}else{
							$('.question','.phase'+phaseNumber+'-wrapper').removeClass('smallerFont');
						}

						// now we need to show answers related to ahat question
						var ret = '';
						var iterator = 0;
						// Iterate through all answers to get a match
						$(associated_answers).each(function(){

							// get the answers from the json object
							var allAnswers = productSelectorData.answers;

							// the associated answer title, we use it as a key to get the answer from the json object
							var urlTitle = $(this)[0].urlTitle;
							var jsonAnswer = allAnswers[urlTitle];

							// now we join the tags for easy matching
							var answerMasterTags = jsonAnswer.parent_tags.join();

							//test if the tags in the answer are matching the tags in the question
							var answerMatch = answerMasterTags.match(regex);
							var precedentQuestionTagRegex = new RegExp(theBrain['phase'+phaseNumber].matchedAnswerTag[theBrain['phase'+phaseNumber].matchedAnswerTag.length-1]);

							var precedentAnswerTagMatch = answerMasterTags.match(precedentQuestionTagRegex);

							// answer belongs to the actual question and to the last one as well
							if(answerMatch && precedentAnswerTagMatch){
								// init the list item
								ret += '<li>';

								// adds the divider before the div but not in the first one
								if(iterator !== 0){
								 ret += '<div class="divider" />';
								}
								iterator = ++ iterator;

								// the answer content
								if(typeof $(this)[0].urlTitle !== undefined){
									ret+= '<a class="selectAnAnswer" href="#'+$(this)[0].urlTitle.replace('WW','') + '" data-phase="'+phaseNumber+'" data-urltitle="' + $(this)[0].urlTitle.replace('WW','') + '"><img src="/images/pages/product-selector/icons/'+$(this)[0].urlTitle.replace('WW','')+'.png" data-color="/images/pages/product-selector/icons/'+$(this)[0].urlTitle.replace('WW','')+'.png" data-hover="/images/pages/product-selector/icons/hover/'+$(this)[0].urlTitle.replace('WW','')+'.png"><span>' + $(this)[0].content + '</span></a></li>';
								}
							}

						})
						//push the content
						$('.answers','.phase'+phaseNumber+'-wrapper').html(ret);
					}
				}
			}
		}
		// if there is an answer return true if not we are at the last phase
		if(typeof ret == 'undefined'){
			// last phase (dead end)
			$('.product-list').attr('data-hitlastquestion','phase'+phaseNumber);

			return false;
		}else{
			$('.product-list').attr('data-hitlastquestion','not yet')
			$('.product-list').attr('data-processingphase','phase'+phaseNumber);
			return true
		}
	}




	/**
	 * selectProducts function
	 * Purpose: Select and add class to the products following the tags associated with the answers from user
	 *
	 * @param phase String The phase user is while answering questions
	 *
	 * @author Luc Martin
	 * @version $ID
	 */
	function selectProducts(phase){

		//grab all tags for the current phase
		var tags = theBrain[phase].tags;
		var count = 0;
		// iterate through all tags
		for(var n in tags){
			// codename for the tags
			var tagUrlTitle = tags[n];

			// iterate through all products
			$('.productWrapper').each(function(){

				//grab the tags associated with the product
				var productTags = $(this).attr('data-tags');

				// match with current tag
				var regex = new RegExp(tagUrlTitle);
				var match = productTags.match(regex);

				//test if there is a match
				if(match){
					// if htere is a match increase count
					count = ++count;

					// add class to mark that product as belonging to the current phase
					$(this).addClass('selectedBy'+phase);

				}
			});


		}
	}

	/**
	 * function getTagsForAnswer
	 * Purpose Get the tags for a certain answer in the main json
	 *
	 * @param urlTitle String The key
	 * @author Luc Martin
	 * @version $ID
	 */
	function getTagsForAnswer(urlTitle){

		var relatedAnswer, // init variables
			relatedTags,
			masterTags,
			answer = productSelectorData.answers[urlTitle];

		relatedAnswer = answer;
		if(typeof relatedAnswer == 'undefined' || typeof relatedAnswer.tags == 'undefined'){
			return {'tags': 'empty',
				'masterTags': 'empty'};
		}
		relatedTags = relatedAnswer.tags;
		masterTags = relatedAnswer.parent_tags;

		return {'tags': relatedTags,
				'masterTags': masterTags};

	}

	/**
	 * function privateGetTags
	 * purpose: get the CMS generated list of questions answers and their tags
	 * will generate a module and a data object containing 3 keys
	 * questions, answers, products
	 *
	 * @author Luc Martin -at- Clorox
	 * @version $ID
	 */
	function privateGetTags() {
		// the url of the json object located in the ee system
		var url = "/trash/product-selector-object";

		// go ajax
		$.ajax({
			url : url,
			dataType : "text",
			success : function(r) {

				var regex = new RegExp('PHP Error')
					match = r.match(regex);
				if(match){
					//console.log('GETTING JSON OBJECT')
					return privateGetTags();
				}
				// //console.log(r);
				// Inject the json object into the page
				$('body').append('<script>' + r + '</script>')
				$('.intro-action-js').fadeIn();
			},
			error : function(e) {
				//console.log(e)
			}
		});
	}

	/**
	 * function productSelectorTagsCallback
	 * purpose: to be executed by the return of the ajax call performed by the privateGetTag
	 * the call back will execute once the result is injected in the product selector page
	 *
	 * @author Luc Martin -at- clorox
	 * @version $ID
	 */
	function productSelectorTagsCallback() {
		//console.log('callback initiated')

		// call back for the json ajax
	}

	/**'
	 * function activatePhase1
	 * purpose:
	 * 1- will show the first part of the product selector process
	 * 2- Will filter the products to original state
	 */
	function activatePhase1(forcePhase){

		var firstQuestion;

		// init the first question
		currentPhase = (typeof forcePhase !== 'undefined') ? forcePhase : 0;

		// cleanup a bit so products will not be hidden
		$('HIDE').removeClass('HIDE');

		// turn all products gray
		toogleProductColor('gray');

		// reset the products
		$('.productWrapper').each(function(){
			for(var n = 0; n <= totalPhases ; ++n){

				// cleanup phases (used by breadcrumbs)
				$(this).removeClass('selectedByphase'+n);

				// show all
				$(this).addClass('SHOW');

				// fade in the product
				$(this).fadeIn();
			}
		});

		// count all products
		var count = $('.SHOW').length;

		// update UI for the count
		$('.countNumber').html(count);

		// find intro leve question
		$(productSelectorData.questions).each(function(){
			if(this.level == 'Intro'){
				firstQuestion = this;
			}
		});
		// grab all info about the first question
		var urlTitle = firstQuestion.urlTitle, // codename
			content = firstQuestion.question_content, // readeable content
			answers = firstQuestion.associated_answers; // all answers

		// stuff info into the brain
		theBrain['phase1'] = {'question':urlTitle}

		// set UI
		$('.question','.phase1-wrapper').html(content);

		// prepare the answers
		var ret = '';
		var iterator = 0;

		// Iterate through all answers
		$(answers).each(function(){
			var first = iterator == 0 ? ' first ':'';

			ret += '<li class="'+first+' selectAnAnswer"><div class="divider" /><a class="selectAnAnswer " href="#'+$(this)[0].urlTitle + '" data-phase="1" data-urltitle="' + $(this)[0].urlTitle + '"><img src="/images/pages/product-selector/icons/'+$(this)[0].urlTitle.replace('WW','')+'.png" data-color="/images/pages/product-selector/icons/'+$(this)[0].urlTitle.replace('WW','')+'.png" data-hover="/images/pages/product-selector/icons/hover/'+$(this)[0].urlTitle.replace('WW','')+'.png"><span>' + $(this)[0].content + '</span></a></li>';
			iterator = ++iterator;
		})
		$('.answers','.phase1-wrapper').html(ret);
	}

	function privateInitHashnav(){
		//console.log('INIT HASH')
		$('body').hashnav({

			'listenForTagChange':true,
	        'updateMetatags' : false,
	        'useOnlyDataTag':true,
				'after' : function (){
					 //console.info('Callback function for hashnav');
				}

		});
	}

	/**
	 * publicly exposed methods and variables
	 */
	return {
		'initListeners' : function(){
			privateInitListeners();
		},
		'getTags' : function() {
			privateGetTags();
		},
		'productSelectorTagsCallback':function(){
			productSelectorTagsCallback()
		},
		'setTotalPhases' : function(n){
			totalPhases = n;
		},
		'toogleProductColor':function(saturation){
			toogleProductColor(saturation);
		},
		'initHashnav' : function(){
			privateInitHashnav();
		}
	}
})()

$(document).ready(function() {
	addthis.init();
	window.location.hash = 'start-over';
	// set the last phase
	productSelectorManager.setTotalPhases(4);
	productSelectorManager.toogleProductColor(0);
	productSelectorManager.toogleProductColor('gray');
	// ajax to get the json object
	//productSelectorManager.getTags();

	// initialize listeners
	productSelectorManager.initListeners();
	productSelectorManager.initHashnav();
	$('.intro-action-js').fadeIn();
})
