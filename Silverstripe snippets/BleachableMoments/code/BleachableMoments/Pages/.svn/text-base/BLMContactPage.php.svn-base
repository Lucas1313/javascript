<?php
/*
 * Class BLMFAQPage
 * Describes the Model for a BLMFAQPage
 *
 * @author Luc Martin lmartin -at- clorox.com
 * @version $Id
 */
class BLMContactPage extends BLMMasterPage {
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
       // $fields -> addFieldToTab('Root.Main', $FeaturePanelField);
        return $fields;

    }

}

class BLMContactPage_Controller extends BLMMasterPage_Controller {

    public function init() {
        Requirements::javascript("js/BLM-contact-page.js");
        parent::init();
    }

}