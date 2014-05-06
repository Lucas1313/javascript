<?php
/*
 * Class CLTLandingPage
 *
 * Describes the Model for a CLTLandingPage the home page for the Cleaning and Laundry tips
 *
 * @author Luc Martin lmartin -at- clorox.com
 * @version $Id
 */
class ClassroomsLandingPage extends Page {
    static $db = array(
        'Publication Date' => 'Date',
        'Panel_A_Title' => 'Text',
        'Panel_A_description' => 'Text',
 
    );

 
	static $has_one = array(
		
	);
    static $has_many = array(

        "ClassroomsPromoProduct" => "ClassroomsPromoProduct"
    );

    static $many_many = array(
        "ClassroomsPageFeaturePanels" => "FeaturePanel",


    );

    public static $many_many_extraFields = array(
        'ClassroomsPageFeaturePanels' => array('SortOrderClassroomsPageFeaturePanels' => 'Int')
    );

	public function Product(){
		//$Productz = ClassroomsPromoProduct::get()->first();
		//$Product = $Product::get('ClassroomsPromoProduct')->first();
		//print_r($Product);
		//$Product = $Product::get('Product');
		//print_r($Productz);
		$Product = Product::get('Product')->filter('Name','Clorox Disinfecting Wipes')->first();
		return $Product;
	}
    public function getCMSFields() {

        $fields = parent::getCMSFields();

        $fields -> removeFieldFromTab('Root', 'Content');
        $fields -> removeFieldsFromTab('Root', array('TipsAndTricks'));

        $dateField = new DateField('Publication Date');

        //************** slideshows
        $fields -> addFieldToTab('Root.Main', new HeaderField('TopSlideShowHeader', 'Top SlideShow </h3><p>This is the ClassroomsPage Top Slideshow, add a slide to build</p><h3>'));
        $fields -> addFieldToTab('Root.Main', new HeaderField('ClassroomsPageSlideShowHeader', 'Classrooms SlideShow </h3><p>This is the ClassroomsPage Slideshows</p><h3>'));
        $topSlidesConfig = GridFieldConfig_RelationEditor::create();
        $topSlidesConfig -> addComponent(new GridFieldSortableRows('ClassroomsLandingPageSortOrder'));


      //  $fields -> addFieldToTab('Root.Main', new HelpField('helpClassroomsPage',array( __CLASS__ , 'ClassroomsPageSlideShowHeader', '')));

      

        //************** feature panels
        $FeaturePanelField = new GridField('ClassroomsPageFeaturePanels', 'ClassroomsPageFeaturePanels', $this -> ClassroomsPageFeaturePanels(), GridFieldConfig_RelationEditor::create() -> addComponents(new GridFieldSortableRows('SortOrderClassroomsPageFeaturePanels'), new GridFieldDeleteAction('unlinkrelation')));
        $fields -> addFieldToTab('Root.Main', $FeaturePanelField);
		
		
		
		
		//************* feature product for classrooms
		$ClassroomsPromoProductField = new GridField('ClassroomsPromoProduct', // Field name
        'ClassroomsPromoProduct', // Field title
        $this -> ClassroomsPromoProduct(), // List of all Ratings_Reviews slides
        GridFieldConfig_RelationEditor::create());
		
		$fields -> addFieldToTab('Root.FeatureProduct', $ClassroomsPromoProductField);
		
		
        return $fields;

    }
	public function getPledgeCount(){
		$count = DB::query("SELECT COUNT('ID') FROM ClassroomsPledge")->value();
		return $count;
	}
    public function ClassroomsPageFeaturePanels() {
        return $this -> getManyManyComponents('ClassroomsPageFeaturePanels') -> sort('SortOrderClassroomsPageFeaturePanels');
    }

    /**
     * Method called before an Object is saved
     * Will synchronise the "specific uses for the product with the selected checkboxes"
     * @param none
     * @return void
     */
    public function onBeforeWrite() {
        parent::onBeforeWrite();
    }



}

class ClassroomsLandingPage_Controller extends Page_Controller {

    public function init() {
    	Requirements::javascript("js/pages/CLTPageNavigation.js");
		Requirements::javascript("js/pages/CLTLandingPage.js");
		
        parent::init();
    }

}
