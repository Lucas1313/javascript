<?php
/*
 * ClassroomsPanel
 *
 * Describes the Model for a ClassroomsPanel
 *
 * @author Luc Martin lmartin -at- clorox.com
 * @version $Id: FaqCategoy.php 21419 2013-04-12 23:01:24Z lmartin $
 *
 * Relationships:
 *
 * hasOne =CTLLandongPage
 * many-many =
 * belong-many-many = Products
 */
class ClassroomsPanel extends DataObject {

    static $db = array(
        'Name' => 'HTMLText',
        'Display_Name' => 'HTMLText',
        'Subtitle' => 'HTMLText',
        'Quick_Tip' => 'HTMLText',
        'Deep_Clean_Tip' => 'HTMLText',
        'Release_Date' => 'Date',
        'Color_Class' => 'Text',
        'Panel_Class' => 'Text',
        'Prep_Time'=>'Int',
        'Activity_Time'=>'Int',
        'ParentPageID' => 'Int',
        'Headline' => 'HTMLText'
    );

    static $has_one = array(
        'MainImage' => 'Image',
        'SmallImage' => 'Image',
        'FrontPagePanelImage' => 'Image',
        'ClassroomsSingleItemPage' => 'ClassroomsArticlesPage',
        'ActivityInstructions' => 'File'
    );
    static $belong_many_many = array(
        'ClassroomsArticlesPage' => 'ClassroomsArticlesPage',
        'ClassroomsLandingPage' => 'ClassroomsLandingPage',
        
    );
    static $searchable_fields = array(
        'Name',
        'Quick_Tip',
        'Deep_Clean_Tip'
    );
    public static $summary_fields = array(
    	//'SortOrderLandingPageClassroomsPanels'=>'SortOrderLandingPageClassroomsPanels',
        'ID' => 'ID',
        'Name' => 'Name',
        'Quick_Tip' => 'Quick_Tip',
        'Deep_Clean_Tip' => 'Deep_Clean_Tip',
    );

	public function removeUnderscore($inputString){
		return str_replace($inputString,'_',' ');
	}
    public function getCMSFields() {

        if (!empty($this -> Name)) {
            $this -> generatePage();
        }

      //  $cssClasses_Controller = new CssClasses_Controller();
		$cssClasses_Controller = new CssClasses_Controller('Color_Class');
        $fields = parent::getCMSFields();
		
		$fields -> removeFieldFromTab('Root.Main', 'Color_Class');
		$fields -> removeFieldFromTab('Root.Main', 'Panel_Class');
		$fields -> removeFieldFromTab('Root.Main', 'Headline');
        //$fields -> removeFieldFromTab('Root.Main', 'ClassroomsSingleItemPageID');

        $fields -> addFieldToTab('Root.Main', new ReadOnlyField('ClassroomsSingleItemPageID', 'ClassroomsSingleItemPageID'));

        //***************** Release DATE
        $fields -> addFieldToTab('Root.Main', new HeaderField('releaseDateHeader', 'Release date of the Tip / Article'));

        $dateField = new DateField('Release_Date');
        $dateField -> setConfig('showcalendar', true);
        $fields -> addFieldToTab('Root.Main', $dateField);

        //***************** STYLE AND CLASSES
      //  $fields -> addFieldToTab('Root.Main', new HeaderField('stylesHeader', 'Panel Type'));
      //  $fields -> addFieldToTab('Root.Main', $cssClasses_Controller -> CLTPanel_Colors_Class('Panel_Class'));
      	$fields -> addFieldToTab('Root.Main', new HeaderField('productHeader', 'Product Class'));
     	$field = new DropdownField('Panel_Class', 'Panel_Class', array(
			'Tip' => 'Tip',
			'Article' => 'Article',
		));
		$fields -> addFieldToTab('Root.Main', $field, '');
        $fields -> addFieldToTab('Root.Main', new HeaderField('stylesHeader', 'ClassroomPanel Color'));
        $fields -> addFieldToTab('Root.Main', $cssClasses_Controller -> CLTPanel_Colors_Class('Color_Class'));

        //***************** ASSOCIATED PRODUCT
        $fields -> addFieldToTab('Root.Main', new HeaderField('productHeader', 'Associated product'));
        $field = new DropdownField('ProductID', 'Product', Product::get() -> map('ID', 'Display_Name'));
        $field -> setEmptyString('(Select Associated Product)');
        $fields -> addFieldToTab('Root.Main', $field, '');

        //***************** NAME
        $fields -> addFieldToTab('Root.Main', new HeaderField('nameHeader', 'Searchable Name'));

        $fields -> addFieldToTab('Root.Main', new TextField('Name'));

        //***************** DISPLAY NAME

        $fields -> addFieldToTab('Root.Main', new HeaderField('DisplayNameHeader', 'Display Name <h1> and subtitles <h2>'));
        $fields -> addFieldToTab('Root.Main', new TextField('Display_Name'));
		
		//***************** HEAD LINE
		$fields -> addFieldToTab('Root.Main', new HeaderField('Headline', 'Headline'));
        $fields -> addFieldToTab('Root.Main', new TextField('Headline'));
		
        //***************** SUBTITLE
        $fields -> addFieldToTab('Root.Main', new TextField('Subtitle'));

        //***************** IMAGES
        $fields -> addFieldToTab('Root.Main', new HeaderField('Images', 'Main Image and the half width Image'));
        $fields -> addFieldToTab('Root.Main', new UploadField('MainImage'));
        $fields -> addFieldToTab('Root.Main', new UploadField('SmallImage'));
		$fields -> addFieldToTab('Root.Main', new UploadField('FrontPagePanelImage'));
		
 		//***************** ACTIVITY TIME ESTIMATE -- for classrooms curriculum articles
 		$fields -> addFieldToTab('Root.Main', new HeaderField('nameHeader', 'Prep / Activity Time'));
 		$fields -> addFieldToTab('Root.Main', new NumericField('Prep_Time','Prep Time (minutes)'));
 		$fields -> addFieldToTab('Root.Main', new NumericField('Activity_Time','Activity Time (minutes)'));
		
		//***************** ACTIVITY INSTRUCTIONS PDF
		$fields -> addFieldToTab('Root.Main', new HeaderField('nameHeader', 'Activity Instructions'));
		$fields -> addFieldToTab('Root.Main', $activityInstructionsUpload = new UploadField('ActivityInstructions', 'Activity Instructions'));
		$activityInstructionsUpload->getValidator()->setAllowedExtensions(array('pdf'));
		
        //***************** CONTENT OF THE ARTICLE
        $fields -> addFieldToTab('Root.Main', new HeaderField('quickTipsHeader', 'Quick Tips'));
        $fields -> addFieldToTab('Root.Main', new HTMLEditorField('Quick_Tip','Quick Tips'));

        $fields -> addFieldToTab('Root.Main', new HeaderField('mainContentTipsHeader', 'Deep Clean / Article Content'));
        $fields -> addFieldToTab('Root.Main', new HTMLEditorField('Deep_Clean_Tip','Deep Clean Tips'));

        return $fields;
    }

    /**
     * function GeneratePage
     * Method that generates a Single Item page if it doesn't exists.
     */
    public  function generatePage() {


        $alreadyExistingPage = ClassroomsArticlesPage::get() -> filter(array('Title' => $this -> Name))->first();

        if(isset($alreadyExistingPage->ID) ){
			if($this->ClassroomsSingleItemPageID == $alreadyExistingPage->ID){
            return $this -> ClassroomsSingleItemPageID;
			}
        }elseif(!empty($alreadyExistingPage->ID)){
            //error_log('There is a page but it was not associated to the Panel '.$alreadyExistingPage->ID);
            $this->ClassroomsSingleItemPageID = $alreadyExistingPage->ID;
            $this -> write();
            return $this -> ClassroomsSingleItemPageID;

        }elseif(!isset($alreadyExistingPage->ID)){
	
	        //error_log('There is NO page but we are fixing this!');
	        $this -> createNewPage();
		}
    }
    /**
     * function createNewPage
     * Definition: Generates a Page for any single panel
     */
    public  function createNewPage() {

        // Single Item page
        $page = new ClassroomsArticlesPage();

         // Page title
        $page -> Title = $this -> Name;
		
		       // All parent pages
        $parentPage = ClassroomsResourcesTipsPage::get()->first();
		
		
			
		$page -> MainContentPanels() -> ID = $this->ID;

		
        $page -> setParent($parentPage -> ID);

        $page -> write();

        $page -> MainContentPanels() -> add($this);

        $page -> doPublish();

        $this -> ClassroomsSingleItemPageID = $page -> ID;

        $this -> write();
        return $this -> ClassroomsSingleItemPageID;
	    
    }
    /**
     * function GetSingleItemPageUrl
     *
     * Description Get or Defines the url of the single page for this ClassroomsPanel
     * If the ClassroomsPanel already has a url it will return it if not it will search and create one
     * @author Luc Martin at Clorox
     * @version $ID
     */
    public function GetSingleItemPageUrl($section=null) {

        // Test if the page exists in case something happen to the relationship
        if (!isset($this -> ClassroomsSingleItemPageID) && empty($this -> ClassroomsSingleItemPageID)) {
            // if the page dowsn't exist generate it
            $singlePageId = $this -> generatePage();

        }
        // return only the last part of the url
        if($section == 'segment'){
            return $this -> ClassroomsSingleItemPage() -> URLSegment;
        }
        // full url
        $url = $this -> ClassroomsSingleItemPage() -> Link();
        return $url;
    }

 


}
