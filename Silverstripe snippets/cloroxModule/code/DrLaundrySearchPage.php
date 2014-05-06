<?php
class DrLaundrySearchPage extends Page {
}
class DrLaundrySearchPage_Controller extends Page_Controller {
	  public function init() {
    	Requirements::javascript("js/pages/CLTPageNavigation.js");
		
        parent::init();
    }
}