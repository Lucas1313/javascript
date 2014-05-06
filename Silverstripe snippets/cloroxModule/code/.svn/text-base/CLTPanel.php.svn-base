<?php
/*
 * CLTPanel
 *
 * Describes the Model for a CLTPanel
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
class CLTPanel extends DataObject {

    static $db = array(
        'Name' => 'HTMLText',
        'Display_Name' => 'HTMLText',
        'Subtitle' => 'HTMLText',
        'Quick_Tip' => 'HTMLText',
        'Deep_Clean_Tip' => 'HTMLText',
        'Release_Date' => 'Date',
        'Color_Class' => 'Text',
        'Ribbon_Class' => 'Text',
        'Good_Tip_Count' => 'Int',
        'Ick_Count' => 'Int',
        'Quick_Tip_Count' => 'Int',
        'Just_For_Mom_Count' => 'Int',
        'Fun_Count' => 'Int',
        'Panel_Class' => 'Text',
        'CTA_Link' => 'Text',
        'CTA_Title' => 'Varchar(255)'
    );

    static $has_one = array(
        'MainImage' => 'Image',
        'SmallImage' => 'Image',
        'FrontPagePanelImage' => 'Image',
        'Product' => 'Product',
        'CLTSingleItemPage' => 'CLTSingleItemPage'
    );
    static $belong_many_many = array(
        'CLTLocationPage' => 'CLTLocationPage',
        'CLTLandingPage' => 'CLTLandingPage'
    );
    static $searchable_fields = array(
        'Name',
        'Quick_Tip',
        'Deep_Clean_Tip'
    );
    public static $summary_fields = array(
    	'SortOrderLandingPageCLTPanels'=>'SortOrderLandingPageCLTPanels',
        'ID' => 'ID',
        'Name' => 'Name',
        'Quick_Tip' => 'Quick_Tip',
        'Deep_Clean_Tip' => 'Deep_Clean_Tip',
    );

    public function getCMSFields() {

        if (!empty($this -> Name)) {
            $this -> generatePage();
        }

        $cssClasses_Controller = new CssClasses_Controller();

        $fields = parent::getCMSFields();

        //$fields -> removeFieldFromTab('Root.Main', 'CLTSingleItemPageID');
        $fields -> addFieldToTab('Root.Main', new HeaderField('userVotesHeader', 'User votes'));
        $fields -> addFieldToTab('Root.Main', new ReadOnlyField('Good_Tip_Count', 'Good_Tip_Count'));
        $fields -> addFieldToTab('Root.Main', new ReadOnlyField('Ick_Count', 'Ick_Count'));
        $fields -> addFieldToTab('Root.Main', new ReadOnlyField('Quick_Tip_Count', 'Quick_Tip_Count'));
        $fields -> addFieldToTab('Root.Main', new ReadOnlyField('Just_For_Mom_Count', 'Just_For_Mom_Count'));
        $fields -> addFieldToTab('Root.Main', new ReadOnlyField('Fun_Count', 'Fun_Count'));

        $fields -> addFieldToTab('Root.Main', new ReadOnlyField('CLTSingleItemPageID', 'CLTSingleItemPageID'));

        //***************** Release DATE
        $fields -> addFieldToTab('Root.Main', new HeaderField('releaseDateHeader', 'Release date of the Tip / Article'));

        $dateField = new DateField('Release_Date');
        $dateField -> setConfig('showcalendar', true);
        $fields -> addFieldToTab('Root.Main', $dateField);

        //***************** STYLE AND CLASSES
        $fields -> addFieldToTab('Root.Main', new HeaderField('stylesHeader', 'Styles and Classes'));
        $fields -> addFieldToTab('Root.Main', $cssClasses_Controller -> CLTPanel_Class('Panel_Class'));
        $fields -> addFieldToTab('Root.Main', $cssClasses_Controller -> CLTPanel_Ribbons_Class('Ribbon_Class'));
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

        //***************** SUBTITLE
        $fields -> addFieldToTab('Root.Main', new TextField('Subtitle'));

        //***************** IMAGES
        $fields -> addFieldToTab('Root.Main', new HeaderField('Images', 'Main Image and the half width Image'));
        $fields -> addFieldToTab('Root.Main', new UploadField('MainImage'));
        $fields -> addFieldToTab('Root.Main', new UploadField('SmallImage'));
		$fields -> addFieldToTab('Root.Main', new UploadField('FrontPagePanelImage'));


        //***************** CONTENT OF THE ARTICLE
        $fields -> addFieldToTab('Root.Main', new HeaderField('quickTipsHeader', 'Quick Tips'));
        $fields -> addFieldToTab('Root.Main', new HTMLEditorField('Quick_Tip','Quick Tips'));

        $fields -> addFieldToTab('Root.Main', new HeaderField('mainContentTipsHeader', 'Deep Clean / Article Content'));
        $fields -> addFieldToTab('Root.Main', new HTMLEditorField('Deep_Clean_Tip','Deep Clean Tips'));

        //***************** CTA Fields
        $fields -> addFieldToTab('Root.Main', new HeaderField('CTAHeader','Call To Action (Optional will default to "ReadOn" )'));
        $fields -> addFieldToTab('Root.Main', new TextField('CTA_Title','CTA Title'));
        $fields -> addFieldToTab('Root.Main', new TextField('CTA_Link','CTA Link'));


        return $fields;
    }

    /**
     * function GeneratePage
     * Method that generates a Single Item page if it doesn't exists.
     */
    public  function generatePage() {

        $alreadyExistingPage = CLTSingleItemPage::get() -> filter(array('Title' => $this -> Name))->first();
        if($this->CLTSingleItemPageID == $alreadyExistingPage->ID){

            return $this -> CLTSingleItemPageID;

        }elseif(!empty($alreadyExistingPage->ID)){
            //error_log('There is a page but it was not associated to the Panel '.$alreadyExistingPage->ID);
            $this->CLTSingleItemPageID = $alreadyExistingPage->ID;
            $this -> write();
            return $this -> CLTSingleItemPageID;

        }

        //error_log('There is NO page but we are fixing this!');
        $this -> createNewPage();
    }
    /**
     * function createNewPage
     * Definition: Generates a Page for any single panel
     */
    public  function createNewPage() {

        // Single Item page
        $page = new CLTSingleItemPage();

        // Page title
        $page -> Title = $this -> Name;

        // All parent pages
        $allParentPages = CLTLocationPage::get();

		// Landing page to assign articles to
		$landingPage = CLTLandingPage::get();

		if($landingPage){
			 foreach ($allParentPages as $key => $landingPage) {

            $allTipsPanels = $landingPage -> Tips();

            foreach ($allTipsPanels as $key => $Tip) {

                if ($Tip -> Name == $this -> Name) {

                    $parentpageId = $landingPage -> ID;

                }
            }

		// Create article pages on landing page
		// Iterate Through all Articles

            	$allArticlesPanels = $landingPage -> Articles();

            foreach ($allArticlesPanels as $key => $Article) {

                if ($Article -> Name == $this -> Name) {

                    $parentpageId = $parentPage -> ID;

                }
            }
		 }
		$page -> setParent($parentpageId);

        $page -> write();

        $page -> MainContentPanels() -> add($this);

        $page -> doPublish();

        $this -> CLTSingleItemPageID = $page -> ID;

        $this -> write();
        return $this -> CLTSingleItemPageID;

	 }
        // Make sure that all panels we are displaying have a parent page

        // Iterate thought all Parent pages
        foreach ($allParentPages as $key => $parentPage) {

            $allTipsPanels = $parentPage -> Tips();

            foreach ($allTipsPanels as $key => $Tip) {

                if ($Tip -> Name == $this -> Name) {

                    $parentpageId = $parentPage -> ID;

                }
            }

            // Iterate Through all Articles
            $allArticlesPanels = $parentPage -> Articles();

            foreach ($allArticlesPanels as $key => $Article) {

	                if ($Article -> Name == $this -> Name) {

	                    $parentpageId = $parentPage -> ID;

	                }
	            }
        }
        if (!isset($parentpageId)) {
            return;
        }

        $page -> setParent($parentpageId);

        $page -> write();

        $page -> MainContentPanels() -> add($this);

        $page -> doPublish();

        $this -> CLTSingleItemPageID = $page -> ID;

        $this -> write();
        return $this -> CLTSingleItemPageID;

    }
    /**
     * function GetSingleItemPageUrl
     *
     * Description Get or Defines the url of the single page for this CLTPanel
     * If the CLTPanel already has a url it will return it if not it will search and create one
     * @author Luc Martin at Clorox
     * @version $ID
     */
    public function GetSingleItemPageUrl($section=null) {

        // Test if the page exists in case something happen to the relationship
        if (!isset($this -> CLTSingleItemPageID) && empty($this -> CLTSingleItemPageID)) {
            // if the page dowsn't exist generate it
            $singlePageId = $this -> generatePage();

        }
        // return only the last part of the url
        if($section == 'segment'){
            return $this -> CLTSingleItemPage() -> URLSegment;
        }
        // full url
        $url = $this -> CLTSingleItemPage() -> Link();
        return $url;
    }

    /**
     * Function getVotingBubble
     *
     * Definition:  This function returns the class name of the bubble that has been voted the most for this panel / article
     *
     * input: none
     * output: bubbleFun      --the class name of the winning bubble which will be used as the filename of the bubbles
     *
     * @author: kody smith -at- clorox
     */
    public function getVotingBubble() {

        $countArray = array(
            "bubbleGoodTip" => $this -> Good_Tip_Count,
            "bubbleIck" => $this -> Ick_Count,
            "bubbleQuickTip" => $this -> Quick_Tip_Count,
            "bubbleJustForMom" => $this -> Just_For_Mom_Count,
            "bubbleFun" => $this -> Fun_Count
        );

        $max = max($countArray);

        if ($max > 0) {

            return array_search($max, $countArray);
        }else{
        	return null;
        }

    }

	public function levelTest(){

        $this->FrontPagePanelImage = $this->MainImage;
		return 'saving to db';
	}


}
