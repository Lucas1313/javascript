<?php
/*
 * Class BLMVotingPage
 * Describes the Model for a BLMVotingPage
 *
 * @author Luc Martin lmartin -at- clorox.com
 * @version $Id
 */
class BLMVotingPage extends BLMMasterPage {
    static $db = array(
        'PublicationDate' => 'Date',
        'Description' => 'HtmlText'
    );

    static $many_many = array();

    public function getCMSFields() {

        $fields = parent::getCMSFields();

        $fields -> addFieldToTab('Root.Main', new TextField('Title'));

        $fields -> removeFieldFromTab('Root', 'Content');

        $dateField = new DateField('Publication Date');


        //***************** Description
        $fields -> addFieldToTab('Root.Main', new TextareaField('Description'));

        //***************** Feature Panels
        //$FeaturePanelField = new GridField('FeaturePanel', 'FeaturePanel', $this -> FeaturePanel(), GridFieldConfig_RelationEditor::create() -> addComponents(new GridFieldDeleteAction('unlinkrelation')));
        //$fields -> addFieldToTab('Root.Main', $FeaturePanelField);
        return $fields;

    }

}

class BLMVotingPage_Controller extends BLMMasterPage_Controller {


    public function init() {

        Requirements::javascript("js/pages/blm-voting-page.js");
        parent::init();
    }

	 /**
     * function BLMoment
     * Purpose retreive a BLMoment using the variable $this->BLMomentID
     * as set using the url parameter.
     *
     * @author Luc Martin -at- Clorox.com
     * @param integer from the action /id/#
     * @version $ID
     */
    function BLMoment (){
        return BLMoment::get()->filter(array('ID'=> $this->BLMomentID))->first();
    }
	public function singleMomentPage() {
		$momentPage = new BLMDetailPage();
	    $momentPage->BLMoment = $this->BLMoment();
		return $this->customise(new ArrayData($momentPage))->renderWith(array('BLMDetailPage', 'Page'));
		//error_log('::: bl moment ::::'.print_r($momentPage,1));
		//return $this->customise(new ArrayData($this->BLMoment()))->renderWith(array('BLMDetailPage', 'Page'));
	}
	 /**
     * Determine the body id based on the URI
     * @return string The body id
     */
    public function bodyId() {
        // Get the URI components
       return "vote-for-moments";
    }
}