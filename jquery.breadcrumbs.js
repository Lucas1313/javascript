/**
 * Name: jquery.breadcrumbs.js
 *
 * Description: Generates breadcrumbs using the url of the page
 * @ID
 * @autor Luc Martin
 **/

(function($) {
	$.fn.breadcrumbs = function(args) {

		//consolidate the 'this' for the root object
		var base = this,
			// Set the default values
			defaults = {
				excludeActualPage: true,
				homeName:'Home',
				homeUrl: 'http://'+window.location.hostname,
				path : window.location.pathname,
				search:window.location.search,
				separator:'|',
				//currentPageColor : '#8b8b8b',
				titleField : $('title').html()
			};
		//merge in user supplied args
		$.extend(defaults, args || {});

		//Iterate trough all elements set by the plugin
		return this.each(function() {

			generateBreadcrumbs(defaults);

		});

		function capitaliseFirstLetter(string)
		{
			var stringAr = string.split('-');
			var ret = '';
			for(var i in stringAr){
				var fixed = stringAr[i].charAt(0).toUpperCase() + stringAr[i].slice(1);
				ret += fixed+' ';
			}
			ret = ret.replace('Regular Bleach1', 'Regular Bleach<sub>1</sub>')
						.replace('Germicidal Bleach Concentrated','Germicidal Bleach<sub>1</sub> Concentrated')
						.replace('Clorox','Clorox&reg;')
						.replace('Fraganzia','Fraganzia&reg;')
						.replace('Splash Less', 'Splash-Less&trade;');
			ret = ret.replace('Clorox&reg; Smart Seek Bleach', 'Clorox Smart Seek&trade; Bleach')
						.replace('Fraganzia&reg; Bleach','Fraganzia&trade;  Bleach');

		    return ret;
		}

		function generateBreadcrumbs(args){

			var breadcrumbs = '<nav id="breadcrumbs" role="navigation">';
			var length;

			if(args.path){
				var addressAr = args.path.split('/');
				for (var i in addressAr){
					if(addressAr[i] == ''){
						addressAr.splice(i,1);
					}
				}
				length = addressAr.length;

				if(addressAr[0].length > 0){
					breadcrumbs += '<a href="'+args.homeUrl+'">'+args.homeName+'</a>';


					for(var i in addressAr){

						var item = addressAr[i];

						if(i < length - 1 && addressAr[0].length > 0){

							breadcrumbs += '<span>'+args.separator+'</span>'+'<a href="'+item+'">'+capitaliseFirstLetter(item)+'</a>';

						}else if(addressAr[0].length > 0){

							//breadcrumbs += '<span>'+args.separator+'</span>'+'<span class="home child">'+capitaliseFirstLetter(args.titleField)+'</span>';
							breadcrumbs += '<span>'+args.separator+'</span>'+'<span class="home child">'+capitaliseFirstLetter(addressAr[i])+'</span>';
						}

					}
				}else{
					breadcrumbs += '<span class="home child "></span>';
				}
			}
			breadcrumbs += '</nav>';

			$(base).html(breadcrumbs);


			// Call back function
			if(args.after){
				args.after(args);
			}

		}
	};
})(jQuery);



