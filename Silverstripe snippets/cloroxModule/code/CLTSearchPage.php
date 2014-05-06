<?php
class CLTSearchPage extends Page {
}
class CLTSearchPage_Controller extends Page_Controller {
	  public function init() {
    	Requirements::javascript("js/pages/CLTPageNavigation.js");
		
        parent::init();
    }
}