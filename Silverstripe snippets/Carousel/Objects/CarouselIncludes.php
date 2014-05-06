<?php
/**
 * Fixes URL segment editor issues in model admin without breaking Pages
 *
 * Note. This will stop translation of URL segment field buttons.
 *
 */
class CarouselIncludes extends Extension{

    function init() {
        Requirements::javascript('../js/jquery.cycle2.min.js');
    }

}