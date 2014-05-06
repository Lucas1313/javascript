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
class ClassroomsCurriculumPanel extends DataObject {


static $db = array(
        'Name' => 'HTMLText',
        'ArticleHeader' => 'HTMLText',
        'Display_Name' => 'HTMLText',
        'Subtitle' => 'HTMLText',
        'Quick_Tip' => 'HTMLText',
        'Deep_Clean_Tip' => 'HTMLText',
        'Release_Date' => 'Date',
        'Color_Class' => 'Text',
        'Panel_Class' => 'Text',
        'Activity_Grade_Type' => 'Text',
   		'Activity_Type' => 'Text',
        'Prep_Time'=>'Int',
        'Activity_Time'=>'Int',
        'Headline' => 'HTMLText',
        'Summary' => 'HTMLText',
        'Included' => 'HTMLText',
        'ParentPageID' => 'Int'
    );

    static $has_one = array(
      'MainImage' => 'Image',
        'SmallImage' => 'Image',
        'FrontPagePanelImage' => 'Image',
        'ClassroomsSingleItemPage' => 'ClassroomsCurriculumArticlesPage',
        'ActivityInstructions' => 'File'
    );
	
    static $belong_many_many = array(
        'ClassroomsCurriculumArticlesPage' => 'ClassroomsCurriculumArticlesPage',
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
	
	
    public function getCMSFields() {

        if (!empty($this -> Name)) {
            $this -> generateArticlesPage();
        }

      //  $cssClasses_Controller = new CssClasses_Controller();
		$cssClasses_Controller = new CssClasses_Controller('Color_Class');
        $fields = parent::getCMSFields();

		$fields -> removeFieldFromTab('Root.Main', 'Color_Class');
		$fields -> removeFieldFromTab('Root.Main', 'Panel_Class');
		$fields -> removeFieldFromTab('Root.Main', 'Quick_Tip');
		$fields -> removeFieldFromTab('Root.Main', 'Deep_Clean_Tip');
		$fields -> removeFieldFromTab('Root.Main', 'releaseDateHeader');
		$fields -> removeFieldFromTab('Root.Main', 'productHeader');
		$fields -> removeFieldFromTab('Root.Main', 'stylesHeader');
		$fields -> removeFieldFromTab('Root.Main', 'productHeader');
		$fields -> removeFieldFromTab('Root.Main', 'nameHeader');
		$fields -> removeFieldFromTab('Root.Main', 'DisplayNameHeader');
		$fields -> removeFieldFromTab('Root.Main', 'mainContentTipsHeader');
		$fields -> removeFieldFromTab('Root.Main', 'quickTipsHeader');
		$fields -> removeFieldFromTab('Root.Main', 'nameHeader');
		$fields -> removeFieldFromTab('Root.Main', 'Images');
		$fields -> removeFieldFromTab('Root.Main', 'PrepTime');
		$fields -> removeFieldFromTab('Root.Main', 'ActivityTime');
		$fields -> removeFieldFromTab('Root.Main', 'ParentPageID');
		$fields -> removeFieldFromTab('Root.Main', 'ActivityInstructions');
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

		//***************** ARTICLE HEADER
		$fields -> addFieldToTab('Root.Main', new HeaderField('ArticleHeader', 'Black bar article header'));
        $fields -> addFieldToTab('Root.Main', new TextField('ArticleHeader'));
		
		
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

		//**************** ACTIVITY TYPE
      	$field = new DropdownField('Activity_Type', 'Activity_Type', array(
			'Type_All' => 'All',
			'Investigation' => 'Investigation',
			'Family_take_home_activity' => 'Family_take_home_activity',
			'Interactive_white_board' => 'Interactive_white_board'
		));
		$fields -> addFieldToTab('Root.Main', $field, '');

		//**************** ACTIVITY GRADE TYPE
      	$field = new DropdownField('Activity_Grade_Type', 'Activity_Grade_Type', array(
			'Grade_All' => 'All',
			'Grade_K_2' => 'Grade_K_2',
			'Grade_3_5' => 'Grade_3_5',
		));
		$fields -> addFieldToTab('Root.Main', $field, '');

		//***************** ACTIVITY INSTRUCTIONS PDF
		$fields -> addFieldToTab('Root.Main', new HeaderField('nameHeader', 'Activity Instructions'));
		$fields -> addFieldToTab('Root.Main', $activityInstructionsUpload = new UploadField('ActivityInstructions', 'Activity Instructions'));
		$activityInstructionsUpload->getValidator()->setAllowedExtensions(array('pdf'));

        //***************** CONTENT OF THE ARTICLE
        $fields -> addFieldToTab('Root.Main', new HeaderField('quickTipsHeader', 'Summary'));
        $fields -> addFieldToTab('Root.Main', new HTMLEditorField('Summary','Summary'));

        $fields -> addFieldToTab('Root.Main', new HeaderField('mainContentTipsHeader', 'Included'));
        $fields -> addFieldToTab('Root.Main', new HTMLEditorField('Included','Included'));

        return $fields;
    }
	public  function updateClasses(){
		/**
		 * updateClasses is just a maintenance function that is to be used when needing to massively update filter values
		 * 
		 * This currently will replace all spaces with underscores of the Activity_Grade_Type and Activity_Type fields of ClassroomsCurriculumPanel table
		 * 
		 * this is a public  function, and should be called indirectly... or just switched to public if you want to call it directly
		 
		$count = DB::query("update ClassroomsCurriculumPanel set Activity_Grade_Type = replace(Activity_Grade_Type, ' ', '_')")->value();
		$count = DB::query("update ClassroomsCurriculumPanel set Activity_Grade_Type = replace(Activity_Grade_Type, '-', '_')")->value();
		$count = DB::query("update ClassroomsCurriculumPanel set Activity_Type = replace(Activity_Type, ' ', '_')")->value();
		$count = DB::query("update ClassroomsCurriculumPanel set Activity_Type = replace(Activity_Type, '-', '_')")->value();
		return 'Activity_Grade_Type: updated;  Activity_Type: updated;  ';
		 * */
	}
	
    /**
     * function generateArticlesPage
     * Method that generates a Single Item page if it doesn't exists.
     */
    public  function generateArticlesPage() {

       $alreadyExistingPage = ClassroomsCurriculumArticlesPage::get()-> filter(array('Title' => $this -> Name))->first();

        if(isset($alreadyExistingPage->ID) ){
        	
			if($this->ClassroomsSingleItemPageID == $alreadyExistingPage->ID){
            	return $this -> ClassroomsSingleItemPageID;
			}
        }elseif(!empty($alreadyExistingPage->ID)){
            //error_log('There is a page but it was not associated to the Panel '.$alreadyExistingPage->ID);
            $this->ClassroomsSingleItemPageID = $alreadyExistingPage->ID;
            $this -> write();
            return $this -> ClassroomsSingleItemPageID;

        }elseif(!isset($alreadyExistingPage)){

	        //error_log('There is NO page but we are fixing this!');
	        $this -> createNewArticlesPage();
		}
    }
    /**
     * function createNewArticlesPage
     * Definition: Generates a Page for any single panel
     */
    public  function createNewArticlesPage() {

        // Single Item page
        $page = new ClassroomsCurriculumArticlesPage();

         // Page title
        $page -> Title = $this -> Name;

		       // All parent pages
        $parentPage = ClassroomsCurriculumPage::get()->first();
	//	error_log(' The title is::::: '.$parentPage->Title);
		// Landing page to assign articles to

//		$page -> setParent($parentPage->ID);



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
            $singlePageId = $this -> generateArticlesPage();

        }
        // return only the last part of the url
        if($section == 'segment'){
            return $this -> ClassroomsSingleItemPage() -> URLSegment;
        }
        // full url
        $url = $this -> ClassroomsSingleItemPage() -> Link();
        return $url;
    }
	public function gradeType(){
		$string = strrev($this->Activity_Grade_Type);
		$string = substr_replace($string, '-',1,1);
		$string = str_replace('_', ' ',$string);
		return strrev($string);
	}
	public function activityType(){
		$string = $this->Activity_Type;
		$string = str_replace('_', ' ',$string);
		return $string;
	}


}
