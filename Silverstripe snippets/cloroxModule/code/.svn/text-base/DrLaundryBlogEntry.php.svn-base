<?php
/*
 * Class DrLaundryBlogEntry
 *
 * Describes the Model for the Dr Laundry Blog Entry
 *
 *
 */
class DrLaundryBlogEntry extends BlogEntry {

    static $singular_name = "Dr Laundry Blog Entry";
    static $belong_many_many = array('CLTLandingPage' => 'CLTLandingPage');

	public function SubContent($count=400) {
		return substr($this->Content,0,$count);
	}
}

class DrLaundryBlogEntry_Controller extends BlogEntry_Controller {
			function init() {
		Requirements::javascript("/js/plugins/jquery.filteredPages.js");
		Requirements::javascript("js/pages/DrLaundryBlogHolderPage.js");
		
		Requirements::javascript("js/pages/CLTPageNavigation.js");
        parent::init();
		
	}
}
?>