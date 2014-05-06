<?php
/*
 * Class BLMGalleryPage
 * Describes the Model for a GenericPage
 *
 * @author Luc Martin lmartin -at- clorox.com
 * @version $Id
 */
class BLMGalleryPage extends BLMMasterPage {
     static $db = array(
        'PublicationDate' => 'Date',
        'Description' => 'HtmlText'
    );
    public static $has_one = array();
    public static $has_many = array();
    public static $many_many = array();
    public static $belong_many_many = array();

    public function getCMSFields() {

        $fields = parent::getCMSFields();

        $fields -> addFieldToTab('Root.Main', new TextField('Title'));

        $fields -> removeFieldFromTab('Root', 'Content');

        $dateField = new DateField('PublicationDate');


        //***************** Description
        $fields -> addFieldToTab('Root.Main', new TextareaField('Description'));

        //***************** Feature Panels
        //$FeaturePanelField = new GridField('manymany', 'manymany', $this -> manymany(), GridFieldConfig_RelationEditor::create() -> addComponents(new GridFieldDeleteAction('unlinkrelation')));
        //$fields -> addFieldToTab('Root.Main', $FeaturePanelField);
        return $fields;

    }


}

class BLMGalleryPage_Controller extends BLMMasterPage_Controller {

    public function init() {
        Requirements::javascript("js/BLM-gallery-page.js");
        parent::init();
    }

	public static $allowed_actions = array(
		 'index',
		 'id',
		 'test'
	);
	
	public function test(){
		return "";
	}
    /**
     * function id
     * Purpose: respond the request id as defined in the allowed_action
     * it will the MomentID for this controller
     * so the function BLMoment will retreive the BLMoment using the first parameter of the action
     * Example http://[website]/laug/bleach-it-away/vote-for-moments/moment/idnumber/12345
     * will retreive the moment id # 12345
     *
     * @author Luc Martin -at- Clorox.com
     * @version $ID
     * @param /id/Number integer passed in the url
     */
    public function id(SS_HTTPRequest $request){
			return "this is a moment";
         //requests all the params
         $ids = $request->allParams();
         // extracts the first one
         $id = $ids['ID'];
         // sets the ID
         $this->BLMomentID = $id;
         // sets the title
         $this->Title = $id;

         // renders the page
         return $this;
    }


}