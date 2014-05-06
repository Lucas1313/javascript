<?php
/*
 * Class BLMContactPage
 * Describes the Model for a BLMFAQPage
 *
 * @author Luc Martin lmartin -at- clorox.com
 * @version $Id
 */
class BLMFAQPage extends BLMMasterPage {
    static $db = array(
        'PublicationDate' => 'Date',
        'Name' => 'Text',
    );
    public static $has_one = array();
    public static $has_many = array('BLMfaqs' => 'BLMfaq');
    public static $many_many = array();
    public static $belong_many_many = array();

    public function getCMSFields() {

        $fields = parent::getCMSFields();


        $fields -> removeFieldFromTab('Root', 'Content');

    //    $dateField = new DateField('PublicationDate');


        //***************** Feature Panels
        $FaqField = new GridField('BLMfaqs', 'BLMfaqs', $this -> BLMfaqs(), GridFieldConfig_RelationEditor::create() -> addComponents(new GridFieldDeleteAction('unlinkrelation')));
        $fields -> addFieldToTab('Root.FAQs', $FaqField);
        return $fields;

    }

}

class BLMFAQPage_Controller extends BLMMasterPage_Controller {

    public function init() {
        Requirements::javascript("js/BLM-faq-page.js");
        parent::init();
    }

}
