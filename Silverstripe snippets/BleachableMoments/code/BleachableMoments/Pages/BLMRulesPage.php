<?php
/*
 * Class BLMRulesPage
 * Describes the Model for a BLMFAQPage
 *
 * @author Luc Martin lmartin -at- clorox.com
 * @version $Id
 */
class BLMRulesPage extends BLMMasterPage {
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

class BLMRulesPage_Controller extends BLMMasterPage_Controller {
/*
    public function init() {
		//$this->redirect('./laugh/bleach-it-away/rules.pdf');
        Requirements::javascript("js/BLM-confirmation-page.js");
        parent::init();
    }
 * 
 */
	public function index() {
        // Automatically handles URLs like http://example.com/Download
        //$fileID = $this->request->param('ID');
        $fileID = BLMLandingPage::get()->first();
		//print_r($fileID);
		$fileID = $fileID->RulesPDFURL;
		return array();
    }
}
class rulesPDF extends Controller {
    

    public function exampleaction() {
        // Automatically handles URLs like http://example.com/Download/exampleaction
    }
}