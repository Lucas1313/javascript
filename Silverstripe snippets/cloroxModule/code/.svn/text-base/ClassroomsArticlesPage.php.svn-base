<?php
/*
 * Class CLTLocationPage
 *
 * Describes the Model for a CLTArticlesPage the home page for the Cleaning and Laundry tips
 *
 * @author Luc Martin lmartin -at- clorox.com
 * @version $Id
 */
class ClassroomsArticlesPage extends Page {
    static $db = array(
        'Subtitle' => 'HTMLText',
       // 'Slideshow'=>'HTMLText',
        'Youtube_Id'=>'Text',

    );

    static $has_one = array(
        'CLTAppPanel' => 'CLTAppPanel'
	
    );

    static $many_many = array(
        'Slide_Show' => 'ClassroomsPanel',
        'TopTips' => 'ClassroomsPanel',
        'RelatedArticles' => 'ClassroomsPanel',
        'MainContentPanels' => 'ClassroomsPanel'
    );

    public static $many_many_extraFields = array(
        'TopTips' => array('SortOrderTopTips' => 'Int'),
        'RelatedArticles' => array('SortOrderRelatedArticles' => 'Int'),
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

        $CLTPanelsField = new GridField('MainContentPanels', 'Main Content Panels', $this -> MainContentPanels(), GridFieldConfig_RelationEditor::create() -> addComponents(new GridFieldSortableRows('SortOrderMainContentPanels'), new GridFieldDeleteAction('unlinkrelation')));
        $fields -> addFieldToTab('Root.Main', $CLTPanelsField);



        return $fields;

    }

   
    public function MainContentPanels(){
    	//error_log('::::::: ClassroomsArticlesPage MainContentPanels :::::');
        return $this -> getManyManyComponents('MainContentPanels') -> sort('SortOrderMainContentPanels');;
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

class ClassroomsArticlesPage_Controller extends Page_Controller {

    public function init() {
    	// Requirements::javascript("js/pages/CLTipsPages.js");
		Requirements::javascript("js/pages/ClassroomsPage.js");
        parent::init();
    }

}
