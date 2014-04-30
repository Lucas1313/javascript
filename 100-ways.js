/**
 * urlParams reads the query string
 * parameters based on passing in the key, e.g.
 * queryVal = $clx.urlParams["query"]
 * To set a variable use $clx.addUrlParams(key, value)
 * To send the new query use: $clx.sendQuery(newOnly)
 * Note: $clx.sendQuery(true)   will send only the data freshly set sendQuery()
 * will use the old query as well.
 * TODO: this belongs to the common javascript file...
 *
 */
var $urlQuery = (function(){

    var urlParams = {};
    var newQueries = {};

    var pl = /\+/g,
        search = /([^&=]+)=?([^&]*)/g,
        decode = function(s) {
            return decodeURIComponent(s.replace(pl, " "));
        },
        query = window.location.search.substring(1),
        match = true;


    while (match = search.exec(query)) {
        ////console.log(match);
        urlParams[decode(match[1])] = decode(match[2]);
    }

    /**
     *  addUrlParams function
     *  purpose: Uses the $clx.urlParams object to add and
     *  if required send a search string
     *  If the key already exist, it will simply update it
     *  @author Luc Martin -at- Clorox.com
     *  @version $ID
     *  @param key String
     *  @param value String
     */
    function privateAddUrlParams (key, value ){

        urlParams[key] = value;
        newQueries[key] = value;

    }

    /**
     * privateBuildSearch function
     * Purpose: Generates a search string using either all parameters already there or only the new parameters set by this module
     *
     * @author Luc Martin -at- Clorox.com
     * @version $ID
     */
    function privateBuildSearch(newOnly){

        //initialize the search will force a refresh
        var search = '?1=1';

        // test if we need only the new params
        var obj = (typeof newOnly == 'boolean' && newOnly == true) ? newQueries : urlParams;

        // iterate through the params
        for(var key in obj){
            var value = encodeURIComponent(urlParams[key]);
            search += '&'+key+'='+value;
        }
        return search;
    }

    /**
     * privateSendQuery function
     * Purpose: Will send a query string to the server
     * @param newOnly // Boolean true will send only the new parameters false will send all
     *
     * @author Luc Martin -at- clorox.com
     * @version $ID
     */
    function privateSendQuery(newOnly){
        var search = privateBuildSearch(newOnly);
        window.location.search = search;
    }

    function privateRemoveUrlParam(key){
        delete urlParams[key];
        delete newQueries[key];
    }

    function privateClearAll(){
        urlParams = {};
        newQueries = {};
    }
    /**
     * public section of the object
     */
    return {

            urlParams : urlParams,

            addUrlParams : function(key, value){
                privateAddUrlParams(key, value)
            },
            removeUrlParam : function(key){
                privateRemoveUrlParam(key);
            },
            sendQuery : function(newOnly){
                privateSendQuery(newOnly)
            },
            clearAll : function(){
                privateClearAll();
            }

    }
})()

var cookieMonster = (function(){

    /**
     * @method createCookie
     * Purpose: Set the cookies in the user computer
     *
     * @param {Object} args
     * @author Luc Martin  from http://stackoverflow.com/questions/1458724/how-to-set-unset-cookie-with-jquery
     * @version 1.0
     */
    function privateCreateCookie(args) {
        var expires, name = args.name, value = args.value, days = args.days || null;

        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toGMTString();
        } else {
            expires = "";
        }
        document.cookie = escape(name) + "=" + escape(value) + expires + "; path=/";
    }

    /**
     * @method readCookie
     * Purpose: Reads cookie from User computer.
     *
     * @param {string} name
     * @author Luc Martin  from http://stackoverflow.com/questions/1458724/how-to-set-unset-cookie-with-jquery
     * @version 1.0
     */
    function privateReadCookie(name) {

        var nameEQ = encodeURI(name) + "=";
        var ca = document.cookie.split(';');

        for (var i = 0; i < ca.length; i++) {

            var c = ca[i];

            while (c.charAt(0) === ' ') {
                c = c.substring(1, c.length);
            }
            var reg = new RegExp(nameEQ)
            var match = c.match(reg);


            if (match) {
                return decodeURIComponent(c.substring(nameEQ.length, c.length));
            }
        }
        return null;
    }

    /**
     * @method eraseCookie
     * Purpose: deletes cookie from User computer.
     *
     * @param {string} name
     * @author Luc Martin  from http://stackoverflow.com/questions/1458724/how-to-set-unset-cookie-with-jquery
     * @version 1.0
     */
    function privateEraseCookie(name) {
        privateCreateCookie({'name':name, 'value':"", 'expires':-1});
    }

    return {

        // cookies test
        'testCookies' : function() {
            privateCreateCookie({
                'name' : 'hundredWays-accepted-rules',
                'value' : 'true'
            });
            setTimeout(function() {
                console.log('A cookie has been set and read name is: ' + privateReadCookie('hundredWays-accepted-rules'));
                privateEraseCookie('testCookie');
                console.log('Deleting cookie now the value is ' + privateReadCookie('hundredWays-accepted-rules'))
            }, 1000)
        },
        'createCookie' : function(args){privateCreateCookie(args)},
        'eraseCookie' : function(arggs){privateEraseCookie(args)},
        'readCookie' : function(args){return privateReadCookie(args)}
    }

})()

/**
 * @module quizManager
 * purpose: Manages the quiz
 *
 * @author Luc Martin @ Clorox
 * @version 1.1
 */
var quizManager = (function(){

    // closure to maintain the state of affairs
    var quizBrain = {
        allQuiz : {}
    };

    /**
     * @method initListeners
     * purpose initalize the Listeners for a quiz
     *
     * @author Luc Martin at clorox
     * @version 1.0
     */
    function privateInitListeners(){

        console.log('privateInitListeners')

        /**
         * Listener for the quiz answer click
         */
        $('body').on('click','.quiz-answer', function(){

            // handle click
            handleQuizAnswerClick(this);
            return false;
        })

        /**
         *  Listener for the cycle-2 slide change event
         * Purpose: Will handle the quiz solves when the slide with all solves is being display
         */
        $('body').on('cycle-before', function(currSlideElement, nextSlideElement, options, forwardFlag){
            console.log($(forwardFlag))
            $('.hidden-by-tip').show().removeClass('hidden-by-tip');
            $('.show-tip').addClass('hidden').removeClass('show-tip');
            // test if we have a solve slide
            var solve = $(forwardFlag).find('.solve-wrapper');
            if($(solve).length > 0){
                console.log('fund a solve')
                // we do process the solve
                solveQuiz(forwardFlag);
            }

        });
    }

    /**
     * @method privateFormatList
     * purpose: formats the list html for stuff that needs to be calculated after
     *
     * @author Luc Martin at Clorox.com
     * @version 1.0
     */
    function privateFormatList(){

        var quiz = $('.quiz-with-result');
        var slide = $('.slide');
        $(quiz).each(function(){
            var countTotal = $('.quiz-question', this).length;
            console.log('Count total '+countTotal)
            var count = 1;
            $(this).find('.slide').each(function(){
               $('.top-right',this).prepend('<span><strong>'+count+' </strong></span>'+'<span>of</span> <span><strong> '+countTotal+'</strong></span>')
               count = ++count;
            });

        });
    }

    /**
     * @method handleQuizAnswerClick
     * Purpose: handles when a user answer a quiz question
     *
     * @params target Object: the event target
     * @author Luc Martin at clorox
     * @version 1.0
     *
     */
    function handleQuizAnswerClick(target){
        // makes the a click on an answer the div act like radio buttons

        // removes the class selected from all siblings
        $(target).siblings().removeClass('selected');

        // add class selected to the answer
        $(target).addClass('selected');

        // show the tip
        showTip(target)
    }

    /**
     * @method showTip
     * Purpose: show the tip
     *
     * @params target Object: the event target
     * @author Luc Martin at clorox
     * @version 1.0
     *
     */
    function showTip(target){

        // up one level in th html hierarchy
        var $parent = $(target).parent();
        var slide = $parent.closest('.slide');

        // find the tip related to that answer
        var $tip = $('.quiz-answer-tip',$parent);

        if(typeof $(target).attr('data-hide-on-tip') !== 'undefined' && $(target).attr('data-hide-on-tip') == '1'){
            $('.quiz-answer', $parent).hide().addClass('hidden-by-tip');
            $('.top-right', slide).hide().addClass('hidden-by-tip');
        }
        // show the tip
        $tip.removeClass('hidden').addClass('show-tip');
    }

    /**
     *
     * @method countSelected
     * Purpose: counts all the selected answers
     * find the score for each of them
     * cleanup the Brain from old memories of precedent counts
     * compile scores in the brain
     *
     * @params target Object: the event target
     * @author Luc Martin at clorox
     * @version 1.0
     *
     */
    function countSelected(target){
        // up in the html hierarchy to the parent div
        var $parent = $(target).closest('.quiz-with-result');


        // sub brain to check if memory is cleared of old scores
        var dataIsCleared = {};

        // iterate through all quiz slides
        $('.quiz-answer.selected', $parent).each(function(){

            // the quiz being evaluated

            // get the quiz id
            var quizId = $(this).attr('data-quizid');

            // Is the data already cleared? this is in case the user is rolling back n his answers and changes them.
            if(typeof dataIsCleared[quizId] == 'undefined'){

                // the brain needs to forget about all answers since we are recalculating them
                for(var n  in quizBrain.allQuiz[quizId] ){

                    // clean up the brain from scores
                    var answerObj = quizBrain.allQuiz[quizId][n];
                    answerObj.score = 0;
                }

                // set the sub brain id as cleared
                dataIsCleared[quizId] = 'cleared';
            }

            // now we process the data
            var questionId = $(this).attr('data-question');
            var answerId = $(this).attr('data-answer');

            // evaluate the score for the question
            for(var n  in quizBrain.allQuiz[quizId] ){

                // the answer in the brain
                var answerObj = quizBrain.allQuiz[quizId][n];

                // all values for that solve
                var scoringChart = answerObj.scoringChart;
                if(typeof scoringChart == undefined){
                    continue;
                }
                // create a Regex to compare with the scoring chart
                var regex = new RegExp(answerId);

                // match the answer to the chart
                var match = scoringChart.search(regex)

                // there is a match!
                if(match > -1){
                    // add to the score
                    answerObj.score = ++answerObj.score;
                }
            }
        });
    }
    /**
     * @method privateProcessQuizSolve
     * Purpose: Explodes a <ul> into multiple divs,
     * hides the divs,
     * setup an object in the brain
     * the object will hold the solve, the chart of ansers for a solve
     * the score
     * for further quiz management
     * @author Luc Martin @ clorox
     * @version ID
     */
    function privateProcessQuizSolveList(quiz){

        // extract the answers in the solve ul
        var $quizElements = $('.solve-wrapper ul', quiz);

        // the actual quiz
        var quizId = $('.solve-wrapper', quiz).attr('data-quizid');

        // init the brain with the new id
        quizBrain.allQuiz[quizId] = {};

        var iterator = 0;

        // Iterate through all the quiz elements
        $('li',$quizElements).each(function(){

            var image = $('img',this).attr('src');
            $('img',this).remove();

            // extract the html data
            var element = $(this).html();

            // split (per convention we are using ++ as separator)
            var elementAr = element.split('++');

            // the scoring chart is the second element of the split
            var scoringChart = elementAr[1];

            // the text content
            var content = elementAr[0];

            // set an id to the element (not sure why I did that Dhuuu)
            $(this).attr('id',String(quizId)+'-'+String(iterator));

            // stuff the Brain with the information
            quizBrain.allQuiz[quizId][iterator] = {'scoringChart' : scoringChart, 'text':content, 'image':image, score:0};
            iterator = ++iterator;


        })

    }

    /**
     * @method solveQuiz(forwardFlag)
     * purpose calculates the results of a quiz, show the solve
     *
     * @param forwardFlag Object (generated by the cycle2 event propagation)
     * @author Luc Martin at Clorox
     * @version 1.0
     */
    function solveQuiz(forwardFlag){

        // up in the html hierarchy to the parent div
        var $parent = $(forwardFlag).closest('.quiz-with-result');

        hundredWaysManager.stampGameItem($parent);
        var gameTag = $parent.attr('data-u');


        console.log('THE GAME QUIZ HAS A TAG '+gameTag);

        // we are at the last slide, so we need to count all the answers
        countSelected(forwardFlag);

        // grab the quiz id from the first element
        var quizId = $(forwardFlag).find('.solve-wrapper').first().attr('data-quizid');

        // grab the data in the brain
        var actualQuiz = quizBrain.allQuiz[quizId];

        // winner object
        var winner = {};

        // top score init
        var topScore = 0;

        // iterate through the brain data for that quiz
        for (var n in actualQuiz){

            // each question has data
            var questionData = actualQuiz[n];

            // the score
            var score = questionData.score;

            // grab the top score
            //TODO if we have the same score should we have 2 solves?
            if(score > topScore){
                // WINNER!!!
                winner = questionData;
                $('body').trigger('satelliteEvent',{'type':'solve', 'data':winner.text });
                topScore = score;
            }
        }

        // prep the solve text
        var solve = winner.text;
        var solveImage = winner.image;
        // stuff the text in the last slide
        $(forwardFlag).find('.solved').first().html('<div class="solveText">'+solve+'</div>');
        $(forwardFlag).find('.solved').first().prepend('<img src="'+solveImage+'" class="solvedImage">');
        $('body').trigger('quiz-solved',  $(forwardFlag).find('.solved').first());

        //TODO should we communicate the data to someone?

        $('body').trigger('completed-quiz',{'type':'quiz', 'task-id':quizId, u:gameTag});
        //hundredWaysManager.handleGameAction($parent)

    }

    return {
        'processQuizSolveList' : function(quiz){
            privateProcessQuizSolveList(quiz);
         },
        'getQuizBrain' : quizBrain,
        'initListeners' : function(){
             privateInitListeners();

        },
        'formatList' : function(){
            privateFormatList();
        }

    }
})()

//******************************************************************************************************************** HUNDRED WAYS MANAGER

/**
 *@method hundredWaysManager
 * The main object for the 100-ways page
 *
 * @author Luc Martin
 * @ersion $ID
 */
var hundredWaysManager = (function() {

    /*
     * maintain state for user
     */
    var hundredWaysManagerBrain = {
        'playerId':'',
        'hundredWaysAcceptedTheRules' : false,
        'hundredWaysOptedEmails' : false,
        'userSkipRegistration':false,
        'actionData':undefined,
        'winState' : {
            'gameStarted':false,
            'prize': '',
            'gameActions' : 0,
            'prizeData' : {}
         },
         'loved':[]
    };
    // stamps for game cheating lookup
    var gd = [];
    var us = [];
    var validGameActions = [];
    var gameActionData;
    var pos;
    var scrollTo;
    var nonuser;

     /**
     * @method privateKillBrain
     * Purpose Kill the braina nd it's cookies
     *
     * @author Luc Martin
     * @version 1.0
     */
    function privateKillBrain(){
        console.log('KILLING THE BRAIN!!! ',hundredWaysManagerBrain)
        hundredWaysManagerBrain = {
            'playerId':'',
            'hundredWaysAcceptedTheRules' : false,
            'hundredWaysOptedEmails' : false,
            'userSkipRegistration':false,
            'winState' : {
                'gameStarted':false,
                'prize': '',
                'gameActions' : 0,
                'prizeData' : {}
             },
             'loved':[]
        }
        console.log('KILLed THE BRAIN!!! ',hundredWaysManagerBrain)
        $(window).trigger('100-ways-state-change');
    }

    /**
     * @method clearBrainfromPrizes
     * Purpose Kill the braina nd it's cookies
     *
     * @author Luc Martin
     * @version 1.0
     */
    function clearBrainfromPrizes(){
        hundredWaysManagerBrain.winState.prizeData = 'claimed';
        hundredWaysManagerBrain.winState.prize = 'claimed',
        $(window).trigger('100-ways-state-change');
        $(window).trigger('clearedBrainfromPrizes');
    }

    /**
     * @method brainIsRestored
     * Purpose: will restore the content of the brain to recover the game state
     */
    function restoreBrain() {

        var restore = cookieMonster.readCookie(userInfo.email.replace('@','at').replace('.','dot')+'_100-ways-state');

        // test if there is a value to restore
        if (restore) {
            hundredWaysManagerBrain = JSON.parse(restore);
            console.log('state restore',hundredWaysManagerBrain);
            hundredWaysManagerBrain.userSkipRegistration = false;
            return true;
        }
        else{
            return false;
        }

    };

    // saves the expanded content in the closure
    var actualShowingItemContent;

    /**
     * @method privateInitFirstState
     * Purpose: Setup the page using the user cookies
     * and the logged in information if any
     * Note: this is in fact a listener but I reather isolate it here because in also is the initial page setup
     *
     * @version 1.0
     * @author Luc Martin at Clorox
     */
    function privateInitFirstState(){

        // cleans the product description from the extra divs
        $('.description').each(function(){
            $('p',this).addClass('hidden');
            $(this).find('p').first().removeClass('hidden');
        });
        // We need an interval here to compensate with the mysterious disaperarance of the data-u tags, this probably due to sattelite
        setTimeout(function(){
            var interval = setInterval(function(){
                console.log('TAGGING INTERVAL RUNNING');
                privateStampGameItems();
                $(window).trigger('TaggingComplete',gd);
                var tagged = false;
                $('.game-action').each(function(){
                    if(typeof $(this).attr('data-u') !== undefined ){
                        tagged = true;
                        clearInterval(interval);
                    }
                })
            }, 3000);
        },1000);
        privateGetMostRecentWinners();

        // test if user is already logged in
        if(gld_logged_in == true){
            console.log('INIT:: getting user info');
            //get the user info
            getUserInfo();
        }else{
            // we set the banner state to initial if the user is not logged in
            privateSetRedBannerState('initial-state');

        }

    }

    /**
     * @method restoreLove()
     * Purpose: Restores the loved items
     *
     * @author Luc Martin
     * @version 1.0
     */
    function privateRestoreLove(){

        // recovers the state of loved items from the brain
        var loved = hundredWaysManagerBrain.loved;

        // restore the state of the loved buttons
        for (var n in loved){

            // double check if user still love the item
            var lovedItem = loved[n];

            // OK set the class
            if(lovedItem){
                $('.love-'+lovedItem).addClass('loved').removeClass('game-action');
            }
        }
    }

    /**
     * @method privateStampGameItems();
     * Purpose: Insert a unique id to game elements
     * generates an array with these to verify cheating
     *
     * @author Luc Martin
     * @version 1.0
     */
    function privateStampGameItems(itemToTag, forceTagging){
        var stamp = 0;
        var random = Math.random() * 1000;
        var iterator = 1;
        var date = new Date();
        date = date.getTime();
        // list all items with the game-action class
        $('.game-action').each(function(){
            // add stamp
            addStamp(this);
        });
        if(typeof itemToTag !== 'undefined'){
            addStamp(itemToTag, forceTagging);
        }
        /**
         * @method addStamp(caller)
         * Purpose will insert a data tag to all objects
         */
        function addStamp(caller, forceTagging){
            // only tag the elements that have no tag
            if(typeof $(caller).attr('data-u') == 'undefined' || (typeof forceTagging !== 'ndefined' && forceTagging == true)){

                // generates a stamp
                stamp = random * date * iterator;
                iterator = ++iterator;

                // insert the stamp to the html object
                $(caller).attr('data-u',stamp);

                // push in the closure array, so we can test later if the tag is valid
                gd.push(String(stamp));

            }
        }

    }

    /**
     * @method getUserInfo
     * Purpose: Ajax the backend to get the user info
     *
     * @version 1.0
     * @author Luc Martin at Clorox
     */
    function getUserInfo(){
        // Api for the user information
        var url = '/cwf-api/info/user/';

        console.log('REQUESTING USER INFO')
        // Do the ajax call to get user info
        $.ajax({
            'url':url,
            // handle success from API
            'success':function(response){
                handleUserInfo(response);
            },
            // error message in console if user info api fails
            error : function(e){
                console.log('userinfo error '+e)
                console.log(e)
            }
        // ajax cycle is done
        }).done(function(){
            console.log('Userinfo ajax DONE ')
        })
    }


    /**
     * @method handleUserInfo
     * Purpose: dispatch the result of an ajax call to the userinfo api
     * Reads the content of the cookie that stores the opt in
     *
     * dispatches a global event in the occurence of a user info
     */
    function handleUserInfo(response){
        console.log('INIT:: User info received!!! ',response)

        // build the response first with the ajax call
        var response = response.data;

        // test mode
        if(response == null && testMode == true){
            console.error('TESTING MODE handleUserInfo RESPONSE IS BEING SIMULATED!!! ')
            response = {'firstName':'Tester', 'lastName':'Testing G. Glad', 'email':'tester@glad.com'};
        }

        if(response !== null){

            // now we need to get the cookies for that game
            response.acceptedRules = {
                'hundredWays' : hundredWaysManagerBrain.hundredWaysAcceptedTheRules
            };
            $('.email-feedback').html(response.email);
            userInfo.gld_logged_in = true;
            userInfo.firstName = response.firstName;
            userInfo.lastName = response.lastName;
            userInfo.email = response.email;
            // pass that information to the ajaxUserForms.js

            $('body').trigger('user-info-received',[response]);
        }


    }

    function getPlayerId(){
         var testMode = ($urlQuery.urlParams.test == 'wincoupon' || $urlQuery.urlParams.test == 'wincard') ? 'success' : null;

        console.log('GETTING PLAYER INFORMATIONS FROM AJAX USING USER INFO ',userInfo)

        var url = '/cwf-api/100ways/getUserInfo';
        var data = {
            'email' : userInfo.email,
            'fname' : userInfo.firstName,
            'lname' : userInfo.lastName,
            //'testmode' : 'success'
        }
        console.log('REQUIRING THE PLAYER INFO, USING PARAMETERS: ',data)
        $.ajax({
            'url' : url,
            'data' : data,
            'success' : function(response){
                processUserInfoFromGaming(response);
            }
        })
    }

    /**
     * @method processUserInfoFromGaming
     * Purpose: Handle the user id from the gaming company
     *
     * @author Luc Martin
     */
    function processUserInfoFromGaming(response){

        console.log('PLAYER INFO RECEIVED!!! ',response);
        hundredWaysManagerBrain.playerId = response.data.profileid;

        if(typeof gameActionData !== 'undefined'){
            $(window).trigger('allowedGameAction', gameActionData);
        }

        $(window).trigger('100-ways-state-change');

        if(hundredWaysManagerBrain.hundredWaysAcceptedTheRules == true &&
            (hundredWaysManagerBrain.winState.prize !== 'lostToday' &&
            hundredWaysManagerBrain.winState.prize !== 'coupon' &&
            hundredWaysManagerBrain.winState.prize !== 'giftCard')){

            privateSetRedBannerState('already-started-wrapper');
        }

    }

    /**
     * @method privateSetRedBannerState
     * Purpose: hides and show the status bar (red)
     *
     * @param toShow String -- The class of the span to show in the red bar
     *
     * @author Luc Martin at Clorox
     * @version 1.0
     */
    function privateSetRedBannerState(toShow, description){
        ////;
        console.log('RESET THE RED BARS ',toShow)
        // reset the bars (hide all)
        $('span','.red-bar').addClass('hidden').removeClass('showing');

        // Show the right one
        $('.'+toShow,'.red-bar').removeClass('hidden').addClass('showing');

        if(description){
            $('.description','.red-bar '+'.'+toShow).html(description+'!<br/>').removeClass('hidden');
        }
    }

    /**
     * @method privateInitListeners
     * Purpose Initialize all listeners for the 100 ways page
     * @param {Object} target
     *
     * @author Luc Martin
     * @version 1.0
     */
    function privateInitListeners() {

        /**
         * Item click will expand the hidden content
         */
        $('body').on('click','.item-tile', function(e,flag){

            handleItemClick(this,flag);
            return false;
        });

         /**
         * @Listener ('body').on('user-info-received')
         * Purpose : Listen for asynchronious request response to the server if user is logged in
         * PRETTY MUCH THE HUB FOR THE GAME ACTIONS FOLLOWING
         *
         * @author Luc Martin at Clorox
         * @version 1.0
         */
        $('body').on('user-info-received', function(e, response){
            var expired = '';

            // Test if user has already registered
            if (typeof userInfo !== 'undefined' && userInfo.gld_logged_in == true) {

                // request the player ID from the game service
                getPlayerId(response);

                console.log('USER INFO HAS BEEN RECEIVED HUHUHU !!!',userInfo);
                ;
                // Restore the Brain using the user email
                restoreBrain();
                privateRestoreLove();

                // test if user has a win
                // Here we set the red banneers!!!
                if(hundredWaysManagerBrain.winState !== 'undefined' && hundredWaysManagerBrain.winState.prize !== ''){

                    var prize = hundredWaysManagerBrain.winState.prize;
                    var description = hundredWaysManagerBrain.winState.prizeData.prizedesc || null;
                    var d = new Date();

                    var todayMorning = Date.parse(new Date( d.getUTCFullYear(),d.getUTCMonth(),d.getUTCDate()));
                    expired = (Number(hundredWaysManagerBrain.winState.prizeData.expiration) < todayMorning) ;

                    switch(prize){
                        case 'coupon':
                            privateSetRedBannerState('win-coupon',description);
                        break;
                        case 'giftCard':
                            privateSetRedBannerState('win-gift-card',description);
                        break;
                        case 'lostToday':

                            if(!expired){
                                privateSetRedBannerState('no-win');
                            }else{

                                hundredWaysManagerBrain.winState.prizeData = {
                                    "prizeid": '',
                                    "prizedesc": 'expired',
                                    "prize" : 'nowin',
                                    'expiration': 'expired'
                                   };
                                   $(window).trigger('100-ways-state-change');
                                   privateSetRedBannerState('already-started-wrapper');
                            }

                        break;
                        case 'noWin':

                        break;
                    }

                }
                $(window).trigger('UserInfoProcessed',{'prize': prize, 'expired':expired, 'todayMorning':todayMorning});
            }else{

                privateSetRedBannerState('initial-state');
            }

        });


        $('body').on('mouseover', '.addthis_button_compact', function(){

            if($(this).hasClass('initDone')){
                return;
            }
            $('.addthis_button_compact').addClass('initDone');

            setTimeout(function(){

                $('#at20mc').find('a').each(function(){


                    if(($(this).attr('id') == 'atic_facebook' ||
                        $(this).attr('id') == 'atic_twitter' ||
                        $(this).attr('id') == 'atic_pinterest_share' ||
                        $(this).attr('id') == 'atic_google_plusone_share' ||
                        $(this).attr('id') == 'atic_stumbleupon' ||
                        $(this).attr('id') == 'atic_digg' ||
                        $(this).attr('id') == 'atic_delicious') && ! $(this).hasClass('game-action')){

                        if(!$(this).hasClass('game-action')){
                            $(this).addClass('game-action');
                        }
                        privateStampGameItems(this, true);
                    }
                })
            },1000);
        })
        /**
         * @Listener window scroll
         *
         * Purpose detects window scroll
         * @author Luc Martin
         * @version 1.0
         */
        $(window).on('scroll', function(){handleWindowScroll(this)});

        $('body').on('click','.claim-prize',function(){
            managePopupsAndBanners();
        })

        /**
         * the following listeners are tied to the events Richard's registration JS is dispatching
         * they are the following:
         * 'login.success'
         * 'login.error'
         *
         * 'registration.success'
         * 'registration.error'
         *
         * 'forgotpassword.success'
         * 'forgotpassword.error'
         *
         * 'profile.success'
         * 'profile.error'
         * 'registration.facebook'
         * 'fb.registration.success'
         * 'fb.registration.error'
         *
         * */
        var $win = $(window);

        $win.on('login.success', processLoginSuccess);
        $win.on('login.error',processLoginError);
        $win.on('registration.success',processRegistrationSuccess);
        $win.on('registration.error',processRegistrationError);
        $win.on('forgotpassword.success', processForgotPasswordSuccess);
        $win.on('forgotpassword.error', forgotPasswordError);
        $win.on('profile.success', processProfileSuccess);
        $win.on('profile.error', processProfileError);
        $win.on('registration.facebook', processRegistrationFacebook);
        $win.on('fb.registration.success', processFacebookRegistrationSuccess);
        $win.on('fb.registration.error', processFacebookRegistrationError);

        $('body').on('click','#termsOfUse2', function(){

            hundredWaysManagerBrain.hundredWaysAcceptedTheRules = $(this).is(':checked');
            $(window).trigger('100-ways-state-change');

        });

        $('body').on('click','#termsOfUse1', function(){

            hundredWaysManagerBrain.hundredWaysAcceptedTheRules = $(this).is(':checked');
            console.info('hundredWaysManagerBrain.hundredWaysAcceptedTheRules is CHECKED '+hundredWaysManagerBrain.hundredWaysAcceptedTheRules)
            $(window).trigger('100-ways-state-change');

        })

        // The first modal dialog where user decide to participate or not
        $('body').on('click', '.button-register', function(){
            closePopup(e);
            showPopup('.registration-login-portal');
            return false;
        })

        // User decide not to participate
        $('body').on('click','.button-skip-register', function(){
            handleSkipRegister();
            return false;
        })

        // close button for popup
        $('body').on('click', '.modal-fixed .close-popup-button, .close-loser-div', function(){
            console.log('CLOSE CLOSE!!!')
            closePopup(e);
            return false;
        })
        /**
         * @Listener click .final-win-feedback .button-red
         * clear the data when user claims his prize
         */
         $('body').on('click','.final-win-feedback .button-red,.final-win-feedback .show-coupon', function(){
             console.log('PRIZE CLAIMED')
            hundredWaysManagerBrain.winState.prizeData.prizedesc = 'claimed';
            hundredWaysManagerBrain.winState.prizeData.expiration = Date.parse(Date('now'));
            $(window).trigger('100-ways-state-change');
        })

        /*
         * clear msgs on submit user form
         */
        $('#loginForm,#forgotPasswordForm,#regForm').submit(function(){
            $('.msg',this).empty();
        });

        /*
         * show forgot password form from login form
         */
        $('#loginForm #forgot_pw').on('click',function(){
            $('.forgot_pw_panel').removeClass('hidden').show();
            $('.login_panel #loginForm').hide();
            $('.sign-in-fb').hide();
        });

        /*
         * return to login from from forgot password form
         */
        $('#back-to-login').on('click',function(){
            $('.forgot_pw_panel').hide();
             $('.login_panel #loginForm').show();
            $('.sign-in-fb').show();
        });

        /**
         * closes the expanded content
         */
        $('body').on('click','.close-button', function(e,flag){
            handleCloseButtonClick(e,this,flag);
        })

        /**
         * Listen to the red CTA designers have added to the video hidden content
         * triggers the .video
         */
        $('body').on('click','.video-cta', function(){
            var parent = $(this).closest('.content-main');
            console.log(parent);
            $('.video', parent).trigger('click');
        })

         /**
          * @ Listener click.game-action
         *  Set Listeners for user allowed interactions
         *  Verify if the game-action stamp is registered in the array of allowed stamps
         *  Verify if the action has not been performed before
         *  Push allowed action in used action
         *  Trigger an allowedInteraction event to the global window
         *
         * @author Luc Martin at Clorox
         * @version 1.0
         */
        $('body').on('click','.game-action', function(){
            console.log('$$$ game action class item clicked! $$$')
           privateHandleGameAction(this);
        });



        /**
         * @Listener $win.allowedGameAction
         * Purpose Asynchronious respond to a user action on game
         * Send a ajax request to the server
         * process call back and dispatch the response
         *
         * @author Luc Martin
         * @version 1.0
         */
        $win.on('allowedGameAction', function(e,data){

            console.log('GAME ACTION LISTENER');

            if(typeof data == 'undefined'){
                console.log('$$$ GAME ACTION HAS NO DATA :: UNUSABLE $$$');
                return;
            }

            gameActionData = data;

            // User needs to be registered and logged in to get prize
            // However if user has not set the skip registration to true, user will be prompted to the start of the game
            if ((typeof userInfo == 'undefined' || userInfo.gld_logged_in == false) && hundredWaysManagerBrain.userSkipRegistration !== true){

                console.log('$$$$$ User is not registered and have NOT set the play option to skip registration $$$$$')
                startGamePre();
                return;

            }else if((typeof userInfo == 'undefined' || userInfo.gld_logged_in == false) && hundredWaysManagerBrain.userSkipRegistration == true){

                // User is not registered and is skipping registration
                console.log('$$$$$ User decided to skip registration $$$$$')
                return;

            }else if((typeof userInfo !== 'undefined' || userInfo.gld_logged_in == true)  && hundredWaysManagerBrain.hundredWaysAcceptedTheRules == false){

                hundredWaysManagerBrain.winState.gameStarted = true;

                startGamePre()
                return

            }

            // User is registred and game is running
            console.log('$$$$$  GAME EVENT $$$$$');

            // u stands for unique LOL (I don't use single letters so often... I know I know they suck...)
            var u = data.u;

            // just making sure we have a valid event
            var allowed = ($.inArray(u, gd) > -1 && $.inArray(u, us) > -1 ) ? true : false;

            console.log('$$$ GAME EVENT IS LEGAL USER IS REGISTERED GETTING DATA FROM SERVER $$$ ',data);

            hundredWaysManagerBrain.winState.gameActions += 1;
            $($win).trigger('100-ways-state-change');

            var url = '/cwf-api/100ways/recordAction';
            var data = {
                'profileid' : hundredWaysManagerBrain.playerId,
                'testmode' : $urlQuery.urlParams.test
             }
            console.log('THIS IS THE GAME ACTION RECORD AJAX DATA: ',data);
            $.ajax({
                'url' : url,
                'data' : data,
                'success' : function(response){
                        processGameActionResponse(response);
                    }

            })
        })

        $('body').on('click','.love', function(event){
            handleLoveClick(this);
            event.preventDefault();
            return false;
        })

        $('body').on('click','.get-started', function(){
            // Two things can happen if the user clicks the get Started button
            startGamePre();
        })

        $('body').on('click','.get-started-confirmed', function(){

            handleGetStartedConfirmed();


        })

        $('body').on('click','.button-play', function(){
            verifyEmailAndOptin();
        })


        /**
         * @Listener '100-ways-state-change'
         * purpose; saves the state of affaire during game play
         *
         * @author Richard and Luc
         * @version 1.0
         */
        $($win).on('100-ways-state-change',function(e, data){
            // get the name and value of the state from the event
            var name = (typeof data !== 'undefined' && typeof data.name !== 'undefined') ? data.name : 'init';
            var value = (typeof data !== 'undefined' && typeof data.value !== 'undefined') ? data.value :'init';

            // set the brain
            if(name !== 'init'){
                hundredWaysManagerBrain.winState = name;
            }

            var cookieName = userInfo.email.replace('@','at').replace('.','dot')+'_100-ways-state' || '100-ways-state';

            console.log('&& CREATING COOKIES FOR THE BRAIN && ',cookieName)
            // save brain as cookie
            ;

            cookieMonster.createCookie({
                name: cookieName,
                value: JSON.stringify(hundredWaysManagerBrain)
            });

        });

        /**
         * @Listener 'click','.get-gift-card'
         * Purpose:
         *
         * @author Luc Martin at Clorox.com
         * @version 1.0
         */
        $('body').on('click','.get-gift-card',function(){
            handleGetGiftCardClick(this);
            return false;
        })
        $('body').on('click','.get-coupon-verify',function(){
            handleGetGiftCardClick(this);
            return false;
        })
        $('body').on('click','.get-coupon',function(){
            clearBrainfromPrizes();
            closePopup();
        })




    }

    /**
        * @method handleLoveClick(this)
        * Purpose: Handle a love this item clic
        * creates cookie for the love items
        *
        * @author Luc Martin at Clorox.com
        * @version 1.0
        */
        function handleLoveClick(caller){
            // Prep the brain
            if(typeof hundredWaysManagerBrain.loved == 'undefined'){
                hundredWaysManagerBrain.loved = [];
            }
            ////;
            var urlTitle = $(caller).attr('data-urltitle');

            if($(caller).hasClass('loved')){
                $('.loved').each(function(){
                    if($(this).attr('data-urltitle') == $(caller).attr('data-urltitle')){
                        $(this).removeClass('loved').addClass('game-action');
                    }
                })
                $(caller).removeClass('loved').addClass('game-action');
                var index = $.inArray( urlTitle, hundredWaysManagerBrain.loved );
                hundredWaysManagerBrain.loved[index] = 'deleted';

            }else{
                $('.love').each(function(){
                    if($(this).attr('data-urltitle') == $(caller).attr('data-urltitle')){
                        $(this).css('color','red')
                        $(this).addClass('loved').removeClass('game-action');
                    }
                })
                $(caller).addClass('loved').removeClass('game-action');
                $('.modal-fixed').css('top',Number(pos+65)+'px');
                hundredWaysManagerBrain.loved.push(urlTitle);
            }

            $(window).trigger('100-ways-state-change');
        }


        /**
         * @method handleGetGiftCardClick
         * Purpose: handle the submit from the ticket win confirmation dialog
         * updates the values of the first name and last name + zip
         *
         * @author Luc Martin
         * @version 1.0
         */
        function handleGetGiftCardClick(caller){
            var parent = $(caller).closest('.modal-controls');
            //console.log($('#firstName', parent).text())

            // trigger this only when  the submit red button listener is pushed
            var data = {
                        'profileid':hundredWaysManagerBrain.playerId,
                        'prizeid':hundredWaysManagerBrain.winState.prizeData.prizeid,

                        'fname': $('#firstName', parent).val(),
                        'lname' : $('#lastName', parent).val(),
                        'zipcode' : $('#zip', parent).val(),
                    }
            userInfo.firstName = $('#firstName', parent).val();
            userInfo.lastName = $('#lastName', parent).val();
            updateWinner(data);
        }

        /**
         * @Listener 'handleSkipRegister'
         * purpose; saves the state of affaire during game play
         *
         * @author Richard and Luc
         * @version 1.0
         */
        function handleSkipRegister(){
            closePopup(e);
            hundredWaysManagerBrain.userSkipRegistration = true;
            hundredWaysManagerBrain.winState.gameStarted = false;
            $(window).trigger('100-ways-state-change');
        }
        /**
         * handlers for the registration
         */
        var loginProcessed = false;
        function processLoginSuccess(e,args){
            if(loginProcessed == true) return;
            if(typeof e !== 'undefined') console.log(e);
            //if(typeof args !== 'undefined') console.log(args);
            console.log('processLoginSuccess');

            getUserInfo();
            if( $('.registration-login-portal').hasClass('popup-showing')){
                $('.close-popup-button', '.registration-login-portal').trigger('click');
            }
            loginProcessed = true;
            //verifyEmailAndOptin();
        }
        function processLoginError(e,args){
            if(typeof e !== 'undefined') console.log(e);
            if(typeof args !== 'undefined') console.log(args);
            console.log('processLoginError')
            $('.msg',"#loginForm").text(args);
        }
        var registrationProcessed = false;
        function processRegistrationSuccess(e,args){
            if(registrationProcessed == true) return;
            if(typeof e !== 'undefined') console.log(e);
            //if(typeof args !== 'undefined') console.log(args);

            console.log('processRegistrationSuccess');
            getUserInfo();
            if( $('.registration-login-portal').hasClass('popup-showing')){
                $('.close-popup-button', '.registration-login-portal').trigger('click');
            }
            $('.get-started-confirmed').trigger('click');
            registrationProcessed = true;
            //verifyEmailAndOptin('registrationSuccess');
        }
        function processRegistrationError(e,args){
            if(testMode == true){
                ;
                testModeRegistration = true;
                console.error('Registration error override !!!')
                console.log('************** TEST MODE ENABLED TESTING THE REGISTRATION WITH FAKE DATA **************************')
                var args = {'firstName':'Luc', 'lastname':'Martin', 'email':'testing@glad.com'};
                processRegistrationSuccess(e,args);
            }
            if(typeof e !== 'undefined') console.log(e);
            if(typeof args !== 'undefined') console.log(args);
            console.log('processRegistrationError')
            $('.msg',"#regForm").text(args);
        }
        function processForgotPasswordSuccess(e,args){
            if(typeof e !== 'undefined') console.log(e);
            if(typeof args !== 'undefined') console.log(args);
            console.log('processForgotPasswordSuccess')
            $('.msg',"#forgotPasswordForm").text(args);
        }
        function forgotPasswordError(e,args){
            if(typeof e !== 'undefined') console.log(e);
            if(typeof args !== 'undefined') console.log(args);
            console.log('forgotPasswordError')
            $('.msg',"#forgotPasswordForm").text(args);
        }
        function processProfileSuccess(e,args){
            if(typeof e !== 'undefined') console.log(e);
            if(typeof args !== 'undefined') console.log(args);
            console.log('processProfileSuccess')
        }
        function processProfileError(e,args){
            if(typeof e !== 'undefined') console.log(e);
            if(typeof args !== 'undefined') console.log(args);
            console.log('processProfileError')
        }
        function processRegistrationFacebook(e,args){
            if(typeof e !== 'undefined') console.log(e);
            //if(typeof args !== 'undefined') console.log(args);
            console.log('processRegistrationFacebook')
            $('#fbRegForm').removeClass('hidden').show();
            $('#regForm').hide();
        }
        function processFacebookRegistrationSuccess(e,args){
            if(typeof e !== 'undefined') console.log(e);
            //if(typeof args !== 'undefined') console.log(args);
            console.log('processFacebookRegistrationSuccess');
            processRegistrationSuccess(e,args);
            //getUserInfo();
        }
        function processFacebookRegistrationError(e,args){
            if(typeof e !== 'undefined') console.log(e);
            if(typeof args !== 'undefined') console.log(args);
            console.log('processFacebookRegistrationError');
            $('.msg',"#fbRegForm").text(args);
        }

        /**
         * @method showHideDivs
         * Purpose: show and hides modals divs
         *
         * @param toShow String the div to show
         *
         * @author Luc Martin at Clorox
         * @version 1.0
         */
        function showHideDivs(toShow){
            $('.modal.visible').removeClass('visible').addClass('.hidden');
            $('.modal'+toShow).removeClass('hidden').addClass('visible');
        }


        /**
         * @method handleCloseButtonClick
         * Purpose: handler for the close button located in the modal dialogs
         *
         * @param e the event object
         * @param target the div where the close button has beeen trigerred
         */
        function handleCloseButtonClick(e,target,flag){
            var $toHide = $(target).closest('.row.item-detail');
            $toHide.animate({
                'height':0,
            },
            1000,
            function(){
                $toHide.html('');
            });

            // call item.open with no param for closed
            if (flag !== 'nonuser') {
                $(document).trigger('item.open');
            }
        }

        /**
         * @method closePopup
         * purpose closes the popup dhuuu
         */
        function closePopup(){
            $('.popup-showing').removeClass('popup-showing').addClass('hidden')
        }

    function handleGetStartedConfirmed(){

        var registerTermsOfUseChecked = $('#termsOfUse2', '.register-panel').is(':checked');
        var registerOptInChecked = $('.brandOptIn', '.register-panel').is(':checked');

        // Two things can happen if the user clicks the get Started button
        var mailingListChecked = $('.agree-mailing-list','.email-confirmation-wrapper').is(':checked');
        var termOfUseChecked = $('.agree-official-rules','.email-confirmation-wrapper').is(':checked');

        // test if either one of the check box is checked in the register dialog
        if(termOfUseChecked == true || registerTermsOfUseChecked == true){
            // Update the brain
            hundredWaysManagerBrain.hundredWaysAcceptedTheRules = true;
            hundredWaysManagerBrain.winState.gameStarted = true;

            // write cookie
            $(window).trigger('100-ways-state-change');

            // change the red banneer message
            privateSetRedBannerState('already-started-wrapper');

            // closes the dialog
            closePopup(e);

        }else{
            // User needs to accept the rules
            $('.msg','.agree-official-rules-wrapper').html('<strong>Do you agree to the rules?</strong>');

            // set the brain state
            hundredWaysManagerBrain.hundredWaysAcceptedTheRules = false;

            // write cookie
            $(window).trigger('100-ways-state-change');
        }

        // the other dialog also has opt in
        // the is the dialog if user
        if(mailingListChecked == true || registerOptInChecked == true){

            //send OPT In  to backend
            $('#promoForm').submit(); // send to server

            hundredWaysManagerBrain.hundredWaysOptedEmails = true;
            $(window).trigger('100-ways-state-change');
        }else{
            hundredWaysManagerBrain.hundredWaysOptedEmails = false;
            $(window).trigger('100-ways-state-change');
        }

        if( termOfUseChecked && typeof hundredWaysManagerBrain.winState.gameStarted !== undefined){
            console.log('USER IS HAVING A GAME ACTION PENDING')
            $(window).trigger('GameActionPending', gameActionData);
            $(window).trigger('allowedGameAction', gameActionData);
            gameActionData = '';
        }
    }

    /**
     * @method processGameActionResponse
     * Purpose this is the game action last step before getting into the local machine
     * Will analyse the response from the Gaming Service
     * Will define the state of winnning
     *
     * @author Luc Martin at Clorox
     * @version 1.0
     */
    function processGameActionResponse(response){

        // first did the player played?
        console.log('GAME ACTION RESPONSE ', response);

        var winner = response.data.win;
        var coupon = response.data.iscoupon;
        var loser = (response.data.played == true && winner == false) ? true : false;
        var now = new Date();

        if(loser == true){
            privateSetRedBannerState('no-win');
            showPopup('.lose-feedback');
        }

        // defines the type of prize
        var prize = (winner == true && coupon == false) ? 'giftCard' : (winner == true && coupon == true) ? 'coupon' : (loser == true) ? 'lostToday': 'noWin';

        // set the brain
        hundredWaysManagerBrain.winState.prize = prize;

        hundredWaysManagerBrain.winState.prizeData = {
                        "prizeid": response.data.prizeid,
                        "prizedesc": response.data.prizedesc,
                        "prize" : prize,
                        'expiration': Date.parse(Date('now'))
                };
        $(window).trigger('100-ways-state-change');

        managePopupsAndBanners(prize);
        $(window).trigger('processGameActionResponse',prize);

    }

    function managePopupsAndBanners(){

        var prize = hundredWaysManagerBrain.winState.prize;

        $('#firstName','.win-feedback').val(userInfo.firstName);
        $('#lastName','.win-feedback').val(userInfo.lastName);
        $('#zip','.win-feedback').val(userInfo.zipcode);
        $('.email-populated','.win-feedback').html(userInfo.email);

        switch(prize){
            case 'coupon':
                // If the prize is a coupon we process the win right away
                privateSetRedBannerState('win-coupon',hundredWaysManagerBrain.winState.prizeData.prizedesc);
                $('.win-data','.win-coupon-wrapper').html(hundredWaysManagerBrain.winState.prizeData.prizedesc);
                showPopup('.win-feedback', ['.win-coupon-wrapper','.show-coupon-verify', '.win-card-input', '.get-coupon-verify']);

            break;
            case 'giftCard':
                // if it's a card we need one more step from our user
                $('.get-gift-card').attr('data-prizeid', hundredWaysManagerBrain.winState.prizeData.prizeid);

                privateSetRedBannerState('win-gift-card',hundredWaysManagerBrain.winState.prizeData.prizedesc);
                $('.win-data','.win-gift-card-wrapper').html(hundredWaysManagerBrain.winState.prizeData.prizedesc);
                showPopup('.win-feedback', ['.win-gift-card-wrapper','.show-gift-card', '.win-card-input', '.get-gift-card']);

            break;
            case 'lostToday':
                privateSetRedBannerState('no-win');
            break;
        }
        $(window).trigger('testManagePopupsAndBanners',prize);
    }

    /**
     * @method processWin(data)
     * Purpose: Process the win
     *
     * @author Luc Martin
     * @version 1.0
     */
     function updateWinner(userData){

         var testMode = ($urlQuery.urlParams.test == 'wincoupon' || $urlQuery.urlParams.test == 'wincard') ? true : false;
         var url = '/cwf-api/100ways/updateWinner';
         var data = userData || {
                        'profileid':hundredWaysManagerBrain.playerId,
                        'prizeid':hundredWaysManagerBrain.winState.prizeData.prizeid,
                        'fname': userInfo.firstName,
                        'lname' : userInfo.lastName,
                        'testmode' : testMode
                };
         console.log('GETTING PRICE DISTRIBUTION READY TO GO ',data);
         $(window).trigger('updateWinner',data);

         // #############################################  THIS IS FOR TESTING PURPOSE
         var ret;
         if($urlQuery.urlParams.test == 'wincoupon'){
             ret =  {
              "status":true,
              "message":"updateWinner",
              "data":{
                "couponurl":"http:\/\/bricks.coupons.com\/enable.asp?o=117429&c=GT&p=3&cpt=ubuyZvuPyFL2SGodNxWQ",
                "prizedesc":"$3 off GLAD products"
                }
              }
         }else if($urlQuery.urlParams.test == 'wincard'){
             ret = {
                  "status":true,
                  "message":"updateWinner",
                  "data":null
                };
          }
          if(testMode == true){
              processPrizeDistribution(ret);
              return
          }

          // ############################################## END TEST

         $.ajax({
             'url':url,
             'data' : data,
             'success' : function(response){
                 processPrizeDistribution(response);
             }
         })

     }

    /**
     * @method processPrizeDistribution
     * @purpose: callback after prize request when a user has won.
     *
     * @author Luc Martin
     * @version 1.0
     */
    function processPrizeDistribution(response){

        //console.info(response)
        console.log('$$$$$  PROCESSING PRICE DISTRIBUTION $$$$$',response);

        $(window).trigger('testProcessPrizeDistribution',response);

        if(response.data && response.data.couponurl){

            // Its a coupon, we need to save the coupon url as cookie
            hundredWaysManagerBrain.winState.prizeData['couponUrl'] = response.data.couponurl;

            // Change the red Banner
            privateSetRedBannerState('win-coupon',hundredWaysManagerBrain.winState.prizeData.prizedesc);

            // populate the coupon claim div
            $('.show-coupon').attr('href',response.data.couponurl);
            $('.couponDescription').html(hundredWaysManagerBrain.winState.prizeData.prizedesc)

            // shows all the coupon claim div and controls
            showPopup('.win-feedback', ['.win-coupon-wrapper', '.show-coupon']);

        }
        // the win is a card
        else if(response.status == true && response.message == 'updateWinner'){

            //TODO update the coupon info from the real prize
            // shows all the gift card controls

            $('.email-feedback','.final-win-feedback').html(userInfo.email);
            showPopup('.final-win-feedback');
            clearBrainfromPrizes();

        }
        $(window).trigger('100-ways-state-change');
    }

    /**
     * @method startGame
     * purpose Handles the beginning the game
     * Test if User has opted to the rules or not
     * If user has opted, will show the opt in dialog
     *
     */
    function startGamePre(){

        if(!gld_logged_in && testMode == true && typeof testModeRegistration !== 'undefined'){
            console.log('USER IS NOT LOGGED IN BUT TEST MODE IS ON SO SIMULATING USER LOGGED IN!');
            gld_logged_in = true;
        }
        // Test if the user is already logged in
        if (!gld_logged_in) {

            console.log('USER IS NOT LOGGED IN!!!  GAME PRE registration')
            showPopup('.registration-pre');
            return false;
        }
        // User is logged in but did user opted in the rules?
        else if(userInfo){
            ;
            var optedToRules = verifyEmailAndOptin();
            if(optedToRules == 'accepted all'){
                $('.get-started-confirmed').trigger('click');
            }else{
                showPopup('.email-confirmation-wrapper');
                $('.hide-if-logged-in').hide().addClass('hidden');
                $('.show-if-logged-in').show().removeClass('hidden');

            }
        }
    }

    /**
     * @method  verifyEmailAndOptin()
     * Purpose: Verify if User has accepted the rules already
     * verify the email
     * starts the game
     *
     * @author Luc Martin at Clorox
     *
     * @version 1.0
     */
    function verifyEmailAndOptin(registrationSuccess){

        // reset the value of the skip registration button
        // user is now registered
        hundredWaysManagerBrain.userSkipRegistration = false;
        $(window).trigger('100-ways-state-change');

        // check if user has already opted in
        var acceptedRules = hundredWaysManagerBrain.hundredWaysAcceptedTheRules;
        var optInEmail = hundredWaysManagerBrain.hundredWaysOptedEmails;

        if(acceptedRules){
            $('.agree-official-rules','.email-confirmation-wrapper').attr('checked',true);
        }
        if(optInEmail){
            $('.agree-mailing-list','.email-confirmation-wrapper').attr('checked',true);
        }
        // If user has already accepted rules and Opt in, we just jump to the game play
        if(acceptedRules && optInEmail || typeof registrationSuccess !== 'undefined'){
            return 'accepted all';
            //$('.get-started-confirmed').trigger('click');
        }else if(acceptedRules){
            return 'rules accepted';
        }else{
            return false;
        }

    }


    /**
     * @method showPopup
     * Purpose: show a popup
     *
     * @author Luc Martin
     * @version 1.0
     */
    function showPopup(name, controls) {
        setTimeout(function(){
            // first cleanup
            $('.popup-showing').addClass('hidden').removeClass('popup-showing');
            $('.control-showing').addClass('hidden').removeClass('control-showing');

            console.log('Showing Hidden div :: '+name)

            // the registration overlay
            $('.overlay-wrapper').removeClass('hidden').addClass('popup-showing');

            // the gray background
            $('.overlay-background').removeClass('hidden').addClass('popup-showing');

            // show the div
            $(name).removeClass('hidden').show().addClass('popup-showing');

            // show/hide soccer welcome (registration-pre) based on url
            if (History.getState().hash.indexOf('entertainment-will-it-fit-world-cup') !== -1) {
                $('.soccer-welcome').removeClass('hidden');
            } else {
                $('.soccer-welcome').addClass('hidden');
            }


            if(typeof controls !== 'undefined'){
                for(var n = 0; n < controls.length; ++n){
                    var control = controls[n];
                    $(control, name).removeClass('hidden').addClass('control-showing');
                }
            }

            var pos = $(window).scrollTop();
            $(window).trigger('popupUpPositioning',{'name':name, 'pos':pos});
            $(name).animate({'top': Number(pos - 100)+'px'});

        },500);
    };

    /**
     * @method privateHandleGameAction
     * Purpose: Handles the game actions
     * test if that action has been performed in the same session
     * test if the action is in hte list of registered actions
     *
     * @author Luc Martin at Clorox
     * @version 1.0
     */
    function privateHandleGameAction(caller){
        // Is it a non user click?
        // meaning loaded at page load by Richard url load function
        if(nonuser == true){
            nonuser = false;
            return;
        }
        // unique id for the html entity that triggers the game action
        var u = $(caller).attr('data-u');

        // Test if the game-action for that object is allowed
        var allowed = ($.inArray(u, gd) > -1 && $.inArray(u, us) == -1) ? true : false;
        var reason = ($.inArray(u, gd) == -1) ? 'Action Unique id not registered' : ($.inArray(u, us) > -1) ? 'Action already used.' : 'legal';

        console.log('$$$ IS THAT GAME ACTION ALLOWED ??? '+allowed, reason+' $$$')
        // filter the valid from the unvalid actions
        if(allowed){

            // set that action as being already performed
            us.push(u);

            // dispatch event NOTE: this can be hacked by generating a event dispatcher
            $(window).trigger('allowedGameAction', { 'trigger':this, 'type' : $(caller).attr('data-game-action-type'), 'u' : $(caller).attr('data-u')});
        }else{
            $(window).trigger('GameActionDenied',reason);
        }
    }

    /**
     * @method handleItemClick
     * Purpose Call back for the 100-ways-item click
     * @param {Object} target
     *
     * @author Luc Martin
     * @version 1.0
     */
    function handleItemClick(target,flag){
        // test for non user tag

        nonuser = (typeof nonuser == 'undefined' && typeof flag !== 'undefined' && flag == 'nonuser' ) ? true : false;
        console.log('NON USER HAS BEEN SET TO '+nonuser);

        // the final size of the expanded row
        var expandedRowHeight = '655px';

        var serialNumber = $('a',target).first().attr('data-u');

        // get the parent of the clicked item
        var parentRow = $(target).closest('.row');

        var parent = $(target).closest('section');

        // every different item has a specific class, that may have some specific behavior
        var parentClass = $(parent).attr('class').replace('hundred-ways-item-wrapper ','');

        // the target row for the expanded content
        var detailRow = $(parentRow).parent().find('.row.item-detail').first();

        // contract the row if it's already expanded'
        if(typeof $(detailRow).attr('data-u') !== 'undefined' && $(detailRow).attr('data-u') == serialNumber){
            $(detailRow).animate({'height':'0px'},500).removeClass('expanded').attr('data-u',null).html('');
            return false;
        }

        $(detailRow).attr('data-u',serialNumber);


        // check if there is a data-newheight on the target item, if not default to the preset height
        var newHeight = $(target).attr('data-newheight')  || expandedRowHeight;

        // hidden content to show
        var detailContent = $(target).next('.content').html();

        // prepare for the auto scroll
        var itemPos = $(target).offset();
        var itemTop = itemPos.top;
        var itemHeight = $(parentRow).css('height').replace('px','');

        // the scroll to position
        scrollTo = Number(itemTop) + Number(itemHeight);

        // is there as slide show (cycle-2)
        var slideShow = ($(parent).children().find('.carousel').first().length > 0) ? $(parent).children().find('.carousel').first() : 'no Slide Show';

        // Is it a youtube?
        var videoYoutube = ($(parent).children().find('.hundred-ways-youtube-item-content').first().length > 0) ? $(parent).children().find('.hundred-ways-youtube-item-content').first() : 'no youtube';

        // Is it a quiz?
        var quiz = ($(parent).children().find('.hundred-ways-quiz-with-result-item-content').first().length > 0) ? $(parent).children().find('.hundred-ways-quiz-with-result-item-content').first() : 'no quiz';

        // cleanup and close any already expanded row
        $('.expanded').html('').css('height',0)

        // preserve the new content into the closure
        actualShowingItemContent = detailContent;

        // stuff the expandable row with the content
        $(detailRow).html(detailContent);
        $(window).trigger('testItemDetailClickProcess1',{'parentRow':parentRow, 'parent':parent, 'detailRow':detailRow});
        // addthis button init
        addthis.button(detailRow.find('.add-this-trigger')[0])

        // initialize cycle if necessary
        if(slideShow !== 'no Slide Show'){
            console.log('Initialize the slide show '+slideShow);
            $(detailRow).find('div.carousel').first().attr('id','actualSlideShow');
            privateInitSlideShow('#actualSlideShow');
        }

        // handle you tube if required
        if(videoYoutube !== 'no youtube'){

            // finds the trigger class
            var youtubeTrigger = $(detailRow).children().find('.video');

            // the video id is in the html tag
            var playerId = $(youtubeTrigger).attr('data-videoplayerid');

            // Finds the player div
            $('#videoPlayer', detailRow).attr('id', playerId);

            // initialize the video
            //console.log('playerId '+playerId )
            privateInitVideo({'videoPlayerId':playerId});
        }

        // handle the quiz
        if(quiz !== 'no quiz'){
           quizManager.processQuizSolveList(quiz);
        }

        //eye candy
        $(detailRow).animate({
            'height' : newHeight
        },1000,function(){
            $('html,body').animate({
                scrollTop: $(detailRow).offset().top - 65
            },500,function(){
                var top = $(window).scrollTop();
                $('.modal-fixed.popup-showing').css('top', Number(top - 65)+'px');
            });
        }).addClass('expanded');

        // event for url updates
        if (flag !== 'nonuser') {
            var data = $('.cta',target).data();
            $(document).trigger('item.open',[data['urltitle'],data['metatitle']]);
        }else{
            // add data type no action
        }
    }

    /**
     * @method handleWindowScroll
     * Purpose Call back for the window scrolling
     * @param {Object} target
     *
     * @author Luc Martin
     * @version 1.0
     */
    function handleWindowScroll(target) {

        // the scroll position
        pos = $(target).scrollTop();
        //console.log('POSITION :: ',pos)
        //console.log('phase '+currentPhase+ ' position '+ pos+ 'currentPhase '+currentPhase+' totalPhases '+totalPhases+' moreQuestions '+moreQuestions)
        // 340 is the height where the keep narrowing needs to start showing

            // window is at the top
            if( pos < 380){
                $('.red-bar').removeClass('fixed');
            }else{
                $('.red-bar').not('.fixed').addClass('fixed');
            }
    }

    /**
     * @method getMostRecentWinners
     * purpose: Get the last winners from the server using Ajax
     *
     * @author Luc Martin
     * @version 1.0
     */
    function privateGetMostRecentWinners() {

        console.log('INIT:: ajax getting most recent winners');

        var url = '/cwf-api/100ways/getWinners/';
        $.ajax(
            {
                'url' : url,
                'dataType' : "json",
                'success' : handleMostRecentWinners,
                'error' : function(e){
                    console.error(e)
                    console.log('DIDN\'T GET THE WINNERS')
                }
            }
        )
    }

    /**
     * @method handleMostRecentWinners
     * purpose: Handles ajax response for the mostRecentWinners ajax request
     *
     * @author Luc Martin
     * @version 1.0
     */
    function handleMostRecentWinners(response){
        //console.log("AJAX RESPONSE most recent winners ",response.data);

        // extract all the winners
        var allWinners = response.data;
        var ret = '';

        // build UI
        for (var n in allWinners){
            var winnerType = allWinners[n];
            for(var m in winnerType){
                ret += '<li class="winner-item"><span class="vertical-line"></span>'
                var winner = winnerType[m];
               // console.log('WINNER ',winner);
                var first_name = winner.first_name;
                var last_initial = winner.last_initial;
                var prize_name = winner.prize_name;
                ret += '<span class="winner-name">'+first_name+' '+last_initial+'</span><span class="winner-prize">'+prize_name+'</span></li>';
            }
        }

        // setting the html content of the div
        if(typeof ret == 'undefined') return;
        $('.winners-wrapper').html(ret);

    }




    /**
     * @method privateInitVideo(args)
     * To setup the youtube API
     *
     * @params args Object
     * @author Luc Martin
     */
    function privateInitVideo(args) {

        // video wrapper defines the size of the generated I-frame
        var videoWrapperClass = args.videoWrapperClass || '.videoWrapper' ;

        // the player
        var videoPlayerId =  args.videoPlayerId || 'videoPlayer' ;

        // autoplay default to false
        var autoPlay = args.autoPlay || false;

        // is the first image hidden default to visible
        var hideFirstImage = args.hideFirstImage || false;

        // the class of the image to show after the video is done
        var postImageClass = args.postImage || '.postImage';

        // the trigger that setup the video id
        var triggerClass = args.triggerClass || '.video';

        // not used yet TODO add the play function
        var playOnClick = args.playOnClick || true;

        console.log('INIT VIDEO ' + videoPlayerId);

        // hides the first image
        $('body').on('click', '.video', function(e) {
            console.info(e);
            $(e.currentTarget).fadeOut(1000,function(){
                $('.video.hidden').removeClass('hidden');
                //$(this).addClass('hidden');
            })

        });

        // measure the iframe
        var videoWidth = $(videoWrapperClass).width();
        var videoHeight = videoWidth / (713 / 434);

        // setup the plugin
        $('.video').youtubewrapper({
            targetId : videoPlayerId,
            height : videoHeight,
            width : videoWidth,
            videoId : 'data-videoid',
            modestbranding : 1,
            rel : 0,
            showinfo : 0,
            autoplay : 0,
            postImage:postImageClass,
            dataLayerEventName : 'video_click-to-play_click',
            events : {
                'onStateChange' : function() {
                    onPlayerStateChange();
                }
            },
            afterClick : function(e, args) {
                console.info('AfterClick functions running');

                $('#videoTitle').html($(e).attr('data-headline'));
                $('#videoDescription').html($(e).attr('data-description'));
            }
        });

    }

    // call back on player state change
    function onPlayerStateChange(){
        console.log('Player State changed')
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
    function privateInitSlideShow(id){

        console.log('INIT SLIDE SHOW >>>>> ');

        // the slideshow controls
        var arrowLeft = $(id).parent().find('.previous');
        var arrowRight = $(id).parent().find('.next');

        // the pager controls
        var pager = $(id).attr('data-cycle-pager');


        // Init the slideshow
        $(id).cycle({
            'timeout': 0,
            'slides':'> .slide',
            'prev':arrowLeft,
            'next':arrowRight,
            'fx':'scrollHorz',
            'cleartypeNoBg':true,
            'pager':pager,
            'pagerActiveClass':'activeSlide',
            'autoHeight':'calc',
            'allowWrap':false

        });

        // call back after a slide is being shown
        $(id).on('cycle-before', function(e, currSlideElement, nextSlideElement, options, forwardFlag){
            console.log(currSlideElement)

            var width = $(nextSlideElement).find('img').width();
            var parent = $(nextSlideElement).closest('.carousel').parent();

            $('.carousel-controls',parent).css('width','100%').css('left','-4px');
            $('.previous',parent);

            //console.log(nextSlideElement)
            //console.log(options)
            console.log(forwardFlag)
            var eventName = id.replace('#','').replace('.','');
            // propagate the event to the all page
            $('body').trigger('cycle-after_'+eventName, forwardFlag);

            // local function for the call back
        });



        return;

    }

    /**
     * public functions for the hundredWaysManager
     *
     * @author Luc Martin at Clorox
     * @version 1.0
     */
    return {
        'getBrain':  function(){return  hundredWaysManagerBrain},
        'killBrain': function(){
            privateKillBrain();
        },
        'initFirstState' : function(){
            privateInitFirstState();
        },
        'stampGameItem' : function(itemToTag){
            privateStampGameItems(itemToTag);
        },
        'handleGameAction' : function(caller){
            privateHandleGameAction(caller);
        },
        'testListeners' : function(toTest,args){
            $(window).trigger(toTest,['some parameters to test']);
        },
        'initListeners' : function() {
            privateInitListeners();
        },
        'getMostRecentWinners' : function(){
            privateGetMostRecentWinners();
        },
        'initVideo' : function(args){
            privateInitVideo(args)
        },
        'initSlideShow': function(className){
            privateInitSlideShow(className)
        },
        'restoreLove' : function(){
            privateRestoreLove();
        },
        'setNonuser' : function(args){
            if(typeof nonuser == 'undefined'){
                nonuser = args;
            }
        },
        'getNonuser': function(){
            return nonuser;
        }
    }
})()


/**
 * @module itemManager
 * Purpose: handle Load More items page load thru ajax
 *
 * @author Richard d @ GPDigital
 * @version 1.0
 */
var itemManager = (function(){

    /**
     * @method privateGetPageByOffset
     *
     * Purpose: get new page of items from server & display
     *
     * @author Richard d @ GPDigital
     */
    function privateGetPageByOffset(offset,callback) {
        var url = '/trash/100-ways-items/P'+offset+'/';
        $.ajax(url).done(function(result){
            $('#hundred-ways-items').html(result);
            privateUpdateNav();
            hundredWaysManager.restoreLove();
            if (callback) callback();
        });
    };

    /**
     * @method privateLoadPageForItem
     *
     * Purpose: determine page to load for an item, & load if not current
     *
     * @author Richard d @ GPDigital
     */
    function privateLoadPageForItem(item,callback) {
        var page = parseInt($.inArray(item, gld_itemIndex) / gPagingInfo.itemsPerPage);
        var newOffset = page * gPagingInfo.itemsPerPage;
        if (newOffset !== gPagingInfo.masterItemOffset) {
            gPagingInfo.masterItemOffset = newOffset;
            privateGetPageByOffset(newOffset,callback);
        } else {
            // already on correct page
            if (callback) callback();
        }

    };


    /**
     * @method privateUpdateNav
     *
     * Purpose: update paging controls - hide more on last page
     *
     * @author Richard d @ GPDigital
     */
    function privateUpdateNav() {
//        var data = $('.hundred-ways-item-wrapper').last().data();
//        if (data && data.count === data.results) {
//            $('#buttonLoadMore').hide();
//        }
    };

    /**
     * @method privateInit
     *
     * Purpose: init Load More button
     *
     * @author Richard d @ GPDigital
     */
    function privateInit() {
        // add handler for Load More button
//        $('#buttonLoadMore').on('click',function(){
//            gPagingInfo.masterItemOffset += gPagingInfo.itemsPerPage;
//            privateGetPageByOffset(gPagingInfo.masterItemOffset);
//        });
//        privateUpdateNav();
    };

    return {
        initListeners: privateInit,
        loadPageForItem: privateLoadPageForItem
    };
})();


/**
 * @module urlManager
 * Purpose: URL path management & restore
 *
 * @author Richard d @ GPDigital
 * @version 1.0
 */
var urlManager = (function(){
    /**
     * @method privateUpdateUrl
     *
     * Purpose: update url history as openItem changes
     *
     * @author Richard d @ GPDigital
     */
    function privateUpdateUrl(openItem,title) {
        //console.log('privateUpdateUrl: '+openItem);
        var url = '/trash/100-ways/';
        if (openItem) {
            url += openItem+'/';
        } else {
            title = '100 Ways | Glad';
        }
        History.pushState('',title,url);
    };

    /**
     * @method privateStateChange
     *
     * Purpose: handles change in state based on url
     *    will load page & open/close item as needed
     *
     * @author Richard d @ GPDigital
     */
    function privateStateChange() {
        var state = History.getState();
        var path = state.hash;
        //console.log('statechange received: '+path);
        var parts = path.split('/');
        var item = parts[3];

        if (item && typeof hundredWaysManager.getNonuser() == 'undefined') {
            itemManager.loadPageForItem(item,function(){
                console.log('SETTING NON USER TO TRUE')
                hundredWaysManager.setNonuser(true);
                var regex = new RegExp('\\?');
                var match = item.match(regex);
                if(!match){
                    $('.cta[data-urltitle='+item+']').trigger('click',['nonuser']);
                }else{
                    hundredWaysManager.setNonuser(false);
                }

            });
        } else {
            hundredWaysManager.setNonuser(false);
            //$('.item-detail .close-button').trigger('click',['nonuser']);
        }
        hundredWaysManager.restoreLove();
    };

    /**
     * @method privateInit
     *
     * Purpose: initialize obj & event listeners
     *
     * @author Richard d @ GPDigital
     */
    function privateInit() {
        // listeners
        $(document).on('item.open',function(e,openItem,title){
            privateUpdateUrl(openItem,title);
        });

        // restore state from Url
        $(window).on('statechange', privateStateChange);
        privateStateChange();
    };

    return {
        init: privateInit
    };
})();


/**
 * Test Mode suite is for a serie of qUnit tests
 *
 * @author Luc Martin
 * @version 1.0
 */
var testMode = false;

/**
 * @method setTestCookie
 * purpose : sets the testMode
 * @param {Object} val
 */
function setTestCookie(val){
    cookieMonster.createCookie({name:'10-ways-test', 'value':val});
    console.log('Testing enabled: '+val)
}
function processTestMode(){

    if(cookieMonster.readCookie('10-ways-test') == 'true'){

        testMode = true;

        $("head").append("<link rel='stylesheet' type='text/css' href='/js/test/qunit/qunit-1.14.0.css' />");

        $.getScript( "/js/test/qunit/qunit-1.14.0.js",function(){

            $.getScript('/js/test/100-ways-test.js', function(){

                console.error("!!!!!!!!!!!!!!!!!!!!  TESTING MODE IS NOW ENABLED !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!");
                testItemClickProcess();
                testTagging();
                testBrainStates();
                showTest();
                testWinnerFlow();
                testGameActions();
                testLoginRegisterActions();
                testPopupsPosition();
                hundredWaysManager.initListeners();

                console.error("<<<<<<<<<<<<<<<<<<<<<<<<  RUNNING JAVASCRIPT  >>>>>>>>>>>>>>>>>>>>>>>>>>>>>");

                hundredWaysManager.initFirstState();
               // hundredWaysManager.testListeners('100-ways-state-change');
                quizManager.initListeners();
                quizManager.formatList();
                itemManager.initListeners();
                urlManager.init();
            });
        });
        return true;
    }else{
        return false
    }
}
$(document).ready(function() {

    if(processTestMode() == false){

        hundredWaysManager.initListeners();
        hundredWaysManager.initFirstState();

        quizManager.initListeners();
        quizManager.formatList();
        itemManager.initListeners();

        urlManager.init();

    }

});



