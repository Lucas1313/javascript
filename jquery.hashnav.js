
/**
* Name: jquery.hashnav.js
*
* Description: Detect hashtag in url and search for a link in the page that correspond
* Perform an action on the link. Default action : click
* @ID
* @autor Luc Martin
**/

(function($) {
	$.fn.hashnav = function(args) {

		//consolidate the 'this' for the root object
		var base = this,
			// Set the default values
			defaults = {
				action: 'click',
				element:'a',
				attribute:'href',
				listenForTagChange : false,
				updateMetatags : false,
				metatags : null,
				queryString : 'destination',
				useOnlyDataTag : false
			};
		//merge in user supplied args
		$.extend(defaults, args || {});

		//Iterate trough all elements set by the plugin
		return this.each(function() {

			addHashTagFromQuery(defaults);
			checkForHashTag(defaults);

		});
		/**
		* addHashTagFromQuery
		* Reads the query string and adds a hash tag if the args.queryString parameter is set in the window location
 		* @param {Object} args
		*/
		function addHashTagFromQuery(args){
			// test if there is a query
			if(window.location.search){

				var search = window.location.search;

				//split the query
				var searchAr = search.split('&');

				// iterate through the different queries
				for (var n in searchAr){
					var searchItem = searchAr[n];

					// split the single search key/value
					var searchItemSplit = searchItem.split('=');
					// get the value
					var value = searchItemSplit[1];
					// get the key and replace the question mark if any
					var keyStr = searchItemSplit[0].replace('?','');

					// test against the jQuery parameter
					if(keyStr == args.queryString){
						// if test is a success add the hash
						 window.location.hash = value;
					}

				}
			}
		}
		function checkForHashTag(args){

			var hash = window.location.hash,
			listenForTagChange = args.listenForTagChange;

			if(listenForTagChange){
				var interval = setInterval(function(){

					var newHash = window.location.hash;
					if(newHash !== hash){
						hash = newHash;
						performAction(args);
					}
				}, 500);
			}

			if(hash){
				args.hash = hash;
				performAction(args);
			}

			function performAction(){
				var action = args.action,
					element = args.element,
					attribute = args.attribute,
					keepPropagation = args.attribute,
					updateMetatags = args.updateMetatags,
					metatags = args.metatags,
					useOnlyDataTag = args.useOnlyDataTag;

				$(element).each(function(){
					console.info($(this).attr('data-hashnav'))
					var found = -1;
					if($(this).attr(attribute) && useOnlyDataTag == false){
						found = 	$(this).attr(attribute).search(hash);
					}
					if(found == -1 && $(this).attr('data-hashnav')){
						found = 	$(this).attr('data-hashnav').search(hash);
					}

					if(found > -1){

						$(this).trigger(action);

						if(updateMetatags && metatags){

							$('head').find('meta').each(function(){

								for(var tag in metatags){

										var metatag = metatags[tag];
										var foundTag = false;

										if($(this).attr('property') == metatag.attribute){

											$(this).attr('content',metatag.value);
											foundTag = true;

										}
										if(!foundTag && !metatag.added){
											$('head').append('<meta property="'+metatag.attribute+'" content="'+metatag.value+'" /> ');
											metatag.added = true;
										}

								}
							})

						}
					}else{
						//console.info('NOT  :( )')
					}
				})

			}
			// Call back function
			if(args.after){
				args.after(args);
			}

		}
	};
})(jQuery);