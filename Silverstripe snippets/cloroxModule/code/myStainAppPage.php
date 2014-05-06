<?php

class myStainAppPage extends Page {

	 static $db = array(
        'Title' => 'Text',
        'Subtitle' => 'HTMLText',
        
    );

   

    static $has_one = array(
        
    );
	
}

class myStainAppPage_Controller extends Page_Controller {
	

	function init() {
		
        parent::init();
		
	}
}
?>