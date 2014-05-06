<% with IcktionaryItemPage %>
	<% loop IckItems %>
		<% if $Pos == 1 %>
			<section class="ickHome blueIck IckCarouselItem ick-slide" style="background:url({$Image.URL});">

				<div class="ickContent">

					<div class="ickContainer">

						<div class="ickWord">$Display_Name()</div>
						<br />
						<span class="ickPhonetic">{$Slogan}</span>

						<div class="ickDefinitionContainer">
							<span class="ickDefinition">{$Definition}</span>
						</div><!--/.ickDefinitionContainers-->

					</div><!--/.ickContainer-->

				</div><!--/.ickContent-->


                    <div class="center-panel-wrapper">
                        <div class="center-panel">
                            <div class="triple-col col-left">
                                <a class="ick-left-link" href="{$itemPageUrl}?comments=open">
                                    <div class="count"><span class="number">{$User_Click_Counter} </span><span class="other">others</span></div>
                                </a>
                            </div>
                              <div class="vertical-line"></div>
                            <div class="triple-col col-center">
                                <a href="{$itemPageUrl}?comments=open">
                                    <div class="title">
                                        <div class='title-1'>Use it in</div><div class="title-2">a sentence</div>
                                    </div>
                                    <div class="content">Use it in a  sentence <br/>to write and view all <br/>comments<i class="icon-chevron-right"></i></div>
                                </a>
                            </div>
                              <div class="vertical-line"></div>
                            <div class="triple-col col-right">
							<% if AssociatedProductPage %>
								<% with AssociatedProductPage %>
									<% loop Product %>
										<span class="product">{$Image}</span>
										<div class="content"><a data-couponcode="{$Coupon_Code}" href="{$Up.Link()}"><% if Up.Up.CTA_Title %>{$Up.Up.CTA_Title}<% else %>Get rid of that<br />sticky mess.<br/><i class="icon-chevron-right"></i><% end_if %></a></div>
									<% end_loop %>
								<% end_with %>
							<% else %>
							<a href="/products/clorox-concentrated-regular-bleach/">
								<img class="product" src="Carousel/templates/img/laugh/clorox-bottle.png">
								<div class="content">Get rid of that <br/>sticky mess.<br/><i class="icon-chevron-right"></i></div>
							</a>
							<% end_if %>

						</div>
					</div>
				</div><!--/.center-panel-wrapper-->

			</section><!--/.ickHome-->

		<% end_if %>
	<% end_loop %>
<% end_with %>

<a class="corner-wrapper-image">
	<img class="corner" src="/Carousel/templates/img/laugh/animated-corner/ick/mobile-corner.png">
</a>

<a class="corner-wrapper" href="/laugh/ick-tionary/">
	<img class="corner corner1 showing" src="/Carousel/templates/img/laugh/animated-corner/ick/corner_ab1.png">
	<img class="corner corner2 hidden" src="/Carousel/templates/img/laugh/animated-corner/ick/corner_ab2.png">
	<img class="corner corner3 hidden" src="/Carousel/templates/img/laugh/animated-corner/ick/corner_ab3.png">
	<img class="corner corner4 hidden" src="/Carousel/templates/img/laugh/animated-corner/ick/corner_ab4.png">
	<img class="corner corner5 hidden" src="/Carousel/templates/img/laugh/animated-corner/ick/corner_ab5.png">
	<img class="corner corner6 hidden" src="/Carousel/templates/img/laugh/animated-corner/ick/corner_ab6.png">
	<img class="corner corner7 hidden" src="/Carousel/templates/img/laugh/animated-corner/ick/corner_ab7.png">
</a>
