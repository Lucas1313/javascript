<?php
/*
 * Class CLTLocationPage
 *
 * Describes the Model for a CLTArticlesPage the home page for the Cleaning and Laundry tips
 *
 * @author Luc Martin lmartin -at- clorox.com
 * @version $Id
 */
class ClassroomsCurriculumArticlesPage extends Page {
    static $db = array(
        'Subtitle' => 'HTMLText',
       // 'Slideshow'=>'HTMLText',
        'Youtube_Id'=>'Text',

    );

    static $has_one = array(
        'CLTAppPanel' => 'CLTAppPanel',
		//'ClassroomsCurriculumArticlesPage' => 'ClassroomsCurriculumArticlesPage'
    );

    static $many_many = array(
        'MainContentPanels' => 'ClassroomsCurriculumPanel'
    );

    public static $many_many_extraFields = array(
        'MainContentPanels' => array('SortOrderMainContentPanels' => 'Int')
    );
	
	//****************** FEATURE PRODUCT DEFINED ON CLASSROOMS LANDING
	public function Product(){
		//$Product = ClassroomsLandingPage::get()->first();
		//$Product = $Product::get('ClassroomsPromoProduct')->first();
		//$Product = $Product::get('Product')->first();
		$Product = Product::get('Product')->filter('Name','Clorox Disinfecting Wipes')->first();
		return $Product;
	}
    public function getCMSFields() {

        $fields = parent::getCMSFields();

        $fields -> removeFieldFromTab('Root', 'Content');
		$fields -> removeFieldFromTab('Root', 'Content');
		$fields -> removeFieldFromTab('Root', 'Content');
		$fields -> removeFieldFromTab('Root', 'Content');
		$fields -> removeFieldFromTab('Root', 'Content');
		
        //***************** Description
        $fields -> removeFieldFromTab('Root.Main', 'Description');

        //***************** Main content Panels
        $fields -> addFieldToTab('Root.Main', new HeaderField('MainContentPanelsHeader', 'Main Content'));

        $CLTPanelsField = new GridField('MainContentPanels', 'Main Content Panels', $this -> MainContentPanels(), GridFieldConfig_RelationEditor::create() -> addComponents(new GridFieldSortableRows('SortOrderMainContentPanels'), new GridFieldDeleteAction('unlinkrelation')));
        $fields -> addFieldToTab('Root.Main', $CLTPanelsField);

 

        return $fields;

    }

    public function MainContentPanels(){
        return $this -> getManyManyComponents('MainContentPanels') -> sort('SortOrderMainContentPanels');;
    }

	public function removeUnderscore($inputString){ //This function is handy to remove underscores to make filenames, or data look better for display
		return str_replace($inputString,'_',' ');
	}
}

class ClassroomsCurriculumArticlesPage_Controller extends Page_Controller {

    public function init() {
    	 //Requirements::javascript("js/pages/CLTipsPages.js");
		 Requirements::javascript("js/pages/ClassroomsPage.js");
        parent::init();
    }

}
