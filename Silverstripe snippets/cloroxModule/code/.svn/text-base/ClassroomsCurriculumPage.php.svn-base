<?php
/*
 * Class CLTLandingPage
 *
 * Describes the Model for a CLTLandingPage the home page for the Cleaning and Laundry tips
 *
 * @author Luc Martin lmartin -at- clorox.com
 * @version $Id
 */
class ClassroomsCurriculumPage extends Page {
    static $db = array(
        'Title' => 'Text',
        'Subtitle' => 'HTMLText',
        'Color_Class' => 'Text'
    );

    public static $allowed_children = array('ClassroomsCurriculumArticlesPage');

    static $has_one = array(
        'ClassroomsTagGroup' => 'CLTTagGroup',
        'ClassroomsAppPanel' => 'CLTAppPanel',

    );

	static $has_many = array(
		'ClassroomsCurriculumArticlesPages' => 'ClassroomsCurriculumArticlesPage',
		
	);
    static $many_many = array(
        'ClassroomsCurriculumPanels' => 'ClassroomsCurriculumPanel',

    );

    public static $many_many_extraFields = array(
        'ClassroomsCurriculumPanels' => array('SortOrderClassroomsCurriculumPanels' => 'Int'),
	
    );
	
	//****************** CLASSROOMS CURRICULUM PANELS - for articles and tips
	public function Tips(){
		return $this->ClassroomsCurriculumPanels;
	}
	
	//****************** FEATURE PRODUCT DEFINED ON CLASSROOMS LANDING
	public function Product(){
		//$Product = ClassroomsLandingPage::get()->first();
		//$Product = $Product::get('ClassroomsPromoProduct')->first();
		$Product = Product::get('Product')->filter('Name','Clorox Disinfecting Wipes')->first();
		 
		return $Product;
	}
    public function getCMSFields() {

        $cssClasses_Controller = new CssClasses_Controller('Color_Class');

        $fields = parent::getCMSFields();
		
        $fields -> addFieldToTab('Root.Main',$cssClasses_Controller -> CLTPanel_Colors_Class());

        $fields -> removeFieldFromTab('Root', 'Content');

        //***************** Description
        $fields -> removeFieldFromTab('Root.Main', 'Description');

        //***************** Feature Panels
        $fields -> addFieldToTab('Root.Main', new HeaderField('Articles_and_Tips', 'Articles and Tips:'));
		
 
		//************* feature product for classrooms
		foreach($this->ClassroomsCurriculumPanels() as $panel){ //Assign parent page ID to all panels contained within this page
			$panel->ParentPageID = $this->ID;
			if($panel->ParentPageID == 0){
				$panel->write();	
			}
		}
	/*	$ClassroomsCurriculumPanelsField = new GridField('ClassroomsCurriculumPanels', // Field name
        'ClassroomsCurriculumPanels', // Field title
        $this -> ClassroomsCurriculumPanels(), // List of all Ratings_Reviews slides
        GridFieldConfig_RelationEditor::create());
	 */


      //  $fields -> addFieldToTab('Root.Main', new HelpField('helpClassroomsPage',array( __CLASS__ , 'ClassroomsPageSlideShowHeader', '')));

      

        //************** feature panels
        $ClassroomsCurriculumPanelsField = new GridField('ClassroomsCurriculumPanels', 'ClassroomsCurriculumPanels', $this -> ClassroomsCurriculumPanels(), GridFieldConfig_RelationEditor::create() -> addComponents(new GridFieldSortableRows('SortOrderClassroomsCurriculumPanels'), new GridFieldDeleteAction('unlinkrelation')));
        $fields -> addFieldToTab('Root.Main', $ClassroomsCurriculumPanelsField);
		
		
		$dataColumns = $ClassroomsCurriculumPanelsField->getConfig()->getComponentByType('GridFieldDataColumns');
	    $dataColumns->setDisplayFields(array(
	        'ID' => 'ID',
	        'Title' => 'Title',
	        'ParentPageID' => 'ParentPageID'
	    ));

		 
		$fields -> addFieldToTab('Root.Main', $ClassroomsCurriculumPanelsField);
		
        $fields -> addFieldToTab('Root.Main', new HeaderField('RelatedObjects', 'Related to that page'));
        
		
		
		
        return $fields;

    }
/*
    public function ClassroomsCurriculumPanels() {
        return $this -> getManyManyComponents('ClassroomsCurriculumPanels') -> sort('SortOrderClassroomsCurriculumPanels');
    }
*/
}

class ClassroomsCurriculumPage_Controller extends Page_Controller {

    public function init() {
    	//Requirements::javascript("js/pages/CLTPageNavigation.js");
		//Requirements::javascript("js/pages/CLTLandingPage.js");
		Requirements::javascript("js/pages/CLTPanelFilter.js");
		Requirements::javascript("js/pages/ClassroomsPage.js");
		
        parent::init();
    }

}
