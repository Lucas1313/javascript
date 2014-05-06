<?php
/*
 * Class CLTLocationPage
 *
 * Describes the Model for a CLTArticlesPage the home page for the Cleaning and Laundry tips
 *
 * @author Luc Martin lmartin -at- clorox.com
 * @version $Id
 */
class ClassroomsFAQPage extends Page {
    static $db = array(
        'Subtitle' => 'HTMLText',
    );

    static $has_one = array(
    );

    static $many_many = array(
	  'ClassroomsFaq'=>'ClassroomsFaq',
    );

    public static $many_many_extraFields = array(
		'ClassroomsFaq' => array('SortOrderClassroomsFaq' => 'Int')    
    );
	
	//****************** FEATURE PRODUCT DEFINED ON CLASSROOMS LANDING
	public function Product(){
		/*	
		$Product = ClassroomsLandingPage::get()->first();
		$Product = $Product::get('ClassroomsPromoProduct')->first();
		$Product = $Product::get('Product')->first();
		*/
		$Product = Product::get('Product')->filter('Name','Clorox Disinfecting Wipes')->first();
		return $Product;
	}
    public function getCMSFields() {

        $fields = parent::getCMSFields();

        $fields -> removeFieldFromTab('Root', 'Content');

        //***************** Description
        $fields -> removeFieldFromTab('Root.Main', 'Description');

        /***************** Slide Show
        $fields -> addFieldToTab('Root.Main', new HeaderField('SlideshowHeader', 'Slideshow </h2><h4>(Create as a List, each <li> will be a slide)</h4><h2>'));
        $fields -> addFieldToTab('Root.Main', new HTMLEditorField('Slideshow', 'Slideshow'));

        //***************** Video
        $fields -> addFieldToTab('Root.Main', new HeaderField('VideoHeader', 'Youtube Id </h2><p>( If you need a video to play in that page, please add the Youtube ID here)</p><h2>'));
        $fields -> addFieldToTab('Root.Main', new TextField('Youtube_Id', 'Youtube Id'));
*/
        //***************** Main content Panels
        $fields -> addFieldToTab('Root.Main', new HeaderField('MainContentPanelsHeader', 'Main Content'));

        $ClassroomsFaqField = new GridField('ClassroomsFaq', 'ClassroomsFaq', $this -> getClassroomsFaq(), GridFieldConfig_RelationEditor::create() -> addComponents(new GridFieldSortableRows('SortOrderClassroomsFaq'), new GridFieldDeleteAction('unlinkrelation')));
        $fields -> addFieldToTab('Root.Main', $ClassroomsFaqField);



        return $fields;

    }


    public function getClassroomsFaq(){
        return $this -> getManyManyComponents('ClassroomsFaq') -> sort('SortOrderClassroomsFaq');;
    }

	public function PrevNextPage($Mode = 'next') {
	
		if($Mode == 'next'){ 
		   $Where = "ParentID = ($this->ParentID) AND Sort > ($this->Sort)"; 
			$Sort = "Sort ASC"; 
		} 
		elseif($Mode == 'prev'){ 
		   $Where = "ParentID = ($this->ParentID) AND Sort < ($this->Sort)"; 
			$Sort = "Sort DESC"; 
		} 
		else{ 
		   return false; 
		}
		
		return DataObject::get("SiteTree", $Where, $Sort, null, 1); 
	    
	}
	
}

class ClassroomsFAQPage_Controller extends Page_Controller {

    public function init() {
    	 Requirements::javascript("js/pages/CLTipsPages.js");
		 Requirements::javascript("js/pages/CLTPageNavigation.js");
        parent::init();
    }

}
