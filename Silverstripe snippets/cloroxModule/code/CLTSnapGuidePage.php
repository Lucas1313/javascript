<?php
/*
 * Class CLTSnapGuidePage
 *
 * Describes the Model for a CLTSnapGuidePage the home page for the Cleaning and Laundry tips
 *
 * @author Kody Smith -at- clorox.com
 * @version $Id
 */
class CLTSnapGuidePage extends Page {
    static $db = array(
        'Subtitle' => 'HTMLText',
    );

    static $has_one = array(
        'CLTAppPanel' => 'CLTAppPanel'

    );

    static $many_many = array(
        'SnapGuides' => 'SnapGuide'
    );

    public static $many_many_extraFields = array(
        'SnapGuides' => array('SortOrderSnapGuides' => 'Int'),
    );

    public function getCMSFields() {

        $fields = parent::getCMSFields();

        $fields -> removeFieldFromTab('Root', 'Content');

        //***************** Description
        $fields -> removeFieldFromTab('Root.Main', 'Description');


        //***************** Main content Panels
        $fields -> addFieldToTab('Root.Main', new HeaderField('SnapGuideHeader', 'Snap Guide Content'));

        $CLTSnapGuidesField = new GridField('SnapGuides', 'Snap Guide Panels', $this -> SnapGuides(), GridFieldConfig_RelationEditor::create() -> addComponents(new GridFieldSortableRows('SortOrderSnapGuides'), new GridFieldDeleteAction('unlinkrelation')));
        $fields -> addFieldToTab('Root.Main', $CLTSnapGuidesField);

        return $fields;

    }


    public function SnapGuides(){
        return $this -> getManyManyComponents('SnapGuides') -> sort('SortOrderSnapGuides');;
    }

}

class CLTSnapGuidePage_Controller extends Page_Controller {

    public function init() {
		Requirements::javascript("js/pages/CLTPageNavigation.js");
        Requirements::javascript("js/pages/CLTipsPages.js");
		
        parent::init();
    }

}
