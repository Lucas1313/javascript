<?php
/*
 * Class FAQPage
 *
 * Describes the Model for a FAQPage the home page 
 *
 * @author Kody.Smith -at- clorox.com
 * @version $Id
 */
class FAQPage extends Page {
    static $db = array(
    	'Subtitle' => 'HTMLText',
    );

    static $has_one = array(
    );

    static $many_many = array(
		'CloroxFaqs'=>'Faq',
    );

    public static $many_many_extraFields = array(
		'CloroxFaqs' => array('SortOrderCloroxFaqs' => 'Int')    
    );
	
    public function getCMSFields() {

        $fields = parent::getCMSFields();

        $fields -> removeFieldFromTab('Root', 'Content');

        //***************** Description
        $fields -> removeFieldFromTab('Root.Main', 'Description');

        //***************** Main content Panels
        $fields -> addFieldToTab('Root.Main', new HeaderField('MainContentPanelsHeader', 'Main Content'));

        $FaqField = new GridField('CloroxFaqs', 'CloroxFaqs', $this -> getCloroxFaqs(), 
        GridFieldConfig_RelationEditor::create() -> addComponents(new GridFieldSortableRows('SortOrderCloroxFaqs'), 
        new GridFieldDeleteAction('unlinkrelation')));
        $fields -> addFieldToTab('Root.Main', $FaqField);



        return $fields;

    }


    public function getCloroxFaqs(){
        return $this -> getManyManyComponents('CloroxFaqs') -> sort('SortOrderCloroxFaqs');;
    }

	
}

class FAQPage_Controller extends Page_Controller {

    public function init() {
        parent::init();
    }
}
