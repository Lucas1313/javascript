<?php
class iFrameHolder extends Page {
    static $db = array(
       'iFrameURL' => 'HTMLText'
    );

    public function getCMSFields() {
        $fields = parent::getCMSFields();
		$fields -> addFieldToTab('Root.Main', new TextField('iFrameURL'));
        return $fields;
    }

	public function isMember(){
		return Member::currentUserID();
	}
}

class iFrameHolder_Controller extends Page_Controller {

    public function init() {
    	 Requirements::javascript("js/pages/iframePage.js");
        parent::init();
    }

}
