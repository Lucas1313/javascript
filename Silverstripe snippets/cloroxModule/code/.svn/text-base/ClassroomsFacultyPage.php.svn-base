<?php
/*
 * Class CLTLocationPage
 *
 * Describes the Model for a CLTArticlesPage the home page for the Cleaning and Laundry tips
 *
 * @author Luc Martin lmartin -at- clorox.com
 * @version $Id
 */
class ClassroomsFacultyPage extends Page {

    static $db = array(
        'Display_Name'=> 'HTMLText',
        'Subtitle' => 'HTMLText'
        );

    static $has_one = array('CLTAppPanel' => 'CLTAppPanel');

    static $can_be_root = false;
	static $has_many = array(
		'FacultyProfiles' => 'Faculty'
	);
    static $many_many = array(
        'Tips' => 'FacultyTips',
        'Articles' => 'CLTPanel',
        'RelatedArticles' => 'CLTPanel',
    );

	public function Product(){ // Return the feature product for this promotion... which is clorox disinfecting wipes
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

	//************************* Remove excess fields
		$fields -> removeFieldFromTab('Root.Main', 'Content');


 	//************************* Faculty
        // setup the grid
        $allFaculty = $this -> FacultyProfiles();
        //Product::get();


        $gridFieldConfig = GridFieldConfig::create() -> addComponents(new GridFieldSortableRows('Sort_Order_Faculty'), new GridFieldToolbarHeader(), new GridFieldAddNewButton('toolbar-header-right'), new GridFieldSortableHeader(), new GridFieldDataColumns(), new GridFieldPaginator(500), new GridFieldEditButton(), new GridFieldDeleteAction(), new GridFieldDetailForm(), new GridFieldAddExistingAutocompleter('toolbar-header-left'), new GridField('Faculty'));

	//***************** Faculty BIO Description
        $fields -> addFieldToTab('Root.Main', new TextareaField('Content'));
        $FacultyField = new GridField('Faculty', 'Faculty', $allFaculty, $gridFieldConfig);
        $fields -> addFieldToTab('Root.Main', $FacultyField);

        return $fields;

    }


}

class ClassroomsFacultyPage_Controller extends Page_Controller {

    public function init() {
    	Requirements::javascript("js/pages/CLTipsPages.js");
		Requirements::javascript("js/pages/CLTPageNavigation.js");
        parent::init();
    }

}
