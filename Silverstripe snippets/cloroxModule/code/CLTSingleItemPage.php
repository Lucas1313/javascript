<?php
/*
 * Class CLTLocationPage
 *
 * Describes the Model for a CLTLocationPage the home page for the Cleaning and Laundry tips
 *
 * @author Luc Martin lmartin -at- clorox.com
 * @version $Id
 */
class CLTSingleItemPage extends Page {
    static $db = array(
        'Subtitle' => 'HTMLText',
        'Slideshow'=>'HTMLText',
        'Youtube_Id'=>'Text'
    );

    static $has_one = array(
        'CLTAppPanel' => 'CLTAppPanel',
        'PinterestImage' => 'Image'
    );

    static $many_many = array(
        'Slide_Show' => 'CLTPanel',
        'TopTips' => 'CLTPanel',
        'RelatedArticles' => 'CLTPanel',
        'MainContentPanels' => 'CLTPanel'
    );

    public static $many_many_extraFields = array(
        'TopTips' => array('SortOrderTopTips' => 'Int'),
        'RelatedArticles' => array('SortOrderRelatedArticles' => 'Int'),
        'MainContentPanels' => array('SortOrderMainContentPanels' => 'Int')
    );

    public function getCMSFields() {

        $fields = parent::getCMSFields();

        $fields -> removeFieldFromTab('Root', 'Content');

        //***************** Description
        $fields -> removeFieldFromTab('Root.Main', 'Description');

        //***************** Slide Show
        $fields -> addFieldToTab('Root.Main', new HeaderField('SlideshowHeader', 'Slideshow </h2><h4>(Create as a List, each <li> will be a slide)</h4><h2>'));
        $fields -> addFieldToTab('Root.Main', new HTMLEditorField('Slideshow', 'Slideshow'));

        //***************** Video
        $fields -> addFieldToTab('Root.Main', new HeaderField('VideoHeader', 'Youtube Id </h2><p>( If you need a video to play in that page, please add the Youtube ID here)</p><h2>'));
        $fields -> addFieldToTab('Root.Main', new TextField('Youtube_Id', 'Youtube Id'));

        //***************** Main content Panels
        $fields -> addFieldToTab('Root.Main', new HeaderField('MainContentPanelsHeader', 'Main Content'));

        $CLTPanelsField = new GridField('MainContentPanels', 'Main Content Panels', $this -> MainContentPanels(), GridFieldConfig_RelationEditor::create() -> addComponents(new GridFieldSortableRows('SortOrderMainContentPanels'), new GridFieldDeleteAction('unlinkrelation')));
        $fields -> addFieldToTab('Root.Main', $CLTPanelsField);


        $fields -> addFieldToTab('Root.Main', new DropDownField('TipArticle', 'Main article'));
        //***************** TopTips Panels
        $fields -> addFieldToTab('Root.Main', new HeaderField('TopTips', 'Top Tips of the Season'));

        $CLTPanelsField = new GridField('TopTips', 'Top Tips of the season', $this -> TopTips(), GridFieldConfig_RelationEditor::create() -> addComponents(new GridFieldSortableRows('SortOrderTopTips'), new GridFieldDeleteAction('unlinkrelation')));
        $fields -> addFieldToTab('Root.Main', $CLTPanelsField);

        $fields -> addFieldToTab('Root.Main', new DropDownField('CLTAppPanel', 'App Marketing Panel'));

        //***************** TopTips Panels
        $fields -> addFieldToTab('Root.Main', new HeaderField('Related_Articles', 'Related Articles'));

        $CLTPanelsField = new GridField('RelatedArticles', 'Related Articles', $this -> RelatedArticles(), GridFieldConfig_RelationEditor::create() -> addComponents(new GridFieldSortableRows('SortOrderRelatedArticles'), new GridFieldDeleteAction('unlinkrelation')));
        $fields -> addFieldToTab('Root.Main', $CLTPanelsField);

        $fields -> addFieldToTab('Root.Main', new DropDownField('CLTAppPanel', 'App Marketing Panel'));

        //***************** IMAGES
        $fields -> addFieldToTab('Root.Main', new HeaderField('Images', 'Pinterest Image Override'));
        $fields -> addFieldToTab('Root.Main', new UploadField('PinterestImage'));

        return $fields;

    }

    public function TopTips() {
        return $this -> getManyManyComponents('TopTips') -> sort('SortOrderTopTips');
    }

    public function RelatedArticles() {
        return $this -> getManyManyComponents('RelatedArticles') -> sort('SortOrderRelatedArticles');
    }
    public function MainContentPanels(){
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
	/**
	 * Function: getParentPageTitle  returns the parent page Title of a singleItemPage
	 */
	public function getParentPageTitle(){
		return $this->Parent()->Title;
	}
}

class CLTSingleItemPage_Controller extends Page_Controller {

    public function init() {
		Requirements::javascript("js/pages/CLTPageNavigation.js");
        Requirements::javascript("js/pages/CLTipsPages.js");
        Requirements::javascript('js/plugins/jquery.youtubewrapper.js');

        parent::init();
    }

}
