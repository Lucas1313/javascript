<?php
/*
 * Class BLMDetailPage
 * Describes the Model for a BLMDetailPage
 *
 * @author Luc Martin lmartin -at- clorox.com
 * @version $Id
 */
class BLMDetailPage extends BLMMasterPage {
    static $db = array(
        'PublicationDate' => 'Date',
        'Description' => 'HtmlText',
        'BLMomentName'=>'Text'
    );
    public static $allowed_actions = array('id');
    public static $has_one = array('BLMoment' => 'BLMoment');
    public static $has_many = array();
    public static $many_many = array();
    public static $belongs_many_many = array();

    public function getCMSFields() {
        $fields = parent::getCMSFields();
        $this->BLMomentName = $this->BLMomentName();
        $fields -> addFieldToTab('Root.Main', new TextField('Title'));
        $fields -> addFieldToTab('Root.Main', new ReadonlyField('BLMomentName','BLMomentID'));

        $fields -> removeFieldFromTab('Root', 'Content');

        $dateField = new DateField('PublicationDate');

        //***************** Description
        $fields -> addFieldToTab('Root.Main', new TextareaField('Description'));

        //***************** Feature Panels
        //$FeaturePanelField = new GridField('manymany', 'manymany', $this -> manymany(), GridFieldConfig_RelationEditor::create() -> addComponents(new GridFieldDeleteAction('unlinkrelation')));
        //$fields -> addFieldToTab('Root.Main', $FeaturePanelField);
        return $fields;

    }

    function BLMomentName(){
        $blm = BLMoment::get()->filter('ID',$this->BLMomentID)->first();

        if(!empty($blm->ID)){
            return BLMoment::get()->filter('ID',$this->BLMomentID)->first()->ID;
        }else{
            return 'Deleted Moment';
        }

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

class BLMDetailPage_Controller extends BLMMasterPage_Controller {

    public static $allowed_actions = array('idnumber');

    /**
     * function id
     * Purpose: respond the request id as defined in the allowed_action
     * it will the MomentID for this controller
     * so the function BLMoment will retreive the BLMoment using the first parameter of the action
     * Example http://[website]/laug/bleach-it-away/vote-for-moments/moment/ididnumber12345
     * will retreive the moment id # 12345
     *
     * @author Luc Martin -at- Clorox.com
     * @version $ID
     * @param /id/Number integer passed in the url
     */
    public function idnumber(SS_HTTPRequest $request){
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

    public function init() {
        Requirements::javascript("js/BLM-detail-page.js");
        parent::init();
    }

}
