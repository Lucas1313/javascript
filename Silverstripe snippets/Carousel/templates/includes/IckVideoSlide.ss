<% with VideoItem %>
<section class='video-slide'>
	<a class="playVideo" id="{$Youtube_Id}" data-videoid="{$Youtube_Id}"></a>
	<div class="videoWrapper">

		<div id="videoPlayer">

		</div><!--/#videoPlayer-->

    </div>
</section>
<% end_with %>

<a class="corner-wrapper-image">
    <img class="corner" src="/Carousel/templates/img/laugh/animated-corner/video/mobile-corner.png">
</a>

<a class="corner-wrapper" href="/laugh/ick-videos/">

    <img class="corner corner1 showing" src="/Carousel/templates/img/laugh/animated-corner/video/corner_ab1.png">
    <img class="corner corner2 hidden" src="/Carousel/templates/img/laugh/animated-corner/video/corner_ab2.png">
    <img class="corner corner3 hidden" src="/Carousel/templates/img/laugh/animated-corner/video/corner_ab3.png">
    <img class="corner corner4 hidden" src="/Carousel/templates/img/laugh/animated-corner/video/corner_ab4.png">
    <img class="corner corner5 hidden" src="/Carousel/templates/img/laugh/animated-corner/video/corner_ab5.png">
    <img class="corner corner6 hidden" src="/Carousel/templates/img/laugh/animated-corner/video/corner_ab6.png">
    <img class="corner corner7 hidden" src="/Carousel/templates/img/laugh/animated-corner/video/corner_ab7.png">

</a>

<% require javascript("js/plugins/jquery.youtubewrapper.js") %>
<script>
    var disableAutoplay = true;
</script>
<% require javascript("Carousel/templates/js/IckVideoSlide.js") %>