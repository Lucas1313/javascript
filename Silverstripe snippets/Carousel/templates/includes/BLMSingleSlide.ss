<% control BLMMasterPage %>
<section class="bleachable-moments-slide">
	<div class="blm-left-side">
		<div class="headline">
			<img src="/Carousel/templates/img/laugh/blm-title.png">
		</div>

		<% control $MomentForm %>
			<form class="momentSubmitForm" id="Form_MomentForm" action="/laugh/bleach-it-away/MomentForm" method="post" enctype="application/x-www-form-urlencoded" novalidate="novalidate">
				<input type="hidden" name="SecurityID" value="$SecurityID" class="hidden" id="Form_MomentForm_SecurityID" />
				<textarea onFocus="if (this.value == this.defaultValue) { this.value = '' }" onBlur="if (this.value == '') { this.value = this.defaultValue }" wrap="hard" name="Moment" class="textarea panelShadow" id="Form_MomentForm_Moment" maxlength="450">Enter your Bleachable Moment &amp; SAVE 50¢</textarea>
				<input type="submit" value="SHARE" class="submit panelShadow" name="action_ShareMomentAction" id="Form_MomentForm_action_ShareMomentAction" />

				<!--<span class="countdown"></span>/.countdown-->
			</form>
		<% end_control %>

		<span class="CLBSave50">
			<img src="Carousel/templates/img/laugh/clorox-bottle-for-blm.png" />
		</span>
	</div><!--/.blm-left-side-->

	<div class="blm-right-side">
		<% control $getLastWinner() %>
			<% include BLMoment %>
			<!--<span class="link-to-moment"><a href="/laugh/bleach-it-away/vote-for-moments/moment/idnumber/{$ID}">How to Bleach it away > </a></span>-->
		<% end_control %>

	</div><!--/.blm-right-side-->
<% end_control %>
</section><!--/.bleachable-moments-slide-->

<a class="corner-wrapper-image">
	<img class="corner" src="/Carousel/templates/img/laugh/animated-corner/blm/mobile-corner.png">
</a>

<a class="corner-wrapper" href="/laugh/bleach-it-away/">
	<img class="corner corner1 showing" src="/Carousel/templates/img/laugh/animated-corner/blm/corner_ab1.png">
	<img class="corner corner2 hidden" src="/Carousel/templates/img/laugh/animated-corner/blm/corner_ab2.png">
	<img class="corner corner3 hidden" src="/Carousel/templates/img/laugh/animated-corner/blm/corner_ab3.png">
	<img class="corner corner4 hidden" src="/Carousel/templates/img/laugh/animated-corner/blm/corner_ab4.png">
	<img class="corner corner5 hidden" src="/Carousel/templates/img/laugh/animated-corner/blm/corner_ab5.png">
	<img class="corner corner6 hidden" src="/Carousel/templates/img/laugh/animated-corner/blm/corner_ab6.png">
	<img class="corner corner7 hidden" src="/Carousel/templates/img/laugh/animated-corner/blm/corner_ab7.png">
</a>
