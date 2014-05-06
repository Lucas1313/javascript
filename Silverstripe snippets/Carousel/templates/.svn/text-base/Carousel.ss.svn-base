<% if isMobile() %>
	<script>
	    var isMobile = true;
	</script>
<% else %>
	<script>
	    var isMobile = false;
	</script>
<% end_if %>

<section class="carouselWrapper genericCarousel" id="carousel_{$codename}">

	<div class="clr"></div>

	<div class="carouselControls">

		<% loop CarouselSlides %>
			<% if Pos == 1 %>

				<% if Background_Class == 'whiteLines' %>
					<a href="#" class="slideArrow left"><img src="/Carousel/templates/img/arrow-left-blue.png" alt="" /></a>
					<a href="#" class="slideArrow right"><img src="/Carousel/templates/img/arrow-right-blue.png" alt="" /></a>
				<% else %>
					<a href="#" class="slideArrow left"><img src="/Carousel/templates/img/arrow-left-large-white.png" alt="" /></a>
					<a href="#" class="slideArrow right"><img src="/Carousel/templates/img/arrow-right-large-white.png" alt="" /></a>
				<% end_if %>

			<% end_if %>
		<% end_loop %>

	</div><!--/.carouselControls-->

	<ul class="carousel" id="slides_{$codename}" data-cycle-pager="pager-{$codename}">
		<% loop CarouselSlides %>
		<li class="carousel-slide-{$Pos} slide {$Name}">
			{$renderSlide()}
		</li>
		<% end_loop %><!-- end of UniversalSlides loop -->
	</ul><!--/.carousel-->

	<div class="carousel-pager">
		<span class="js-pager-arrow-left pager-arrow-left"><i class="fa icon-chevron-left"></i></span>

		<div class="pager-slider-wrapper">
			<ul class="js-pager-slider pager-slider">
				<% loop CarouselSlides %>
					<li data-name="pager-item-{$Pos}" data-target="carousel-slide-{$Pos}" class="js-pager-item pager-item">
						<a href="#" class="">
							<% if Navigation_Image %><span class="pager-image">{$Navigation_Image}</span><% end_if %>
							<% if Navigation_Text %><div class="pager-text"><p>{$Navigation_Text} <i class="fa icon-chevron-right"></i></p></div><% end_if %>
						</a>
					</li>
				<% end_loop %><!-- end of UniversalSlides loop -->
			</ul><!--/.pager-slider-->
		</div><!--/.pager-slider-wrapper-->

		<span class="js-pager-arrow-right pager-arrow-right"><i class="fa icon-chevron-right"></i></span>
	</div><!--/.carousel-pager-->

</section>
<% require javascript("Carousel/js/carousel-loader.js") %>