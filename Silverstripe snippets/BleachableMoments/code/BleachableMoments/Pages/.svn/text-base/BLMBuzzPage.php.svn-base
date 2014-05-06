<?php
/*
 * Class BLMBuzzPage.php
 * Describes the Model for a BLMRulesPage
 *
 * @author Luc Martin lmartin -at- clorox.com
 * @version $Id
 */
class BLMBuzzPage extends BLMMasterPage {
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
       // $FeaturePanelField = new GridField('manymany', 'manymany', $this -> manymany(), GridFieldConfig_RelationEditor::create() -> addComponents(new GridFieldDeleteAction('unlinkrelation')));
        //$fields -> addFieldToTab('Root.Main', $FeaturePanelField);
        return $fields;

    }

}

class BLMBuzzPage_Controller extends BLMMasterPage_Controller {

    public function init() {
        Requirements::javascript("js/BLM-buzz-page.js");
        parent::init();
    }

}