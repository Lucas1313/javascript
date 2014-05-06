<?php
/*
 * Class ClassroomsResourcesTipsPage
 *
 * Describes the Model for a clLandingPage the home page for the Cleaning and Laundry tips
 *
 * @author Luc Martin lmartin -at- clorox.com
 * @version $Id
 */
class ClassroomsResourcesTipsPage extends Page {
    static $db = array(
        'Title' => 'Text',
        'Subtitle' => 'HTMLText',
        'Color_Class' => 'Text'
    );

    public static $allowed_children = array('ClassroomsArticlesPage');

    static $has_one = array(
        'ClassroomsTagGroup' => 'clTagGroup',
        'ClassroomsAppPanel' => 'clAppPanel',

    );

	static $has_many = array(
		'ClassroomsArticlesPages' => 'ClassroomsArticlesPage',
		
	);
    static $many_many = array(
        'ClassroomsPanels' => 'ClassroomsPanel',

    );

    public static $many_many_extraFields = array(
        'ClassroomsPanels' => array('SortOrderClassroomsPanels' => 'Int'),

    );
	public function Tips(){
		return $this->ClassroomsPanels;
	}
	public function Product(){ // Return the feature product for this promotion... which is clorox disinfecting wipes
		
		$Product = Product::get('Product')->filter('Name','Clorox Disinfecting Wipes')->first(); 
		return $Product;
	}
    public function getCMSFields() {

        $cssClasses_Controller = new CssClasses_Controller('Color_Class');

        $fields = parent::getCMSFields();

        $fields -> removeFieldFromTab('Root', 'Content');

        //***************** Description
        $fields -> removeFieldFromTab('Root.Main', 'Description');
		
 
		//************* feature product for classrooms
		
		foreach($this->ClassroomsPanels() as $panel){
			$panel->ParentPageID = $this->ID;
			$panel->write();
		}
		$ClassroomsPanelsField = new GridField('ClassroomsPanels', // Field name
        'ClassroomsPanels', // Field title
        $this -> ClassroomsPanels(), // List of all Ratings_Reviews slides
        GridFieldConfig_RelationEditor::create() -> addComponents(new GridFieldSortableRows('SortOrderClassroomsPanels'), 
        new GridFieldDeleteAction('unlinkrelation')));
		
		$dataColumns = $ClassroomsPanelsField->getConfig()->getComponentByType('GridFieldDataColumns');
	    $dataColumns->setDisplayFields(array(
	        'ID' => 'ID',
	        'Title'=>'Title',
	        'ParentPageID' => 'ParentPageID'
	    ));
		  
		$fields -> addFieldToTab('Root.Main', $ClassroomsPanelsField);
		
		
        return $fields;

    }

    public function ClassroomsPanels() {
        return $this -> getManyManyComponents('ClassroomsPanels') -> sort('SortOrderClassroomsPanels');
    }


}

class ClassroomsResourcesTipsPage_Controller extends Page_Controller {

    public function init() {
		//Requirements::javascript("js/pages/CLTLandingPage.js");
		Requirements::javascript("js/pages/ClassroomsPage.js");
		Requirements::javascript("js/pages/CLTPanelFilter.js");
		
        parent::init();
    }

}
