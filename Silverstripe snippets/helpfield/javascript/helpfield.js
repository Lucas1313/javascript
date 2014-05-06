(function($) {
	$('.helpFieldSeed').livequery(function() {
		var content = $(this).html();
		var targetID = $(this).attr('id').replace(/_HelpFieldSeed$/, '');

		$(this).remove();

		var target = $('#Form_EditForm_' + targetID);
		if ($(target).length == 0) {
			target = $('#Form_ItemEditForm_' + targetID);
		}
		target.append("<a href='#' class='helpFieldButton' id='" + targetID + "_HelpFieldButton'>?</a>").after("<div class='helpField' id='" + targetID + "_HelpField'>" + content + "</div>");

		$('#' + targetID + '_HelpFieldButton').click(function() {
			var box = $('#' + targetID + '_HelpField');
			if (box.hasClass('open')) {
				box.removeClass('open');
				$(this).removeClass('open').blur();
			} else {
				box.addClass('open');
				$(this).addClass('open').blur();
			}
			return false;
		});

	});
	$('.helpField.open').livequery(function() {
		$(this).mousedown(function(){
			var pos = $(this).offset();
			console.info(pos);
			$(this).css('top',pos.top+'px').css('left',pos.left+'px').css('position','fixed').css('z-index',9999);
		})
		$(this).draggable({
			start : function(event, ui) {
				console.info(ui.offset.top);
			}
		});
	});
})(jQuery);